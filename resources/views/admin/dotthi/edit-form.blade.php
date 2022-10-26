@extends('layouts.admin.master')
@section('module-name', "Edit đơt thi")
@section('content')
    <div class="container-xxl">
        <div class="card pt-5 pb-5">
            <form action="" method="post">
                @csrf
                <div class="col-md-6 offset-md-3">
                    <div class="form-group mb-3">
                        <label for="">Tên đợt thi<span class="text-danger">*</span></label>
                        <input type="text" name="name_exam" value="{{ $data_dot_thi->name }}" class="form-control">
                        @error('name_exam')
                        <span class="text-danger">{{$message}}</span>
                        @enderror
                    </div>
                    <div class="form-group mb-3">
                        <label for="">ID file google sheet<span class="text-danger">*</span></label>
                        <input type="text" name="sheet_id_exam" value="{{ $data_dot_thi->sheet_id }}" class="form-control">
                        @error('sheet_id_exam')
                        <span class="text-danger">{{$message}}</span>
                        @enderror
                    </div>
                    <div class="d-flex justify-content-end mb-3">
                        <button type="submit" class="btn btn-sm btn-primary">Lưu</button>
                        &nbsp;
                        <a href="{{route('dotthi.index')}}" class="btn btn-sm btn-danger">Hủy</a>
                    </div>
                </div>

            </form>
        </div>
    </div>
@endsection
