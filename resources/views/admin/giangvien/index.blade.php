@extends('layouts.admin.master')
@section('module-name', "Giảng viên")
@section('page-name', 'Danh sách giảng viên')
@section('content')
    <div class="card card-flush pt-5 pb-5">
        <div class="card-body">
            <table class="table table-hover table-responsive table-bordered">
                <thead>
                <tr>
                    <th class="fw-bolder">Tên giảng viên</th>
                    <th class="fw-bolder">Email</th>
                </tr>
                </thead>
                <tbody>
                @foreach($users as $user)
                    <tr>
                        <td>{{$user->name}}</td>
                        <td>{{$user->email}}</td>
                        <td>
                            <a href="{{route('fuge.danhsachupload')}}?id={{ $user->id }}" class="btn btn-sm btn-info"
                               title="Chỉnh sửa">
                                Danh sách upload file fuge
                            </a>
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endsection
