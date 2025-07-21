<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        $user = User::create([
            'name' => $request->input('name'),
            'email'=> $request->input('email'),
            'password' => Hash::make($request->input('password'))
        ]);
        return response()->json($user);
    }

    public function login(Request $request)
    {
        $user = User::where('email', $request->input('email'))->first();
        if ($user && Hash::check($request->input('password'), $user->password)) {
            // Token logic here for real app
            return response()->json($user);
        }
        return response()->json(['error'=>'Invalid credentials'], 401);
    }
}