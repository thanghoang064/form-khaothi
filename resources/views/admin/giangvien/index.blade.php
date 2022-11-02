@extends('layouts.admin.master')
@section('module-name', "Giảng viên")
@section('page-name', 'Danh sách giảng viên')
@section('content')
    <div class="card card-flush pt-5 pb-5">
        <div class="card-body">
            <div>
                <form action="" method="get">
                    <div class="row">
                        <div class="col-4">
                            <div class="mb-3">
                                <input type="text" class="form-control" name="name_search" id="giangvien" placeholder="Nhập tên giảng viên hoặc email..." >
                            </div>
                        </div>
                        <div class="col-4">
                            <div class="mb-3">
                                <select name="bomon" class="form-select" id="">
                                    <option selected>Chọn bộ môn</option>
                                    @foreach($options as $value)
                                    <option value="{{$value->id}}" @php if(isset($role_bomon_id)){ echo $role_bomon_id == $value->id ? 'selected' : '';}  @endphp  >{{$value->name}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-4"><button type="submit" class="btn btn-primary">Tìm kiếm</button></div>
                    </div>
                </form>
            </div>
            <table class="table table-hover table-responsive table-bordered">

                <thead>
                <tr>
                    <th class="fw-bolder">Tên giảng viên</th>
                    <th class="fw-bolder">Email</th>
                    <th class="fw-bolder">Chức vụ</th>
                    <th class="fw-bolder">Bộ môn</th>
                </tr>
                </thead>
                <tbody>
                @foreach($users as $user)
                    <tr>
                        <td>{{$user->name}}</td>
                        <td>{{$user->email}}</td>
                        <td>{{$roles->find($user->role_id)->name}}</td>
                        <td>{{$bomon->find($user->role_bomon)->name}}</td>
                        <td>
                            <a href="{{route('fuge.danhsachupload')}}?id={{ $user->id }}" class="btn btn-sm btn-info"
                               title="Chỉnh sửa">
                                Danh sách upload
                            </a>
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
            {{ $users->appends($datas)->links() }}
        </div>
    </div>

@endsection
