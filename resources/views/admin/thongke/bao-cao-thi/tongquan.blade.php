@extends('layouts.admin.master')
@section('module-name', "Thống kê báo cáo thi")
@section('page-name', $dotThiName)
@section('content')

    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.7.1/Chart.min.js" charset="utf-8"></script>
    <div class="row py-3">
        <div class="my-3">
            <!-- Card  -->
            <div class="card bg-primary text-white mb-5">
                <div class="card-header align-items-center justify-content-between">
                    <h5 class="fs-2 text-white">Tổng quan</h5>
                    <a href="{{ route('thongke.bao-cao-thi-theo-bo-mon') }}" class="btn btn-info">Xem theo bộ môn</a>
                </div>
                <div class="card-body d-flex justify-content-between">
                    <div class="card-info px-3 py-3 bg-info rounded" style="width: 30%;">
                        <p class="mb-2 fw-bolder fs-3">Tổng số</p>
                        <p class="m-0 fs-4">
                            <span class="text-white fs-3">
                                {{ $thongKeBaoCaoThiTong['so_ca_thi'] }}
                            </span>
                            ca thi
                        </p>
                    </div>
                    <div class="card-info px-3 py-3 bg-success rounded" style="width: 30%;">
                        <p class="mb-2 fw-bolder fs-3">Đã báo cáo</p>
                        <p class="m-0 fs-4">
                            <span class="text-white fs-3">
                                {{ $thongKeBaoCaoThiTong['so_ca_da_bao_cao'] }}/{{ $thongKeBaoCaoThiTong['so_ca_thi'] }}
                            </span>
                            ca đã báo cáo
                        </p>
                    </div>
                    <div class="card-info px-3 py-3 bg-danger rounded" style="width: 30%;">
                        <p class="mb-2 fw-bolder fs-3">Chưa nhập điểm</p>
                        <p class="m-0 fs-4">
                            <span class="text-white fs-3">
                                {{ $thongKeBaoCaoThiTong['so_ca_chua_bao_cao'] }}/{{ $thongKeBaoCaoThiTong['so_ca_thi'] }}
                            </span>
                            ca chưa báo cáo
                        </p>
                    </div>
                </div>
            </div>
            <!-- End Card -->
            <div class="mt-5 pt-5">
                <h3>Biểu đồ thống kê lượt báo cáo thi - {{ $dotThiName }}</h3>
                <div id="chartJSContainer" style="">
                    <canvas id="myBarChart"></canvas>
                </div>
            </div>
        </div>

    </div>
    <script>
        const labels = @json($labels);
        const tongSoCa = @json($tongSoCa);
        const soCaDaBaoCao = @json($soCaDaBaoCao);
        const soCaChuaBaoCao = @json($soCaChuaBaoCao);
        $(document).ready(function () {
            const barChart = $('#myBarChart').get(0).getContext('2d');
            const data = {
                labels: labels,
                datasets: [
                    {
                        label: 'Số ca đã báo cáo',
                        data: soCaDaBaoCao,
                        backgroundColor: 'rgb(75, 192, 192)',
                        stack: 'Stack 0',
                    },
                    {
                        label: 'Số ca chưa báo cáo',
                        data: soCaChuaBaoCao,
                        backgroundColor: 'rgb(255, 99, 132)',
                        stack: 'Stack 0',
                    },
                    {
                        label: 'Tổng số ca',
                        data: tongSoCa,
                        backgroundColor: 'rgb(54, 162, 235)',
                        stack: 'Stack 1',
                    },
                ]
            };
            const options = {
                indexAxis: 'y',
                scales: {
                    x: {
                        ticks: {
                            stepSize: 1,
                        },
                        stacked: true,
                    },
                    y: {
                        ticks: {
                            stepSize: 1,
                        },
                        stacked: true,
                    }
                }
            }
            new Chart(barChart, {
                type: 'bar',
                data: data,
                options: options,
            });
        })
    </script>
@endsection
