@extends('adminlte::page')

@section('title', 'Chỉnh sửa sản phẩm')

@section('content_header')
    
    
@stop

@section('content')
    <!-- Nội dung chính của trang quản trị -->
  <form action="{{ route('adminProduct.update', $product->id) }}" method="POST">
    @csrf
    @method('PUT')
    <div class="card card-primary">
      <div class="card-header">
        <h1 class="card-title">Chỉnh sửa sản phẩm</h1>
      </div>
      <!-- /.card-header -->
      <div class="card-body col-md-8">
        <div class="form-group">
          <label for="productName">Tên sản phẩm</label>
          <input type="text" name="name" class="form-control form-control-border" id="productName" value="{{ $product->name }}">
        </div>
        <div class="form-group">
          <label>Lựa chọn danh mục cho sản phẩm</label>          
          <select name="category_id" class="form-control select2bs4 select2-hidden-accessible" style="width: 100%;" data-select2-id="17" tabindex="-1" aria-hidden="true">
            @foreach ($categories as $category)
              @if ($category->id == $product->category_id)
                <option value="{{ $category->id }}" selected="selected">{{ $category->name }}</option>
              @else
                <option value="{{ $category->id }}">{{ $category->name }}</option>
              @endif
            @endforeach          
          </select>
        </div>
        <div class="form-group">
          <input type="hidden" name="image" value="{{ $product->image }}">
          <label for="productImage">Ảnh sản phẩm</label>
          <div>
          @if ($images != null)
          @foreach ($images as $image)
            <img src="/uploads/imagesProduct/{{ $image }}" alt="" height="200">
          @endforeach
          @endif
          </div>
        </div>
        <div class="form-group">
          <label for="shortDesc">Mô tả ngắn</label>
          <input type="text" name="short_desc" class="form-control form-control-border" id="shortDesc" value="{{ $product->short_desc }}">
        </div>
        <div class="form-group">
          <label for="fullDesc">Mô tả chi tiết</label>
          <textarea class="form-control" name="full_desc" id="fullDesc" rows="3">{{ $product->full_desc }}</textarea>
        </div>
        <div class="form-group">
          <label for="productPrice">Giá bán sản phẩm</label>
          <input type="number" name="price" class="form-control form-control-border" id="productPrice" value="{{ $product->price }}">
        </div>
        <div class="form-group">
          <label>Thông số kỹ thuật</label>
          <a class="btn btn-app" href="{{ route('listInfoProduct', $product->id) }}">
            <i class="fas fa-edit"></i> Edit
          </a>
          <textarea class="form-control" rows="5" disabled>@foreach ($product->info_products as $info){{ $info->attribute }}: {{ $info->information }}&#13;&#10;@endforeach</textarea>
        </div>
        <div class="form-group">
          <label>Trạng thái sản phẩm</label>          
          <select name="status_product_id" class="form-control select2bs4 select2-hidden-accessible" style="width: 100%;" data-select2-id="17" tabindex="-1" aria-hidden="true">
            @foreach ($status_products as $status_product)
              @if ($status_product->id == $product->status_product_id)
                <option value="{{ $status_product->id }}" selected="selected">{{ $status_product->name }}</option>
              @else
                <option value="{{ $status_product->id }}">{{ $status_product->name }}</option>
              @endif
            @endforeach          
          </select>
        </div>
        <div class="custom-control custom-switch">
          @if ($product->star)
          <input name="star" type="checkbox" class="custom-control-input" id="productStar" checked>
          @else
          <input name="star" type="checkbox" class="custom-control-input" id="productStar">
          @endif
          <label class="custom-control-label" for="productStar">Sản phẩm nổi bật?</label>
        </div>
        <div>
          <br>          
          <button type="submit" class="btn btn-success float-right" onclick="return confirmDelete()">Xác nhận</button>
          <a href="{{ route('adminProduct.index') }}" class="btn btn-danger float-right">Hủy bỏ</a>
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
      function confirmDelete() {
          return confirm('Bạn có chắc muốn thay đổi thông tin sản phẩm này?');
      }        
  </script>
@stop