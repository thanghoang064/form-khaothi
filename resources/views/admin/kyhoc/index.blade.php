@extends('layouts.admin.master')
@section('content')
    <div class="card card-flush pt-5 pb-5">
        <div class="card-body">
            <table class="table table-hover table-responsive text-center">
                <thead>
                <th>Id</th>
                <th>Tên kỳ thi</th>
                <th>Ngày tạo</th>
                <th>Ngày cập nhật</th>
                <th>
                    <a href="{{route('ky_hoc.add')}}" class="btn btn-sm btn-success">Tạo mới</a>
                </th>
                </thead>
                <tbody>
                @isset($delete)
                    <div class="alert alert-success" role="alert">
                        {{$delete}}
                    </div>
                @endisset
                @foreach($datas as $item)
                    <tr >
                        <td>{{ $item->id }}</td>
                        <td>{{ $item->name }}</td>
                        <td>{{ $item->created_at == "" ? "Chưa có thời gian tạo" : $item->created_at }}</td>
                        <td>{{ $item->updated_at == "" ? "Chưa có thời gian cập nhật" : $item->updated_at }}</td>
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
                            <a href="{{route('ky_hoc.edit')}}?id={{$item->id}}" class="btn btn-sm btn-info" title="Chỉnh sửa">
                                <i class="fa fa-pencil-alt"></i>
                            </a>
                            <form method="POST" action="{{ route('ky_hoc.delete',[$item->id]) }}" style="display: inline-block;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-danger" id="delete_ky_hoc" title="Xóa">
                                    <i class="fa fa-trash"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    </div>

@endsection
