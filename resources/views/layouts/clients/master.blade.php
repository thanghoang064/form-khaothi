<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Phòng Khảo thí - Cao đẳng FPT Polytechnic - Hà Nội</title>
    <link rel="shortcut icon" href="{{asset('metronic')}}/media/logos/favicon.ico" />
    <link rel="stylesheet" href="{{asset('vendor/bootstrap/css/bootstrap.css')}}">
    <link rel="stylesheet" href="{{asset('styles/client-layout.css')}}">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js" integrity="sha512-894YE6QWD5I59HgZOGReFYm4dnWc1Qt5NtvYSaNcOP+u1T9qYdvdihz0PPSiiqn/+/3e7Jo4EaG7TubfWGUrMQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script src="https://kit.fontawesome.com/29b41ee1c8.js" crossorigin="anonymous"></script>
    @yield('style-custom')
</head>
<body>

    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg bg-fpt-orange fixed-top">
        <div class="container-fluid">
            <a class="navbar-brand" href="#">Khảo thí _ FPL Hà Nội</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <ul class="navbar-nav mb-2 mb-lg-0 ms-auto">

{{--                    @guest--}}
{{--                        <li class="nav-item text-uppercase">--}}
{{--                            <a class="nav-link" href="{{route('login.google')}}">Đăng nhập</a>--}}
{{--                        </li>--}}
{{--                    @endguest--}}
                    @auth
                            <li class="nav-item">
                                <a class="nav-link text-uppercase" href="{{route('form.uploadfuge')}}">Upload file fuge</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link active text-uppercase" aria-current="page" href="{{route('form.lichsu')}}">Lịch sử báo cáo thi</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link text-uppercase" href="{{route('form.baocaothi')}}">Gửi báo cáo mới</a>
                            </li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            {{\Illuminate\Support\Facades\Auth::user()->name}}
                        </a>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item" href="{{route('logout')}}">Đăng xuất</a></li>
                        </ul>
                    </li>
                    @endauth
                </ul>
            </div>
        </div>
    </nav>
    @yield('content')
    <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.5/dist/umd/popper.min.js" integrity="sha384-Xe+8cL9oJa6tN/veChSP7q+mnSPaj5Bcu9mPX5F5xIGE0DVittaqT5lorf0EI7Vk" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/js/bootstrap.min.js" integrity="sha384-ODmDIVzN+pFdexxHEHFBQH3/9/vQ9uori45z4JjnFsRydbmQbmL5t1tQ0culUzyK" crossorigin="anonymous"></script>
@yield('page-script')
</body>
</html>
