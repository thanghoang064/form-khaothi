@extends('layouts.admin.master')
@section('content')
    <table class="table align-middle mb-0 bg-white">
        <thead class="bg-light">
        <tr>
            <th>Tên giảng viên</th>
            <th>Chức vụ</th>
            <th>Link</th>
            <th>Thời gian</th>
            <th>Actions</th>
        </tr>
        </thead>
        <tbody>
        @foreach($user as $item)
        <tr>
            <td>
                <div class="d-flex align-items-center">
                    <img
                        src="https://mdbootstrap.com/img/new/avatars/8.jpg"
                        alt=""
                        style="width: 45px; height: 45px"
                        class="rounded-circle"
                    />
                    <div class="ms-3">
                        <p class="fw-bold mb-1">{{ $item->name }}</p>
                        <p class="text-muted mb-0">{{ $item->email }}</p>
                    </div>
                </div>
            </td>
            <td>
                <p class="fw-normal mb-1">Software engineer</p>
                <p class="text-muted mb-0">IT department</p>
            </td>
            <td>
                <span class="badge badge-success rounded-pill d-inline">Active</span>
            </td>
            <td>{{ $item->created_at == ""  ? "Không có thời gian" : $item->created_at }}</td>
            <td>
                <button  type="button" onclick="location.href='{{ route('ky-hoc.index') }}'" class="btn btn-link btn-sm btn-rounded">
                    View
                </button>
            </td>
        </tr>
        @endforeach

        </tbody>
    </table>
@endsection
