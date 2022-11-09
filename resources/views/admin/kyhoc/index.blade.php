@extends('layouts.admin.master')
@section('module-name', "Kỳ học")
@section('page-name', 'Danh sách kì học')
@section('page-style')
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script>
        function delete_ky_hoc(url){
            $.get(url, function(){
                location.reload();
            });
        }
    </script>
@endsection
@section('content')
    <div class="card card-flush pt-5 pb-5">
        @if (session('status'))
            <div id="emailHelp" class="form-text alert alert-success mx-5">{{ session('status') }}</div>
        @endif
        <div class="card-body">
            <div>
                <form action="" method="get">
                    <div class="row">
                        <div class="col-4">
                            <div class="mb-3">
                                <input type="text" class="form-control" name="name_search" id="giangvien" placeholder="Nhập tên kỳ học" required>
                            </div>
                        </div>
                        <div class="col-4"><button type="submit" class="btn btn-primary">Tìm kiếm</button></div>
                    </div>
                </form>
            </div>
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
                        <td>{{  $item->created_at == "" ? "Chưa có thời gian tạo" : date('d-m-Y / h:i A', strtotime($item->created_at))}}</td>
                        <td>{{ $item->updated_at == "" ? "Chưa có thời gian cập nhật" : date('d-m-Y / h:i A', strtotime($item->updated_at)) }}</td>
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
{{--                            location.href='{{route('ky_hoc.delete')}}?id={{$item->id}}'--}}

                            @csrf
                                <button type="button" onclick="confirm('Bạn có chắc chắn muốn xóa không ?') ? delete_ky_hoc('{{route('ky_hoc.delete')}}' + '?id=' + '{{$item->id}}') : ''" class="btn btn-sm btn-danger" id="delete_ky_hoc" title="Xóa">
                                    <i class="fa fa-trash"></i>
                                </button>
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
            {{ $datas->appends($paginate)->links() }}
        </div>
    </div>

@endsection
