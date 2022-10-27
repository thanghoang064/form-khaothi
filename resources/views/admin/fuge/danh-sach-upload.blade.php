@extends('layouts.admin.master')
@section('module-name', "Fuge")
@section('page-name', 'Lịch sử upload')
@section('content')
    <div class="container main-content ">
        <div class="d-flex justify-content-between align-items-center">
            <h3>Lịch sử upload của giảng viên {{ $user->name }} </h3>
            <form action="" method="get" class="d-flex">
                <input type="hidden" name="id" value="{{ $user->id }}">
                <select name="ky_hoc" class="form-select me-3" id="">
                    <option value=""> -- Chọn kỳ học --</option>
                    @foreach($arrKyHoc as $id => $kh)
                        <option value="{{ $id }}">
                            {{ $kh }}
                        </option>
                    @endforeach
                </select>
                <button type="submit" name="search_btn" class="btn btn-primary">
                    Tìm
                </button>
            </form>
        </div>
        <table class="table table-hover table-bordered">
            <thead>
            <tr class="">
                <th class="fw-bolder">Học Kỳ</th>
                <th class="fw-bolder">Ngày upload</th>
                <th class="fw-bolder">Tải file</th>
            </tr>
            </thead>
            <tbody>
            @foreach($danhsach as $ds)
                <tr class="">
                    <td>
                        {{ $arrKyHoc[$ds->hoc_ky_id] }}
                    </td>
                    <td>{{Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $ds->created_at)->format('d/m/Y H:i:s')}}</td>
                    <td>
                        <a target="_blank" href="{{route('fuge.download', ['id' => $ds->id])}}"
                           class="btn btn-sm btn-info" title="Tải file xuống">
                            <i class="fa fa-download"></i>
                        </a>
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>
@endsection
