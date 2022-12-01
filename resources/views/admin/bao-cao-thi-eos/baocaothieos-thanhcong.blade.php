@extends('layouts.admin.master')
@section('module-name', "EOS")
@section('page-name', 'Thành công')
{{--@endsection--}}
@section('content')
    <div class="col-10 offset-1 panel-thankyou mt-5 mb-5">
        <div class="d-flex justify-content-center pt-5">
            <img src="{{asset('images/success.png')}}" width="300">
        </div>
        <h2 class="text-center success-msg">Nộp file điểm EOS thành công</h2>
        <p class="text-center " style="color: #05058B;">
            Thầy cô muốn tiếp tục gửi báo cáo file điểm EOS khác? <a style="color: #ff9000;"
                                                                     href="{{route('form.baocaothieos')}}">Bấm vào
                đây.</a>
        </p>

    </div>

@endsection
