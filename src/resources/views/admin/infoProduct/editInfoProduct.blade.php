@extends('adminlte::page')

@section('title', 'Chỉnh sửa thông số')

@section('content_header')
    
    
@stop

@section('content')
    <!-- Nội dung chính của trang quản trị -->
  <form action="{{ route('updateInfoProduct', [$product->id, $info->id]) }}" method="POST">
    @csrf
    @method('PUT')
    <div class="card card-primary">
      <div class="card-header">
        <h1 class="card-title">Chỉnh sửa thông số sản phẩm: <strong>{{ $product->name }}</strong></h1>
      </div>
      <!-- /.card-header -->
      <div class="card-body col-md-8">
        <div class="form-group">
          <label for="infoName">Tên thông số</label>
          <input type="text" name="attribute" class="form-control form-control-border" id="infoName" value="{{ $info->attribute }}">
        </div>
        <div class="form-group">
          <label for="infoValue">Mô tả</label>
          <input type="text" name="imformation" class="form-control form-control-border" id="infoValue" value="{{ $info->information }}">
        </div>
        <div>          
          <button type="submit" class="btn btn-success float-right" onclick="confirmEdit()">Hoàn thành</button>
          <a href="{{ route('listInfoProduct', $product->id) }}" class="btn btn-danger float-right">Hủy bỏ</a>
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
        return confirm('Bạn có chắc muốn sửa thông số sản phẩm này?');
    }
  </script>
@stop