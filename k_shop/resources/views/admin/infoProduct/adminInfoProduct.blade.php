@extends('adminlte::page')

@section('title', 'Thông số sản phẩm')

@section('content_header')    
  <h1>Thông số kỹ thuật: <strong>{{ $product->name }}</strong></h1>
@stop

@section('content')
    <!-- Nội dung chính của trang quản trị -->
    <div class="card">
        <div class="card-header">          
          <form action="{{ route('createInfoProduct', $product->id) }}" method="POST">            
              @csrf
              <button type="submit" class="btn btn-success" >Thêm</button>
              <input name="attribute" id="edit1" type="text" placeholder="Thông số...">
              <input name="information" id="edit2" type="text" placeholder="Mô tả...">            
          </form>          
        </div>
        <table class="table table-bordered">
            <thead>
            <tr>
                <th style="width: 5%">#</th>
                <th style="width: 25%">Thông số</th>
                <th style="width: 58%">Mô tả</th>
                <th style="width: 12%">Thao tác</th>
            </tr>
            </thead>
            <tbody>
            @foreach ($listInfo as $info)
            <tr>
                <td>{{ $loop->index+1 }}</td>
                <td>{{ $info->attribute }}</td>
                <td>{{ $info->information }}</td>
                <td >
                    <a href="{{ route('editInfoProduct', [$product->id, $info->id]) }}" class="btn btn-warning btn-sm" >Sửa</a>                    
                    <form action="{{ route('deleteInfoProduct', [$product->id, $info->id]) }}" method="POST" style="display: inline;">
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
    <a href="{{ route('adminProduct.index') }}" class="btn btn-primary btn-lg float-right">Tiếp tục</a>
@stop

@section('css')
    <!-- <link rel="stylesheet" href="/css/admin_custom.css"> -->
    <style>
      #edit1{
        display: inline-block;
        height: 42px;
        margin-left: 20px;
        width: 220px;
      }
      #edit2{
        display: inline-block;
        height: 42px;
        margin-left: 10px;
        width: 350px;
      }
    </style>
@stop

@section('js')
  <script>
    function confirmDelete() {
        return confirm('Bạn có chắc muốn xóa thông số này khỏi sản phẩm?');
    }
  </script>
@stop