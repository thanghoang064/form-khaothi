<?php

namespace App\Http\Controllers;

use App\Models\KyHoc;
use Illuminate\Http\Request;

use App\Rules\CheckExam;

class HocKyController extends Controller
{
    public function __construct() {
        date_default_timezone_set("Asia/Ho_Chi_Minh");
    }

    public function index_ky_hoc(Request $request){
        $model = new KyHoc();
        $datas = KyHoc::whereNot('status',0)->paginate(5);
        if(isset($request->name_search)){
            $data_name = $request->name_search;
            $datas = KyHoc::where('name','like','%'.$data_name.'%')->whereNot('status',0)->paginate(5);
        }

        $paginate = $request->all();
        return view('admin.kyhoc.index',compact('datas','paginate'));
    }

    public function add_ky_hoc(){
        return view('admin.kyhoc.add_ky_hoc');
    }

    public function new_ky_hoc(request $request){
//        unique:ky_hoc,name,{$request->name}
        $request->validate(
            ['name_ky_hoc'=> ['required',new CheckExam(),'min:6'],
            ],
            [
                'unique' => 'Đã tồn tại kì học',
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
        KyHoc::where('id',$request->id)
            ->update(
                ["status" => 0]
            );
        return redirect(route('ky-hoc.index'));
    }
}
