@extends('layouts.admin.master')
@section('module-name', "Đợt thi")
@section('page-name', 'Danh sách đợt thi')
@section('content')
    <div class="card card-flush pt-5 pb-5">
        <div class="card-body">
            <table class="table table-hover table-responsive">
                <thead>
                <th>Tên đợt thi</th>
                <th>Sheet Id</th>
                <th>Trạng thái</th>
                <th>Đồng bộ</th>
                <th>Lượt đồng bộ</th>
                <th>Thống kê</th>
                <th>
                    <a href="{{route('dotthi.add')}}" class="btn btn-sm btn-success">Tạo mới</a>
                </th>
                </thead>
                <tbody>
                @isset($delete)
                    <div class="alert alert-success" role="alert">
                        {{$delete}}
                    </div>
                @endisset
                @foreach($dotthi as $dt)
                    <tr>
                        <td>{{$dt->name}}</td>
                        <td>{{$dt->sheet_id}}</td>
                        <td>{{$dt->status == 1 ? "Active" : "Inactive"}}</td>
                        <td>{{$dt->trang_thai_dong_bo == 1 ? "Đã đồng bộ" : "Đang đồng bộ"}}</td>
                        <td>
                            @if(count($dt->dong_bo_dot_thi) == 0)
                                0
                            @else
                                {{$dt->dong_bo_dot_thi[count($dt->dong_bo_dot_thi) - 1]->luot_dong_bo}}
                            @endif
                        </td>
                        <td>
                            @if(count($dt->dong_bo_dot_thi) == 0)
                                Chưa có dữ liệu
                            @else
                                {{count($dt->luot_bao_cao)}}/{{$dt->dong_bo_dot_thi[count($dt->dong_bo_dot_thi) - 1]->so_ban_ghi}}
                            @endif
                        </td>
                        <td>
                            <button class="btn btn-icon btn-color-gray-400 btn-active-color-primary justify-content-end show menu-dropdown" data-kt-menu-trigger="click" data-kt-menu-placement="bottom-end" data-kt-menu-overflow="true">
                                <!--begin::Svg Icon | path: icons/duotune/general/gen023.svg-->
                                <span class="svg-icon svg-icon-1">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                                        <rect opacity="0.3" x="2" y="2" width="20" height="20" rx="4" fill="black"></rect>
                                        <rect x="11" y="11" width="2.6" height="2.6" rx="1.3" fill="black"></rect>
                                        <rect x="15" y="11" width="2.6" height="2.6" rx="1.3" fill="black"></rect>
                                        <rect x="7" y="11" width="2.6" height="2.6" rx="1.3" fill="black"></rect>
                                    </svg>
                                </span>
                                <!--end::Svg Icon-->
                            </button>
                            <a href="{{route('dotthi.edit')}}?id={{ $dt->id }}" class="btn btn-sm btn-info" title="Chỉnh sửa">
                                <i class="fa fa-pencil-alt"></i>
                            </a>
                            <a href="{{route('dotthi.delete')}}?id={{ $dt->id }}" class="btn btn-sm btn-danger" title="Xóa">
                                <i class="fa fa-trash"></i>
                            </a>
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endsection
