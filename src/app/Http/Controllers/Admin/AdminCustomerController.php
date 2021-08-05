<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\User;
use Illuminate\Http\Request;

class AdminCustomerController extends Controller
{
    //
    public function listCustomer(Request $request) {
        $keywork = $request->q;
        if( $keywork ) {
            $listCustomer = User::where('role_id', 1)
                                    ->where(function($query) use ($keywork) {
                                        $query->where('name', 'like', '%'.$keywork.'%')
                                                ->orWhere('email', 'like', '%'.$keywork.'%');
                                    })
                                    ->paginate(10);
        }
        else {
            $listCustomer = User::where('role_id', 1)->paginate(10);
        }
        return view('admin.customer.adminCustomer',
                    [
                        'listCustomer' => $listCustomer,
                    ]);
    }
    public function listCustomerBanned(Request $request) {
        $keywork = $request->q;
        if( $keywork ) {
            $listCustomer = User::onlyTrashed()
                                    ->where('role_id', 1)
                                    ->where(function($query) use ($keywork) {
                                        $query->where('name', 'like', '%'.$keywork.'%')
                                                ->orWhere('email', 'like', '%'.$keywork.'%');
                                    })
                                    ->paginate(10);
        }
        else {
            $listCustomer = User::onlyTrashed()->where('role_id', 1)->paginate(10);
        }
        return view('admin.customer.adminCustomerBanned',
                    [
                        'listCustomer' => $listCustomer,
                    ]);
    }
    public function banCustomer($id) {
        User::findOrFail($id)->delete();
        return redirect()->route('listCustomer');
    }
    public function unbanCustomer($id) {
        User::withTrashed()
                ->where('id', $id)
                ->restore();
        return redirect()->route('listCustomerBanned');
    }
}
