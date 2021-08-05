<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Cart;
use App\Models\Product;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class CartController extends Controller
{
    public function store(Request $request) {
        $new_cart = new Cart();
        $new_cart->fill([
            'user_id' => $request->user_id,
            'product_id' => $request->product_id,
        ]);
        $new_cart->save();

        return response()->json([
            'result' => 'success'
        ], 200);
    }

    public function getCartByUserId() {
        // $products_in_cart = Cart::where('user_id', $user_id)
        //                         ->selectRaw('product_id')
        //                         ->get();
        // $ids = $products_in_cart->pluck('product_id')->toArray();
        // $ids_ordered = implode(',', $ids);
        // $products = Product::whereIn('id', $products_in_cart->pluck('product_id'))
        //                     ->orderByRaw("FIELD(id, $ids_ordered)")
        //                     ->get();
        $user_id = Auth::id();
        $products_in_cart = DB::table('carts')
                            ->join('products', 'products.id', '=', 'carts.product_id')
                            ->select('carts.*', 'products.name', 'products.price', 'products.thumbnail')
                            ->where('carts.user_id', $user_id)
                            ->get();
        return $products_in_cart;
    }

    public function destroy($id_in_cart) {
        Cart::where('id', $id_in_cart)->delete();
        return response()->json([
            'result' => 'delete success',
        ], 200);
    }

    public function destroy_all() {
        $user_id = Auth::id();
        Cart::where('user_id', $user_id)->delete();
        return response()->json([
            'result' => 'delete all success',
        ], 200);
    }
}
