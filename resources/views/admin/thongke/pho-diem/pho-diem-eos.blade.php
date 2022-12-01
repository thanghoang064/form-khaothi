@extends('layouts.admin.master')
@section('module-name', "Phổ điểm EOS")
@section('page-name', )
@section('content')

    <div class="row">
        <div class="pt-5 col">
            <div class="row d-flex align-items-center mb-5">
                <div class="col-md-6">
                    <h5 class="fs-1 col m-0">Phổ điểm chi tiết các môn thi EOS</h5>
                    <p class="fs-6" id="updateElement"></p>
                </div>
                <select name="hoc_ky_id" onchange="listThongKe(this.value)" id="" class="form-select col">
                    @foreach($hocKy as $hk)
                        <option value="{{ $hk['id'] }}"
                                @if ($hk['id'] == $idHocKyHienTai)
                                    selected
                            @endif
                        >
                            {{ $hk['name'] }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="p-2 rounded">
                <div class="px-5 shadow rounded">
                    <table id="table1" class="table table-hover table-responsive table-bordered">

                        <thead>
                        <tr class="">
                            <th class="fw-bolder text-left">Mã môn</th>
                            <th class="fw-bolder text-left">0-0.99</th>
                            <th class="fw-bolder text-left">1-1.99</th>
                            <th class="fw-bolder text-left">2-2.99</th>
                            <th class="fw-bolder text-left">3-3.99</th>
                            <th class="fw-bolder text-left">4-4.99</th>
                            <th class="fw-bolder text-left">5-5.99</th>
                            <th class="fw-bolder text-left">6-6.99</th>
                            <th class="fw-bolder text-left">7-7.99</th>
                            <th class="fw-bolder text-left">8-8.99</th>
                            <th class="fw-bolder text-left">9-9.99</th>
                            <th class="fw-bolder text-left">10</th>
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
        let dataTable;
        const thongKeDiemTheoKy = Object.values(@json($thongKeDiemTheoKy));

        $(document).ready(function () {
            const hocKyElement = $('select[name="hoc_ky_id"]');
            listThongKe(hocKyElement.val());
        })

        function listThongKe(hocKyId) {
            const thongKe = thongKeDiemTheoKy.find(item => item.hoc_ky_id == hocKyId);
            const updateElement = $('#updateElement');
            let status = '(Chưa có dữ liệu)';
            if (thongKe.thoi_gian_cap_nhat) {
                status = `\(Cập nhật lúc ${thongKe.thoi_gian_cap_nhat}\)`
            }
            updateElement.html(status);
            const tableBody = $('table tbody');
            if (dataTable) {
                dataTable.destroy();
            }
            const lstThongKe = Object.values(thongKe.thong_ke).map(e => {
                const thongKeDiem = Object.values(e.thong_ke_diem).map(item => {
                    return `<td class="text-left">${item}</td>`;
                })
                return `
                    <tr>
                        <td>${e.ma_mon}</td>
                        ${thongKeDiem.join('')}
                    </tr>
            `
            });
            tableBody.html(lstThongKe.join(''));
            dataTable = new DataTable('#table1');
        }
    </script>
@endsection
