<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\AirportImport;
use App\Models\Airport;


class AirportController extends Controller
{
    public function import()
    {
    Excel::import(
            new AirportImport,
            storage_path('app/airports.xlsx')
        );

        return "Imported Successfully";
    }

    public function searchAirport(Request $request)
    {
        $q = trim($request->q);

        $airports = Airport::where('airport_name', 'LIKE', "%{$q}%")
            ->orWhere('city_name', 'LIKE', "%{$q}%")
            ->orWhere('airport_code', 'LIKE', "%{$q}%")
            ->orderBy('city_name')
            ->limit(10)
            ->get([
                'airport_name',
                'airport_code',
                'city_name',
                'city_code'
            ]);

        return response()->json($airports);
    }
}
