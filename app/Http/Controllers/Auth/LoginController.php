<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    public function login(Request $request)
    {
        $credentials = $request->only('email', 'password');

        if (Auth::attempt($credentials)) {
            // Authentication passed, return success response with user ID
            return response()->json([
                'status' => 'success',
                'code' => 200,
                'message' => 'Login successful',
                'userId' => Auth::user()->id, // Return the user's ID
            ]);
        }

        // Authentication failed, return error response
        return response()->json([
            'status' => 'error',
            'code' => 401,
            'message' => 'Unauthorized',
            'errors' => ['Invalid email or password'],
        ], 401);
    }
}
