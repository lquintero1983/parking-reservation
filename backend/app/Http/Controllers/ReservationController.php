<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Reservation;
use App\Models\Customer;
use App\Models\Rate;
use App\Models\Location;

class ReservationController extends Controller
{
    public function store(Request $request)
    {
        $customer = Customer::firstOrCreate(['email' => $request->input('email')], [
            'name' => $request->input('name'),
            'phone' => $request->input('phone')
        ]);

        $location = Location::find($request->input('location_id'));
        $rate = Rate::find($request->input('rate_id'));

        $days = (strtotime($request->input('check_out')) - strtotime($request->input('check_in'))) / 86400;
        $total_price = $rate->price_per_day * $days;

        $reservation = Reservation::create([
            'customer_id' => $customer->id,
            'location_id' => $location->id,
            'rate_id' => $rate->id,
            'check_in' => $request->input('check_in'),
            'check_out' => $request->input('check_out'),
            'total_price' => $total_price,
            'status' => 'active'
        ]);

        // Send rates via email (Laravel Mail/Notification)
        // Notification logic would go here

        return response()->json($reservation);
    }

    public function cancel($id)
    {
        $reservation = Reservation::findOrFail($id);
        $reservation->status = 'cancelled';
        $reservation->save();
        return response()->json(['status' => 'cancelled']);
    }

    public function index()
    {
        return Reservation::with('customer', 'location', 'rate')->get();
    }
}