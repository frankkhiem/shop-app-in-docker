<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthSPAController extends Controller
{
    public function login(Request $request) {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);
        if (Auth::attempt($credentials)) {
            return response()->json([
                'message' => 'login successfully',
                'user' => Auth::user(),
            ], 200);
        }
        return response()->json([
            'message' => 'Unauthorized',
        ], 401);
    }

    public function logout(Request $request) {
        Auth::guard('web')->logout();
        $request->session()->invalidate();
    
        $request->session()->regenerateToken();

        return response()->json([
            'message' => 'logout successfully',
        ]);
    }
}
