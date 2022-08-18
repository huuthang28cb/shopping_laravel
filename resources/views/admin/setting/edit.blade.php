@extends('layouts.admin')

@section('title')
    <title>Trang chủ</title>
@endsection
@section('content')
    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        @include('partials.content-header',['name'=>'settings', 'key'=>'Edit'])
        <!-- /.content-header -->

        <!-- Main content -->
        <div class="content">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-md-6">
                        <form action="{{route('settings.update', ['id'=>$dataSetting->id])}}" method="post">
                            @csrf
                            <div class="form-group">
                                <label>Config key</label>
                                <input type="text"
                                       class="form-control @error('config_key') is-invalid @enderror"
                                       name="config_key"
                                       placeholder="Nhập congfig key"
                                       value="{{$dataSetting->config_key}}"
                                >
                                @error('config_key')
                                <div class="alert alert-danger">{{ $message }}</div>
                                @enderror
                            </div>
                            @if(request()->type === 'Text') {{-- === là vười so sánh text vừa so sánh kiêểu dữ liệu--}}
                            <div class="form-group">
                                <label>Config value</label>
                                <input type="text"
                                       class="form-control @error('config_value') is-invalid @enderror"
                                       name="config_value"
                                       placeholder="Nhập config value"
                                       value="{{$dataSetting->config_value}}"
                                >
                                @error('config_value')
                                <div class="alert alert-danger">{{ $message }}</div>
                                @enderror
                            </div>
                            @elseif(request()->type === 'Textarea')
                                <div class="form-group">
                                    <label>Config value</label>
                                    <textarea
                                        class="form-control @error('config_value') is-invalid @enderror"
                                        name="config_value"
                                        rows="5"
                                    >{{$dataSetting->config_value}}
                                        </textarea>
                                    @error('config_value')
                                    <div class="alert alert-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                            @endif
                            <button type="submit" class="btn btn-primary">Submit</button>
                        </form>
                    </div>
                </div>
                <!-- /.row -->
            </div><!-- /.container-fluid -->
        </div>
        <!-- /.content -->
    </div>
    <!-- /.content-wrapper -->
@endsection

