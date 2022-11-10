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
            $(".btn-status").click(function () {
                var dataId = $(this).attr("data-id");
                var dataStatus = $(this).attr("status-id");
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
                            },
                            success:function(response) {
                                // console.log(response.status);
                                if (response.status === 1)    {
                                    if (dataStatus == 0) {
                                        // console.log("mau do")
                                        $('#btn-status' + dataId).attr("status-id", 1);
                                        $('#btn-status' + dataId).removeClass('btn-primary');
                                        $('#btn-status' + dataId).addClass('btn-danger');
                                        $('#btn-status' + dataId).html('Bỏ set');
                                    }if (dataStatus == 1) {
                                    // console.log("mau xanh")

                                    $('#btn-status' + dataId).attr("status-id", 0);
                                        $('#btn-status' + dataId).removeClass('btn-danger');
                                        $('#btn-status' + dataId).addClass('btn-primary');
                                        $('#btn-status' + dataId).html('Set chức vụ');
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
                    <th class="fw-bolder">Bộ môn</th>
                    <th colspan="2" class="fw-bolder">Set bộ môn</th>
                </tr>
                </thead>
                <tbody>
                @if($user_account->role_id == 2)
                    @foreach($users as $user)
                        @if($user->role_bomon == $user_account->role_bomon && $user->role_id != 2 )
                    <tr>
                        <td>{{$user->name}}</td>
                        <td>{{$user->email}}</td>
                        <td>{{$roles->find($user->role_id)->name}}</td>
                        <td>
{{--                                  <form id="frm" action="{{route('giangvien.list')}}"  >--}}
{{--                                      <input type="hidden" name="id_set" value="{{$user->id}}">--}}
{{--                                      <select class="form-select" name="bomon_set" onchange="this.form.submit()" id="">--}}
{{--                                    @foreach($options as $value)--}}
{{--                                        <option value="{{$value->id}}" {{$user->role_bomon == $value->id ? 'selected' : ''}}>{{$value->name}}</option>--}}
{{--                                        @endforeach--}}
{{--                                    </select>--}}
{{--                                </form>--}}
                            {{$value->name}}
                        </td>
                        <td>
                            <form action=""   method="POST">
                                @if($user->status == 0)
                                    <button data-id="{{ $user->id }}" name="status" status-id="1" type="button" id="btn-status{{ $user->id }}" class="btn btn-danger btn-status" title="Bỏ set"  >Bỏ set</button>
                                @else
                                    <button data-id="{{ $user->id }}" name="status" status-id="0" type="button" id="btn-status{{ $user->id }}" class="btn btn-primary btn-status" title="Set chức vụ" >Set chức vụ</button>
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


