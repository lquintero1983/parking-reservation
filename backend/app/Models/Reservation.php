<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Reservation extends Model
{
    protected $fillable = [
        'customer_id', 'location_id', 'rate_id',
        'check_in', 'check_out', 'total_price', 'status'
    ];
}