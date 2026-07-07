<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\FlightBooking;
use App\Models\FlightPassenger;
use App\Models\FlightSegment;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class FlightBooking extends Model
{
    protected $guarded = [];

    protected $casts = [
        'api_response' => 'array',
    ];

    public function passengers()
    {
        return $this->hasMany(FlightPassenger::class,'booking_id');
    }

    public function segments()
    {
        return $this->hasMany(FlightSegment::class,'booking_id');
    }
}
