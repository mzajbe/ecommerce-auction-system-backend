<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Company;
use Illuminate\Support\Facades\Auth;

use Exception;

class CompanyAuthController extends Controller
{
    // Register  
    public function register(Request $request)
    {
        $request->validate([
            'company_name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255',
            'password' => 'required|string|min:8',
            'coordinator_name' => 'nullable|string',
            'coordinator_contact' => 'nullable|string',
        ]);

        $company = Company::create([
            'company_name' => $request->company_name,
            'email' => $request->email,
            'password' => bcrypt($request->password), // Ensure password is hashed  
            'coordinator_name' => $request->coordinator_name,
            'coordinator_contact' => $request->coordinator_contact,
        ]);

        return response()->json(['message' => 'Registration successful', 'company' => $company], 201);
    }


    public function login(Request $request)
    {
        try {
            $request->validate([
                'email' => 'required|email',
                'password' => 'required'
            ]);

            if (!Auth::guard('company')->attempt($request->only('email', 'password'))) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Invalid credentials'
                ], 401);
            }

            $company = Auth::guard('company')->user();
            $token = $company->createToken('companyAuthToken')->plainTextToken;

            $cookie = cookie('companyAuthToken', $token, 60, '/', null, true, true);

            return response()->json([
                'status' => 'success',
                'company' => $company,
                'token' => $token
            ], 200)->cookie($cookie);

        } catch (Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    // Company info  
    public function companyInfo(Request $request)
    {
        return response()->json($request->user()); // Return the authenticated company info  
    }
}
