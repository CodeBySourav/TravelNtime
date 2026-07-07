<?php

namespace App\Services;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use App\Models\ApiToken;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;
use Exception;

class TboFlightService
{
    /**
     * TBO URLs
     */
    protected string $authUrl =
        'https://Sharedapi.tektravels.com/SharedData.svc/rest/Authenticate';

    protected string $searchUrl =
        'https://api.tektravels.com/BookingEngineService_Air/AirService.svc/rest/Search';

    protected string $fareQuoteUrl =
    'https://api.tektravels.com/BookingEngineService_Air/AirService.svc/rest/FareQuote';

    protected string $ssrUrl =
    'https://api.tektravels.com/BookingEngineService_Air/AirService.svc/rest/SSR';

    protected string $bookUrl =
    'https://api.tektravels.com/BookingEngineService_Air/AirService.svc/rest/Book';

    protected string $ticketUrl =
    'https://api.tektravels.com/BookingEngineService_Air/AirService.svc/rest/Ticket';

    protected string $bookingDetailsUrl =
    'https://api.tektravels.com/BookingEngineService_Air/AirService.svc/rest/GetBookingDetails';

        /**
     * Get Authentication Token
     */
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
    /**
     * Search Flight
     */
    public function search(Request $request): array
    {
        $token = $this->getToken();

        $payload = [

            "EndUserIp"      => config('services.tbo.end_user_ip'),

            "TokenId"        => $token,

            "AdultCount"     => (int) $request->adult,

            "ChildCount"     => (int) ($request->child ?? 0),

            "InfantCount"    => (int) ($request->infant ?? 0),

            "DirectFlight"   => $request->boolean('direct_flight'),

            "OneStopFlight"  => $request->boolean('one_stop_flight'),

            "JourneyType"    => (int) $request->journey_type,

            "PreferredAirlines" => $request->filled('preferred_airline')
                ? [strtoupper($request->preferred_airline)]
                : null,

            "Segments" => [],

            "Sources" => null,

        ];

        /**
         * Outbound
         */
        $payload['Segments'][] = [

            "Origin" => strtoupper($request->origin),

            "Destination" => strtoupper($request->destination),

            "FlightCabinClass" => (int) $request->cabin_class,

            "PreferredDepartureTime" =>
                date('Y-m-d', strtotime($request->departure_date)) . 'T00:00:00',

            "PreferredArrivalTime" =>
                date('Y-m-d', strtotime($request->departure_date)) . 'T00:00:00',

        ];


        
        /**
         * Return
         */
        if ($request->journey_type == 2) {

            $payload['Segments'][] = [

                "Origin" => strtoupper($request->destination),

                "Destination" => strtoupper($request->origin),

                "FlightCabinClass" => (int) $request->cabin_class,

                "PreferredDepartureTime" =>
                    date('Y-m-d', strtotime($request->return_date)) . 'T00:00:00',

                "PreferredArrivalTime" =>
                    date('Y-m-d', strtotime($request->return_date)) . 'T00:00:00',

            ];
        }

        Log::info('========== FLIGHT SEARCH REQUEST ==========');
        Log::info(json_encode($payload, JSON_PRETTY_PRINT));

        $response = Http::timeout(120)
            ->acceptJson()
            ->post($this->searchUrl, $payload);

        if (!$response->successful()) {
            throw new Exception('Unable to connect to TBO Search API.');
        }

        Log::info('========== FLIGHT SEARCH RESPONSE ==========');
        Log::info([
            'status' => $response->status(),
            'body'   => $response->body(),
        ]);
        $data = $response->json();

        if (!isset($data['Response'])) {
            throw new Exception('Invalid response received from TBO.');
        }

        if (
            !isset($data['Response']['ResponseStatus']) ||
            $data['Response']['ResponseStatus'] != 1
        ) {
            throw new Exception(
                $data['Response']['Error']['ErrorMessage']
                    ?? 'Flight search failed.'
            );
        }

        return $data;
    }

    /**
     * Fare Quote
     */
    public function fareQuote(string $traceId, string $resultIndex): array
    {
        $payload = [

            "EndUserIp" => config('services.tbo.end_user_ip'),

            "TokenId" => $this->getToken(),

            "TraceId" => $traceId,

            "ResultIndex" => $resultIndex,

        ];

        Log::info('========== FARE QUOTE REQUEST ==========');
        Log::info(json_encode($payload, JSON_PRETTY_PRINT));

        $response = Http::timeout(120)
            ->acceptJson()
            ->contentType('application/json')
            ->post($this->fareQuoteUrl, $payload);

        Log::info('========== FARE QUOTE RESPONSE ==========');
        Log::info([
            'status' => $response->status(),
            'body'   => $response->body(),
        ]);

        if (!$response->successful()) {
            throw new Exception(
                "Unable to connect Fare Quote API."
            );
        }

        $result = $response->json();

        if (
            !isset($result['Response']) ||
            $result['Response']['ResponseStatus'] != 1
        ) {

            throw new Exception(
                $result['Response']['Error']['ErrorMessage']
                ?? 'Fare Quote Failed.'
            );

        }

        return $result;
    }


    public function ssr(string $traceId, string $resultIndex): array
    {
        $payload = [
            "EndUserIp" => config('services.tbo.end_user_ip'),
            "TokenId"   => $this->getToken(),
            "TraceId"   => $traceId,
            "ResultIndex" => $resultIndex,
        ];

        Log::info('========== SSR REQUEST ==========');
        Log::info(json_encode($payload, JSON_PRETTY_PRINT));

        $response = Http::timeout(120)
            ->acceptJson()
            ->contentType('application/json')
            ->post($this->ssrUrl, $payload);

        Log::info('========== SSR RESPONSE ==========');
        Log::info([
            'status' => $response->status(),
            'body'   => $response->body(),
        ]);

        if (!$response->successful()) {
            throw new Exception('Unable to connect SSR API.');
        }

        $result = $response->json();

        if (
            !isset($result['Response']) ||
            $result['Response']['ResponseStatus'] != 1
        ) {
            throw new Exception(
                $result['Response']['Error']['ErrorMessage']
                ?? 'SSR Failed.'
            );
        }

        return $result;
    }

    private function formatPassengers(array $travellers, array $fareQuote)
    {
        $fare = $fareQuote['Response']['Results']['Fare'] ?? [];

        $passengers = [];

        foreach ($travellers['travellers'] as $t) {

            $passengers[] = [
                "Title" => $t['title'],
                "FirstName" => $t['first_name'],
                "LastName" => $t['last_name'],
                "PaxType" => $t['pax_type'], // 1 Adult, 2 Child, 3 Infant

                "DateOfBirth" => $t['dob'] ?? null,
                "Gender" => $t['gender'],

                "AddressLine1" => $t['address'] ?? 'N/A',
                "City" => $t['city'] ?? 'Jamshedpur',
                "CountryCode" => "IN",
                "CountryName" => "India",
                "ContactNo" => $t['mobile'],
                "Email" => $t['email'],
                "IsLeadPax" => $t['is_lead'] ?? false,

                "Nationality" => "IN",

                // 🔥 IMPORTANT: Fare must be inside passenger
                "Fare" => [
                    "BaseFare" => $fare['BaseFare'] ?? 0,
                    "Tax" => $fare['Tax'] ?? 0,
                    "TransactionFee" => $fare['TransactionFee'] ?? 0,
                    "YQTax" => $fare['YQTax'] ?? 0,
                    "AdditionalTxnFeeOfrd" => 0,
                    "AdditionalTxnFeePub" => $fare['AdditionalTxnFeePub'] ?? 0,
                    "AirTransFee" => $fare['AirTransFee'] ?? 0,
                ]
            ];
        }

        return $passengers;
    }

     public function book(array $travellers, array $fareQuote, string $traceId, string $resultIndex, string $endUserIp)
    {
        $token = $this->getToken();

        $payload = [
            "EndUserIp"   => $endUserIp,
            "TokenId"     => $token,
            "TraceId"     => $traceId,
            "ResultIndex" => $resultIndex,

            "Passengers"  => $this->formatPassengers($travellers, $fareQuote),
        ];

        Log::info('========== BOOK REQUEST ==========');
        Log::info(json_encode($payload, JSON_PRETTY_PRINT));

        $response = Http::timeout(120)
            ->acceptJson()
            ->post($this->bookUrl, $payload);

        Log::info('========== BOOK RESPONSE ==========');
        Log::info([
            'status' => $response->status(),
            'body'   => $response->body(),
        ]);

        if (!$response->successful()) {
            throw new Exception("Unable to connect Book API.");
        }

        $data = $response->json();

        if (!isset($data['Response'])) {
            throw new Exception("Invalid Book response.");
        }

        if (($data['Response']['Status'] ?? 0) != 1) {
            throw new Exception(
                $data['Response']['Error']['ErrorMessage'] ?? 'Booking Failed'
            );
        }

        return $data;
    }


    private function formatTicketPassengers(array $data, array $fareQuote)
{
    Log::info("========== formatTicketPassengers START ==========");

    Log::info("Input Data", $data);

    $email = $data['travellers']['email'] ?? '';
    $mobile = $data['travellers']['mobile'] ?? '';

    Log::info("Contact Details", [
        'email' => $email,
        'mobile' => $mobile,
    ]);

    $travellers = $data['travellers']['travellers'] ?? [];

    Log::info("Traveller Count", [
        'count' => count($travellers)
    ]);

    $passengers = [];

    foreach ($travellers as $index => $t) {

        Log::info("Processing Traveller #" . ($index + 1), $t);

        $passenger = [
            "Title" => $t['title'] ?? '',
            "FirstName" => $t['first_name'] ?? '',
            "LastName" => $t['last_name'] ?? '',
            "PaxType" => $t['pax_type'] ?? '1',

            "DateOfBirth" => $t['dob'] ?? null,
            "Gender" => $t['gender'] ?? '',

            "PassportNo" => $t['passport_no'] ?? '',
            "PassportExpiry" => $t['passport_expiry'] ?? '',

            "AddressLine1" => $t['address'] ?? '',
            "City" => $t['city'] ?? '',
            "CountryCode" => "IN",
            "CountryName" => "India",

            "ContactNo" => $mobile,
            "Email" => $email,

            "IsLeadPax" => $t['is_lead'] ?? ($index == 0),
            "Nationality" => $t['nationality'] ?? 'IN',

            "Fare" => [
                "BaseFare" => $fareQuote['Response']['Results']['Fare']['BaseFare'] ?? 0,
                "Tax" => $fareQuote['Response']['Results']['Fare']['Tax'] ?? 0,
                "TransactionFee" => $fareQuote['Response']['Results']['Fare']['TransactionFee'] ?? 0,
                "YQTax" => $fareQuote['Response']['Results']['Fare']['YQTax'] ?? 0,
                "AdditionalTxnFeeOfrd" => 0,
                "AdditionalTxnFeePub" => $fareQuote['Response']['Results']['Fare']['AdditionalTxnFeePub'] ?? 0,
                "AirTransFee" => $fareQuote['Response']['Results']['Fare']['AirTransFee'] ?? 0,
            ],
        ];

        Log::info("Formatted Passenger #" . ($index + 1), $passenger);

        $passengers[] = $passenger;
    }

    Log::info("========== FINAL PASSENGERS ==========");
    Log::info($passengers);

    Log::info("========== formatTicketPassengers END ==========");

    return $passengers;
}

    private function formatPassport(array $data)
    {
        $passengers = [];

        foreach ($data['travellers'] as $t) {

            if (!empty($t['passport_no'])) {

                $passengers[] = [
                    "PaxId" => $t['pax_id'] ?? null,
                    "PassportNo" => $t['passport_no'],
                    "PassportExpiry" => $t['passport_expiry'] ?? null,
                    "DateOfBirth" => $t['dob'] ?? null,
                ];
            }
        }

        return $passengers;
    }

    public function ticket(array $data, string $endUserIp)
{
    Log::info("========== TICKET SERVICE START ==========");

    try {

        Log::info("Incoming Data", $data);

        /*
        |--------------------------------------------------------------------------
        | Token
        |--------------------------------------------------------------------------
        */
        $token = $this->getToken();

        Log::info("Token Generated", [
            'TokenId' => $token
        ]);

        /*
        |--------------------------------------------------------------------------
        | Session Data
        |--------------------------------------------------------------------------
        */
        $fareQuote = session('flight.fare_quote');
        $isLcc     = session('flight.is_lcc');

        Log::info("Session Data", [
            'IsLcc'       => $isLcc,
            'TraceId'     => $data['trace_id'] ?? '',
            'ResultIndex' => session('flight.result_index'),
        ]);

        /*
        |--------------------------------------------------------------------------
        | Base Payload
        |--------------------------------------------------------------------------
        */
        $payload = [
            "EndUserIp" => $endUserIp,
            "TokenId"   => $token,
            "TraceId"   => $data['trace_id'],
        ];

        Log::info("Base Payload", $payload);

        /*
        |--------------------------------------------------------------------------
        | NON LCC
        |--------------------------------------------------------------------------
        */
        if (!$isLcc) {

            Log::info("Flow : NON LCC");

            $booking = session('flight.booking');

            Log::info("Booking Session", $booking);

            $payload["PNR"] = $booking['Response']['FlightItinerary']['PNR'] ?? null;
            $payload["BookingId"] = $booking['Response']['FlightItinerary']['BookingId'] ?? null;
            $payload["IsPriceChangeAccepted"] = true;

            Log::info("Formatting Passport");

            $payload["Passport"] = $this->formatPassport($data);

            Log::info("Passport", [
                'Passport' => $payload["Passport"]
            ]);
        }

        /*
        |--------------------------------------------------------------------------
        | LCC
        |--------------------------------------------------------------------------
        */
        else {

            Log::info("Flow : LCC");

            $payload["ResultIndex"] = session('flight.result_index');

            Log::info("Formatting Ticket Passengers");

            $payload["Passengers"] = $this->formatTicketPassengers(
                $data,
                $fareQuote
            );

            Log::info("Passengers", [
                'Passengers' => $payload["Passengers"]
            ]);
        }

        /*
        |--------------------------------------------------------------------------
        | Final Payload
        |--------------------------------------------------------------------------
        */
        Log::info("========== FINAL REQUEST ==========");
        Log::info(json_encode($payload, JSON_PRETTY_PRINT));

        /*
        |--------------------------------------------------------------------------
        | API Call
        |--------------------------------------------------------------------------
        */
        $response = Http::timeout(120)
            ->acceptJson()
            ->post($this->ticketUrl, $payload);

        Log::info("HTTP Status", [
            'status' => $response->status()
        ]);

        Log::info("========== RAW RESPONSE ==========");
        Log::info($response->body());

        /*
        |--------------------------------------------------------------------------
        | HTTP Error
        |--------------------------------------------------------------------------
        */
        if (!$response->successful()) {

            Log::error("HTTP Request Failed", [
                'status' => $response->status(),
                'body'   => $response->body()
            ]);

            throw new Exception("Ticket API failed.");
        }

        /*
        |--------------------------------------------------------------------------
        | Parse Response
        |--------------------------------------------------------------------------
        */
        $responseData = $response->json();

        Log::info("Parsed Response", $responseData);

        /*
        |--------------------------------------------------------------------------
        | API Error
        |--------------------------------------------------------------------------
        */
        $ticketStatus = data_get($responseData, 'Response.Response.TicketStatus');

        if ($ticketStatus != 1) {

            throw new Exception(
                data_get($responseData, 'Response.Response.Message')
                ?? data_get($responseData, 'Response.Error.ErrorMessage')
                ?? 'Ticket Failed'
            );
        }

        /*
        |--------------------------------------------------------------------------
        | Success
        |--------------------------------------------------------------------------
        */
        Log::info("========== TICKET SUCCESS ==========");
        Log::info($responseData);

        return $responseData;

    } catch (Exception $e) {

        Log::error("========== TICKET EXCEPTION ==========");
        Log::error($e->getMessage());

        Log::error($e->getTraceAsString());

        throw $e;
    }
}
}