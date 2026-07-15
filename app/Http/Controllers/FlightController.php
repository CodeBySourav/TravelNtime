<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\TboFlightService;
use Illuminate\Support\Facades\Log;
use Exception;
use App\Models\FlightBooking;
use App\Models\FlightPassenger;
use App\Models\FlightSegment;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Models\PaymentBooking;

class FlightController extends Controller
{
    protected $flight;

    public function __construct(TboFlightService $flight)
    {
        $this->flight = $flight;
    }

    /**
     * Search Page
     */
    public function index()
    {
        return view('tourbooking::flights.search');
    }

    /**
     * Search Flights
     */
    public function search(Request $request)
    {
        $request->validate([
            'origin'          => 'required|string',
            'destination'     => 'required|string',
            'departure_date'  => 'required|date',
            'adult'           => 'required|integer|min:1|max:9',
            'child'           => 'nullable|integer|min:0|max:8',
            'infant'          => 'nullable|integer|min:0|max:8',
            'journey_type'    => 'required|in:1,2',
            'cabin_class'     => 'required|in:1,2,3,4,5,6'
        ]);

        if ($request->journey_type == 2) {
            $request->validate([
                'return_date' => 'required|date|after_or_equal:departure_date'
            ]);
        }

        try {

            $response = $this->flight->search($request);

            if (
                empty($response['Response']['Results'])
            ) {
                return back()->with('error', 'No flights found.');
            }

            session([
                'flight.search' => $request->all(),
                'flight.response' => $response,
                'flight.results' => $response['Response']['Results'],
                'flight.trace_id' => $response['Response']['TraceId']
            ]);

            return redirect()->route('flight.results', [
                'origin' => $request->origin,
                'destination' => $request->destination,
                'departure_date' => $request->departure_date,
                'return_date' => $request->return_date,
                'adult' => $request->adult,
                'child' => $request->child,
                'infant' => $request->infant,
                'cabin_class' => $request->cabin_class,
                'journey_type' => $request->journey_type,
            ]);

        } catch (Exception $e) {

            return back()->with('error', $e->getMessage());

        }
    }

    /**
     * Result Page
     */
    public function results(Request $request)
    {
        if (!session()->has('flight.response')) {

            return redirect()->route('flight.search.form')
                ->with('error','Please search flights first.');

        }

        $response = session('flight.response');

        return view('tourbooking::flights.results', [

            'response' => $response,

            'results' => $response['Response']['Results'] ?? [],

            'search' => [

                'origin' => $request->origin,
                'destination' => $request->destination,
                'departure_date' => $request->departure_date,
                'return_date' => $request->return_date,
                'adult' => $request->adult,
                'child' => $request->child,
                'infant' => $request->infant,
                'journey_type' => $request->journey_type,
                'cabin_class' => $request->cabin_class,

            ],

            'traceId' => session('flight.trace_id')

        ]);
    }

    /**
     * Fare Quote
     */
    public function fareQuote(Request $request)
    {
        $request->validate([

            'result_index' => 'required',

        ]);

        try {

            $traceId = session('flight.trace_id');

            if (!$traceId) {

                return redirect()
                    ->route('flight.search.form')
                    ->with('error', 'Flight session expired.');

            }

            $response = $this->flight->fareQuote(

                $traceId,

                $request->result_index

            );

            session([

                'flight.fare_quote' => $response,

                'flight.result_index' => $request->result_index,

                'flight.is_lcc' => $response['Response']['Results']['IsLCC'] ?? false,

            ]);

            return redirect()->route('flight.traveller');

        } catch (Exception $e) {

            return back()->with('error', $e->getMessage());

        }
    }

    public function traveller()
    {
        if (!session()->has('flight.fare_quote')) {
            return redirect()
                ->route('flight.results')
                ->with('error', 'Please select a flight.');
        }

        $search = session('flight.search', []);

        $traceId = session('flight.trace_id');
        $resultIndex = session('flight.result_index');

        try {

            $ssr = $this->flight->ssr($traceId, $resultIndex);

            session([
                'flight.ssr' => $ssr
            ]);

        } catch (\Exception $e) {

            Log::error($e->getMessage());

            $ssr = [];
        }

        $baggage = $ssr['Response']['Baggage'][0] ?? [];
        $meals   = $ssr['Response']['MealDynamic'][0] ?? [];

        $seats = [];

        if(isset($ssr['Response']['SeatDynamic'][0]['SegmentSeat'][0]['RowSeats'])) {

            foreach ($ssr['Response']['SeatDynamic'][0]['SegmentSeat'][0]['RowSeats'] as $row) {

                foreach ($row['Seats'] as $seat) {

                    $seats[] = $seat;

                }

            }

        }

        return view('tourbooking::flights.traveller', [
            'fareQuote' => session('flight.fare_quote'),
            'search'    => $search,
            'ssr'       => $ssr,
            'baggage'   => $baggage,
            'meals'     => $meals,
            'seats'     => $seats,
        ]);
    }

    public function checkout(Request $request)
    {
        $request->validate([
            'travellers' => 'required|array|min:1',
            'email'      => 'required|email',
            'mobile'     => 'required',
        ]);

        $fareQuote = session('flight.fare_quote');

        if (!$fareQuote) {
            return redirect()
                ->route('flight.search.form')
                ->with('error', 'Session expired.');
        }

        $ssr = session('flight.ssr');

        $mealOptions = $ssr['Response']['MealDynamic'][0] ?? [];
        $bagOptions  = $ssr['Response']['Baggage'][0] ?? [];

        $travellers = $request->travellers;

        $mealTotal = 0;
        $bagTotal  = 0;

        foreach ($travellers as &$traveller) {

            /*
            |--------------------------------------------------------------------------
            | Meal
            |--------------------------------------------------------------------------
            */

            if (!empty($traveller['meal'])) {

                foreach ($mealOptions as $meal) {

                    if ($meal['Code'] == $traveller['meal']) {

                        $traveller['meal_details'] = $meal;

                        $mealTotal += $meal['Price'];

                        break;
                    }

                }

            }

            /*
            |--------------------------------------------------------------------------
            | Baggage
            |--------------------------------------------------------------------------
            */

            if (!empty($traveller['baggage'])) {

                foreach ($bagOptions as $bag) {

                    if ($bag['Code'] == $traveller['baggage']) {

                        $traveller['baggage_details'] = $bag;

                        $bagTotal += $bag['Price'];

                        break;
                    }

                }

            }

        }

        $publishedFare = $fareQuote['Response']['Results']['Fare']['PublishedFare'];

        $grandTotal = $publishedFare + $mealTotal + $bagTotal;

        session([

            'flight.travellers' => [

                'travellers' => $travellers,

                'email' => $request->email,

                'mobile' => $request->mobile,

            ],

            'flight.meal_total' => $mealTotal,

            'flight.baggage_total' => $bagTotal,

            'flight.total_amount' => $grandTotal,

        ]);

        return redirect()->route('flight.payment');
    }

    public function payment()
    {
        $fareQuote = session('flight.fare_quote');
        $travellers = session('flight.travellers');

        if (!$fareQuote || !$travellers) {
            return redirect()
                ->route('flight.search.form')
                ->with('error', 'Session expired.');
        }

        return view('tourbooking::flights.payment', [

            'fareQuote' => $fareQuote,

            'travellers' => $travellers,

            'publishedFare' => $fareQuote['Response']['Results']['Fare']['PublishedFare'],

            'mealTotal' => session('flight.meal_total', 0),

            'baggageTotal' => session('flight.baggage_total', 0),

            'grandTotal' => session('flight.total_amount'),

        ]);
    }
 
    public function paymentSuccess(Request $request)
    {
        session([

            'flight.payment_id' => $request->razorpay_payment_id,

            'flight.payment_status' => true,

        ]);

        if(session('flight.is_lcc')){

            return redirect()->route('flight.ticket');

        }

        return redirect()->route('flight.book');
    }


    public function book(Request $request)
    {
        try {

            $travellers = session('flight.travellers');
            $fareQuote  = session('flight.fare_quote');
            $traceId    = session('flight.trace_id');
            $resultIndex = session('flight.result_index');

            $response = $this->flight->book(
                $travellers,
                $fareQuote,
                $traceId,
                $resultIndex,
                $request->ip()
            );

            session([
                'flight.booking' => $response
            ]);

            return redirect()->route('flight.ticket');

        } catch (Exception $e) {

            return back()->with('error', $e->getMessage());
        }
    }

    public function ticket(Request $request)
    {
        if (session()->has('flight.ticket')) {

                return view('tourbooking::flights.ticket', [
                    'ticket' => session('flight.ticket')
                ]);

            }

            Log::info("function ticket(Reques");

        try {
            Log::info("TRY function ticket(Reques");
            $response = $this->flight->ticket([
                'trace_id' => session('flight.trace_id'),
                'travellers' => session('flight.travellers'),
            ], $request->ip());

            Log::info($response);

            try {
                $this->saveBooking($response);
            } catch (\Throwable $e) {

                Log::error('SAVE BOOKING ERROR');

                Log::error($e->getMessage());

                Log::error($e->getFile());

                Log::error($e->getLine());

                throw $e;
            }

            session([
                'flight.ticket' => $response
            ]);
            Log::info($response);

            
            return view('tourbooking::flights.ticket', [
                'ticket' => $response
            ]);

        } catch (Exception $e) {

            return back()->with('error', $e->getMessage());
        }
    }

    private function saveBooking($response)
    {
        DB::transaction(function () use ($response) {

            $travellers = session('flight.travellers');
            $fareQuote  = session('flight.fare_quote');

            $result = $response['Response']['Response'] ?? [];

            $segments = $fareQuote['Response']['Results']['Segments'][0] ?? [];

            $booking = FlightBooking::create([

                'user_id' => Auth::id(),

                'booking_id' => $result['BookingId'] ?? null,

                'booking_ref' => $result['BookingRefNo'] ?? null,

                'pnr' => $result['PNR'] ?? null,

                'trace_id' => session('flight.trace_id'),

                'result_index' => session('flight.result_index'),

                'airline' => $segments[0]['Airline']['AirlineCode'] ?? null,

                'flight_number' => ($segments[0]['Airline']['FlightNumber'] ?? null),

                'origin' => $segments[0]['Origin']['Airport']['AirportCode'] ?? null,

                'destination' => $segments[count($segments)-1]['Destination']['Airport']['AirportCode'] ?? null,

                'departure' => $segments[0]['Origin']['DepTime'] ?? null,

                'arrival' => $segments[count($segments)-1]['Destination']['ArrTime'] ?? null,

                'published_fare' => $fareQuote['Response']['Results']['Fare']['PublishedFare'] ?? 0,

                'offered_fare' => $fareQuote['Response']['Results']['Fare']['OfferedFare'] ?? 0,

                'is_lcc' => session('flight.is_lcc'),

                'status' => 'Booked',

                'api_response' => $response,
            ]);

            PaymentBooking::create([

                'user_id' => Auth::id(),

                'booking_id' => $booking->id,

                'payment_for' => 'flight',

                'gateway' => 'Razorpay',

                'payment_id' => session('flight.payment_id'),

                'order_id' => session('flight.order_id'),

                'signature' => session('flight.signature'),

                'amount' => $fareQuote['Response']['Results']['Fare']['PublishedFare'] ?? 0,

                'currency' => 'INR',

                'payment_method' => session('flight.payment_method'),

                'status' => session('flight.payment_status'),

                'response' => session('flight.payment_response'),

            ]);

            foreach ($segments as $segment) {

                FlightSegment::create([

                    'booking_id' => $booking->id,

                    'airline' => $segment['Airline']['AirlineCode'],

                    'flight_number' => $segment['Airline']['FlightNumber'],

                    'origin' => $segment['Origin']['Airport']['AirportCode'],

                    'destination' => $segment['Destination']['Airport']['AirportCode'],

                    'departure' => $segment['Origin']['DepTime'],

                    'arrival' => $segment['Destination']['ArrTime'],
                ]);
            }

            foreach ($travellers['travellers'] as $index => $pax) {

                FlightPassenger::create([

                    'booking_id' => $booking->id,

                    'title' => $pax['title'],

                    'first_name' => $pax['first_name'],

                    'last_name' => $pax['last_name'],

                    'pax_type' => $pax['pax_type'],

                    'dob' => $pax['date_of_birth'] ?? null,

                    'gender' => $pax['gender'] ?? null,

                    'passport_no' => !empty($pax['passport_no']) ? $pax['passport_no'] : null,

                    'passport_expiry' => !empty($pax['passport_expiry']) ? $pax['passport_expiry'] : null,

                    'meal' => $pax['meal'] ?? null,

                    'seat' => $pax['seat'] ?? null,

                    'baggage' => $pax['baggage'] ?? null,

                    'is_lead' => $index == 0,
                ]);
            }

        });
    }

    private function redirectToSearch()
    {
        session()->forget([
            'flight.search',
            'flight.response',
            'flight.results',
            'flight.trace_id',
            'flight.fare_quote',
            'flight.result_index',
            'flight.ssr',
            'flight.booking',
            'flight.ticket',
            'flight.travellers',
            'flight.selected_ssr',
            'flight.is_lcc'
        ]);

        return redirect()
            ->route('flight.search.form')
            ->with('error', 'Your flight session has expired. Please search again.');
    }

    public function flightbookingDetails(Request $request)
{
    $request->validate([
        'booking_id' => 'nullable',
        'pnr'        => 'nullable',
        'trace_id'   => 'nullable',
    ]);

    try {

        $response = $this->flight->getBookingDetails(
            $request->booking_id,
            $request->pnr,
            $request->trace_id
        );

        return response()->json([
            'success' => true,
            'data'    => $response
        ]);

    } catch (Exception $e) {

        return response()->json([
            'success' => false,
            'message' => $e->getMessage()
        ],500);

    }
}
}