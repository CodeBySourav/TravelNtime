<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PaymentBooking extends Model
{
    protected $fillable = [

        'user_id',

        'booking_id',

        'payment_for',

        'gateway',

        'payment_id',

        'order_id',

        'signature',

        'amount',

        'currency',

        'payment_method',

        'status',

        'response'

    ];

    protected $casts = [

        'response' => 'array',

    ];
}