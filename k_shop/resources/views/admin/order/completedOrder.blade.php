@extends('adminlte::page')

@section('title', 'Quản lý đơn hàng')

@section('content_header')
    <h1>Đơn hàng đã hoàn thành</h1>
@stop

@section('content')
    <!-- Nội dung chính của trang quản trị -->
    <div class="card">
        <div class="card-header">
            <div class="card-tools">
                <ul class="pagination pagination-sm m-0 float-right">
                    {{ $listCompletedOrder->links() }}
                </ul>
            </div>
        </div>
        <table class="table table-bordered">
            <thead>
            <tr>
                <th style="width: 5%">#</th>
                <th style="width: 25%">Người mua hàng</th>
                <th style="width: 25%">Mặt hàng</th>
                <th style="width: 30%">Mô tả</th>
                <th style="width: 10%">Thao tác</th>
            </tr>
            </thead>
            <tbody>
            @foreach ($listCompletedOrder as $order)
            <tr>
                <td>{{ $loop->index+1 }}</td>
                <td>
                    <p>{{ $order->user->name }}</p>
                    <p>({{ $order->user->email }})</p>
                </td>
                <td>{{ $order->product->name }}</td>
                <td>
                  <p>Id đơn hàng: {{ $order->id }}</p>
                  <p>Số lượng: {{ $order->quantity }}</p>
                  <p>Đơn giá: {{ $order->sale_price }}</p>
                  <p>SĐT: {{ $order->phone_order }}</p>
                  <p>Địa chỉ nhận hàng: {{ $order->address_order }}</p>
                  <p>Thời điểm hoàn thành: {{ $order->completed_date }}</p>
                </td>
                <td >
                    <a href="{{ route('detailOrder', $order->id) }}" class="btn btn-warning btn-sm" >Chi tiết</a>           
                </td>
            </tr>
            @endforeach
            </tbody>
        </table>
    </div>
@stop

@section('css')
    <!-- <link rel="stylesheet" href="/css/admin_custom.css"> -->
@stop

@section('js')
    <script>
        function confirmDelete() {
            return confirm('Bạn có chắc muốn xóa danh mục sản phẩm này khỏi hệ thống?');
        }
    </script>
@stop