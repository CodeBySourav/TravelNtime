<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Carbon\Carbon;
use App\Models\ApiToken;
use Illuminate\Support\Facades\Log;
use Exception;

class TboService
{

    protected $authUrl = "https://Sharedapi.tektravels.com/SharedData.svc/rest/Authenticate";

    protected $hotelSearchUrl = "https://HotelBE.tektravels.com/hotelservice.svc/rest/GetHotelResult";

    protected $hotelInfoUrl = "https://HotelBE.tektravels.com/hotelservice.svc/rest/GetHotelInfo";

    protected $hotelRoomUrl = "https://HotelBE.tektravels.com/hotelservice.svc/rest/GetHotelRoom";

    protected $blockRoomUrl = "https://HotelBE.tektravels.com/hotelservice.svc/rest/BlockRoom";

    protected $bookUrl = "https://HotelBE.tektravels.com/hotelservice.svc/rest/Book";

    protected $bookingDetailUrl = "https://HotelBE.tektravels.com/internalhotelservice.svc/rest/GetBookingDetail";

    protected $sendChangeRequestUrl ="https://HotelBE.tektravels.com/internalhotelservice.svc/rest/SendChangeRequest";

    protected $changeRequestStatusUrl ="https://HotelBE.tektravels.com/internalhotelservice.svc/rest/GetChangeRequestStatus";

    public function getToken()
    {
        $today = Carbon::today()->toDateString();

        // Check today's token
        $saved = ApiToken::where('token_date', $today)->first();

        if ($saved) {
            return $saved->token;
        }

        // Authenticate
        $response = Http::timeout(30)->post($this->authUrl, [
            "ClientId"  => config('services.tbo.client_id'),
            "UserName"  => config('services.tbo.username'),
            "Password"  => config('services.tbo.password'),
            "EndUserIp" => config('services.tbo.end_user_ip')
        ]);

        if (!$response->successful()) {
            throw new \Exception("Unable to connect to TBO.");
        }

        $data = $response->json();

        if (($data['Status'] ?? 0) != 1) {
            throw new \Exception($data['Error']['ErrorMessage'] ?? 'Authentication Failed');
        }

        $token = $data['TokenId'];

        ApiToken::updateOrCreate(
            ['token_date' => $today],
            ['token' => $token]
        );

        return $token;
    }

    public function searchHotels(array $request)
    {
        $token = $this->getToken();

        // Calculate number of nights
        $checkIn = Carbon::createFromFormat('d/m/Y', $request['checkin']);
        $checkOut = Carbon::createFromFormat('d/m/Y', $request['checkout']);

        $nights = $checkIn->diffInDays($checkOut);

        $payload = [

            "EndUserIp" => config('services.tbo.end_user_ip'),

            "TokenId" => $token,

            "CheckInDate" => $request['checkin'],

            "NoOfNights" => $nights,

            "CountryCode" => $request['country'] ?? "IN",

            "CityId" => (int)$request['city'],

            "ResultCount" => null,

            "PreferredCurrency" => "INR",

            "GuestNationality" => "IN",

            "NoOfRooms" => (int)($request['rooms'] ?? 1),

            "RoomGuests" => [
                [
                    "NoOfAdults" => (int)($request['adults'] ?? 2),
                    "NoOfChild" => (int)($request['children'] ?? 0),
                    "ChildAge" => []
                ]
            ],

            "MaxRating" => 5,

            "MinRating" => 0,

            "ReviewScore" => 0,

            "IsNearBySearchAllowed" => false
        ];

        $response = Http::timeout(120)
            ->acceptJson()
            ->post($this->hotelSearchUrl, $payload);

        if (!$response->successful()) {
            throw new \Exception(
                "Hotel Search Failed. HTTP Code : ".$response->status()
            );
        }

        $data = $response->json();

        return $data;
    }

    public function getHotelInfo($traceId, $resultIndex, $hotelCode)
    {
        $token = $this->getToken();

        $payload = [
            "EndUserIp"   => config('services.tbo.end_user_ip'),
            "TokenId"     => $token,
            "TraceId"     => $traceId,
            "ResultIndex" => (int) $resultIndex,
            "HotelCode"   => $hotelCode,
        ];

        $response = Http::acceptJson()
            ->timeout(60)
            ->post($this->hotelInfoUrl, $payload);

        if (!$response->successful()) {
            throw new \Exception('Unable to fetch hotel details.');
        }

        $data = $response->json();

        if (
            !isset($data['HotelInfoResult']['ResponseStatus']) ||
            $data['HotelInfoResult']['ResponseStatus'] != 1
        ) {
            throw new \Exception(
                $data['HotelInfoResult']['Error']['ErrorMessage']
                    ?? 'Hotel information not found.'
            );
        }

        return $data;
    }

    public function getHotelRoom($traceId, $resultIndex, $hotelCode)
    {
        $token = $this->getToken();

        $payload = [
            "EndUserIp"   => config('services.tbo.end_user_ip'),
            "TokenId"     => $token,
            "TraceId"     => $traceId,
            "ResultIndex" => (int) $resultIndex,
            "HotelCode"   => $hotelCode,
        ];

        $response = Http::acceptJson()
            ->timeout(120)
            ->post($this->hotelRoomUrl, $payload);
            \Log::info('BlockRoom Payload', $payload);
            \Log::info('BlockRoom Status', [
                'status' => $response->status(),
            ]);
            \Log::info('BlockRoom Response', [
    'body' => $response->body(),
]);

        if (!$response->successful()) {
            throw new \Exception(
                'Unable to fetch hotel room details. HTTP '.$response->status()
            );
        }

        $data = $response->json();

        if (
            !isset($data['GetHotelRoomResult']['ResponseStatus']) ||
            $data['GetHotelRoomResult']['ResponseStatus'] != 1
        ) {
            throw new \Exception(
                $data['GetHotelRoomResult']['Error']['ErrorMessage']
                    ?? 'Hotel room details not found.'
            );
        }

        return $data;
    }

    public function blockRoom(array $data)
{
    $token = $this->getToken();

    $room = $data['room'];

    $payload = [
        "EndUserIp"         => config('services.tbo.end_user_ip'),
        "TokenId"           => $token,
        "TraceId"           => $data['trace_id'],
        "ResultIndex"       => (int) $data['result_index'],
        "HotelCode"         => $data['hotel_code'],
        "HotelName"         => $data['hotel_name'],
        "GuestNationality"  => $data['guest_nationality'],
        "NoOfRooms"         => (int) $data['no_of_rooms'],
        "ClientReferenceNo" => "0",
        "IsVoucherBooking"  => true,

        "HotelRoomsDetails" => [
            [
                "RoomIndex"         => (int) $room['RoomIndex'],
                "RoomTypeCode"      => $room['RoomTypeCode'],
                "RoomTypeName"      => $room['RoomTypeName'],
                "RatePlanCode"      => $room['RatePlanCode'],
                "BedTypeCode"       => $room['BedTypeCode'] ?? null,
                "SmokingPreference" => is_numeric($room['SmokingPreference'] ?? null)
                    ? (int) $room['SmokingPreference']
                    : 0,
                "Supplements"       => $room['Supplements'] ?? null,

                "Price" => [
                    "CurrencyCode"             => $room['Price']['CurrencyCode'],
                    "RoomPrice"                => (float) $room['Price']['RoomPrice'],
                    "Tax"                      => (float) $room['Price']['Tax'],
                    "ExtraGuestCharge"         => (float) ($room['Price']['ExtraGuestCharge'] ?? 0),
                    "ChildCharge"             => (float) ($room['Price']['ChildCharge'] ?? 0),
                    "OtherCharges"            => (float) ($room['Price']['OtherCharges'] ?? 0),
                    "Discount"                => (float) ($room['Price']['Discount'] ?? 0),
                    "PublishedPrice"          => (float) $room['Price']['PublishedPrice'],
                    "PublishedPriceRoundedOff"=> (float) $room['Price']['PublishedPriceRoundedOff'],
                    "OfferedPrice"            => (float) $room['Price']['OfferedPrice'],
                    "OfferedPriceRoundedOff"  => (float) $room['Price']['OfferedPriceRoundedOff'],
                    "AgentCommission"         => (float) ($room['Price']['AgentCommission'] ?? 0),
                    "AgentMarkUp"             => (float) ($room['Price']['AgentMarkUp'] ?? 0),
                    "TDS"                     => (float) ($room['Price']['TDS'] ?? 0),
                ]
            ]
        ]
    ];

    Log::info('========== BLOCK ROOM REQUEST ==========');
    Log::info(json_encode($payload, JSON_PRETTY_PRINT));

    $response = Http::timeout(120)
        ->acceptJson()
        ->contentType('application/json')
        ->post($this->blockRoomUrl, $payload);

    Log::info('========== BLOCK ROOM RESPONSE ==========');
    Log::info('Status : ' . $response->status());
    Log::info($response->body());

    if (!$response->successful()) {
        throw new \Exception(
            "HTTP {$response->status()}\n\n{$response->body()}"
        );
    }

    $result = $response->json();

    if (
        !isset($result['BlockRoomResult']) ||
        $result['BlockRoomResult']['ResponseStatus'] != 1
    ) {
        throw new \Exception(
            $result['BlockRoomResult']['Error']['ErrorMessage']
                ?? 'Unable to block room.'
        );
    }

    return $result['BlockRoomResult'];
}

    public function bookHotel(array $payload)
    {
        $token = $this->getToken();

        $payload['EndUserIp'] = config('services.tbo.end_user_ip');
        $payload['TokenId'] = $token;

        $response = Http::timeout(120)
    ->acceptJson()
    ->contentType('application/json')
    ->post($this->bookUrl, $payload);

    Log::info('BOOK API STATUS', [
        'status' => $response->status()
    ]);

    Log::info('BOOK API RESPONSE', [
        'body' => $response->body()
    ]);

    if (!$response->successful()) {
        throw new \Exception($response->body());
    }

        $result = $response->json();

        if (
            !isset($result['BookResult']['ResponseStatus']) ||
            $result['BookResult']['ResponseStatus'] != 1
        ) {

            throw new \Exception(
                $result['BookResult']['Error']['ErrorMessage']
                ?? 'Booking failed.'
            );

        }

        return $result['BookResult'];
    }

    public function getBookingDetail(int $bookingId)
    {
        $payload = [

            "EndUserIp" => config('services.tbo.end_user_ip'),

            "TokenId" => $this->getToken(),

            "BookingId" => $bookingId

        ];
        Log::info('getBookingDetail'.json_encode($payload));
        $response = Http::timeout(120)
            ->acceptJson()
            ->contentType('application/json')
            ->post($this->bookingDetailUrl, $payload);

        Log::info('BOOKING DETAIL REQUEST', $payload);

        Log::info('BOOKING DETAIL RESPONSE', [
            'status' => $response->status(),
            'body' => $response->body()
        ]);

        if (!$response->successful()) {
            throw new \Exception(
                'Unable to fetch booking details.'
            );
        }

        $result = $response->json();

        if (
            !isset($result['GetBookingDetailResult'])
        ) {
            throw new \Exception(
                'Invalid booking detail response.'
            );
        }

        return $result['GetBookingDetailResult'];
    }

public function sendChangeRequest(int $bookingId, string $remarks = 'Cancelled by customer')
{
    $payload = [
        "BookingMode" => 5,
        "RequestType" => 4,
        "Remarks" => $remarks,
        "BookingId" => $bookingId,
        "EndUserIp" => config('services.tbo.end_user_ip'),
        "TokenId" => $this->getToken(),
    ];

    Log::info('========== CANCEL REQUEST ==========');
    Log::info(json_encode($payload, JSON_PRETTY_PRINT));

    $response = Http::acceptJson()
        ->contentType('application/json')
        ->timeout(120)
        ->post($this->sendChangeRequestUrl, $payload);

    Log::info('========== CANCEL HTTP RESPONSE ==========');
    Log::info([
        'status' => $response->status(),
        'body'   => $response->body(),
    ]);

    if (!$response->successful()) {
        throw new Exception('Unable to cancel booking.');
    }

    $result = $response->json();

    Log::info('========== CANCEL PARSED RESPONSE ==========');
    Log::info($result);

    $hotelResult = $result['HotelChangeRequestResult'] ?? null;

    if (!$hotelResult || $hotelResult['ResponseStatus'] != 1) {
        throw new Exception(
            $hotelResult['Error']['ErrorMessage'] ?? 'Cancellation failed.'
        );
    }

    return $hotelResult;
}

public function getChangeRequestStatus(int $changeRequestId)
{
    $payload = [
        "BookingMode"     => 5,
        "ChangeRequestId" => $changeRequestId,
        "EndUserIp"       => config('services.tbo.end_user_ip'),
        "TokenId"         => $this->getToken(),
    ];

    Log::info('========== CHANGE REQUEST STATUS REQUEST ==========');
    Log::info(json_encode($payload, JSON_PRETTY_PRINT));

    $response = Http::acceptJson()
        ->contentType('application/json')
        ->timeout(120)
        ->post($this->changeRequestStatusUrl, $payload);

    Log::info('========== CHANGE REQUEST STATUS RESPONSE ==========');
    Log::info([
        'status' => $response->status(),
        'body'   => $response->body(),
    ]);

    if (!$response->successful()) {
        throw new Exception(
            "Unable to fetch change request status. HTTP " . $response->status()
        );
    }

    $result = $response->json();

    Log::info('========== CHANGE REQUEST STATUS PARSED ==========');
    Log::info($result);

    if (
        !isset($result['HotelChangeRequestStatusResult']) ||
        $result['HotelChangeRequestStatusResult']['ResponseStatus'] != 1
    ) {
        throw new Exception(
            $result['HotelChangeRequestStatusResult']['Error']['ErrorMessage']
            ?? 'Failed to get change request status.'
        );
    }

    return $result['HotelChangeRequestStatusResult'];
}
}