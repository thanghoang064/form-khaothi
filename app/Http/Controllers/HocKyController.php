<?php

namespace App\Http\Controllers;

use App\Models\KyHoc;
use Illuminate\Http\Request;

class HocKyController extends Controller
{
    public function __construct() {
        date_default_timezone_set("Asia/Ho_Chi_Minh");
    }

    public function index_ky_hoc(){
        $model = new KyHoc();
        $datas = KyHoc::all();
        return view('admin.kyhoc.index',compact('datas'));
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
        $date = date('Y-m-d h:i:s');
        $model = new KyHoc();
        KyHoc::insert(
            [
                'name' => $request->name_ky_hoc,
                'created_at' => $date
            ]
        );

        return redirect(route('ky-hoc.index'));
    }

    public function edit(request $request){
        $model = new KyHoc();
        $data_view = $model::find($request->id);
        return view('admin.kyhoc.edit',compact('data_view'));
    }

    public function update_ky_hoc(request $request){
        $date = date('Y-m-d h:i:s');
        $rules = [
            'name_ky_hoc' => 'required|min:6',
        ];
        $messages = [
            'required'=> 'Không để trống',
            'min' => "Phải trên :min kí tự "
        ];
        $request->validate($rules,$messages);
        new KyHoc();
        KyHoc::where('id',$request->id)
            ->update(
                [
                    'name' => $request->name_ky_hoc,
                    'updated_at' => $date
                ]
            );
        return redirect(route('ky-hoc.index'));
    }

    public function delete(request $request){
        new KyHoc();
        KyHoc::find($request->id)
            ->delete();
        return redirect(route('ky-hoc.index'));
    }
}
