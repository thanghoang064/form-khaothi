@extends('layouts.admin.master')
@section('content')
    <div class="container-xxl">
        <div class="card pt-5 pb-5">
            <form action="" method="post">
                @csrf
                <div class="col-md-6 offset-md-3">
                    <div class="form-group mb-3">
                        <label for="">Tên kỳ học<span class="text-danger">*</span></label>
                        <input type="text" name="name_ky_hoc" value="{{$data_view->name}}" class="form-control">
                        @error('name_ky_hoc')
                        <span class="text-danger">{{$message}}</span>
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
