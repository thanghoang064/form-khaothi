@extends('layouts.admin.master')
@section('module-name', "Giảng viên")
@section('page-name', 'Danh sách giảng viên')
@section('page-style')
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>

        function search(url){
            $.get(url, function(){
                location.reload();
            });
        }

        $(document).ready(function() {
            $(".sl-bomon").change(function () {
                var id_user = $(this).attr("dataaa-id");
                var id_bomon = $(`#bomon_${id_user}`).val();
                var id_acc = $(this).attr("id_ac")
                Swal.fire({
                    title: 'Bạn chắc chắn set bộ môn không ?',
                    text: "Hãy kiểm tra lại thông tin !",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Tiếp tục!'
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            headers: {
                                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                            },
                            url:'{{route('giangvien.list')}}',
                            method:"POST", //First change type to method here
                            data:{
                                idUser: id_user,
                                idBoMon : id_bomon,
                                idAcc : id_acc
                            },
                            success:function() {
                                location.reload();
                            },
                            error:function(){
                                location.reload();
                            }

                        });
                    }else {
                        location.reload();
                    }
                })
            })
            $(".btn-status").click(function () {
                var dataId = $(this).attr("data-id");
                var dataStatus = $(this).attr("status-id");
                var id_acc = $(this).attr("id_ac")
                var id_bomon = $(this).attr("id_bomon")
                // console.log(dataId,dataStatus,id_acc,id_bomon);
                Swal.fire({
                    title: 'Bạn chắc chắn set bộ môn không ?',
                    text: "Hãy kiểm tra lại thông tin !",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Tiếp tục!'
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            headers: {
                                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                            },
                            url:'{{route('giangvien.list')}}',
                            method:"POST", //First change type to method here
                            data:{
                                item_id: dataId,
                                dataStatus : dataStatus,
                                idAcc : id_acc,
                                id_bomon : id_bomon
                            },
                            success:function(response) {
                                console.log(response.role_bomon);
                                if (response.status === 1)    {
                                    if (dataStatus != id_bomon) {
                                    // console.log("mau xanh")

                                    $('#btn-status' + dataId).attr("status-id", id_bomon);
                                        $('#btn-status' + dataId).removeClass('btn-danger');
                                        $('#btn-status' + dataId).addClass('btn-primary');
                                        $('#btn-status' + dataId).html('Set chức vụ');
                                    }else {
                                        // console.log("mau do")
                                        $('#btn-status' + dataId).attr("status-id",0);
                                        $('#btn-status' + dataId).removeClass('btn-primary');
                                        $('#btn-status' + dataId).addClass('btn-danger');
                                        $('#btn-status' + dataId).html('Bỏ set');
                                    }
                                }
                            },
                            error:function(){
                                console.log(response.status);
                                console.log("Error")
                            }

                        });
                    }
                })
            })
        });
    </script>
@endsection
@section('content')
    <div class="card card-flush pt-5 pb-5">
        @if (Session::has('chucvu'))
            <div id="emailHelp" class="form-text alert alert-success mx-5">{{session('chucvu')}}</div>
        @endif
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
                                <select name="bomon" class="form-select" >
                                    <option value="0" selected>Chọn bộ môn</option>
                                    @foreach($options as $value)
                                    <option value="{{$value->id}}" @if(isset($role_bomon_id))  {{$role_bomon_id == $value->id ? 'selected' : ''}}@endif    >{{$value->name}}</option>
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
                    @if($user_account->role_id == 2)
                        <th class="fw-bolder">Bộ môn</th>
                    @elseif($user_account->role_id == 2)
                        <th colspan="2" class="fw-bolder">Set bộ môn</th>
                    @endif
                </tr>
                </thead>
                <tbody>
{{--                Khảo thí--}}
                @if($user_account->role_id == 2)
                    @foreach($users as $user)
                    <tr>
                        <td>{{$user->id}}</td>
                        <td>{{$user->name}}</td>
                        <td>{{$user->email}}</td>
                        <td>{{$roles->find($user->role_id)->name}}</td>
                        <td>
                            @if($user->role_id == 3 || $user->role_id == 1)

                                      <select  dataaa-id="{{$user->id}}" id_ac="{{$user_account->role_id}}" class="sl-bomon form-select" name="bomon_set" id="bomon_{{ $user->id }}">
                                    @foreach($options as $value)
                                        <option class="btn-status" value="{{$value->id}}"   {{$user->role_bomon == $value->id ? 'selected' : ''}} >{{$value->name}}</option>
                                    @endforeach
                                    </select>

                            @elseif($user->role_id == 2)
                                {{$roles->find($user->role_id)->name}}
                            @endif
                        </td>
                        </td>
                        <td>
                            <a href="{{route('fuge.danhsachupload')}}?id={{ $user->id }}" class="btn btn-sm btn-info"
                               title="Chỉnh sửa">
                                Danh sách upload
                            </a>
                        </td>
                    </tr>
                @endforeach
{{--                    Giáo viên chủ nhiệm--}}
                @elseif($user_account->role_id == 3)
                    @foreach($users as $user)
{{--                        $user->role_bomon == $user_account->role_bomon &&--}}
                        @if( $user->role_id == 1 )
                            <tr>
                                <td>{{$user->name}}</td>
                                <td>{{$user->email}}</td>
                                <td>{{$roles->find($user->role_id)->name}}</td>
                                <td>{{$bomon->find($user_account->role_bomon)->name}}</td>
                                <td>
                                    <form action=""   method="POST">
                                        @if($user->role_bomon == $user_account->role_bomon)
                                            <button data-id="{{ $user->id }}" name="status" id_bomon="{{$user_account->role_bomon}}"  id_ac="{{$user_account->role_id }}" status-id="0" type="button" id="btn-status{{ $user->id }}" class="btn btn-danger btn-status" title="Bỏ set"  >Bỏ set</button>
                                        @else
                                            <button data-id="{{ $user->id }}" name="status"  id_bomon="{{$user_account->role_bomon}}"  id_ac="{{$user_account->role_id }}" status-id="{{$user_account->role_bomon}}" type="button" id="btn-status{{ $user->id }}" class="btn btn-primary btn-status" title="Set chức vụ" >Set chức vụ</button>
                                        @endif
                                    </form>
                                </td>
                                </td>
                                <td>
                                    <a href="{{route('fuge.danhsachupload')}}?id={{ $user->id }}" class="btn btn-sm btn-info"
                                       title="Chỉnh sửa">
                                        Danh sách upload
                                    </a>
                                </td>
                            </tr>
                        @endif
                    @endforeach
                @endif
                </tbody>
            </table>
            {{ $users->appends($datas)->links() }}
        </div>
    </div>

@endsection


