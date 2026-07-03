<?php

namespace App\Http\Controllers;

use App\Services\TboFlightService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Exception;

class FlightController extends Controller
{
    protected $tboFlightService;

    public function __construct(TboFlightService $tboFlightService)
    {
        $this->tboFlightService = $tboFlightService;
    }

    /**
     * Flight Search
     */

    public function index()
{
    return view('tourbooking::flights.search');
}
    public function search(Request $request)
{
    if (
        !$request->filled('origin') &&
        !$request->filled('destination') &&
        !$request->filled('departure_date')
    ) {

        $request->merge([
            "origin" => "DEL",
            "destination" => "BOM",
            "departure_date" => "2026-07-20",
            "return_date" => "2026-07-25",
            "journey_type" => 2,
            "adults" => 1,
            "children" => 0,
            "infants" => 0,
            "cabin" => 2,
            "direct_flight" => false,
            "one_stop_flight" => false,
            "preferred_airlines" => null,
            "sources" => null,
        ]);
    }

    $request->validate([
        'origin'              => 'required|string|size:3',
        'destination'         => 'required|string|size:3',
        'departure_date'      => 'required|date',
        'return_date'         => 'nullable|date',
        'journey_type'        => 'required|integer|in:1,2,3,4,5',
        'adults'              => 'required|integer|min:1|max:9',
        'children'            => 'nullable|integer|min:0|max:8',
        'infants'             => 'nullable|integer|min:0|max:8',
        'cabin'               => 'required|integer|in:1,2,3,4,5,6',
        'direct_flight'       => 'nullable|boolean',
        'one_stop_flight'     => 'nullable|boolean',
        'preferred_airlines'  => 'nullable|array',
        'sources'             => 'nullable|array',
    ]);

    try {

        $result = $this->tboFlightService->searchFlights($request->all());
        Log::info('controller flight search responce'.json_encode($result));
        return view('tourbooking::flights.search-results', [
            'result'  => $result,
            'request' => $request->all(),
        ]);

    } catch (Exception $e) {

        Log::error('Flight Search Error', [
            'message' => $e->getMessage(),
        ]);

        return back()
            ->withInput()
            ->with('error', $e->getMessage());
    }
}
}