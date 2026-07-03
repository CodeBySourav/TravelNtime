<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HotelBooking extends Model
{
    protected $fillable = [
        'user_id',
        'booking_id',
        'booking_ref',
        'confirmation_no',
        'trace_id',
        'hotel_code',
        'hotel_name',
        'lead_name',
        'email',
        'mobile',
        'amount',
        'booking_status',
        'response',
        'search_response',
        'block_response',
        'booking_response',
        'cancel_response',
        'live',
        'change_request_id',
        'refund_amount',
        'cancellation_charge',
        'change_request_status',
        'cancelled_at',
    ];

    protected $casts = [
        'response'            => 'array',
        'search_response'     => 'array',
        'block_response'      => 'array',
        'booking_response'    => 'array',
        'cancel_response'     => 'array',
        'live'                => 'array',
        'refund_amount'       => 'float',
        'cancellation_charge' => 'float',
        'cancelled_at'        => 'datetime',
    ];
}