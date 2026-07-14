<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use App\Services\TboService;
use App\Models\HotelBooking;
use Illuminate\Support\Facades\Log;

class HotelBookingController extends Controller
{

protected $tbo;

    public function __construct(TboService $tbo)
    {
        $this->tbo = $tbo;
    }
    
    
    public function blockRoom(Request $request)
    {
        $room = json_decode(base64_decode($request->room), true);

        $room['Price'] = $room['Price'] ?? [];
        $room['Supplements'] = $room['Supplements'] ?? [];
        $room['BedTypes'] = $room['BedTypes'] ?? [];

        $result = $this->tbo->blockRoom([

            'trace_id' => $request->trace_id,

            'result_index' => $request->result_index,

            'hotel_code' => $request->hotel_code,

            'hotel_name' => $request->hotel_name,

            'guest_nationality' => 'IN',

            'no_of_rooms' => 1,

            'room' => $room

        ]);

        if ($result['IsPriceChanged']) {

            return back()->with(
                'error',
                'Price changed. Please book again.'
            );

        }

        if ($result['IsCancellationPolicyChanged']) {

            return back()->with(
                'error',
                'Cancellation policy changed.'
            );

        }

        session([
            'blockRoom'   => $result,
            'hotelCode'   => $request->hotel_code,
            'hotelName'   => $request->hotel_name,
            'traceId'     => $request->trace_id,
            'resultIndex' => $request->result_index,
        ]);

        return redirect()->route('hotel.guest.details');
    }


    public function guestDetails()
    {
        abort_unless(session()->has('blockRoom'), 404);

        $blockRoom = session('blockRoom');

        return view('tourbooking::hotel.guest-details', [
            'blockRoom' => $blockRoom,
        ]);
    }

    public function book(Request $request)
    {
        $request->validate([
            'guests' => 'required|array|min:1',
            'guests.*.first_name' => 'required|string|max:50',
            'guests.*.last_name' => 'required|string|max:50',
            'email' => 'required|email',
            'mobile' => 'required|string|max:20',
        ]);

        $blockRoom = session('blockRoom');

        if (!$blockRoom) {
            return redirect()
                ->route('hotel.search')
                ->with('error', 'Booking session expired. Please search again.');
        }

        $room = $blockRoom['HotelRoomsDetails'][0] ?? null;

        if (!$room) {
            return back()->with('error', 'Invalid Block Room response.');
        }

        /*
        |--------------------------------------------------------------------------
        | Passenger List
        |--------------------------------------------------------------------------
        */

        $passengers = [];

        foreach ($request->guests as $index => $guest) {

            $passengers[] = [
                "Title" => $guest['title'] ?? 'Mr',
                "FirstName" => trim($guest['first_name']),
                "MiddleName" => "",
                "LastName" => trim($guest['last_name']),
                "Phoneno" => $request->mobile,
                "Email" => $request->email,
                "PaxType" => 1,
                "LeadPassenger" => $index === 0,
                "Age" => (int)($guest['age'] ?? 30),
                "PassportNo" => "",
                "PassportIssueDate" => "0001-01-01T00:00:00",
                "PassportExpDate" => "0001-01-01T00:00:00",
                "PAN" => ""
            ];
        }

        /*
        |--------------------------------------------------------------------------
        | Smoking Preference
        |--------------------------------------------------------------------------
        */

        $smokingPreference = 0;

        if (isset($room['SmokingPreference'])) {

            if (is_numeric($room['SmokingPreference'])) {

                $smokingPreference = (int) $room['SmokingPreference'];

            } else {

                switch (strtolower($room['SmokingPreference'])) {

                    case 'smoking':
                        $smokingPreference = 1;
                        break;

                    case 'nonsmoking':
                        $smokingPreference = 2;
                        break;

                    default:
                        $smokingPreference = 0;
                }
            }
        }

        /*
        |--------------------------------------------------------------------------
        | Booking Payload
        |--------------------------------------------------------------------------
        */

        $payload = [

            "EndUserIp" => config('services.tbo.end_user_ip'),

            "TokenId" => $this->tbo->getToken(),

            "TraceId" => $blockRoom['TraceId'],

            "ResultIndex" => session('resultIndex'),

            "HotelCode" => session('hotelCode'),

            "HotelName" => $blockRoom['HotelName'],

            "GuestNationality" => "IN",

            "NoOfRooms" => 1,

            "ClientReferenceNo" => (string) time(),

            "IsVoucherBooking" => true,

            "IsPackageFare" => $blockRoom['IsPackageFare'] ?? false,

            "HotelRoomsDetails" => [[

                "RoomIndex" => $room['RoomIndex'],

                "RoomTypeCode" => $room['RoomTypeCode'],

                "RoomTypeName" => $room['RoomTypeName'],

                "RatePlanCode" => $room['RatePlanCode'],

                "SmokingPreference" => $smokingPreference,

                "Supplements" => [],

                "Price" => $room['Price'],

                "HotelPassenger" => $passengers

            ]]
        ];

        Log::info('BOOK REQUEST', $payload);

        try {

            $bookingResponse = $this->tbo->bookHotel($payload);

            Log::info('BOOK RESPONSE', $bookingResponse);

            /*
            |--------------------------------------------------------------------------
            | Verify Price
            |--------------------------------------------------------------------------
            */

            if (($bookingResponse['Status'] ?? 0) == 3) {

                session()->put('verify_price', $bookingResponse);

                return redirect()
                    ->route('hotel.verify.price')
                    ->with('warning', 'Price or cancellation policy has changed.');
            }

            /*
            |--------------------------------------------------------------------------
            | Booking Failed
            |--------------------------------------------------------------------------
            */

            if (($bookingResponse['Status'] ?? 0) != 1) {

                throw new \Exception(
                    $bookingResponse['Error']['ErrorMessage'] ?? 'Booking failed.'
                );
            }

            /*
            |--------------------------------------------------------------------------
            | Save Booking
            |--------------------------------------------------------------------------
            */

            $hotelBooking = HotelBooking::create([

                'user_id' => auth()->id(),

                'booking_id' => $bookingResponse['BookingId'] ?? null,

                'booking_ref' => $bookingResponse['BookingRefNo'] ?? null,

                'confirmation_no' => $bookingResponse['ConfirmationNo'] ?? null,

                'trace_id' => $blockRoom['TraceId'],

                'hotel_code' => session('hotelCode'),

                'hotel_name' => $blockRoom['HotelName'],

                'lead_name' => $passengers[0]['FirstName'].' '.$passengers[0]['LastName'],

                'email' => $request->email,

                'mobile' => $request->mobile,

                'amount' => $room['Price']['OfferedPrice'],

                'booking_status' => $bookingResponse['HotelBookingStatus'] ?? null,

                'response' => json_encode($bookingResponse),

            ]);

            /*
            |--------------------------------------------------------------------------
            | Store booking id for success page
            |--------------------------------------------------------------------------
            */

            session([
                'booking_success_id' => $hotelBooking->id
            ]);

            /*
            |--------------------------------------------------------------------------
            | Clear search session
            |--------------------------------------------------------------------------
            */

            session()->forget([
                'blockRoom',
                'resultIndex',
                'hotelCode',
            ]);

            return redirect()
                ->route('hotel.booking.success');

        } catch (\Throwable $e) {

            Log::error('BOOK FAILED', [

                'message' => $e->getMessage(),

                'payload' => $payload,

            ]);

            return back()->with('error', $e->getMessage());
        }
    }

    

        public function bookingSuccess()
    {
        $booking = HotelBooking::findOrFail(session('booking_success_id'));

        return view('tourbooking::hotel.booking-success', compact('booking'));
    }
    public function myBookings()
    {
        $bookings = HotelBooking::where('user_id', 2)
            ->latest()
            ->get();

        foreach ($bookings as $booking) {

            try {

                $detail = $this->tbo->getBookingDetail($booking->booking_id);
                        Log::info('Sourav Booking Detail: ' . json_encode($detail, JSON_PRETTY_PRINT));
                // Save API response for Blade
                $booking->live = $detail;

                // Update database status
                $booking->update([
                    'booking_status' => $detail['HotelBookingStatus'] ?? $booking->booking_status,
                ]);

            } catch (\Exception $e) {

                $booking->live = null;

                Log::error($e->getMessage());
            }
        }

        return view('tourbooking::hotel.my-bookings', compact('bookings'));
    }
    public function show(HotelBooking $booking)
    {
        try {

            $details = $this->tbo->getBookingDetail(
                $booking->booking_id
            );

            return view(
                'hotel.booking-details',
                compact('booking', 'details')
            );

        } catch (\Throwable $e) {

            return back()->with(
                'error',
                $e->getMessage()
            );
        }
    }


    public function cancel(HotelBooking $booking)
    {
        abort_unless($booking->user_id == 2, 403);

        try {

        $change = $this->tbo->sendChangeRequest($booking->booking_id);

    $status = $this->tbo->getChangeRequestStatus(
        $change['ChangeRequestId']
    );

    $detail = $this->tbo->getBookingDetail(
        $booking->booking_id
    );

    $booking->update([
        'change_request_id'     => $change['ChangeRequestId'],
        'change_request_status' => $status['ChangeRequestStatus'] ?? null,
        'booking_status'        => $detail['HotelBookingStatus'],
        'response'              => json_encode($detail),
        'cancelled_at'          => now(),
    ]);

            return back()->with(
                'success',
                'Booking cancelled successfully.'
            );

        } catch (\Throwable $e) {

            Log::error($e);

            return back()->with(
                'error',
                $e->getMessage()
            );
        }
    }


    public function destinationSearch(Request $request)
    {
        Log::info('Destination Search Controller Called');

    Log::info('Request Data', [
        'keyword' => $request->keyword,
        'country' => $request->country,
    ]); 
        try {

            $keyword = strtolower(trim($request->keyword));

            $destinations = $this->tbo->getDestinations(
                $request->country ?? 'IN',
                '1' // 1 = City
            );

            if (!empty($keyword)) {

                $destinations = collect($destinations)
                    ->filter(function ($item) use ($keyword) {

                        return str_contains(
                            strtolower($item['DestinationName'] ?? ''),
                            $keyword
                        );

                    })
                    ->values();

            }

            return response()->json($destinations);

        } catch (\Throwable $e) {

            \Log::error('Destination Search Error', [
                'message' => $e->getMessage()
            ]);

            return response()->json([
                'status' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }
}
