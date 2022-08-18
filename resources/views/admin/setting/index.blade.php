@extends('layouts.admin')

@section('title')
    <title>Trang chủ</title>
@endsection
@section('js')
    <script src="{{asset('vendors/sweetAlert2/sweetalert2@11.js')}}"></script>
    <script src="{{asset('admins/main.js')}}"></script>
@endsection
@section('content')
    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        @include('partials.content-header', ['name'=>'settings', 'key'=>'List'])
        <!-- /.content-header -->

        <!-- Main content -->
        <div class="content">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-md-12">
                        <div class="btn-group float-right mr-5">
                            <button class="btn btn-primary dropdown-toggle" data-toggle="dropdown">
                                Thêm setting
                                <span class="caret"></span>
                            </button>
                            <ul class="dropdown-menu">
                                <li><a href="{{route('settings.create') . '?type=Text'}}">Text</a></li>
                                <li><a href="{{route('settings.create') . '?type=Textarea'}}">Textarea</a></li>
                            </ul>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <table class="table">
                            <thead>
                            <tr>
                                <th scope="col">#</th>
                                <th scope="col">Config key</th>
                                <th scope="col">Config value</th>
                                <th scope="col">Action</th>
                            </tr>
                            </thead>
                            <tbody>

                            @foreach($dataSetting as $settingItem)

                                <tr>
                                    <th scope="row">{{$settingItem->id}}</th>
                                    <td>{{$settingItem->config_key}}</td>
                                    <td>{{$settingItem->config_value}}</td>
                                    <td>
                                        <a
                                            href="{{route('settings.edit', ['id'=>$settingItem->id]) . '?type=' . $settingItem->type}}"
                                            class="btn btn-default">Edit</a>
                                        <a
                                            href="{{route('settings.delete', ['id'=>$settingItem->id])}}"
                                            data-url="{{route('settings.delete', ['id'=>$settingItem->id])}}"
                                            class="btn btn-danger action_delete">Delete</a>
                                    </td>
                                </tr>

                            @endforeach

                            </tbody>
                        </table>
                    </div>
                    <div class="col-md-12">
                        {{$dataSetting->links()}}
                    </div>
                </div>
                <!-- /.row -->
            </div><!-- /.container-fluid -->
        </div>
        <!-- /.content -->
    </div>
    <!-- /.content-wrapper -->
@endsection

