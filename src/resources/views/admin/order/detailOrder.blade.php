@extends('adminlte::page')

@section('title', 'Chi tiết đơn hàng')

@section('content_header')
    
    
@stop

@section('content')
    <!-- Nội dung chính của trang quản trị -->
      <div class="card card-primary">
      <div class="card-header">
        <h1 class="card-title">Thông tin đơn đặt hàng</h1>
      </div>
      <!-- /.card-header -->
      <div class="card-body col-md-10">
        <div class="form-group">
          <label>Id đơn hàng:</label>
          <p>{{ $order->id }}</p>
        </div>
        <div class="form-group">
          <label>Mặt hàng:</label>
          <p>{{ $order->product->name }}</p>
        </div>
        <div class="form-group">
          <label>Người mua hàng:</label>
          <p>{{ $order->user->name }}</p>
        </div>
        <div class="form-group">
          <label>Điện thoại nhận hàng:</label>
          <p>{{ $order->phone_order }}</p>
        </div>
        <div class="form-group">
          <label>Địa chỉ nhận hàng:</label>
          <p>{{ $order->address_order }}</p>
        </div>
        <div class="form-group">
          <label>Số lượng mua:</label>
          <p>{{ $order->quantity }}</p>
        </div>
        <div class="form-group">
          <label>Đơn giá:</label>
          <p>{{ $order->sale_price }}</p>
        </div>
        <div class="form-group">
          <label>Trạng thái đơn hàng:</label>
          <p>{{ $order->status_order->name }}</p>
        </div>
        <div class="form-group">
          <label>Ngày đặt hàng:</label>
          <p>{{ $order->created_at }}</p>
        </div>
        <div class="form-group">
          <label>Ngày bắt đầu giao hàng:</label>
          <p>{{ $order->shipped_date }}</p>
        </div>
        <div class="form-group">
          <label>Ngày hoàn thành đơn hàng:</label>
          <p>{{ $order->completed_date }}</p>
        </div>
        <div class="form-group">
          <label>Tổng giá trị đơn hàng:</label>
          <p>{{ $order->sale_price * $order->quantity }}</p>
        </div>
        <br>
        <div>  
          @if ($order->status_order_id == 1)
          <a href="{{ route('listConfirmOrder') }}" class="btn btn-warning">Quay lại</a>
          <a href="{{ route('confirmOrder', $order->id) }}" class="btn btn-success">Xác nhận</a>
          @elseif ($order->status_order_id == 2)
          <a href="{{ route('listShippedOrder') }}" class="btn btn-warning">Quay lại</a>
          <a href="{{ route('completedOrder', $order->id) }}" class="btn btn-success">Hoàn thành</a>
          @elseif ($order->status_order_id == 3)
          <a href="{{ route('listCompletedOrder') }}" class="btn btn-warning">Quay lại</a>
          @endif
        </div>        
      </div>
      <!-- /.card-body -->      
    </div>
  </form>
@stop

@section('css')
    <!-- <link rel="stylesheet" href="/css/admin_custom.css"> -->
@stop

@section('js')
  <script>
    function confirmEdit() {
        return confirm('Bạn có chắc muốn sửa danh mục sản phẩm này?');
    }
  </script>
@stop