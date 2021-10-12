<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;


class OrderController extends Controller
{
    //
    public function index()
    {
        $id = Auth()->id();
        $listOrder = DB::table('users')
                    ->join('orders', 'users.id','=', 'orders.user_id')
                    ->join('products', 'products.id', '=', 'orders.product_id')
                    ->join('status_orders', 'status_orders.id', '=', 'status_order_id')
                    ->select('products.name as product_name', 'products.thumbnail as thumbnail','status_orders.name as status_name','orders.*')
                    ->where('orders.user_id','=', $id)
                    ->orderByDesc('updated_at')
                    ->get();
        $totalCost =DB::table('orders')
                    ->selectRaw('sum(quantity * sale_price) AS total')
                    ->where('user_id',$id)
                    ->groupBy('user_id')
                    ->get();
        return response()->json([
            'listOrders' => $listOrder,
            'totalAmount' => $totalCost,
            'totalOrders' => $listOrder->count()
        ]);
    }

    public function show($id)
    {
        $order = DB::table('orders')
                    ->join('products', 'products.id', '=', 'orders.product_id')
                    ->join('status_orders', 'status_orders.id', '=', 'status_order_id')
                    ->select('products.name as product_name', 'products.thumbnail as thumbnail', 'status_orders.name as status_name','orders.*')
                    ->where('orders.id', $id)
                    ->get();
        // $unitPrice = Order::find($id)->sale_price;
        return response()->json($order);
    }

    public function store(Request $request)
    {
        //
        $order = new Order;
        $order->fill([
            'user_id' => $request->user_id,
            'product_id' => $request->product_id,
            'quantity' => $request->quantity,
            'sale_price' => $request->sale_price,
            'phone_order' => $request->phone_order,
            'address_order' => $request->address_order,
            'status_order_id' => 1,
        ])->save();
        return 'Dat hang thanh cong';
    }
}
