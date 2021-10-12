@extends('adminlte::page')

@section('title', 'Quản lý danh mục sản phẩm')

@section('content_header')
    <h1>Quản lý danh mục sản phẩm</h1>
@stop

@section('content')
    <!-- Nội dung chính của trang quản trị -->
    <div class="card">
        <div class="card-header">
            <a href=" {{ route('adminCategory.create') }} " class="btn btn-success" >Tạo mới</a>
            <div class="card-tools">
                <ul class="pagination pagination-sm m-0 float-right">
                    {{ $categories->links() }}
                </ul>
            </div>
        </div>
        <table class="table table-bordered">
            <thead>
            <tr>
                <th style="width: 5%">#</th>
                <th style="width: 30%">Tên danh mục</th>
                <th style="width: 53%">Mô tả</th>
                <th style="width: 12%">Thao tác</th>
            </tr>
            </thead>
            <tbody>
            @foreach ($categories as $category)
            <tr>
                <td>{{ $loop->index+1 }}</td>
                <td>{{ $category->name }}</td>
                <td>{{ $category->short_desc }}</td>
                <td >
                    <a href="{{ route('adminCategory.edit', $category->id) }}" class="btn btn-warning btn-sm" >Sửa</a>                    
                    <form action="{{ route('adminCategory.destroy', $category->id) }}" method="POST" style="display: inline;">
                        @method('DELETE')
                        @csrf
                        <button type="submit" class="btn btn-danger btn-sm float-right" onclick="return confirmDelete()">Xóa</button>
                    </form>
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