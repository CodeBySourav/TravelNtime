<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\TboFlightService;
use Illuminate\Support\Facades\Log;
use Exception;

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

        return view('tourbooking::flights.traveller', [
            'fareQuote' => session('flight.fare_quote')
        ]);
    }

    public function checkout(Request $request)
    {
        $request->validate([
            'travellers' => 'required|array|min:1',
            'email'      => 'required|email',
            'mobile'     => 'required'
        ]);

        Log::info($request);

        $fareQuote = session('flight.fare_quote');

        if (!$fareQuote) {
            return redirect()
                ->route('flight.search.form')
                ->with('error', 'Session expired.');
        }

        // Save traveller data
        session([
            'flight.travellers' => $request->all()
        ]);

        $traceId = session('flight.trace_id');
        $resultIndex = session('flight.result_index');

        try {

            $ssr = $this->flight->ssr(
                $traceId,
                $resultIndex
            );

            session([
                'flight.ssr' => $ssr
            ]);

            return redirect()->route('flight.ssr');

        } catch (Exception $e) {

            return back()->with('error', $e->getMessage());

        }
    }

    public function ssr()
    {
        
        $ssr = session('flight.ssr');

        $baggage = $ssr['Response']['Baggage'][0] ?? [];
        $meals    = $ssr['Response']['MealDynamic'][0] ?? [];

        $seats = [];

        if(isset($ssr['Response']['SeatDynamic'][0]['SegmentSeat'][0]['RowSeats'])){

            foreach($ssr['Response']['SeatDynamic'][0]['SegmentSeat'][0]['RowSeats'] as $row){

                foreach($row['Seats'] as $seat){

                    $seats[] = $seat;

                }

            }

        }

        return view('tourbooking::flights.ssr',compact(
            'ssr',
            'baggage',
            'meals',
            'seats'
        ));
    }

    public function afterSSR(Request $request)
    { 
        // Save selected SSR
        session([
            'flight.selected_ssr' => $request->all(),
        ]);

        if (session('flight.is_lcc')) {

            // LCC -> Direct Ticket API
            Log::info("redirect()->route('flight.ticket');");

            return redirect()->route('flight.ticket');

        }
            Log::info("redirect()->route('flight.book');");

        // Non-LCC -> Hold Booking first
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
            Log::info("function ticket(Reques");

        try {
            Log::info("TRY function ticket(Reques");
            $response = $this->flight->ticket([
                'trace_id' => session('flight.trace_id'),
                'travellers' => session('flight.travellers'),
            ], $request->ip());

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
}