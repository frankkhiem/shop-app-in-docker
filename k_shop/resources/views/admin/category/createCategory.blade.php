@extends('adminlte::page')

@section('title', 'Tạo danh mục')

@section('content_header')
    
    
@stop

@section('content')
    <!-- Nội dung chính của trang quản trị -->
  <form action="{{ route('adminCategory.store') }}" method="POST">
    @csrf
    <div class="card card-primary">
      <div class="card-header">
        <h1 class="card-title">Thêm mới danh mục sản phẩm</h1>
      </div>
      <!-- /.card-header -->
      <div class="card-body col-md-8">
        <div class="form-group">
          <label for="categoryName">Tên danh mục mới</label>
          <input type="text" name="name" class="form-control form-control-border" id="categoryName" placeholder="Điện thoại...">
        </div>
        <div class="form-group">
          <label for="shortDesc">Mô tả ngắn</label>
          <input type="text" name="short_desc" class="form-control form-control-border" id="shortDesc" placeholder="Tập hợp các dòng điện thoại mới nhất...">
        </div>
        <div class="form-group">
          <label for="fullDesc">Mô tả chi tiết</label>
          <textarea class="form-control" name="full_desc" id="fullDesc" rows="5" placeholder="Cung cấp điện thoại uy tín..."></textarea>
        </div>
        <div>          
          <button type="submit" class="btn btn-success float-right">Xác nhận</button>
          <a href="{{ route('adminCategory.index') }}" class="btn btn-danger float-right">Hủy bỏ</a>
        </div>        
      </div>
      <!-- /.card-body -->
    </div>
  </form>
  <br>

  <!-- Tạo danh mục bằng file CSV/Excel -->
  <div>
    <div class="card card-primary">
      <div class="card card-primary">
        <div class="card-header">
          <h1 class="card-title">Tạo danh mục sản phẩm bằng file</h1>
        </div>
        <div class="card-body col-md-8">

          @if ( session('error') )
          <div class="alert alert-danger">
            <p>{{ session('error') }}</p>
          </div>
          @elseif ( session('message') )
          <div class="alert alert-info" id="alert-card">
            <p id="message-status">{{ session('message') }}</p>

            <div class="progress">
              <div 
                id="progress-import"
                class="progress-bar progress-bar-striped progress-bar-animated bg-success" 
                role="progressbar" 
                aria-valuenow="0"
                aria-valuemin="0"
                aria-valuemax="100"
                style="width: 0%"
              >
                0%
              </div>
            </div>
            <br>
            <div id="link-admin-page" style="display: none;">
              <div>
                <!-- Button trigger modal -->
                <p data-toggle="modal" class="btn" data-target="#exampleModalCenter" style="color: white;">
                  Chi tiết
                </p>
              </div>
              <a href="{{ route('adminCategory.index') }}">Tới trang quản trị >>>>>>></a>
            </div>
          </div>
          @endif

          <!-- Modal -->
          <div class="modal fade" id="exampleModalCenter" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
              <div class="modal-content">
                <div class="modal-header">
                  <h5 class="modal-title" id="exampleModalLongTitle">Kết quả nhập dữ liệu danh mục sản phẩm</h5>
                  <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                  </button>
                </div>
                <div class="modal-body">
                  <!-- nội dung kết quả quá trình nhập dữ liệu -->
                  <div id="result-import"></div>
                  <form action="{{ route('downloadCategoriesLogFile') }}" method="POST">
                    @csrf
                    <input type="hidden" name="filePath" id="filePath">
                    <button type="submit" class="btn btn-primary btn-sm">Download</button>
                  </form>
                </div>
                <div class="modal-footer">
                  <button type="button" class="btn btn-secondary" data-dismiss="modal">Đóng</button>
                </div>
              </div>
            </div>
          </div>

          <form action="{{ route('importCategoriesByFile') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div id="file-input">
              <input type="file" name="file-import-categories" id="customFile">
              <!-- <label for="customFile">Choose file</label> -->
            </div>
            <br>
            <div>          
              <button type="submit" class="btn btn-success float-right">Xác nhận</button>
              <a href="{{ route('adminCategory.index') }}" class="btn btn-danger float-right">Hủy bỏ</a>
            </div> 
          </form>
        </div>
      </div>
    </div>
  </div>
@stop

@section('css')
  <!-- <link rel="stylesheet" href="/css/admin_custom.css"> -->
<style>
  #file-input {
    margin-bottom: 20px;
  }
</style>
@stop

@section('js')
<script src="http://localhost:6001/socket.io/socket.io.js"></script>
<script type="text/javascript" src="{{ asset('js/more_js/app2.js') }}"></script>
@stop