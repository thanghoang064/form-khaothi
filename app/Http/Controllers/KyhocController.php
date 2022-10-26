<?php

namespace App\Http\Controllers;

use App\Models\KyHoc;

use Illuminate\Http\Request;

class KyhocController extends Controller
{
    public function index(){
        return view('admin.kyhoc.index');
    }

    public function add_ky_hoc(){
        return view('admin.kyhoc.add_ky_hoc');
    }

    public function new_ky_hoc(request $request){
        $request->validate(
            ['name_ky_hoc'=> 'required|min:6',
            ],
            [
                'required' => 'Không để trống tên kỳ học',
                'min' => 'Chiều dài tối thiểu :min kí tự'
            ]
        );


    }
}
