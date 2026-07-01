@extends('layout_inner_page')

@section('title')
    <title>{{ $hotelDetails['HotelName'] ?? __('Hotel Details') }}</title>
    <meta name="title" content="{{ $hotelDetails['HotelName'] ?? __('Hotel Details') }}">
    <meta name="description" content="{{ strip_tags($hotelDetails['Description'] ?? __('Hotel Details')) }}">
@endsection

@section('front-content')
    <!-- main-area -->
    <main class="bg-light py-4">

        <!-- Breadcrumb Navigation -->
        <div class="tg-breadcrumb-list-2-wrap mb-4">
            <div class="container">
                <div class="row">
                    <div class="col-12">
                        <div class="tg-breadcrumb-list-2">
                            <ul class="list-inline m-0 p-0 d-flex align-items-center flex-wrap">
                                <li class="list-inline-item"><a href="{{ url('home') }}"
                                        class="text-secondary">{{ __('Home') }}</a></li>
                                <li class="list-inline-item mx-2 text-muted"><i
                                        class="fa-sharp fa-solid fa-angle-right small"></i></li>
                                <li class="list-inline-item"><a
                                        href="{{ route('front.tourbooking.services') }}">{{ __('Services') }}</a></li>
                                <li class="list-inline-item mx-2 text-muted"><i
                                        class="fa-sharp fa-solid fa-angle-right small"></i></li>
                                <li class="list-inline-item active text-dark font-weight-bold">
                                    <span>{{ $hotelDetails['HotelName'] ?? '' }}</span></li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="container">
            <!-- Hotel Title & Star Rating Header -->
            <div class="d-md-flex justify-content-between align-items-start mb-4">
                <div>
                    <h1 class="h2 font-weight-bold mb-2 text-dark">{{ $hotelDetails['HotelName'] ?? '' }}</h1>
                    @if (!empty($hotelDetails['Address']))
                        <p class="text-muted mb-0 lead-sm">
                            <i
                                class="fa-solid fa-location-dot text-danger mr-2"></i>{{ $hotelDetails['Address'] }}{{ !empty($hotelDetails['PinCode']) ? ' - ' . $hotelDetails['PinCode'] : '' }}
                        </p>
                    @endif
                </div>
                <div class="mt-2 mt-md-0 flex-shrink-0">
                    @if (!empty($hotelDetails['StarRating']))
                        <div class="text-warning h5 mb-0">
                            @for ($i = 0; $i < $hotelDetails['StarRating']; $i++)
                                <i class="fa-solid fa-star"></i>
                            @endfor
                            <span class="text-muted ml-2 small font-weight-normal">({{ $hotelDetails['StarRating'] }}-Star
                                Property)</span>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Modern Interactive Image Gallery Grid -->
            @if (!empty($hotelDetails['Images']) && is_array($hotelDetails['Images']))
                <div class="row no-gutters rounded-lg overflow-visible mb-5 bg-light gallery-container">

                    <!-- Left Column: Main Featured Large Image -->
                    <div class="col-md-7 p-1">
                        <a href="{{ $hotelDetails['Images'][0] }}" data-fancybox="hotel-gallery"
                            data-caption="{{ $hotelDetails['HotelName'] }}" class="gallery-link">
                            <div class="w-100 h-100">
                                <img src="{{ $hotelDetails['Images'][0] }}" alt="{{ $hotelDetails['HotelName'] }}"
                                    class="img-fluid w-100 h-100 object-fit-cover rounded-lg shadow-sm">
                            </div>
                        </a>
                    </div>

                    <!-- Right Column: 1 Top Wide Image + 2 Bottom Split Images -->
                    <div class="col-md-5 d-none d-md-block">
                        <div class="row no-gutters h-100">

                            <!-- Top Secondary Image (Wide) -->
                            @if (isset($hotelDetails['Images'][1]))
                                <div class="col-12 p-1" style="height: 50%;">
                                    <a href="{{ $hotelDetails['Images'][1] }}" data-fancybox="hotel-gallery"
                                        data-caption="{{ $hotelDetails['HotelName'] }}" class="gallery-link">
                                        <img src="{{ $hotelDetails['Images'][1] }}" alt="{{ $hotelDetails['HotelName'] }}"
                                            class="img-fluid w-100 h-100 object-fit-cover rounded-lg shadow-sm">
                                    </a>
                                </div>
                            @endif

                            <!-- Bottom Left Split Image -->
                            @if (isset($hotelDetails['Images'][2]))
                                <div class="col-6 p-1" style="height: 50%;">
                                    <a href="{{ $hotelDetails['Images'][2] }}" data-fancybox="hotel-gallery"
                                        data-caption="{{ $hotelDetails['HotelName'] }}" class="gallery-link">
                                        <img src="{{ $hotelDetails['Images'][2] }}" alt="{{ $hotelDetails['HotelName'] }}"
                                            class="img-fluid w-100 h-100 object-fit-cover rounded-lg shadow-sm">
                                    </a>
                                </div>
                            @endif

                            <!-- Bottom Right Split Image + "See More" Trigger -->
                            @if (isset($hotelDetails['Images'][3]))
                                <div class="col-6 p-1 position-relative" style="height: 50%;">
                                    <a href="{{ $hotelDetails['Images'][3] }}" data-fancybox="hotel-gallery"
                                        data-caption="{{ $hotelDetails['HotelName'] }}" class="gallery-link">
                                        <img src="{{ $hotelDetails['Images'][3] }}" alt="{{ $hotelDetails['HotelName'] }}"
                                            class="img-fluid w-100 h-100 object-fit-cover rounded-lg shadow-sm">
                                    </a>

                                    <!-- Clickable "See More Photos +" label link directly beneath the image framework -->
                                    @if (count($hotelDetails['Images']) > 4)
                                        <div class="position-absolute text-right"
                                            style="bottom: -25px; right: 8px; z-index: 100;">
                                            <a href="javascript:;" id="trigger-remaining-gallery"
                                                class="text-secondary font-weight-bold text-decoration-none"
                                                style="font-size: 0.85rem; background: transparent; display: inline-block !important; width: auto !important; height: auto !important;">
                                                {{ __('See More Photos +') }}
                                            </a>
                                        </div>
                                    @endif
                                </div>
                            @endif

                        </div>
                    </div>

                    <!-- Hidden container for remaining images so they populate the slider -->
                    @if (count($hotelDetails['Images']) > 4)
                        <div class="d-none">
                            @foreach (array_slice($hotelDetails['Images'], 4) as $remainingImage)
                                <a href="{{ $remainingImage }}" data-fancybox="hotel-gallery"
                                    data-caption="{{ $hotelDetails['HotelName'] }}"></a>
                            @endforeach
                        </div>
                    @endif

                </div>
            @endif

            <!-- Fancybox CSS -->
            <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@fancyapps/ui@5.0/dist/fancybox/fancybox.css" />

            <style>
                .object-fit-cover {
                    object-fit: cover !important;
                }

                .rounded-lg {
                    border-radius: 12px !important;
                }

                .gallery-container {
                    height: 420px;
                    overflow: visible !important;
                    /* Critical to ensure the "See More" label beneath doesn't get clipped */
                }

                /* Structured link blocks for images */
                .gallery-container a.gallery-link {
                    display: block;
                    height: 100%;
                    width: 100%;
                    overflow: hidden;
                    border-radius: 12px;
                }

                .gallery-container img {
                    transition: transform 0.3s ease;
                }

                .gallery-container a.gallery-link:hover img {
                    transform: scale(1.02);
                }

                #trigger-remaining-gallery:hover {
                    color: #007bff !important;
                    text-decoration: underline !important;
                }

                @media (max-width: 767.98px) {
                    .gallery-container {
                        height: 260px;
                        overflow: hidden !important;
                    }
                }
            </style>

            <!-- Fancybox JS Package -->
            <script src="https://cdn.jsdelivr.net/npm/@fancyapps/ui@5.0/dist/fancybox/fancybox.umd.js"></script>

            <script>
                document.addEventListener("DOMContentLoaded", function() {
                    // Initializes the image popup slider
                    Fancybox.bind("[data-fancybox='hotel-gallery']", {
                        Infinite: true,
                        Transition: "fade",
                        Thumbs: {
                            autoStart: true,
                        }
                    });

                    // Makes the "See More Photos +" text indicator open the slider instantly
                    const seeMoreBtn = document.getElementById('trigger-remaining-gallery');
                    if (seeMoreBtn) {
                        seeMoreBtn.addEventListener('click', function(e) {
                            e.preventDefault();
                            // Opens up the first element of the gallery stream
                            const firstImg = document.querySelector("[data-fancybox='hotel-gallery']");
                            if (firstImg) firstImg.click();
                        });
                    }
                });
            </script>


            <!-- Main Content Splits: Details vs Sidebar info -->
            <div class="row">
                <!-- Left Details Column -->
                <div class="col-lg-8">

                    <!-- Description Block -->
                    <div class="card border-0 shadow-sm mb-4 rounded-lg">
                        <div class="card-body p-4">
                            <h3 class="h4 font-weight-bold border-bottom pb-3 mb-3 text-dark">
                                <i class="fa-solid fa-circle-info text-primary mr-2"></i>{{ __('About This Property') }}
                            </h3>
                            <div class="hotel-description text-secondary style-description">
                                {{-- Parsing clean description --}}
                                @if (!empty($hotelDetails['Description']))
                                    {!! preg_replace('/Did you know that we’ve got.*?and more\./i', '', $hotelDetails['Description']) !!}
                                @else
                                    <p class="text-muted">{{ __('No description available.') }}</p>
                                @endif
                            </div>
                        </div>
                    </div>

                    <!-- Hotel Amenities Grid -->
                    @if (!empty($hotelDetails['HotelFacilities']) && is_array($hotelDetails['HotelFacilities']))
                        <div class="card border-0 shadow-sm mb-4 rounded-lg">
                            <div class="card-body p-4">
                                <h3 class="h4 font-weight-bold border-bottom pb-3 mb-3 text-dark">
                                    <i
                                        class="fa-solid fa-bell-concierge text-primary mr-2"></i>{{ __('Property Amenities') }}
                                </h3>
                                <div class="row">
                                    @foreach ($hotelDetails['HotelFacilities'] as $facility)
                                        <div class="col-sm-6 col-md-4 mb-3">
                                            <div class="d-flex align-items-center text-secondary">
                                                <i class="fa-solid fa-circle-check text-success mr-2"></i>
                                                <span>{{ ucwords(str_replace('_', ' ', $facility)) }}</span>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    @endif

                    <!-- Dynamic Rooms & Rates Section -->
                    <div class="mb-4">
                        <h3 class="h4 font-weight-bold mb-3 text-dark">
                            <i class="fa-solid fa-bed text-primary mr-2"></i>{{ __('Available Room Options') }}
                        </h3>

                        @if (!empty($roomDetails) && is_array($roomDetails))
                            @foreach ($roomDetails as $room)
                                <div class="card border-0 shadow-sm mb-3 rounded-lg overflow-hidden border-left-prime">
                                    <div class="card-body p-4">
                                        <div class="row align-items-center">
                                            <div class="col-md-7 mb-3 mb-md-0">
                                                <span
                                                    class="badge badge-primary px-3 py-2 mb-2 rounded-pill font-weight-bold">
                                                    {{ $room['RoomDescription'] ?? 'Standard Room' }}
                                                </span>
                                                <h4 class="h5 font-weight-bold text-dark mb-2">
                                                    {{ $room['RoomTypeName'] ?? 'Classic Package' }}</h4>

                                                <!-- Room Details / Inclusions -->
                                                @if (!empty($room['Inclusion']))
                                                    <div class="d-flex flex-wrap align-items-center mt-2">
                                                        @foreach ($room['Inclusion'] as $inc)
                                                            <span class="text-success small font-weight-bold mr-3">
                                                                <i class="fa-solid fa-utensils mr-1"></i>
                                                                {{ $inc }}
                                                            </span>
                                                        @endforeach
                                                    </div>
                                                @endif

                                                <!-- Cancellation Policy Hint -->
                                                @if (!empty($room['LastCancellationDate']))
                                                    <p class="text-muted small mt-3 mb-0">
                                                        <i class="fa-solid fa-clock mr-1 text-warning"></i>
                                                        {{ __('Free Cancellation till:') }} <span
                                                            class="font-weight-bold text-dark">{{ \Carbon\Carbon::parse($room['LastCancellationDate'])->format('d M, Y H:i') }}</span>
                                                    </p>
                                                @endif
                                            </div>

                                            <div class="col-md-5 text-md-right border-left-md py-2">
                                                <p class="text-muted mb-1 small">{{ __('Price for 1 Night') }}</p>
                                                <h3 class="font-weight-black text-primary mb-1">
                                                    ₹{{ number_format($room['Price']['OfferedPriceRoundedOff'] ?? ($room['Price']['RoomPrice'] ?? 0)) }}
                                                </h3>
                                                <p class="text-muted small mb-3">
                                                    <del
                                                        class="mr-1">₹{{ number_format($room['Price']['PublishedPriceRoundedOff'] ?? 0) }}</del>
                                                    <span class="text-success font-weight-bold">
                                                        ({{ __('Save') }}
                                                        ₹{{ number_format(($room['Price']['PublishedPriceRoundedOff'] ?? 0) - ($room['Price']['OfferedPriceRoundedOff'] ?? 0)) }})
                                                    </span>
                                                </p>
                                                <button
                                                    class="btn btn-booking btn-block font-weight-bold text-uppercase shadow-sm">
                                                    {{ __('Book Room') }} <i class="fa-solid fa-arrow-right ml-1"></i>
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        @else
                            <div class="alert alert-warning border-0 shadow-sm" role="alert">
                                <i class="fa-solid fa-triangle-exclamation mr-2"></i>
                                {{ __('No rooms currently available for the selected dates.') }}
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Right Information Sidebar Box -->
                <div class="col-lg-4">
                    <div class="card border-0 shadow-sm sticky-top mb-4 rounded-lg" style="top: 30px; z-index: 10;">
                        <div class="card-body p-4">
                            <h4 class="font-weight-bold mb-3 text-dark d-flex align-items-center">
                                <i class="fa-solid fa-calendar-days text-primary mr-2"></i> {{ __('Stay Overview') }}
                            </h4>
                            <hr class="my-3">
                            <ul class="list-unstyled mb-4">
                                <li class="d-flex justify-content-between align-items-center mb-3">
                                    <span class="text-muted"><i
                                            class="fa-solid fa-door-open mr-2 text-success"></i>{{ __('Check-In') }}</span>
                                    <span class="font-weight-bold badge badge-light border px-2 py-1">After 12:00 PM</span>
                                </li>
                                <li class="d-flex justify-content-between align-items-center mb-3">
                                    <span class="text-muted"><i
                                            class="fa-solid fa-door-closed mr-2 text-danger"></i>{{ __('Check-Out') }}</span>
                                    <span class="font-weight-bold badge badge-light border px-2 py-1">Before 11:00
                                        AM</span>
                                </li>
                                <li class="d-flex justify-content-between align-items-center mb-3">
                                    <span class="text-muted"><i
                                            class="fa-solid fa-hotel mr-2 text-info"></i>{{ __('Property Type') }}</span>
                                    <span class="font-weight-bold text-dark text-right">3-Star Budget</span>
                                </li>
                            </ul>

                            <!-- Dynamic & Structured Restrictions Box -->
                            @if (!empty($hotelDetails['HotelPolicy']))
                                @php
                                    // Explode and filter out generic system flags from API strings
                                    $rawPolicies = explode('|', $hotelDetails['HotelPolicy']);
                                    $bannedKeywords = ['specific_restrictions', 'One Liner', 'no_packages', '', ' '];
                                    $cleanedPolicies = array_filter(array_map('trim', $rawPolicies), function (
                                        $policy,
                                    ) use ($bannedKeywords) {
                                        return !empty($policy) &&
                                            !in_array($policy, $bannedKeywords) &&
                                            !str_contains($policy, '#');
                                    });
                                @endphp

                                @if (!empty($cleanedPolicies))
                                    <div class="bg-warning-light p-3 rounded mb-4 border border-warning-dims">
                                        <h6 class="font-weight-bold text-warning-dark mb-2">
                                            <i class="fa-solid fa-triangle-exclamation mr-1"></i>
                                            {{ __('Important Rules') }}:
                                        </h6>
                                        <ul class="list-unstyled small mb-0 text-dark font-weight-medium">
                                            @foreach ($cleanedPolicies as $policy)
                                                <li class="mb-1 d-flex align-items-start">
                                                    <i class="fa-solid fa-minus text-warning-dark mr-2 mt-1"></i>
                                                    <span>{{ $policy }}</span>
                                                </li>
                                            @endforeach
                                        </ul>
                                    </div>
                                @endif
                            @endif

                            <a href="#available-rooms"
                                class="btn btn-primary btn-block btn-lg shadow-sm font-weight-bold text-uppercase">
                                <i class="fa-solid fa-eye mr-2"></i>{{ __('View All Rates') }}
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>
@endsection

<!-- Upgraded & Isolated CSS Utilities -->
<style>
    .object-fit-cover {
        object-fit: cover !important;
    }

    .rounded-lg {
        border-radius: 0.6rem !important;
    }

    .gallery-container {
        height: 380px;
    }

    @media (max-width: 767.98px) {
        .gallery-container {
            height: auto;
        }

        .border-left-md {
            border-left: none !important;
        }
    }

    .gallery-overlay {
        background: rgba(0, 0, 0, 0.7);
        backdrop-filter: blur(2px);
        transition: background 0.2s ease;
    }

    .gallery-container:hover .gallery-overlay {
        background: rgba(0, 0, 0, 0.8);
    }

    .border-left-prime {
        border-left: 4px solid #007bff !important;
    }

    .border-left-md {
        border-left: 1px solid #e3e6f0;
    }

    .font-weight-black {
        font-weight: 800 !important;
    }

    .bg-warning-light {
        background-color: #fffaf0;
    }

    .border-warning-dims {
        border-color: #ffeeba !important;
    }

    .text-warning-dark {
        color: #856404 !important;
    }

    .font-weight-medium {
        font-weight: 500;
    }

    .btn-booking {
        background-color: #28a745;
        color: #fff;
    }

    .btn-booking:hover {
        background-color: #218838;
        color: #fff;
    }

    .hotel-description p {
        margin-bottom: 0.85rem;
        line-height: 1.6;
    }
</style>
