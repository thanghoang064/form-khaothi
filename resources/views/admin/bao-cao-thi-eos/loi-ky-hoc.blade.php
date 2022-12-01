@extends('layouts.admin.master')
@section('module-name', "EOS")
@section('page-name', 'Error')
@section('content')
    <div class="row py-3">
        <h1>Lỗi</h1>
        <p>Chưa có kỳ học, <a href="{{ route('ky_hoc.add') }}">click vào đây</a> để tạo kỳ học</p>
    </div>
@endsection
