@extends('layouts.admin.master')
@section('module-name', "Thống kê báo cáo thi theo bộ môn")
@section('page-name', $dotThiName)
@section('content')
    <form action="">
        <select class="form-select" name="bo_mon_id" id="bo_mon_id" onchange="listThongKe(this.value)">
            @foreach($boMon as $bm)
                <option value="{{ $bm['id'] }}">
                    {{ $bm['name'] }}
                </option>
            @endforeach
        </select>
    </form>

    {{-- Thống kê tổng quát --}}
    <div class="my-3">
        <!-- Card  -->
        <div class="card bg-primary text-white">
            <div class="card-body d-flex justify-content-between">
                <div class="card-info px-3 py-3 bg-info rounded" style="width: 30%;">
                    <p class="mb-2 fw-bolder fs-3">Tổng số</p>
                    <p class="m-0 fs-4">
                            <span class="text-white fs-3 so_ca_thi">

                            </span>
                        ca thi
                    </p>
                </div>
                <div class="card-info px-3 py-3 bg-success rounded" style="width: 30%;">
                    <p class="mb-2 fw-bolder fs-3">Đã báo cáo</p>
                    <p class="m-0 fs-4">
                            <span class="text-white fs-3 so_ca_da_bao_cao">

                            </span>
                        ca
                    </p>
                </div>
                <div class="card-info px-3 py-3 bg-danger rounded" style="width: 30%;">
                    <p class="mb-2 fw-bolder fs-3">Chưa báo cáo</p>
                    <p class="m-0 fs-4">
                        <span class="text-white fs-3 so_ca_chua_bao_cao">

                        </span>
                        ca
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
            <div class="col p-2 rounded">
                <div class="p-2 shadow rounded">
                    <table id="table1" class="table table-hover table-responsive table-bordered">

                        <thead>
                        <tr>
                            <th class="fw-bolder">Tên giảng viên</th>
                            <th class="fw-bolder">Email</th>
                            <th class="fw-bolder">Số ca thi</th>
                            <th class="fw-bolder">Số ca đã báo cáo</th>
                            <th class="fw-bolder">Tỉ lệ báo cáo</th>
                        </tr>
                        </thead>
                        <tbody>

                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <script>
        const thongKe = Object.values(@json($thongKeBaoCaoThiTheoBoMon));
        console.log(thongKe);
        let dataTable;

        $(document).ready(function () {
            const boMonElement = $('select[name="bo_mon_id"]');
            listThongKe(boMonElement.val());
        });


        function listThongKe(boMonId) {
            console.log(boMonId)
            if (dataTable) {
                dataTable.destroy();
            }

            const soCaBaoCaoElement = $('span.so_ca_thi');
            const soCaDaBaoCaoElement = $('span.so_ca_da_bao_cao');
            const soCaChuaBaoCaoElement = $('span.so_ca_chua_bao_cao');
            const tableBody = $('table tbody');
            const table = $('#table1');

            let boMon = thongKe.find((item) => item.id == boMonId);
            console.log(boMon);
            soCaBaoCaoElement.html(boMon.so_ca_thi);
            soCaDaBaoCaoElement.html(boMon.so_ca_da_bao_cao);
            soCaChuaBaoCaoElement.html(boMon.so_ca_chua_bao_cao);
            let htmlArr = boMon.danh_sach.sort((a, b) => {
                return a.name.localeCompare(b.name);
            })
            htmlArr = htmlArr.map((item) => {
                return `
                <tr>
                    <td>${item.name}</td>
                    <td>${item.email}</td>
                    <td>${item.so_ca_thi}</td>
                    <td>${item.so_ca_da_bao_cao}</td>
                    <td>${item.ti_le_bao_cao}%</td>
                </tr>
                `;
            })
            let html = htmlArr.join('');
            tableBody.html(html);
            dataTable = new DataTable('#table1');
        }
    </script>
@endsection
