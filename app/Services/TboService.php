<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Carbon\Carbon;
use App\Models\ApiToken;

class TboService
{

    protected $authUrl = "https://Sharedapi.tektravels.com/SharedData.svc/rest/Authenticate";

    protected $hotelSearchUrl = "https://HotelBE.tektravels.com/hotelservice.svc/rest/GetHotelResult";

    protected $hotelInfoUrl = "https://HotelBE.tektravels.com/hotelservice.svc/rest/GetHotelInfo";

    protected $hotelRoomUrl = "https://HotelBE.tektravels.com/hotelservice.svc/rest/GetHotelRoom";

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

}