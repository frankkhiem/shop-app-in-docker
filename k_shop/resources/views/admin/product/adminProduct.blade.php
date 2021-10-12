@extends('adminlte::page')

@section('title', 'Quản lý sản phẩm')

@section('content_header')
    <h1>Danh sách sản phẩm</h1>
@stop

@section('content')
    <!-- Nội dung chính của trang quản trị -->
    <div class="card">
        <div class="card-header">
            <a href=" {{ route('adminProduct.create') }} " class="btn btn-outline-success" >Tạo mới</a>
            <a href=" {{ route('viewFileImportProducts') }} " class="btn btn-outline-primary" id="btn-file-import">Nhập bằng file</a>
            <div class="card-tools">
                <ul class="pagination pagination-sm m-0 float-right">
                    {{ $products->onEachSide(1)->links() }}
                </ul>
            </div>
        </div>
        <table class="table table-bordered">
            <thead>
            <tr>
                <th style="width: 3%">#</th>
                <th style="width: 16%">Tên sản phẩm</th>
                <th style="width: 16%">
                    <div>Danh mục</div>
                    <form action="#" method="GET" id="form_category">
                        @csrf
                        <select name="category" class="form-control" onchange="this.form.submit()">
                            @if ($current_category == 0) 
                            <option value="0" selected="selected">Tất cả</option>
                            @else
                            <option value="0">Tất cả</option>
                            @endif
                            @foreach ($categories as $category)
                            @if ($current_category == $category->id)
                            <option value="{{ $category->id }}" selected="selected">{{ $category->name }}</option>
                            @else
                            <option value="{{ $category->id }}">{{ $category->name }}</option>
                            @endif
                            @endforeach
                        </select>
                    </form>
                </th>
                <th style="width: 12%">Ảnh</th>
                <th style="width: 11%">Mô tả</th>
                <th style="width: 12%">Giá bán</th>
                <th style="width: 10%">Trạng thái</th>
                <th style="width: 8%">Nổi bật</th>
                <th style="width: 12%">Thao tác</th>
            </tr>
            </thead>
            <tbody>
            @foreach ($products as $product)
            <tr>
                <td>{{ $loop->index+1 }}</td>
                <td>{{ $product->name }}</td>
                @if ($product->category_id === null)
                <td>...</td>
                @else
                <td>{{ $product->category->name }}</td>
                @endif
                <td><img src="/uploads/imagesProduct/{{ $product->thumbnail }}" height="100"></td>
                <td>{{ $product->short_desc }}</td>
                <td>{{ $product->price }}</td>
                <td>{{ $product->status_product->name }}</td>
                @if ($product->star)
                <td>Có</td>
                @else
                <td>Không</td>
                @endif
                <td >
                    <a href="{{ route('adminProduct.edit', $product->id) }}" class="btn btn-warning btn-sm" >Sửa</a>                    
                    <form action="{{ route('adminProduct.destroy', $product->id) }}" method="POST" style="display: inline;">
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
    <style>
        #btn-file-import {
            margin-left: 20px;
        }
    </style>
@stop

@section('js')
    <script>
        function confirmDelete() {
            return confirm('Bạn có chắc muốn xóa danh mục sản phẩm này khỏi hệ thống?');
        }        
    </script>
@stop