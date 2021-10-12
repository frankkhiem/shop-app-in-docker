@extends('adminlte::page')

@section('title', 'Quản trị hệ thống')

@section('content_header')
    <h1>Quản trị hệ thống</h1>
@stop

@section('content')
<div class="card card-primary">
    <div class="card-header">
        <h3 class="card-title">Tạo thông báo cho người dùng</h3>
    </div>
    <!-- /.card-header -->
    <!-- form start -->
    <form role="form" action="{{ route('createNewNotification') }}" method="POST">
        @csrf
        <div class="card-body">
            <div class="form-group col-sm-6">
                <label for="content">Nội dung thông báo</label>
                <textarea 
                    id="content" 
                    class="form-control" 
                    rows="4" 
                    placeholder="Giảm giá 10% ..."
                    name="notificationContent"
                ></textarea>
            </div>
            <div class="form-group col-sm-6">
                <label for="notiType">Loại thông báo</label>
                <select id="notiType" class="form-control" name="notiType">
                    <option value="all">Chung cho tất cả người dùng</option>
                    <option>Option 2</option>
                    <option>Option 3</option>
                    <option>Option 4</option>
                </select>
            </div>
        </div>
        <!-- /.card-body -->

        <div class="card-footer">
            <button type="submit" class="btn btn-primary">Tạo</button>
        </div>
    </form>
</div>
@stop

@section('css')
<style>
    div.card {
        margin-top: 30px;
    }
</style>
@stop

@section('js')
  
@stop