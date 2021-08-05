<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\User;

class UserController extends Controller
{
    //
    public function profile()
    {   
        $user_id = Auth::id();
        $user = User::findOrFail($user_id);
        return response()->json([
            $user,
            $user->role,
        ]);
    }
    public function update(Request $request)
    {   
        User::findOrFail($request->id)
                ->update(
                    [
                        'name' => $request->name,
                        'birthday' => $request->birthday,
                        'address' => $request->address,
                    ]
                );

        return;
    }
    public function updateAvatar(Request $request)
    {   
        if ($request->hasFile('file')) {
            $file = $request->file('file');
            $filename = $request->file->getClientOriginalName();
            $file->move(public_path() . '/uploads/avatar/', $filename);
        }
        $user_id = Auth::id();
        User::findOrFail($user_id)
                ->update([
                    'avatar' => $filename,
                ]);
        return $filename;
    }

    public function logout(Request $request)
    {
        Auth::logout();
        return $request;
    }
}
