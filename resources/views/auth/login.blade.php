@extends('layouts.clients.master')
@section('style-custom')
    <link rel="stylesheet" href="{{asset('styles/login.css')}}">
@endsection
@section('content')
    <div class="container">
        <div class="d-flex justify-content-center align-items-center h-100">
            <div >

                <div >
                    <div class="card-header text-center mb-3">
                        <img src="{{asset('images')}}/logofpt.png" class="w-75" alt="">
                    </div>
                    <div class="d-grid gap-2 col-12 mx-auto">
                        <a class="btn" href="{{route('login.google')}}" style="background: #fd1361; color: #fff; text-transform: uppercase">
                            <i class="fab fa-google-plus-square me-2" aria-hidden="true"></i>
                             đăng nhập bằng google
                        </a>
{{--                        <a class="btn" href="{{route('login.fake')}}" style="background: #FFC210; color: #fff; text-transform: uppercase">--}}
{{--                            <i class="fab fa-google-plus-square" aria-hidden="true"></i>--}}
{{--                            Đăng nhập fake--}}
{{--                        </a>--}}

                        @if (session('msg'))
                            <div class="alert alert-danger" role="alert" data-mdb-color="danger">
                                <i class="fas fa-times-circle me-3"></i> {{ session('msg') }}
                            </div>

                        @endif
                    </div>
                </div>

            </div>
        </div>
    </div>
@endsection
