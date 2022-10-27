@extends('layouts.clients.master')
@section('style-custom')
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <link rel="stylesheet" href="{{asset('styles/form-bao-cao-thi.css')}}">
@endsection
@section('content')
    <div class="container px-5 my-5 ">
        <div class="row">
            <div class="col-md-10 offset-md-1 card-form pb-5 pt-5">
                <h3>Upload file fuge</h3>
                <p>This's form to upload fuge file</p>
                <form id="fugeForm" method="post" enctype="multipart/form-data">
                    @csrf
                    <div class="row">
                        <div class="mb-3 col">
                            <label class="form-label" for="kyHoc">Học kỳ<span class="text-danger">*</span></label>
                            <select class="form-select" name="ky_hoc" id="kyHoc"
                                    aria-label="Bộ môn">
                                @foreach($kyhoc as $kh)
                                    <option value="{{ $kh['id'] }}">{{ $kh['name'] }}</option>
                                @endforeach

                            </select>
                        </div>
                    </div>

                    <div class="row">
                        <div class="mb-3 col">
                            <label class="form-label" for="fileFuge">File fuge<span class="text-danger">*</span></label>
                            <input class="form-control" id="fileFuge" name="file_fuge" type="file"
                                   placeholder="File fuge" data-sb-validations="required"/>
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
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <script src="https://npmcdn.com/flatpickr/dist/l10n/vn.js"></script>
    <script>
        {{--const monhoc = @json($monhoc);--}}

        $(document).ready(function () {
            $('#fugeForm').validate({
                rules: {
                    file_fuge: {
                        required: true,
                        extension: "fg"
                    },
                },
                messages: {
                    file_fuge: {
                        required: "Hãy chọn file fuge",
                        extension: "Chọn đúng định dạng file fg"
                    },
                }
            })
            // list_mon_hoc($('select[name="bo_mon"]').val());
        })

        function list_mon_hoc(bomonId) {
            const lstMonHoc = monhoc.filter(item => item.bo_mon_id == bomonId).map(e => `<option value="${e.id}">${e.name}</option>`);
            $('select[name="mon_hoc_id"]').html(lstMonHoc);
        }
    </script>
@endsection
