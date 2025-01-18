<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cookie;


class AuthController extends Controller
{
    //Register

    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|string',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string',
            'role' => 'required|in:user,admin'
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password),
            'role' => $request->role
        ]);

        return response()->json(['message' => 'User created successfully', 'user' => $user], 201);
    }

    // login 
    public function login(Request $request)
{
    try {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);

        $credentials = $request->only('email', 'password');

        if (!Auth::attempt($credentials)) {
            return response()->json([
                'status' => 'error',
                'message' => 'Invalid credentials'
            ], 401);
        }

        $user = Auth::user();

         

        $token = $user->createToken('auth_token')->plainTextToken;

         // Create a cookie that lasts for 60 minutes (you can adjust the time)  
         $cookie = cookie('auth_token', $token, 60, '/', null, true, true);


        return response()->json([
            'status' => 'success',
            'user' => $user,
            'token' => $token
        ], 200)->cookie($cookie);

    } catch (\Exception $e) {
        return response()->json([
            'status' => 'error',
            'message' => $e->getMessage()
        ], 500);
    }
}

    //user info
    public function userInfo(Request $request)
    {
        return response()->json($request->user());
    }
}
