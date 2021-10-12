<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use App\User;
use Illuminate\Support\Facades\Hash;
use App\Http\Services\SortJapaneseService;
use App\Http\Helpers\JapaneseHelper;

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

    public function register(Request $request) {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string',
            'email' => 'required|string|email|unique:users',
            'password' => 'required|string|confirmed'
        ]);
        
        if ($validator->fails()) {
            return response()->json([
                'status' => 'fails',
                'message' => $validator->errors()->first(),
                'errors' => $validator->errors()->toArray(),
            ]);
        }

        is_null($request->furigana) ? $furiganaCode = '' : $furiganaCode = JapaneseHelper::nameEncrypt($request->furigana);

        $user = new User([
            'name' => $request->name,
            'furigana' => $request->furigana,
            'furigana_code' => $furiganaCode,
            'email' => $request->email,
            'password' => Hash::make($request->password)
        ]);

        $user->save();

        Auth::login($user);

        return response()->json([
            'status' => 'ok',
            'message' => 'register successfully',
            'user' => $user
        ]);
    }
}
