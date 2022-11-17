@extends('layouts.admin.master')
@section('module-name', "Phổ điểm")
@section('page-name', $dotThiName)
@section('content')
    <div class="row">
        <div class="form-group col-md-6">
            <label for="" class="form-label">Bộ môn</label>
            <select class="form-select" name="bo_mon_id" id="bo_mon_id" onchange="listMonHoc(this.value)">
                @foreach($boMon as $bm)
                    <option value="{{ $bm['id'] }}">
                        {{ $bm['name'] }}
                    </option>
                @endforeach
            </select>
        </div>
        <div class="form-group col-md-6">
            <label for="" class="form-label">Môn học</label>
            <select name="mon_hoc_id" id="mon_hoc_id" class="form-select" onchange="listLopHoc(this.value)">
                @foreach($monHoc as $mh)
                    <option value="{{ $mh['mon_hoc_id'] }}">
                        {{ $mh['name'] }}
                    </option>
                @endforeach
            </select>
        </div>
    </div>

    <div class="row">
        <div class="col-md-6 pt-5">
            <h5 class="fs-1" id="mon_hoc_name">Biểu đồ thống kê</h5>
            <div class="mt-5 pt-10">
                <canvas id="myBarChart"></canvas>
            </div>
            <div class="mt-5 pt-10 d-flex justify-content-center">
                <canvas id="myPieChart" style="width: 400px;"></canvas>
            </div>
        </div>
        <div class="pt-5 col-md-6">
            <h5 class="fs-1">Danh sách chi tiết</h5>
            <div class="p-2 rounded">
                <div class="px-5 shadow rounded">
                    <table class="table table-hover table-responsive table-bordered">

                        <thead>
                        <tr class="">
                            <th class="fw-bolder text-left">Lớp</th>
                            <th class="fw-bolder text-left">0-2</th>
                            <th class="fw-bolder text-left">2-5</th>
                            <th class="fw-bolder text-left">5-8</th>
                            <th class="fw-bolder text-left">8-10</th>
                        </tr>
                        </thead>
                        <tbody>

                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    {{--    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>--}}
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        const $ = document.querySelector.bind(document);
        const $$ = document.querySelectorAll.bind(document);

        let myBarChart;
        let myPieChart;
        const boMon = @json($boMon);
        const monHoc = @json($monHoc);
        const thongKeDiemTheoMon = @json($thongKeDiemTheoMon);
        const lopDotThi = Object.values(@json($lopDotThi));

        const boMonElement = $('select[name="bo_mon_id"]');
        const monHocElement = $('select[name="mon_hoc_id"]');
        const tableBody = $('table tbody');


        listMonHoc(boMonElement.value);

        function listMonHoc(boMonId) {
            const lstMonHocFil = monHoc.filter(item => item.bo_mon_id == boMonId).sort((a, b) => {
                return a.name.localeCompare(b.name);
            });
            const lstMonHoc = lstMonHocFil.map(e => `<option value="${e.mon_hoc_id}">${e.name}</option>`);
            monHocElement.innerHTML = lstMonHoc.join('');
            listLopHoc(monHocElement.value);
        }

        function listLopHoc(monHocId) {
            thongKeMon = thongKeDiemTheoMon[monHocId];
            monHocName = monHoc.find(item => item.mon_hoc_id == monHocId).name;

            soLuongDiemTheoMon = Object.values(thongKeMon).reduce((acc, each) => acc += each);
            thongKeMonTheoPhanTram = [];
            Object.values(thongKeMon).forEach(item => {
                thongKeMonTheoPhanTram.push((item * 100 / soLuongDiemTheoMon).toFixed(2));
            })

            // thongKeMon = Object.values(thongKeDiemTheoMon[monHocId]);
            const barChart = $('#myBarChart').getContext('2d');
            const pieChart = $('#myPieChart').getContext('2d');
            if (myBarChart) {
                myBarChart.destroy();
            }
            if (myPieChart) {
                myPieChart.destroy();
            }
            let dataBarChart = {
                labels: [''],
                datasets: [{
                    label: '0-2',
                    data: [thongKeMon.range_0_2],
                    backgroundColor: [
                        "#4b77a9",
                    ],
                    borderWidth: 1
                }, {
                    label: '2-5',
                    data: [thongKeMon.range_2_5],
                    backgroundColor: [
                        "#5f255f",
                    ],
                    borderWidth: 1
                }, {
                    label: '5-8',
                    data: [thongKeMon.range_5_8],
                    backgroundColor: [
                        "#d21243",
                    ],
                    borderWidth: 1
                }, {
                    label: '8-10',
                    data: [thongKeMon.range_8_10],
                    backgroundColor: [
                        "#B27200",
                    ],
                    borderWidth: 1
                },]
            };

            let optionsBarChart = {
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }, plugins: {
                    title: {
                        display: true,
                        text: 'Biểu đồ hình cột thống kê điểm của môn ' + monHocName,
                        font: {
                            size: 16
                        }
                    }, padding: {
                        top: 10,
                        bottom: 30
                    }
                }
            };

            let dataPieChart = [{
                data: thongKeMonTheoPhanTram,
                backgroundColor: [
                    "#4b77a9",
                    "#5f255f",
                    "#d21243",
                    "#B27200",
                ],
                borderColor: "#fff"
            }];

            let optionsPieChart = {
                tooltips: {
                    enabled: false
                },
                plugins: {
                    title: {
                        display: true,
                        text: 'Biểu đồ hình tròn thống kê % điểm của môn ' + monHocName + ' (đơn vị: %)',
                        font: {
                            size: 16
                        }
                    }, padding: {
                        top: 10,
                        bottom: 30
                    }
                }, responsive: true,
                maintainAspectRatio: false,
                scales: {
                    yAxes: [{
                        ticks: {
                            beginAtZero: true
                        }
                    }]
                }
            };
            myPieChart = new Chart(pieChart, {
                type: 'pie',
                data: {
                    labels: ['0-2', '2-5', '5-8', '8-10'],
                    datasets: dataPieChart
                },
                options: optionsPieChart,
            });
            myBarChart = new Chart(barChart, {
                type: 'bar',
                data: dataBarChart,
                options: optionsBarChart,
            });
            const lstLopHocFil = lopDotThi.filter(item => item.mon_hoc_id == monHocId).sort((a, b) => {
                return a.name.localeCompare(b.name);
            });
            const lstLopHoc = lstLopHocFil.map(e => {
                return `
                    <tr>
                        <td>${e.name}</td>
                        <td class="text-left">${e.thong_ke_diem.range_0_2}</td>
                        <td class="text-left">${e.thong_ke_diem.range_2_5}</td>
                        <td class="text-left">${e.thong_ke_diem.range_5_8}</td>
                        <td class="text-left">${e.thong_ke_diem.range_8_10}</td>
                    </tr>
            `
            });
            tableBody.innerHTML = lstLopHoc.join('')
        }
    </script>
@endsection
