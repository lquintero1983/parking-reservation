<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Billing extends Model
{
    protected $fillable = ['reservation_id', 'amount', 'billed_at', 'status'];
}