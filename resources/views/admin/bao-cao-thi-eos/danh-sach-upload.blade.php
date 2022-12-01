@extends('layouts.admin.master')
@section('module-name', "EOS")
@section('page-name', 'Lịch sử upload')
@section('content')
    @if(Session::has('success'))
        <script>
            swal('Success!', '{{ Session::get('success') }}', 'success');
        </script>
        <?php Session::forget('error'); ?>
    @endif
    <div class="container main-content ">
        <div class="d-flex justify-content-between align-items-center">
            <h3>Lịch sử upload điểm EOS theo kỳ</h3>
            <div class="d-flex">
                <form action="" method="get" class="d-flex">
                    <select name="hoc_ky_id" class="form-select me-3" id="" onchange="listUpload(this.value)">
                        @foreach($hocKy as $id => $kh)
                            <option value="{{ $kh['id'] }}" @if ($kh['id'] == $hocKyHienTai['id']) selected @endif>
                                {{ $kh['name'] }}
                            </option>
                        @endforeach
                    </select>
                </form>
                <a href="{{ route('form.baocaothieos') }}" class="btn btn-success">
                    Nộp file EOS
                </a>
            </div>
        </div>
        <table id="table1" class="table table-hover table-bordered">
            <thead>
            <tr class="">
                <th class="fw-bolder">Ngày upload</th>
                <th class="fw-bolder">Tải file</th>
            </tr>
            </thead>
            <tbody>
            </tbody>
        </table>
    </div>
    <script>
        let dataTable;
        const $danhSachUpload = Object.values(@json($data));

        $(document).ready(function () {
            const hocKyElement = $('select[name="hoc_ky_id"]');
            listUpload(hocKyElement.val());
        })

        function listUpload(hocKyId) {
            let listBaoCao = $danhSachUpload.find(item => item.id == hocKyId);
            listBaoCao = listBaoCao.danh_sach.sort((a, b) => {
                if (a.id < b.id) {
                    return 1;
                }
                if (a.id > b.id) {
                    return -1;
                }
                // names must be equal
                return 0;
            });

            const tableBody = $('table tbody');
            if (dataTable) {
                dataTable.destroy();
            }
            const lstThongKe = Object.values(listBaoCao).map(e => {
                return `
                        <tr>
                            <td>${e.created_at}</td>
                            <td>
                                <a href="tai-file-bao-cao/${e.id}"
                                class="btn btn-sm btn-info" title="Tải file xuống"
                                >
                                <i class="fa fa-download"></i>
                                </a>
                            </td>
                        </tr>
                `
            });
            tableBody.html(lstThongKe.join(''));
            dataTable = new DataTable('#table1', {
                "ordering": false
            });
        }
    </script>
@endsection
