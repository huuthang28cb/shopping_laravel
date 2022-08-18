@extends('layouts.admin')

@section('title')
    <title>Trang chủ</title>
@endsection
@section('js')
    <script src="{{asset('vendors/sweetAlert2/sweetalert2@11.js')}}"></script>
    <script src="{{asset('admins/slider/index/index.js')}}"></script>
@endsection
@section('content')
    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        @include('partials.content-header', ['name'=>'slides', 'key'=>'List'])
        <!-- /.content-header -->

        <!-- Main content -->
        <div class="content">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-md-12">
                        <a href="{{route('slider.create')}}" class="btn btn-success float-right m-2">Thêm slider</a>
                    </div>
                    <div class="col-md-12">
                        <table class="table">
                            <thead>
                            <tr>
                                <th scope="col">#</th>
                                <th scope="col">Tên slider</th>
                                <th scope="col">Mô tả</th>
                                <th scope="col">Hình ảnh</th>
                                <th scope="col">Action</th>
                            </tr>
                            </thead>
                            <tbody>

                            @foreach($dataSlider as $sliderItem)

                                <tr>
                                    <th scope="row">{{$sliderItem->id}}</th>
                                    <td>{{$sliderItem->name}}</td>
                                    <td>{{$sliderItem->description}}</td>
                                    <td>
                                        <img width="350px" height="250px" src="{{$sliderItem->image_path}}">
                                    </td>
                                    <td>
                                        <a href="{{route('slider.edit', ['id'=>$sliderItem->id])}}" class="btn btn-default">Edit</a>
                                        <a
                                            href="{{route('slider.delete', ['id'=>$sliderItem->id])}}"
                                            data-url="{{route('slider.delete', ['id'=>$sliderItem->id])}}"
                                            class="btn btn-danger action_delete">Delete
                                        </a>
                                    </td>
                                </tr>

                            @endforeach

                            </tbody>
                        </table>
                    </div>
                    <div class="col-md-12">
                        {{$dataSlider->links()}}
                    </div>
                </div>
                <!-- /.row -->
            </div><!-- /.container-fluid -->
        </div>
        <!-- /.content -->
    </div>
    <!-- /.content-wrapper -->
@endsection

