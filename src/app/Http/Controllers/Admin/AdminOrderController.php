<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Notification;
use App\Models\Order;
use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Events\NewNotification;
use App\Models\UserNotification;

class AdminOrderController extends Controller
{
    //
    public function listConfirmOrder(Request $request) {
        $keywork = $request->q;
        if( $keywork ) {
            $order = Order::find($keywork);
            if( $order ) {
                return redirect()->route('detailOrder', $order->id);
            }
        }
        return view('admin.order.confirmOrder', 
                [
                    'listConfirmOrder' => Order::where('status_order_id', '1')
                                                ->orderBy('created_at', 'desc')
                                                ->paginate(10),
                ]);
    }

    public function listShippedOrder() {
        return view('admin.order.shippedOrder', 
                [
                    'listShippedOrder' => Order::where('status_order_id', '2')
                                                ->orderBy('shipped_date', 'desc')
                                                ->paginate(10),
                ]);
    }

    public function listCompletedOrder() {
        return view('admin.order.completedOrder', 
                [
                    'listCompletedOrder' => Order::where('status_order_id', '3')
                                                ->orderBy('completed_date', 'desc')
                                                ->paginate(10),
                ]);
    }

    public function detailOrder(Request $request, $id) {
        return view('admin.order.detailOrder',
                [
                    'order' => Order::findOrFail($id)
                ]);
    }

    // Các hàm xử lý việc cập nhật trạng thái đơn đặt hàng
    public function confirmOrder($id) {
        Order::findOrFail($id)
            ->update([
                'status_order_id' => 2,
                'shipped_date' => Carbon::now('Asia/Ho_Chi_Minh'),
            ]);
        $user_id = Order::find($id)->user_id;
        $notification = new Notification([
            'content' => "Đơn hàng #$id của bạn đã được xác nhận và đang trong quá trình vận chuyển",
            'type' => 'only user',
        ]);
        $notification->save();

        $statusNoti = new UserNotification([
            'user_id' => $user_id,
            'notification_id' => $notification->id,
        ]);
        $statusNoti->save();

        // Tạo sự kiện có thông báo mới để realtime tới client
        broadcast(new NewNotification($notification, $statusNoti))->toOthers();

        return redirect()->route('listConfirmOrder');
    }

    public function completedOrder($id) {
        Order::findOrFail($id)
                ->update([
                    'status_order_id' => 3,
                    'completed_date' => Carbon::now('Asia/Ho_Chi_Minh'),
                ]);
        $user_id = Order::find($id)->user_id;
        $notification = new Notification([
            'user_id' => $user_id,
            'content' => "Đơn hàng #$id đã được giao hàng thành công",
        ]);
        $notification->save();

        $statusNoti = new UserNotification([
            'user_id' => $user_id,
            'notification_id' => $notification->id,
        ]);
        $statusNoti->save();

        broadcast(new NewNotification($notification, $statusNoti))->toOthers();
        return redirect()->route('listShippedOrder');
    }
}
