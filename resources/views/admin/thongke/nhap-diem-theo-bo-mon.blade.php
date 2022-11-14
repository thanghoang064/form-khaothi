@extends('layouts.admin.master')
@section('module-name', "Thống kê nhập điểm theo bộ môn")
@section('page-name',$boMon->name . ' - ' . $hocKyName)
@section('content')
    <h5 class="fs-1">Tổng quan {{ $boMon->name }}</h5>

    {{-- Thống kê tổng quát --}}
    <div class="my-3">
        <!-- Card  -->
        <div class="card bg-primary text-white">
            <div class="card-body d-flex justify-content-between">
                <div class="card-info px-3 py-3 bg-info rounded" style="width: 30%;">
                    <p class="mb-2 fw-bolder fs-3">Tổng số</p>
                    <p class="m-0 fs-4">
                                <span class="text-white fs-3">
                                    {{ $giangVien['tong_so'] }}
                                </span>
                        giảng viên
                    </p>
                </div>
                <div class="card-info px-3 py-3 bg-success rounded" style="width: 30%;">
                    <p class="mb-2 fw-bolder fs-3">Đã nhập điểm</p>
                    <p class="m-0 fs-4">
                                <span class="text-white fs-3">
                                    {{ $giangVienDaNhap['tong_so'] }}/{{ $giangVien['tong_so'] }}
                                </span>
                        giảng viên
                    </p>
                </div>
                <div class="card-info px-3 py-3 bg-danger rounded" style="width: 30%;">
                    <p class="mb-2 fw-bolder fs-3">Chưa nhập điểm</p>
                    <p class="m-0 fs-4">
                                <span class="text-white fs-3">
                                    {{ $giangVienChuaNhap['tong_so'] }}/{{ $giangVien['tong_so'] }}
                                </span>
                        giảng viên
                    </p>
                </div>
            </div>
        </div>
        <!-- End Card -->
    </div>

    <!-- Danh sách chi tiết -->
    <div class="pt-5">
        <h5 class="fs-1">Danh sách chi tiết</h5>
        <div class="row mt-5">
            <!-- Đã upload -->
            <div class="col-md-6 p-2 rounded">
                <h3 class="fs-4">Giảng viên đã nhập điểm</h3>
                <div class="p-2 shadow rounded">
                    <table class="table table-hover table-responsive table-bordered">

                        <thead>
                        <tr>
                            <th class="fw-bolder">Tên giảng viên</th>
                            <th class="fw-bolder">Email</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($giangVienDaNhap['danh_sach'] as $user)
                            <tr>
                                <td>{{ $user['name'] }}</td>
                                <td>{{ $user['email'] }}</td>
                                <td>
                                    <a href="{{route('fuge.danhsachupload')}}?id={{ $user['id'] }}"
                                       class="btn btn-sm btn-info"
                                       title="Chỉnh sửa">
                                        DS
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Chưa upload -->
            <div class="col-md-6 p-2 rounded">
                <h3 class="fs-4">Giảng viên chưa nhập điểm</h3>
                <div class="p-2 shadow rounded">
                    <table class="table table-hover table-responsive table-bordered">

                        <thead>
                        <tr>
                            <th class="fw-bolder">Tên giảng viên</th>
                            <th class="fw-bolder">Email</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($giangVienChuaNhap['danh_sach'] as $user)
                            <tr>
                                <td>{{ $user['name'] }}</td>
                                <td>{{ $user['email'] }}</td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection
<script>
    const limit = 10;
    giangVienDaNhap = @json($giangVienDaNhap).danh_sach;
    giangVienChuaNhap = @json($giangVienChuaNhap).danh_sach;
    console.log(giangVienChuaNhap);
</script>
