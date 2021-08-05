@extends('adminlte::page')

@section('title', 'Quản lý người dùng')

@section('content_header')
    <h1>Quản lý tài khoản người dùng</h1>
@stop

@section('content')
    <!-- Nội dung chính của trang quản trị -->
    <div class="card">
        <div class="card-header">
            <div class="card-tools">
                <ul class="pagination pagination-sm m-0 float-right">
                    {{ $listCustomer->links() }}
                </ul>
            </div>
        </div>
        <table class="table table-bordered">
            <thead>
            <tr>
                <th style="width: 5%">#</th>
                <th style="width: 30%">Tên tài khoản</th>
                <th style="width: 35%">Email</th>
                <th style="width: 20%">Ngày đăng ký</th>
                <th style="width: 10%">Thao tác</th>
            </tr>
            </thead>
            <tbody>
            @foreach ($listCustomer as $customer)
            <tr>
                <td>{{ $loop->index+1 }}</td>
                <td>{{ $customer->name }}</td>
                <td>{{ $customer->email }}</td>
                <td>{{ $customer->created_at }}</td>
                <td >
                  <a href="{{ route('banCustomer', $customer->id) }}" class="btn btn-danger btn-sm" onclick="return confirmDelete()">Khóa</a> 
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
            return confirm('Bạn có chắc muốn khóa tài khoản này?');
        }
    </script>
@stop