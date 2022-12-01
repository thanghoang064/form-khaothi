@extends('layouts.admin.master')
@section('module-name', "EOS")
@section('page-name', 'Upload')
@section('page-style')
    <script src="https://unpkg.com/sweetalert2@7.18.0/dist/sweetalert2.all.js"></script>
@endsection
@section('content')
    {{--    @include('sweetalert::alert')--}}
    @if(Session::has('error'))
        <script>
            swal('Error!', '{{ Session::get('error') }}', 'error');
        </script>
        <?php Session::forget('error'); ?>
    @endif
    <div class="container px-5 my-5 ">
        <div class="row">
            <div class="col-md-10 offset-md-1 card-form pb-5 pt-5">
                <div class="d-flex justify-content-between align-items-center">
                    <div class="">
                        <h3>Báo cáo điểm thi EOS</h3>
                    </div>
                    <a href="{{ route('tai-file-bao-cao-mau') }}" class="btn btn-primary">Tải file mẫu</a>
                </div>
                <form id="baocaothiEosForm" method="post" enctype="multipart/form-data">
                    @csrf
                    <div class="row">
                        <div class="mb-3 col">
                            <label class="form-label" for="hocKy">Học kỳ<span class="text-danger">*</span></label>
                            <select class="form-select" name="hoc_ky_id" id="hocKy"
                                    aria-label="Bộ môn">
                                @foreach($hocKy as $hk)
                                    <option value="{{ $hk['id'] }}"
                                            @if($hk['id'] ===  $hocKyHienTai['id'])
                                                selected
                                        @endif
                                    >{{ $hk['name'] }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="row">
                        <div class="mb-3 col">
                            <label class="form-label" for="fileDiểmEOS">File điểm EOS<span
                                    class="text-danger">*</span></label>
                            <input class="form-control" id="fileDiểmEOS" name="file_excel" type="file"
                                   placeholder="File điểm 10b" data-sb-validations="required"/>
                        </div>
                    </div>

                    <div class="d-grid">
                        <button class="btn btn-primary btn-lg" id="submitButton" type="submit">Submit</button>
                    </div>
                </form>
            </div>
        </div>

    </div>

@endsection

@section('page-script')
    <script src="{{asset('vendor/jquery-validate/jquery.validate.min.js')}}"></script>
    <script src="{{asset('vendor/jquery-validate/additional-methods.min.js')}}"></script>
    <script>

        $(document).ready(function () {
            $('#baocaothiEosForm').validate({
                rules: {
                    file_excel: {
                        required: true,
                        extension: "xls|xlsx"
                    },
                },
                messages: {
                    file_excel: {
                        required: "Hãy chọn file điểm EOS",
                        extension: "Chọn đúng định dạng file excel (xls|xlsx)"
                    },
                }
            })
        })
    </script>
@endsection

{{--@endsection--}}
