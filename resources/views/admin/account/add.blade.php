@extends('layouts.admin.master')
@section('module-name', "Account")
@section('page-name', 'Tạo tài khoản')
@section('content')
    <div class="container-xxl">
        <div class="card pt-5 pb-5">
            @if (Session::has('msg'))
                <div class="alert alert-success mx-6" role="alert">
                    {{ session('msg') }}
                </div>
            @endif
            @if (isset($update_chucvu))
                <div id="emailHelp" class="form-text alert alert-success mx-5">{{$update_chucvu}}</div>
            @endif
            <form action="" method="post">

                @csrf
                <div class="col-md-6 offset-md-3">
                    <div class="form-group mb-3">
                        <label for="">Nhập tên<span class="text-danger">*</span></label>
                        <input type="text" name="name_account" value="{{old('name_account')}}" class="form-control">
                        @error('name_account')
                        <div id="emailHelp" class="form-text alert alert-danger">{{$message}}</div>
                        @enderror
                    </div>
                    <div class="form-group mb-3">
                        <label for="">Nhập Email<span class="text-danger">*</span></label>
                        <input type="text" name="email_account" value="{{old('email_account')}}" class="form-control">
                        @error('email_account')
                        <div id="emailHelp" class="form-text alert alert-danger">{{$message}}</div>
                        @enderror
                    </div>
                    <div class="form-group mb-3">
                        <label for="">Password<span class="text-danger">*</span></label>
                        <input type="text" placeholder="Ramdom Password" disabled class="form-control">
                        @error('password_account')
                        <div id="emailHelp" class="form-text alert alert-danger">{{$message}}</div>
                        @enderror
                    </div>
                    <div class="form-group mb-3">
                        <label for="">Chức vụ<span class="text-danger">*</span></label>
                        <input type="text" placeholder="{{$permission->name}}" disabled class="form-control">
                        <input type="hidden" name="permission" value="{{$permission->id}}" class="form-control">
                    </div>
                    <div class="form-group mb-3">
                       @if(!isset($role_bomon))
                            <label for="">Bộ môn<span class="text-danger">*</span></label>
                            <select name="bo_mon" class="form-select" id="">
                                <option value="0" selected>--Chọn bộ môn--</option>
                                @foreach($Subject_Leftovers as $value)
                                    <option value="{{$value->id}}" {{ (old("bo_mon") == $value->id ? "selected":"") }}>{{$value->name}}</option>
                                @endforeach
                            </select>
                       @else
                            <label for="">Bộ môn<span class="text-danger">*</span></label>
                            <input type="text" placeholder="{{$name_bomon}}" disabled class="form-control">
                            <input type="hidden" name="bo_mon" value="{{$role_bomon}}" class="form-control">
{{--                            <select name="bo_mon" class="form-select" id="" >--}}
{{--                                <option value="{{$role_bomon}}">{{$name_bomon}}</option>--}}
{{--                            </select>--}}
                        @endif
                        @error('bo_mon')
                        <div id="emailHelp" class="form-text alert alert-danger">{{$message}}</div>
                        @enderror
                    </div>
                    <div class="d-flex justify-content-end mb-3">
                        <button type="submit" class="btn btn-sm btn-primary">Lưu</button>
                        <a href="{{route('ky-hoc.index')}}" class="btn btn-sm btn-danger">Hủy</a>
                    </div>
                </div>

            </form>
        </div>
    </div>
@endsection

