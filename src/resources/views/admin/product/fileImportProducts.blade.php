@extends('adminlte::page')

@section('title', 'Tạo sản phẩm bằng file')

@section('content_header')
    
    
@stop

@section('content')
    <!-- Nội dung chính của trang quản trị -->
  <div>
    <div class="card card-primary">
      <div class="card-header">
        <h1 class="card-title">Nhập file zip chứa dữ liệu sản phẩm, thông số sản phẩm và ảnh sản phẩm</h1>
      </div>
      <!-- /.card-header -->
      <div class="card-body col-md-8">

        @if ( session('message') )
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
              <a href="{{ route('adminProduct.index') }}">Tới trang quản trị >>>>>>></a>
            </div>
        </div>
        @endif

        <!-- Modal -->
        <div class="modal fade" id="exampleModalCenter" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
          <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
              <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLongTitle">Kết quả nhập dữ liệu sản phẩm</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
                </button>
              </div>
              <div class="modal-body">
                <!-- nội dung kết quả quá trình nhập dữ liệu -->
                <div id="result-import"></div>
                <hr>
                <form action="{{ route('downloadProductsLogFile') }}" method="POST">
                  @csrf
                  <h6>Tải xuống file log:</h6>
                  <input type="hidden" name="filePath" id="logFilePath">
                  <button type="submit" class="btn btn-primary btn-sm">Download</button>
                </form>
              </div>
              <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Đóng</button>
              </div>
            </div>
          </div>
        </div>

        <form action="{{ route('importProductsByFile') }}" method="POST" enctype="multipart/form-data">
          @csrf
          <div class="file-input">
            <input type="file" name="file-import-products" id="customFile">
            <!-- <label for="customFile">Choose file</label> -->
          </div>
          <br>
          <div>          
            <button type="submit" class="btn btn-success float-right">Xác nhận</button>
            <a href="{{ route('adminProduct.index') }}" class="btn btn-danger float-right">Hủy bỏ</a>
          </div>
        </form> 
      </div>
      <!-- /.card-body -->
    </div>
  </div>
  <br><hr><br>
@stop

@section('css')
  <!-- <link rel="stylesheet" href="/css/admin_custom.css"> -->
<style>
  .file-input {
    margin-bottom: 20px;
  }
</style>
@stop

@section('js')
<script src="http://localhost:6001/socket.io/socket.io.js"></script>
<script type="text/javascript" src="{{ asset('js/more_js/app2.js') }}"></script>
@stop