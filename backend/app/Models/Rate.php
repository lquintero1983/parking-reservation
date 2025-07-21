<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Rate extends Model
{
    protected $fillable = ['location_id', 'price_per_day', 'valid_from', 'valid_to'];
}