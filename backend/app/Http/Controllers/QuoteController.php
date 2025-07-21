<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Location;
use App\Models\Rate;

class QuoteController extends Controller
{
    public function getQuote(Request $request)
    {
        $checkIn = $request->input('check_in');
        $checkOut = $request->input('check_out');

        $location = Location::first();
        $rate = Rate::where('location_id', $location->id)->first();

        $days = (strtotime($checkOut) - strtotime($checkIn)) / 86400;
        $price = $rate->price_per_day * $days;

        return response()->json([
            'location' => $location->name,
            'price' => number_format($price, 2)
        ]);
    }
}