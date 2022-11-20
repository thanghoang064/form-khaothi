@extends('layouts.clients.master')
@section('style-custom')
    <link rel="stylesheet" href="{{asset('styles/baocaothi-lich-su.css')}}">
@endsection
@section('content')
    <div class="container main-content ">
        <h3>Báo cáo thi của giảng viên {{\Illuminate\Support\Facades\Auth::user()->email}} </h3>
        <h4>Đợt thi {{$dotthi->name}}</h4>
        <table class="table table-hover table-responsive">
            <thead>
            <th>Ngày thi</th>
            <th>Ca thi</th>
            <th>Môn</th>
            <th>Lớp</th>
            <th>Số sv thi</th>
            <th>Số sv vắng mặt</th>
            <th>Mã sv vắng mặt</th>
            <th>Số sv vi phạm</th>
            <th>Ngày gửi</th>
            <th>Tải file</th>
            </thead>
            <tbody>
            @foreach($ketqua as $kq)
                <tr>
                    <td>{{Carbon\Carbon::createFromFormat('Y-m-d', $kq->ngay_thi)->format('d/m/Y')}}</td>
                    <td>Ca {{$kq->ca_thi}}</td>
                    <td>{{$kq->monhoc->name}}</td>
                    <td>{{$kq->ten_lop}}</td>
                    <td>{{$kq->so_sv_thi}}</td>
                    <td>{{$kq->so_sv_vang_mat}}</td>
                    <td>{{$kq->ma_sv_vang_mat}}</td>
                    <td>{{$kq->so_sv_vi_pham}}</td>
                    <td>{{Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $kq->created_at)->format('d/m/Y H:i:s')}}</td>
                    <td>
                        <a target="_blank" href="{{route('form.taifilebaocao', ['luotbaocao' => $kq->id])}}" class="btn btn-sm btn-info" title="Tải file xuống">
                            <i class="fa fa-download"></i>
                        </a>
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>
@endsection
