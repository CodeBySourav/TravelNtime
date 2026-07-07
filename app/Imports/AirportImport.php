<?php

namespace App\Imports;

use App\Models\Airport;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class AirportImport implements ToModel, WithHeadingRow
{
    public function headingRow(): int
    {
        return 1;
    }

    public function model(array $row)
    {
        if (empty($row['airportname']) || empty($row['airportcode'])) {
            return null;
        }

        return new Airport([
            'airport_name' => trim($row['airportname']),
            'airport_code' => strtoupper(trim($row['airportcode'])),
            'city_name'    => trim($row['cityname'] ?? ''),
            'city_code'    => strtoupper(trim($row['citycode'] ?? '')),
            'country_code' => strtoupper(trim($row['countrycode'] ?? '')),
            'country_name' => trim($row['countryname'] ?? ''),
        ]);
    }
}