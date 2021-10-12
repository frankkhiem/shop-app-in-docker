<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Laravel\Socialite\Facades\Socialite;
use App\User;
use Illuminate\Support\Facades\Auth;

class SocialAuthController extends Controller
{
    //
    // Google
    public function loginGoogle(){
        return Socialite::driver('google')->redirect();
    }
    // xu ly khi dang nhap thong qua Google
    public function loginGoogleCallback(){
        $user = Socialite::driver('google')->user();
        if( !$user ) {
            return 'Dang nhap Google khong thanh cong!';
        }

        $systemUser = User::where('google_id', $user->id)->get()
                                                        ->first();
        if( !$systemUser ) {
            $systemUser = User::create([
                'name' => $user->name,
                'email' => $user->email,
                'google_id' => $user->id,
            ]);
        }                                          
        Auth::loginUsingId($systemUser->id);
        if( $systemUser->role->name == 'admin' ) {
            return redirect()->route('adminPage');
        }
        return redirect()->route('profilePage');
    }
}
