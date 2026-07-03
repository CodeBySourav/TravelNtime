<?php

namespace App\Services;

use App\Models\ApiToken;
use Carbon\Carbon;
use Exception;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class TboFlightService
{
    /**
     * Authentication URL
     */
    protected $authUrl = "https://Sharedapi.tektravels.com/SharedData.svc/rest/Authenticate";

    /**
     * Flight Search URL
     */
    protected $searchUrl = "https://api.tektravels.com/BookingEngineService_Air/AirService.svc/rest/Search";

    /**
     * Get TBO Token
     */
    public function getToken()
    {
        $today = Carbon::today()->toDateString();

        $saved = ApiToken::where('token_date', $today)->first();

        if ($saved) {
            return $saved->token;
        }

        $response = Http::timeout(60)->post($this->authUrl, [
            "ClientId"  => config('services.tbo.client_id'),
            "UserName"  => config('services.tbo.username'),
            "Password"  => config('services.tbo.password'),
            "EndUserIp" => config('services.tbo.end_user_ip'),
        ]);

        if (!$response->successful()) {
            throw new Exception("Unable to connect to TBO Authentication API.");
        }

        $data = $response->json();

        if (($data['Status'] ?? 0) != 1) {
            throw new Exception(
                $data['Error']['ErrorMessage'] ?? 'TBO Authentication Failed'
            );
        }

        ApiToken::updateOrCreate(
            ['token_date' => $today],
            ['token' => $data['TokenId']]
        );

        return $data['TokenId'];
    }

    /**
     * Flight Search
     */
    public function searchFlights(array $request)
    {
        $token = $this->getToken();
        $journeyType = (int)($request['journey_type'] ?? 1);
        $segments = [
            [
                "Origin" => strtoupper($request['origin']),
                "Destination" => strtoupper($request['destination']),
                "FlightCabinClass" => (int)($request['cabin'] ?? 2),
                "PreferredDepartureTime" => $request['departure_date'] . "T00:00:00",
                "PreferredArrivalTime" => $request['departure_date'] . "T00:00:00",
            ]
        ];

        if (
            in_array($journeyType, [2, 5]) &&
            !empty($request['return_date'])
        ) {
            $segments[] = [
                "Origin" => strtoupper($request['destination']),
                "Destination" => strtoupper($request['origin']),
                "FlightCabinClass" => (int)($request['cabin'] ?? 2),
                "PreferredDepartureTime" => $request['return_date'] . "T00:00:00",
                "PreferredArrivalTime" => $request['return_date'] . "T00:00:00",
            ];
        }

        $payload = [

            "EndUserIp" => config('services.tbo.end_user_ip'),

            "TokenId" => $token,

            "AdultCount" => (int)($request['adults'] ?? 1),

            "ChildCount" => (int)($request['children'] ?? 0),

            "InfantCount" => (int)($request['infants'] ?? 0),

            "DirectFlight" => (bool)($request['direct_flight'] ?? false),

            "OneStopFlight" => (bool)($request['one_stop_flight'] ?? false),

            "JourneyType" => (int)$request['journey_type'],

            "Segments" => $segments,

        ];

        if (!empty($request['preferred_airlines'])) {
            $filtered = array_filter($request['preferred_airlines'], fn($v) => !empty($v));
            if (!empty($filtered)) {
                $payload['PreferredAirlines'] = array_values($filtered);
            }
        }

        if (!empty($request['sources'])) {
            $filtered = array_filter($request['sources'], fn($v) => !empty($v));
            if (!empty($filtered)) {
                $payload['Sources'] = array_values($filtered);
            }
        }

        Log::info('================ FLIGHT SEARCH REQUEST ================');
        Log::info(json_encode($payload, JSON_PRETTY_PRINT));

        $response = Http::timeout(180)
            ->acceptJson()
            ->contentType('application/json')
            ->post($this->searchUrl, $payload);

        Log::info('================ FLIGHT SEARCH RESPONSE ================');
        Log::info('HTTP Status : ' . $response->status());
        Log::info($response->body());

        if (!$response->successful()) {
            throw new Exception(
                "Flight Search API Error. HTTP " .
                $response->status() .
                "\n\n" .
                $response->body()
            );
        }

        $result = $response->json();

        $responseData = $result['Response'] ?? $result;

        if (
            !isset($responseData['ResponseStatus']) ||
            $responseData['ResponseStatus'] != 1
        ) {

            throw new Exception(
                $responseData['Error']['ErrorMessage']
                ?? 'No Flights Found.'
            );
        }

        return $responseData;
    }
}