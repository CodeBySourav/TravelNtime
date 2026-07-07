<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\FlightBooking;
use App\Models\FlightPassenger;
use App\Models\FlightSegment;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class FlightSegment extends Model
{
    protected $guarded = [];

    public function booking()
    {
        return $this->belongsTo(FlightBooking::class);
    }
}