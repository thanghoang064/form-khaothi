<div id="kt_aside" class="aside aside-dark aside-hoverable" data-kt-drawer="true" data-kt-drawer-name="aside"
     data-kt-drawer-activate="{default: true, lg: false}" data-kt-drawer-overlay="true"
     data-kt-drawer-width="{default:'200px', '300px': '250px'}" data-kt-drawer-direction="start"
     data-kt-drawer-toggle="#kt_aside_mobile_toggle">
    <!--begin::Brand-->
    <div class="aside-logo flex-column-auto" id="kt_aside_logo">
        <!--begin::Logo-->
        <a href="{{route('ky-hoc.index')}}">
            <img alt="Logo" src="{{asset('metronic')}}/media/logos/FPT_Polytechnic.png" class="w-75 logo"/>
        </a>
        <!--end::Logo-->
        <!--begin::Aside toggler-->
        <div id="kt_aside_toggle" class="btn btn-icon w-auto px-0 btn-active-color-primary aside-toggle"
             data-kt-toggle="true" data-kt-toggle-state="active" data-kt-toggle-target="body"
             data-kt-toggle-name="aside-minimize">
            <!--begin::Svg Icon | path: icons/duotune/arrows/arr079.svg-->
            <span class="svg-icon svg-icon-1 rotate-180">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                    <path opacity="0.5"
                          d="M14.2657 11.4343L18.45 7.25C18.8642 6.83579 18.8642 6.16421 18.45 5.75C18.0358 5.33579 17.3642 5.33579 16.95 5.75L11.4071 11.2929C11.0166 11.6834 11.0166 12.3166 11.4071 12.7071L16.95 18.25C17.3642 18.6642 18.0358 18.6642 18.45 18.25C18.8642 17.8358 18.8642 17.1642 18.45 16.75L14.2657 12.5657C13.9533 12.2533 13.9533 11.7467 14.2657 11.4343Z"
                          fill="black"/>
                    <path
                        d="M8.2657 11.4343L12.45 7.25C12.8642 6.83579 12.8642 6.16421 12.45 5.75C12.0358 5.33579 11.3642 5.33579 10.95 5.75L5.40712 11.2929C5.01659 11.6834 5.01659 12.3166 5.40712 12.7071L10.95 18.25C11.3642 18.6642 12.0358 18.6642 12.45 18.25C12.8642 17.8358 12.8642 17.1642 12.45 16.75L8.2657 12.5657C7.95328 12.2533 7.95328 11.7467 8.2657 11.4343Z"
                        fill="black"/>
                </svg>
            </span>
            <!--end::Svg Icon-->
        </div>
        <!--end::Aside toggler-->
    </div>
    <!--end::Brand-->
    <!--begin::Aside menu-->
    <div class="aside-menu flex-column-fluid">
        <!--begin::Aside Menu-->
        <div class="hover-scroll-overlay-y my-5 my-lg-5" id="kt_aside_menu_wrapper" data-kt-scroll="true"
             data-kt-scroll-activate="{default: false, lg: true}" data-kt-scroll-height="auto"
             data-kt-scroll-dependencies="#kt_aside_logo, #kt_aside_footer" data-kt-scroll-wrappers="#kt_aside_menu"
             data-kt-scroll-offset="0">
            <!--begin::Menu-->
            <div
                class="menu menu-column menu-title-gray-800 menu-state-title-primary menu-state-icon-primary menu-state-bullet-primary menu-arrow-gray-500"
                id="#kt_aside_menu" data-kt-menu="true" data-kt-menu-expand="false">
                {{--                <div class="menu-item here show menu-accordion">--}}
                {{--                    <a href="{{route('dashboard')}}" class="menu-link">--}}
                {{--                        <span class="menu-icon">--}}
                {{--                            <!--begin::Svg Icon | path: icons/duotune/general/gen025.svg-->--}}
                {{--                            <span class="svg-icon svg-icon-2">--}}
                {{--                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"--}}
                {{--                                     fill="none">--}}
                {{--                                    <rect x="2" y="2" width="9" height="9" rx="2" fill="black"/>--}}
                {{--                                    <rect opacity="0.3" x="13" y="2" width="9" height="9" rx="2" fill="black"/>--}}
                {{--                                    <rect opacity="0.3" x="13" y="13" width="9" height="9" rx="2" fill="black"/>--}}
                {{--                                    <rect opacity="0.3" x="2" y="13" width="9" height="9" rx="2" fill="black"/>--}}
                {{--                                </svg>--}}
                {{--                            </span>--}}
                {{--                            <!--end::Svg Icon-->--}}
                {{--                        </span>--}}
                {{--                        <span class="menu-title">Dashboards</span>--}}
                {{--                    </a>--}}
                {{--                </div>--}}
                <div class="menu-item here show menu-accordion">
                    <a href="{{route('dotthi.index')}}" class="menu-link">
                        <span class="menu-icon">
                            <!--begin::Svg Icon | path: icons/duotune/general/gen025.svg-->
                            <span class="svg-icon svg-icon-primary svg-icon-2x"><!--begin::Svg Icon | path:/var/www/preview.keenthemes.com/metronic/releases/2021-05-14-112058/theme/html/demo1/dist/../src/media/svg/icons/Home/Towel.svg--><svg
                                    xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink"
                                    width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
    <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
        <rect x="0" y="0" width="24" height="24"/>
        <path
            d="M18,16 L9,16 C8.44771525,16 8,15.5522847 8,15 C8,14.4477153 8.44771525,14 9,14 L17,14 C17.5522847,14 18,13.5522847 18,13 C18,12.4477153 17.5522847,12 17,12 L9,12 C7.34314575,12 6,13.3431458 6,15 C6,16.6568542 7.34314575,18 9,18 L19.5,18 C21,18 21,18.5 21,19 C21,19.5 21,20 19.5,20 L7,20 C4.790861,20 3,18.209139 3,16 L3,8 C3,5.790861 4.790861,4 7,4 L17,4 C19.209139,4 21,5.790861 21,8 L21,13.0000005 C21,14.6568542 19.6568542,16 18,16 Z"
            fill="#000000"/>
    </g>
</svg><!--end::Svg Icon--></span>
                            <!--end::Svg Icon-->
                        </span>
                        <span class="menu-title">Đợt thi</span>
                    </a>
                </div>
                {{--                <div class="menu-item here show menu-accordion">--}}
                {{--                    <a href="{{route('dashboard')}}" class="menu-link">--}}
                {{--                        <span class="menu-icon">--}}
                {{--                            <!--begin::Svg Icon | path: icons/duotune/general/gen025.svg-->--}}
                {{--                            <span class="svg-icon svg-icon-2">--}}
                {{--                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"--}}
                {{--                                     fill="none">--}}
                {{--                                    <path opacity="0.3"--}}
                {{--                                          d="M14 2H6C4.89543 2 4 2.89543 4 4V20C4 21.1046 4.89543 22 6 22H18C19.1046 22 20 21.1046 20 20V8L14 2Z"--}}
                {{--                                          fill="black"/>--}}
                {{--                                    <path d="M20 8L14 2V6C14 7.10457 14.8954 8 16 8H20Z" fill="black"/>--}}
                {{--                                    <path--}}
                {{--                                        d="M10.3629 14.0084L8.92108 12.6429C8.57518 12.3153 8.03352 12.3153 7.68761 12.6429C7.31405 12.9967 7.31405 13.5915 7.68761 13.9453L10.2254 16.3488C10.6111 16.714 11.215 16.714 11.6007 16.3488L16.3124 11.8865C16.6859 11.5327 16.6859 10.9379 16.3124 10.5841C15.9665 10.2565 15.4248 10.2565 15.0789 10.5841L11.4631 14.0084C11.1546 14.3006 10.6715 14.3006 10.3629 14.0084Z"--}}
                {{--                                        fill="black"/>--}}
                {{--                                </svg>--}}
                {{--                            </span>--}}
                {{--                            <!--end::Svg Icon-->--}}
                {{--                        </span>--}}
                {{--                        <span class="menu-title">Báo cáo thi</span>--}}
                {{--                    </a>--}}
                {{--                </div>--}}
                @if(session('role_id') == 2)

                    <div class="menu-item here show menu-accordion">
                        <a href="{{route('giangvien.list')}}" class="menu-link">
                        <span class="menu-icon">
                            <!--begin::Svg Icon | path: icons/duotune/general/gen025.svg-->
                            <span class="svg-icon svg-icon-primary svg-icon-2x"><!--begin::Svg Icon | path:/var/www/preview.keenthemes.com/metronic/releases/2021-05-14-112058/theme/html/demo1/dist/../src/media/svg/icons/Home/Library.svg--><svg
                                    xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink"
                                    width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
    <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
        <rect x="0" y="0" width="24" height="24"/>
        <path
            d="M5,3 L6,3 C6.55228475,3 7,3.44771525 7,4 L7,20 C7,20.5522847 6.55228475,21 6,21 L5,21 C4.44771525,21 4,20.5522847 4,20 L4,4 C4,3.44771525 4.44771525,3 5,3 Z M10,3 L11,3 C11.5522847,3 12,3.44771525 12,4 L12,20 C12,20.5522847 11.5522847,21 11,21 L10,21 C9.44771525,21 9,20.5522847 9,20 L9,4 C9,3.44771525 9.44771525,3 10,3 Z"
            fill="#000000"/>
        <rect fill="#000000" opacity="0.3"
              transform="translate(17.825568, 11.945519) rotate(-19.000000) translate(-17.825568, -11.945519) "
              x="16.3255682" y="2.94551858" width="3" height="18" rx="1"/>
    </g>
</svg><!--end::Svg Icon--></span>
                            <!--end::Svg Icon-->
                        </span>
                            <span class="menu-title">Danh sách giảng viên</span>
                        </a>
                    </div>

                    <div class="menu-item here show menu-accordion">
                        <a href="{{route('ky-hoc.index')}}" class="menu-link">
                        <span class="menu-icon">
                            <!--begin::Svg Icon | path: icons/duotune/general/gen025.svg-->
                            <span class="svg-icon svg-icon-primary svg-icon-2x"><!--begin::Svg Icon | path:/var/www/preview.keenthemes.com/metronic/releases/2021-05-14-112058/theme/html/demo1/dist/../src/media/svg/icons/Communication/Incoming-box.svg--><svg
                                    xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink"
                                    width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
    <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
        <rect x="0" y="0" width="24" height="24"/>
        <path
            d="M22,17 L22,21 C22,22.1045695 21.1045695,23 20,23 L4,23 C2.8954305,23 2,22.1045695 2,21 L2,17 L6.27924078,17 L6.82339262,18.6324555 C7.09562072,19.4491398 7.8598984,20 8.72075922,20 L15.381966,20 C16.1395101,20 16.8320364,19.5719952 17.1708204,18.8944272 L18.118034,17 L22,17 Z"
            fill="#000000"/>
        <path
            d="M2.5625,15 L5.92654389,9.01947752 C6.2807805,8.38972356 6.94714834,8 7.66969497,8 L16.330305,8 C17.0528517,8 17.7192195,8.38972356 18.0734561,9.01947752 L21.4375,15 L18.118034,15 C17.3604899,15 16.6679636,15.4280048 16.3291796,16.1055728 L15.381966,18 L8.72075922,18 L8.17660738,16.3675445 C7.90437928,15.5508602 7.1401016,15 6.27924078,15 L2.5625,15 Z"
            fill="#000000" opacity="0.3"/>
        <path
            d="M11.1288761,0.733697713 L11.1288761,2.69017121 L9.12120481,2.69017121 C8.84506244,2.69017121 8.62120481,2.91402884 8.62120481,3.19017121 L8.62120481,4.21346991 C8.62120481,4.48961229 8.84506244,4.71346991 9.12120481,4.71346991 L11.1288761,4.71346991 L11.1288761,6.66994341 C11.1288761,6.94608579 11.3527337,7.16994341 11.6288761,7.16994341 C11.7471877,7.16994341 11.8616664,7.12798964 11.951961,7.05154023 L15.4576222,4.08341738 C15.6683723,3.90498251 15.6945689,3.58948575 15.5161341,3.37873564 C15.4982803,3.35764848 15.4787093,3.33807751 15.4576222,3.32022374 L11.951961,0.352100892 C11.7412109,0.173666017 11.4257142,0.199862688 11.2472793,0.410612793 C11.1708299,0.500907473 11.1288761,0.615386087 11.1288761,0.733697713 Z"
            fill="#000000" fill-rule="nonzero"
            transform="translate(11.959697, 3.661508) rotate(-270.000000) translate(-11.959697, -3.661508) "/>
    </g>
</svg><!--end::Svg Icon--></span>
                            <!--end::Svg Icon-->
                        </span>
                            <span class="menu-title">Quản lí kỳ học</span>
                        </a>
                    </div>
                    {{--                    <div class="menu-item here show menu-accordion">--}}
                    {{--                        <a href="{{route('nhap-diem')}}" class="menu-link">--}}
                    {{--                        <span class="menu-icon">--}}
                    {{--                            <!--begin::Svg Icon | path: icons/duotune/general/gen025.svg-->--}}
                    {{--                            <span class="svg-icon svg-icon-2">--}}
                    {{--                                <span class="svg-icon svg-icon-2">--}}
                    {{--                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"--}}
                    {{--                                         fill="none">--}}
                    {{--                                        <path--}}
                    {{--                                            d="M11.2929 2.70711C11.6834 2.31658 12.3166 2.31658 12.7071 2.70711L15.2929 5.29289C15.6834 5.68342 15.6834 6.31658 15.2929 6.70711L12.7071 9.29289C12.3166 9.68342 11.6834 9.68342 11.2929 9.29289L8.70711 6.70711C8.31658 6.31658 8.31658 5.68342 8.70711 5.29289L11.2929 2.70711Z"--}}
                    {{--                                            fill="black"/>--}}
                    {{--                                        <path--}}
                    {{--                                            d="M11.2929 14.7071C11.6834 14.3166 12.3166 14.3166 12.7071 14.7071L15.2929 17.2929C15.6834 17.6834 15.6834 18.3166 15.2929 18.7071L12.7071 21.2929C12.3166 21.6834 11.6834 21.6834 11.2929 21.2929L8.70711 18.7071C8.31658 18.3166 8.31658 17.6834 8.70711 17.2929L11.2929 14.7071Z"--}}
                    {{--                                            fill="black"/>--}}
                    {{--                                        <path opacity="0.3"--}}
                    {{--                                              d="M5.29289 8.70711C5.68342 8.31658 6.31658 8.31658 6.70711 8.70711L9.29289 11.2929C9.68342 11.6834 9.68342 12.3166 9.29289 12.7071L6.70711 15.2929C6.31658 15.6834 5.68342 15.6834 5.29289 15.2929L2.70711 12.7071C2.31658 12.3166 2.31658 11.6834 2.70711 11.2929L5.29289 8.70711Z"--}}
                    {{--                                              fill="black"/>--}}
                    {{--                                        <path opacity="0.3"--}}
                    {{--                                              d="M17.2929 8.70711C17.6834 8.31658 18.3166 8.31658 18.7071 8.70711L21.2929 11.2929C21.6834 11.6834 21.6834 12.3166 21.2929 12.7071L18.7071 15.2929C18.3166 15.6834 17.6834 15.6834 17.2929 15.2929L14.7071 12.7071C14.3166 12.3166 14.3166 11.6834 14.7071 11.2929L17.2929 8.70711Z"--}}
                    {{--                                              fill="black"/>--}}
                    {{--                                    </svg>--}}
                    {{--                                </span>--}}
                    {{--                            </span>--}}
                    {{--                            <!--end::Svg Icon-->--}}
                    {{--                        </span>--}}
                    {{--                            <span class="menu-title">Thống kê nhập điểm</span>--}}
                    {{--                        </a>--}}
                    {{--                    </div>--}}
                @endif

                @if(session('role_id') == 3 || session('role_id') == 2 )
                    @if(session('role_id') == 3 )
                        <div class="menu-item here show menu-accordion">
                            <a href="{{route('giangvien.list')}}" class="menu-link">
                        <span class="menu-icon">
                            <!--begin::Svg Icon | path: icons/duotune/general/gen025.svg-->
                            <span class="svg-icon svg-icon-2">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                     fill="none">
                                    <path opacity="0.3"
                                          d="M14 2H6C4.89543 2 4 2.89543 4 4V20C4 21.1046 4.89543 22 6 22H18C19.1046 22 20 21.1046 20 20V8L14 2Z"
                                          fill="black"/>
                                    <path d="M20 8L14 2V6C14 7.10457 14.8954 8 16 8H20Z" fill="black"/>
                                    <path
                                        d="M10.3629 14.0084L8.92108 12.6429C8.57518 12.3153 8.03352 12.3153 7.68761 12.6429C7.31405 12.9967 7.31405 13.5915 7.68761 13.9453L10.2254 16.3488C10.6111 16.714 11.215 16.714 11.6007 16.3488L16.3124 11.8865C16.6859 11.5327 16.6859 10.9379 16.3124 10.5841C15.9665 10.2565 15.4248 10.2565 15.0789 10.5841L11.4631 14.0084C11.1546 14.3006 10.6715 14.3006 10.3629 14.0084Z"
                                        fill="black"/>
                                </svg>
                            </span>
                            <!--end::Svg Icon-->
                        </span>
                                <span class="menu-title">Danh sách giảng viên</span>
                            </a>
                        </div>
                    @endif
                    {{--                    <div class="menu-item here show menu-accordion">--}}
                    {{--                        <a href="{{route('account.add')}}" class="menu-link">--}}
                    {{--                            <span class="menu-icon">--}}
                    {{--                                <!--begin::Svg Icon | path: icons/duotune/general/gen025.svg-->--}}
                    {{--                                <span class="svg-icon svg-icon-2">--}}
                    {{--                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"--}}
                    {{--                                         fill="none">--}}
                    {{--                                        <path opacity="0.3"--}}
                    {{--                                              d="M14 2H6C4.89543 2 4 2.89543 4 4V20C4 21.1046 4.89543 22 6 22H18C19.1046 22 20 21.1046 20 20V8L14 2Z"--}}
                    {{--                                              fill="black"/>--}}
                    {{--                                        <path d="M20 8L14 2V6C14 7.10457 14.8954 8 16 8H20Z" fill="black"/>--}}
                    {{--                                        <path--}}
                    {{--                                            d="M10.3629 14.0084L8.92108 12.6429C8.57518 12.3153 8.03352 12.3153 7.68761 12.6429C7.31405 12.9967 7.31405 13.5915 7.68761 13.9453L10.2254 16.3488C10.6111 16.714 11.215 16.714 11.6007 16.3488L16.3124 11.8865C16.6859 11.5327 16.6859 10.9379 16.3124 10.5841C15.9665 10.2565 15.4248 10.2565 15.0789 10.5841L11.4631 14.0084C11.1546 14.3006 10.6715 14.3006 10.3629 14.0084Z"--}}
                    {{--                                            fill="black"/>--}}
                    {{--                                    </svg>--}}
                    {{--                                </span>--}}
                    {{--                                <!--end::Svg Icon-->--}}
                    {{--                            </span>--}}
                    {{--                            <span class="menu-title">Tạo tài khoản</span>--}}
                    {{--                        </a>--}}
                    {{--                    </div>--}}
                    <div class="menu-item here show menu-accordion">
                        <a href="{{route('eos.lich-su-bao-cao')}}" class="menu-link">
                        <span class="menu-icon">
                            <!--begin::Svg Icon | path: icons/duotune/general/gen025.svg-->
                            <span class="svg-icon svg-icon-2">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                     fill="none">
                                    <path opacity="0.3"
                                          d="M14 2H6C4.89543 2 4 2.89543 4 4V20C4 21.1046 4.89543 22 6 22H18C19.1046 22 20 21.1046 20 20V8L14 2Z"
                                          fill="black"/>
                                    <path d="M20 8L14 2V6C14 7.10457 14.8954 8 16 8H20Z" fill="black"/>
                                    <path
                                        d="M10.3629 14.0084L8.92108 12.6429C8.57518 12.3153 8.03352 12.3153 7.68761 12.6429C7.31405 12.9967 7.31405 13.5915 7.68761 13.9453L10.2254 16.3488C10.6111 16.714 11.215 16.714 11.6007 16.3488L16.3124 11.8865C16.6859 11.5327 16.6859 10.9379 16.3124 10.5841C15.9665 10.2565 15.4248 10.2565 15.0789 10.5841L11.4631 14.0084C11.1546 14.3006 10.6715 14.3006 10.3629 14.0084Z"
                                        fill="black"/>
                                </svg>
                            </span>
                            <!--end::Svg Icon-->
                        </span>
                            <span class="menu-title">Lịch sử nộp file EOS</span>
                        </a>
                    </div>
                    <div class="menu-item here show menu-accordion">
                        <a href="{{route('thongke.bao-cao-thi')}}" class="menu-link">
                                <span class="menu-icon">
                                    <!--begin::Svg Icon | path: icons/duotune/general/gen025.svg-->
                                   <span class="svg-icon svg-icon-primary svg-icon-2x"><!--begin::Svg Icon | path:/var/www/preview.keenthemes.com/metronic/releases/2021-05-14-112058/theme/html/demo1/dist/../src/media/svg/icons/Shopping/Chart-line1.svg-->
                                       <svg
                                           xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink"
                                           width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
                                            <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                                <rect x="0" y="0" width="24" height="24"/>
                                                <path
                                                    d="M5,19 L20,19 C20.5522847,19 21,19.4477153 21,20 C21,20.5522847 20.5522847,21 20,21 L4,21 C3.44771525,21 3,20.5522847 3,20 L3,4 C3,3.44771525 3.44771525,3 4,3 C4.55228475,3 5,3.44771525 5,4 L5,19 Z"
                                                    fill="#000000" fill-rule="nonzero"/>
                                                <path
                                                    d="M8.7295372,14.6839411 C8.35180695,15.0868534 7.71897114,15.1072675 7.31605887,14.7295372 C6.9131466,14.3518069 6.89273254,13.7189711 7.2704628,13.3160589 L11.0204628,9.31605887 C11.3857725,8.92639521 11.9928179,8.89260288 12.3991193,9.23931335 L15.358855,11.7649545 L19.2151172,6.88035571 C19.5573373,6.44687693 20.1861655,6.37289714 20.6196443,6.71511723 C21.0531231,7.05733733 21.1271029,7.68616551 20.7848828,8.11964429 L16.2848828,13.8196443 C15.9333973,14.2648593 15.2823707,14.3288915 14.8508807,13.9606866 L11.8268294,11.3801628 L8.7295372,14.6839411 Z"
                                                    fill="#000000" fill-rule="nonzero" opacity="0.3"/>
                                            </g>
                                        </svg><!--end::Svg Icon--></span>
                                    <!--end::Svg Icon-->
                                </span>
                            <span class="menu-title">Thống kê báo cáo thi</span>
                        </a>
                    </div>
                    {{--                    <div class="menu-item here show menu-accordion">--}}
                    {{--                        <a href="{{route('thongke.pho-diem')}}" class="menu-link">--}}
                    {{--                                <span class="menu-icon">--}}
                    {{--                                    <!--begin::Svg Icon | path: icons/duotune/general/gen025.svg-->--}}
                    {{--                                    <span class="svg-icon svg-icon-primary svg-icon-2x"><!--begin::Svg Icon | path:/var/www/preview.keenthemes.com/metronic/releases/2021-05-14-112058/theme/html/demo1/dist/../src/media/svg/icons/Home/Book-open.svg--><svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">--}}
                    {{--    <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">--}}
                    {{--        <rect x="0" y="0" width="24" height="24"/>--}}
                    {{--        <path d="M13.6855025,18.7082217 C15.9113859,17.8189707 18.682885,17.2495635 22,17 C22,16.9325178 22,13.1012863 22,5.50630526 L21.9999762,5.50630526 C21.9999762,5.23017604 21.7761292,5.00632908 21.5,5.00632908 C21.4957817,5.00632908 21.4915635,5.00638247 21.4873465,5.00648922 C18.658231,5.07811173 15.8291155,5.74261533 13,7 C13,7.04449645 13,10.79246 13,18.2438906 L12.9999854,18.2438906 C12.9999854,18.520041 13.2238496,18.7439052 13.5,18.7439052 C13.5635398,18.7439052 13.6264972,18.7317946 13.6855025,18.7082217 Z" fill="#000000"/>--}}
                    {{--        <path d="M10.3144829,18.7082217 C8.08859955,17.8189707 5.31710038,17.2495635 1.99998542,17 C1.99998542,16.9325178 1.99998542,13.1012863 1.99998542,5.50630526 L2.00000925,5.50630526 C2.00000925,5.23017604 2.22385621,5.00632908 2.49998542,5.00632908 C2.50420375,5.00632908 2.5084219,5.00638247 2.51263888,5.00648922 C5.34175439,5.07811173 8.17086991,5.74261533 10.9999854,7 C10.9999854,7.04449645 10.9999854,10.79246 10.9999854,18.2438906 L11,18.2438906 C11,18.520041 10.7761358,18.7439052 10.4999854,18.7439052 C10.4364457,18.7439052 10.3734882,18.7317946 10.3144829,18.7082217 Z" fill="#000000" opacity="0.3"/>--}}
                    {{--    </g>--}}
                    {{--</svg><!--end::Svg Icon--></span>--}}
                    {{--                                    <!--end::Svg Icon-->--}}
                    {{--                                </span>--}}
                    {{--                            <span class="menu-title">Phổ điểm đợt thi</span>--}}
                    {{--                        </a>--}}
                    {{--                    </div>--}}
                    <div class="menu-item here show menu-accordion">
                        <a href="{{route('thongke.pho-diem-eos')}}" class="menu-link">
                                <span class="menu-icon">
                                    <!--begin::Svg Icon | path: icons/duotune/general/gen025.svg-->
                                    <span class="svg-icon svg-icon-primary svg-icon-2x"><!--begin::Svg Icon | path:/var/www/preview.keenthemes.com/metronic/releases/2021-05-14-112058/theme/html/demo1/dist/../src/media/svg/icons/Home/Book-open.svg--><svg
                                            xmlns="http://www.w3.org/2000/svg"
                                            xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px"
                                            viewBox="0 0 24 24" version="1.1">
    <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
        <rect x="0" y="0" width="24" height="24"/>
        <path
            d="M13.6855025,18.7082217 C15.9113859,17.8189707 18.682885,17.2495635 22,17 C22,16.9325178 22,13.1012863 22,5.50630526 L21.9999762,5.50630526 C21.9999762,5.23017604 21.7761292,5.00632908 21.5,5.00632908 C21.4957817,5.00632908 21.4915635,5.00638247 21.4873465,5.00648922 C18.658231,5.07811173 15.8291155,5.74261533 13,7 C13,7.04449645 13,10.79246 13,18.2438906 L12.9999854,18.2438906 C12.9999854,18.520041 13.2238496,18.7439052 13.5,18.7439052 C13.5635398,18.7439052 13.6264972,18.7317946 13.6855025,18.7082217 Z"
            fill="#000000"/>
        <path
            d="M10.3144829,18.7082217 C8.08859955,17.8189707 5.31710038,17.2495635 1.99998542,17 C1.99998542,16.9325178 1.99998542,13.1012863 1.99998542,5.50630526 L2.00000925,5.50630526 C2.00000925,5.23017604 2.22385621,5.00632908 2.49998542,5.00632908 C2.50420375,5.00632908 2.5084219,5.00638247 2.51263888,5.00648922 C5.34175439,5.07811173 8.17086991,5.74261533 10.9999854,7 C10.9999854,7.04449645 10.9999854,10.79246 10.9999854,18.2438906 L11,18.2438906 C11,18.520041 10.7761358,18.7439052 10.4999854,18.7439052 C10.4364457,18.7439052 10.3734882,18.7317946 10.3144829,18.7082217 Z"
            fill="#000000" opacity="0.3"/>
    </g>
</svg><!--end::Svg Icon--></span>
                                    <!--end::Svg Icon-->
                                </span>
                            <span class="menu-title">Phổ điểm EOS</span>
                        </a>
                    </div>
                @endif
            </div>
            <!--end::Menu-->
        </div>
        <!--end::Aside Menu-->
    </div>
    <!--end::Aside menu-->

</div>
