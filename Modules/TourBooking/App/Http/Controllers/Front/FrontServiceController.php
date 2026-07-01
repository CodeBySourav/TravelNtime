<?php

declare(strict_types=1);

namespace Modules\TourBooking\App\Http\Controllers\Front;

use App\Enums\Language;
use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Js;
use Illuminate\View\View;
use Modules\GlobalSetting\App\Models\GlobalSetting;
use Modules\TourBooking\App\Models\Amenity;
use Modules\TourBooking\App\Models\AmenityTranslation;
use Modules\TourBooking\App\Models\Booking;
use Modules\TourBooking\App\Models\Destination;
use Modules\TourBooking\App\Models\Review;
use Modules\TourBooking\App\Models\Service;
use Modules\TourBooking\App\Models\ServiceReview;
use Modules\TourBooking\App\Models\ServiceType;
use Modules\TourBooking\App\Repositories\ServiceRepository;
use Modules\TourBooking\App\Repositories\ServiceTypeRepository;
use Illuminate\Support\Facades\Log;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\SupportCollection;
use App\Services\TboService;

final class FrontServiceController extends Controller
{

    public function __construct(
        private ServiceRepository $serviceRepository,
        private ServiceTypeRepository $serviceTypeRepository,
    ) {}

    /**
     * Display the home page of the tour booking module.
     */
    public function index(): View
    {
        $featuredServices = Service::where('status', true)
            ->where('is_featured', true)
            ->with('thumbnail')
            ->take(8)
            ->get();

        $popularServices = Service::where('status', true)
            ->where('is_popular', true)
            ->with('thumbnail')
            ->take(8)
            ->get();

        $serviceTypes = ServiceType::where('status', true)
            ->with('thumbnail')
            ->take(6)
            ->get();

        $popularDestinations = Destination::where('status', true)
            ->where('is_popular', true)
            ->with('thumbnail')
            ->take(6)
            ->get();

        $latestReviews = Review::where('status', true)
            ->with(['service', 'user'])
            ->latest()
            ->take(6)
            ->get();

        return view('tourbooking::front.index', compact(
            'featuredServices',
            'popularServices',
            'serviceTypes',
            'popularDestinations',
            'latestReviews'
        ));
    }

    /**
     * Search for services.
     */
    public function search(Request $request): View
    {
        $query = Service::where('status', true)
            ->with(['thumbnail', 'serviceType', 'reviews']);

        // Apply search filters
        if ($request->filled('keyword')) {
            $keyword = $request->input('keyword');
            $query->where(function ($q) use ($keyword) {
                $q->where('title', 'like', "%{$keyword}%")
                    ->orWhere('description', 'like', "%{$keyword}%")
                    ->orWhere('location', 'like', "%{$keyword}%");
            });
        }

        if ($request->filled('service_type')) {
            $query->where('service_type_id', $request->input('service_type'));
        }

        if ($request->filled('location')) {
            $query->where('location', 'like', "%" . $request->input('location') . "%");
        }

        if ($request->filled('min_price')) {
            $query->where(function ($q) use ($request) {
                $q->where('full_price', '>=', $request->input('min_price'))
                    ->orWhere('discount_price', '>=', $request->input('min_price'))
                    ->orWhere('price_per_person', '>=', $request->input('min_price'));
            });
        }

        if ($request->filled('max_price')) {
            $query->where(function ($q) use ($request) {
                $q->where('full_price', '<=', $request->input('max_price'))
                    ->orWhere('discount_price', '<=', $request->input('max_price'))
                    ->orWhere('price_per_person', '<=', $request->input('max_price'));
            });
        }

        // Sort results
        $sort = $request->input('sort', 'newest');

        switch ($sort) {
            case 'price_low':
                $query->orderBy('discount_price', 'asc');
                break;
            case 'price_high':
                $query->orderBy('discount_price', 'desc');
                break;
            case 'rating':
                $query->withAvg('reviews', 'rating')
                    ->orderByDesc('reviews_avg_rating');
                break;
            case 'oldest':
                $query->oldest();
                break;
            case 'newest':
            default:
                $query->latest();
                break;
        }

        $services = $query->paginate(12)->withQueryString();

        // Get filter options for the search form
        $serviceTypes = ServiceType::where('status', true)->get();
        $destinations = Destination::where('status', true)->get();

        return view('tourbooking::front.search', compact('services', 'serviceTypes', 'destinations'));
    }

    /**
     * Display all service types.
     */
    public function serviceTypes(): View
    {
        $serviceTypes = ServiceType::where('status', true)
            ->with('thumbnail')
            ->paginate(15);

        return view('tourbooking::front.service-types', compact('serviceTypes'));
    }

    /**
     * Display a specific service type with its services.
     */
    public function serviceTypeDetail(string $slug): View
    {
        $serviceType = ServiceType::where('slug', $slug)
            ->where('status', true)
            ->firstOrFail();

        $services = Service::where('service_type_id', $serviceType->id)
            ->where('status', true)
            ->with(['thumbnail', 'reviews'])
            ->paginate(12);

        return view('tourbooking::front.service-type-detail', compact('serviceType', 'services'));
    }

    /**
     * Display all services.
     */
    public function allServices(Request $request)
    {

        $selected_service_layout = GlobalSetting::where('key', 'booking_service_theme')?->first()?->value;
        $breadcrumb_title = trans('translate.All Services');
        $requestView = $request->view;
        if ($requestView == 'hotel_grid' || $selected_service_layout == 'hotel_grid') {
            $serviceView = 'tourbooking::front.services.services';
        } elseif ($requestView == 'tour_grid_one' || $selected_service_layout == 'tour_grid_one') {
            $serviceView = 'tourbooking::front.services.services2';
        } elseif ($requestView == 'tour_grid_two' || $selected_service_layout == 'tour_grid_two') {
            $serviceView = 'tourbooking::front.services.services3';
        } elseif ($requestView == 'hotel_listing' || $selected_service_layout == 'hotel_listing') {
            $serviceView = 'tourbooking::front.services.services4';
        } else {
            $serviceView = 'tourbooking::front.services.services';
        }

        $serviceTypes = $this->serviceTypeRepository->getActiveNameId();

        $amenities = Amenity::where('status', true)->with('translation:id,amenity_id,lang_code,name')->get();
        $languages = Language::cases();
        $destinations = Destination::where('status', true)->get();

        /*
            |--------------------------------------------------------------------------
            | TBO Hotel Search
            |--------------------------------------------------------------------------
            */

            $hotels = [];

            if (
                $request->filled('service_type_ids') &&
                in_array(3, (array) $request->service_type_ids)
            ) {

                $params = [
                    'checkin'  => $request->checkIn ?? date('d/m/Y', strtotime('+1 day')),
                    'checkout' => $request->checkOut ?? date('d/m/Y', strtotime('+2 day')),
                    'city'     => $request->destination_id ?? 130443,
                    'rooms'    => $request->rooms ?? 1,
                    'adults'   => $request->adults ?? 2,
                    'children' => $request->children ?? 0,
                ];

                try {

                    $hotels = app(\App\Services\TboService::class)
                        ->searchHotels($params);

                } catch (\Exception $e) {

                    $hotels = [];
                }
            }
            Log::info('TBO Hotel Response', [
                'hotels' => $hotels
            ]);
            return view($serviceView, compact(
                'serviceTypes',
                'amenities',
                'languages',
                'destinations',
                'breadcrumb_title',
                'hotels'
            ));
        }

    /**
     * load all services.
     */
    public function loadServicesAjax(Request $request)
    {

        $isListView = $request->isListView;
        $style = $request->style;

        /*
    |--------------------------------------------------------------------------
    | TBO HOTEL SEARCH
    |--------------------------------------------------------------------------
    */

        if (
            $request->filled('service_type_ids') &&
            in_array(3, (array) $request->service_type_ids)
        ) {

            $params = [
                'checkin'  => $request->checkIn ?: date('d/m/Y', strtotime('+1 day')),
                'checkout' => $request->checkOut ?: date('d/m/Y', strtotime('+2 day')),
                'city'     => $request->destination_id ?: 130443,
                'rooms'    => $request->rooms ?: 1,
                'adults'   => $request->adults ?: 2,
                'children' => $request->children ?: 0,
            ];

            try {

                $hotels = app(\App\Services\TboService::class)
                    ->searchHotels($params);

            } catch (\Exception $e) {

                \Log::error('TBO Error : '.$e->getMessage());

                $hotels = [];
            }
        $hotelResults = $hotels['HotelSearchResult']['HotelResults'] ?? [];
        $traceId      = $hotels['HotelSearchResult']['TraceId'] ?? '';

        // --- Manual Array Pagination for TBO Hotels ---
        $currentPage = LengthAwarePaginator::resolveCurrentPage();
        $perPage = 9;
        $currentItems = array_slice($hotelResults, ($currentPage - 1) * $perPage, $perPage);
        
        $paginatedHotels = new LengthAwarePaginator(
            $currentItems, 
            count($hotelResults), 
            $perPage, 
            $currentPage, 
            ['path' => $request->url(), 'query' => $request->query()]
        );

        return response()->json([
            'success' => true,
            'view' => view(
                'tourbooking::front.services.hotel-services-item',
                compact('paginatedHotels', 'traceId', 'isListView')
            )->render(),
        ]);
    }


        $allServices = Service::select('id', 'price_per_person', 'slug', 'location', 'is_featured', 'full_price', 'discount_price', 'is_new', 'duration', 'group_size')
            ->withExists('myWishlist')
            ->where('status', true)
            ->with(['thumbnail:id,service_id,caption,file_path', 'translation:id,service_id,locale,title,short_description'])
            ->withCount('activeReviews')
            ->withAvg('activeReviews', 'rating')
            ->when($request->filled('search'), function ($query) use ($request) {
                $query->whereHas('translation', function ($q) use ($request) {
                    $q->where('title', 'like', "%{$request->search}%");
                })
                    ->orWhere('location', 'like', "%{$request->search}%");
            })
            ->when($request->filled('service_type_ids') && is_array($request->service_type_ids), function ($query) use ($request) {
                return $query->whereIn('service_type_id', $request->service_type_ids);
            })
            ->when($request->filled('service_type_id') && $request->service_type_id != 'Type', function ($query) use ($request) {
                return $query->where('service_type_id', $request->service_type_id);
            })
            ->when($request->filled('max_price'), function ($query) use ($request) {
                return $query->where('full_price', '<=', $request->max_price);
            })
            ->when($request->filled('min_price'), function ($query) use ($request) {
                return $query->where('full_price', '>=', $request->min_price);
            })
            ->when($request->filled('amenity_ids') && is_array($request->amenity_ids), function ($query) use ($request) {
                $query->where(function ($q) use ($request) {
                    foreach ($request->amenity_ids as $amenityId) {
                        $q->orWhereJsonContains('amenities', $amenityId);
                    }
                });
            })
            ->when($request->filled('amenity_id') && $request->amenity_id != 'Amenities', function ($query) use ($request) {
                $query->whereJsonContains('amenities', $request->amenity_id);
            })
            ->when($request->filled('languages') && is_array($request->languages), function ($query) use ($request) {
                $query->where(function ($q) use ($request) {
                    foreach ($request->languages as $language) {
                        $q->orWhereJsonContains('languages', $language);
                    }
                });
            })
            ->when($request->filled('destination_id'), function ($query) use ($request) {
                return $query->where('destination_id', $request->destination_id);
            })
            ->when($request->filled('checkIn'), function ($query) use ($request) {
                return $query->whereTime('check_in_time', $request->checkIn);
            })
            ->when($request->filled('checkOut'), function ($query) use ($request) {
                return $query->whereTime('check_out_time', $request->checkOut);
            })
            ->when($request->filled('rooms'), function ($query) use ($request) {
                return $query->where('room_count', $request->rooms);
            })
            ->when($request->filled('adults'), function ($query) use ($request) {
                return $query->where('adult_count', $request->adults);
            })
            ->when($request->filled('children'), function ($query) use ($request) {
                return $query->where('children_count', $request->children);
            })
            ->when($request->filled('destination_id'), function ($query) use ($request) {
                return $query->where('destination_id', $request->destination_id);
            })
            ->when($request->filled('ratings') && is_array($request->ratings), function ($query) use ($request) {
                $minRating = min($request->ratings);
                $query->having('active_reviews_avg_rating', '>=', $minRating);
            })
            ->when($request->filled('ratting') && $request->ratting != 'default', function ($query) use ($request) {
                $query->having('active_reviews_avg_rating', '>=', $request->ratting);
            })
            ->when($request->filled('sort_by'), function ($query) use ($request) {
                switch ($request->sort_by) {
                    case 'price_low':
                        $query->orderBy('price_per_person', 'asc');
                        break;
                    case 'price_high':
                        $query->orderBy('price_per_person', 'desc');
                        break;
                    case 'trending':
                        $query->orderBy('is_featured', 'desc');
                        break;
                    case 'popular':
                        $query->orderBy('is_popular', 'desc');
                        break;
                    case 'latest':
                        $query->orderBy('created_at', 'desc');
                        break;
                    case 'oldest':
                        $query->orderBy('created_at', 'asc');
                        break;
                    case 'location_asc':
                        $query->orderBy('location', 'asc');
                        break;
                    case 'location_desc':
                        $query->orderBy('location', 'desc');
                        break;
                    default:
                        $query->orderBy('created_at', 'desc');
                }
            }, function ($query) {
                $query->orderBy('created_at', 'desc');
            })
            ->paginate(9);

        if ($style == 'style2') {
            $view = view('tourbooking::front.services.services-item2', compact('allServices', 'isListView'))->render();
        } elseif ($style == 'style3') {
            $view = view('tourbooking::front.services.services-item3', compact('allServices', 'isListView'))->render();
        } elseif ($style == 'style4') {
            $view = view('tourbooking::front.services.services-item4', compact('allServices', 'isListView'))->render();
        } else {
            $view = view('tourbooking::front.services.services-item', compact('allServices', 'isListView'))->render();
        }

        $customPaginationCount = customPaginationCount($allServices);

        return response()->json(
            [
                'success' => true,
                'message' => 'Services loaded successfully',
                'view' => $view,
                'customPaginationCount' => $customPaginationCount,
            ]
        );
    }
    /**
     * Display a specific Hotel's details.
     */
 
    public function hotelDetails(Request $request)
    {
        $hotelInfo = app(\App\Services\TboService::class)
            ->getHotelInfo(
                $request->traceId,
                $request->resultIndex,
                $request->hotelCode
            );

        $hotelRoom = app(\App\Services\TboService::class)
            ->getHotelRoom(
                $request->traceId,
                $request->resultIndex,
                $request->hotelCode
            );

        $hotelDetails = $hotelInfo['HotelInfoResult']['HotelDetails'] ?? [];

        $roomDetails = $hotelRoom['GetHotelRoomResult']['HotelRoomsDetails'] ?? [];

        Log::info('TBO Hotel Data', [
        'hotelDetails' => $hotelDetails,
        'roomDetails'  => $roomDetails,
        ]);

        return view(
            'tourbooking::front.services.hotel-details',
            compact('hotelDetails', 'roomDetails')
        );
    }

    /**
     * Display a specific service's details.
     */
    public function serviceDetail(Request $request, string $slug): View
    {

        $selected_service_layout = GlobalSetting::where('key', 'booking_service_detail_theme')?->first()?->value;

        $requestView = $request->view;
        if ($requestView == 'tour_detail_one' || $selected_service_layout == 'tour_detail_one') {
            $serviceView = 'tourbooking::front.services.service-detail';
        } elseif ($requestView == 'tour_detail_two' || $selected_service_layout == 'tour_detail_two') {
            $serviceView = 'tourbooking::front.services.service-detail2';
        }else {
            $serviceView = 'tourbooking::front.services.service-detail';
        }

        $service = Service::where('slug', $slug)
            ->where('status', true)
            ->with([
                'translation',
                'media:id,service_id,file_name,file_path,is_thumbnail',
                'serviceType:id,name',
                'extraCharges' => function ($query) {
                    $query->where('status', true);
                },
                'availabilities',
                'itineraries' => function ($query) {
                    $query->orderBy('day_number');
                }
            ])
            ->withCount('activeReviews')
            ->withAvg('activeReviews', 'rating')
            ->withExists('myWishlist')
            ->firstOrFail();

            // dd($service);

        $amenities = [];
        if ( is_array($service->amenities) && $service->amenities) {
            $amenities = AmenityTranslation::select('id', 'name')->whereIn('id', $service->amenities ?? [])->get();
        }

        // 1. Get approved reviews with rating_attributes for the specific service
        $reviews = Review::select('id', 'service_id', 'rating_attributes')
            ->where('service_id', $service->id)
            ->where('status', true)
            ->get();

        // 2. Calculate overall average rating
        $avgRating = Review::where('service_id', $service->id)
            ->where('status', true)
            ->avg('rating');

        // 3. Calculate average per rating category
        $categories = [];

        foreach ($reviews as $review) {
            $attributes = $review->rating_attributes; // Ensure it's an array

            if (!is_array($attributes)) continue;

            foreach ($attributes as $attr) {
                $category = $attr['category'];
                $rating = floatval($attr['rating']);

                if (!isset($categories[$category])) {
                    $categories[$category] = ['total' => 0, 'count' => 0];
                }

                $categories[$category]['total'] += $rating;
                $categories[$category]['count']++;
            }
        }

        // 4. Format average rating per category
        $averageRatings = collect($categories)->map(function ($data, $category) {
            $avg = $data['total'] / $data['count'];
            return [
                'category' => $category,
                'average' => round($avg, 1),
                'percent' => round(($avg / 5) * 100),
            ];
        })->values()->toArray();

        // 5. Paginated reviews with user info
        $paginatedReviews = Review::where('service_id', $service->id)
            ->where('status', true)
            ->with('user:id,name,image')
            ->latest()
            ->paginate(14);

        $popularServices = $this->popularServices($service);

        return view($serviceView, compact('service', 'paginatedReviews', 'averageRatings', 'reviews', 'avgRating', 'popularServices', 'amenities'));
    }

    public function popularServices()
    {
        return Service::select('id', 'service_type_id', 'price_per_person', 'slug', 'location', 'is_featured', 'full_price', 'discount_price', 'is_new', 'duration', 'group_size')
            ->where('is_popular', true)
            ->withExists('myWishlist')
            ->where('status', true)
            ->with([
                'thumbnail:id,service_id,caption,file_path',
                'translation:id,service_id,locale,title,short_description'
            ])
            ->withCount('activeReviews')
            ->withAvg('activeReviews', 'rating')
            ->latest()
            ->take(6)
            ->get();
    }

    /**
     * Filter services by category (tours, hotels, etc.).
     */
    private function getServicesByType(string $type): View
    {
        $serviceType = ServiceType::where('slug', $type)->firstOrFail();

        $services = Service::where('service_type_id', $serviceType->id)
            ->where('status', true)
            ->with(['thumbnail', 'reviews'])
            ->latest()
            ->paginate(12);

        $title = ucfirst($type);

        return view('tourbooking::front.services-by-type', compact('services', 'serviceType', 'title'));
    }

    /**
     * Display all tours.
     */
    public function tours(): View
    {
        return $this->getServicesByType('tours');
    }

    /**
     * Display all hotels.
     */
    public function hotels(): View
    {
        return $this->getServicesByType('hotels');
    }

    /**
     * Display all restaurants.
     */
    public function restaurants(): View
    {
        return $this->getServicesByType('restaurants');
    }

    /**
     * Display all rentals.
     */
    public function rentals(): View
    {
        return $this->getServicesByType('rentals');
    }

    /**
     * Display all activities.
     */
    public function activities(): View
    {
        return $this->getServicesByType('activities');
    }

    /**
     * Display all destinations.
     */
    public function destinations(): View
    {
        $destinations = Destination::where('status', true)
            ->with('thumbnail')
            ->paginate(12);

        return view('tourbooking::front.destinations', compact('destinations'));
    }

    /**
     * Display a specific destination with related services.
     */
    public function destinationDetail(string $slug): View
    {
        $destination = Destination::where('slug', $slug)
            ->where('status', true)
            ->with('thumbnail')
            ->firstOrFail();

        $services = Service::where('status', true)
            ->where('location', 'like', "%{$destination->name}%")
            ->with(['thumbnail', 'serviceType', 'reviews'])
            ->paginate(12);

        return view('tourbooking::front.destination-detail', compact('destination', 'services'));
    }

    /**
     * Store a new review for a service.
     */
    public function storeReview(Request $request)
    {

        if (!Auth::check()) {
            return response()->json(
                [
                    'success' => false,
                    'message' => 'You must be logged in to submit a review.',
                ]
            );
        }

        $existingBooking = Booking::where('service_id', $request->service_id)
            ->where('user_id', Auth::id())
            ->where('booking_status', 'confirmed')
            ->first();

        if (!$existingBooking) {
            return response()->json(
                [
                    'success' => false,
                    'message' => 'You must have a completed booking to submit a review.',
                ]
            );
        }

        if (Review::where('service_id', $request->service_id)->where('user_id', Auth::id())->exists()) {
            return response()->json(
                [
                    'success' => false,
                    'message' => 'You have already submitted a review for this service.',
                ]
            );
        }

        if (count($request->ratings) != 5) {
            return response()->json(
                [
                    'success' => false,
                    'message' => 'You must rate all categories.',
                ]
            );
        }

        $request->validate([
            'message' => 'required|string',
            'ratings' => 'required|array',
            'ratings.*.category' => 'required|string',
            'ratings.*.rating' => 'required|numeric|min:0|max:5'
        ]);

        $allRating = 0.0;
        foreach ($request->ratings as $rating) {
            $allRating += $rating['rating'];
        }

        Review::create([
            'service_id' => $request->service_id,
            'user_id' => Auth::id(),
            'booking_id' => null,
            'review' => $request->message,
            'rating' => $allRating / 5,
            'rating_attributes' => $request->ratings,
            'status' => false,
        ]);

        return response()->json(
            [
                'success' => true,
                'message' => 'Your review has been submitted and is pending approval.',
            ]
        );
    }
}
