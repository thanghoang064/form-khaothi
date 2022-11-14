@extends('layouts.admin.master')
@section('module-name', "Thống kê nhập điểm theo bộ môn")
@section('page-name', $hocKyName)
@section('content')
    <div class="row py-3">
        @foreach($thongKeNhapDiemTheoBoMon as $bm)
            <div class="my-3">
                <!-- Card  -->
                <div class="card bg-primary text-white">
                    <div class="card-header align-items-center justify-content-between">
                        <h5 class="fs-2 text-white">{{ $bm['name'] }}</h5>
                        <a href="{{ route('nhap-diem-theo-bo-mon', ['idBoMon' => $bm['id']]) }}" class="btn btn-info">Xem chi tiết</a>
                    </div>
                    <div class="card-body d-flex justify-content-between">
                        <div class="card-info px-3 py-3 bg-info rounded" style="width: 30%;">
                            <p class="mb-2 fw-bolder fs-3">Tổng số</p>
                            <p class="m-0 fs-4">
                                <span class="text-white fs-3">
                                    {{ $bm['tong_so_giang_vien'] }}
                                </span>
                                giảng viên
                            </p>
                        </div>
                        <div class="card-info px-3 py-3 bg-success rounded" style="width: 30%;">
                            <p class="mb-2 fw-bolder fs-3">Đã nhập điểm</p>
                            <p class="m-0 fs-4">
                                <span class="text-white fs-3">
                                    {{ $bm['so_giang_vien_da_nhap_diem'] }}/{{ $bm['tong_so_giang_vien'] }}
                                </span>
                                giảng viên
                            </p>
                        </div>
                        <div class="card-info px-3 py-3 bg-danger rounded" style="width: 30%;">
                            <p class="mb-2 fw-bolder fs-3">Chưa nhập điểm</p>
                            <p class="m-0 fs-4">
                                <span class="text-white fs-3">
                                    {{ $bm['so_giang_vien_chua_nhap_diem'] }}/{{ $bm['tong_so_giang_vien'] }}
                                </span>
                                giảng viên
                            </p>
                        </div>
                    </div>
                </div>
                <!-- End Card -->
            </div>
        @endforeach
    </div>
@endsection
