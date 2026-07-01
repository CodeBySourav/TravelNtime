<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Enquiry extends Model
{
    use HasFactory;


    protected $fillable = [
        'service_id',
        'name',
        'phone',
        'email',
        'message',
    ];

}
