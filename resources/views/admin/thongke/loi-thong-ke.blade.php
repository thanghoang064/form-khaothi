@extends('layouts.admin.master')
@section('module-name', "Thống kê nhập")
@section('page-name', 'Error')
@section('content')
    <div class="row py-3">
        <h1>Lỗi</h1>
        <p>Chưa có đợt thi, <a href="{{ route('dotthi.add') }}">click vào đây</a> để tạo kỳ học</p>
    </div>
@endsection
