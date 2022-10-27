@extends('layouts.clients.master')
@section('style-custom')
    <link rel="stylesheet" href="{{asset('styles/baocaothi-thanhcong.css')}}">
@endsection
@section('content')
    <div class="col-10 offset-1 panel-thankyou mt-5 mb-5">
        <div class="d-flex justify-content-center pt-5">
            <img src="{{asset('images/success.png')}}" width="300">
        </div>
        <h2 class="text-center success-msg">Upload file fuge thành công</h2>
        <p class="text-center " style="color: #05058B;">
            Thầy cô muốn tiếp tục gửi file fuge khác? <a style="color: #ff9000;" href="{{route('form.uploadfuge')}}">Bấm vào đây.</a>
        </p>

    </div>

@endsection
