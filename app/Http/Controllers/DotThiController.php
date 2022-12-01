<?php

namespace App\Http\Controllers;

use App\Events\GetDataDotThiProcessed;
use App\Models\DongBoDotThi;
use App\Models\DotThi;
use Illuminate\Http\Request;

class DotThiController extends Controller
{
    public function index(){
        $dotthi = DotThi::all();
        $dotthi->load('dong_bo_dot_thi', 'luot_bao_cao');

        return view('admin.dotthi.index', compact('dotthi'));

    }

    public function addForm(){
        return view('admin.dotthi.add-form');
    }

    public function saveAdd(Request $request){

        $request->validate(
            [
                'name' => "required|unique:dot_thi",
                'sheet_id' => [
                    'required',
                    'unique:dot_thi,sheet_id',
                    function($attribute, $value, $fail){
                        try {
                            $client = getGooogleClient();
                            $service = new \Google_Service_Sheets($client);
                            $range = 'KH thi Block 1!A2:D';
                            $spreadsheetId = $value;
                            $service->spreadsheets_values->get($spreadsheetId, $range);
                        }catch(\Google_Exception $ex){
                            $fail('Google Sheet Id không tồn tại, vui lòng kiểm tra lại');
                        }
                    }
                ]
            ],
            [
                'name.required' => "Hãy nhập tên đợt thi",
                'name.unique' => "Trùng tên đợt thi",
                'sheet_id.required' => "Hãy nhập google sheet id",
                'sheet_id.unique' => "Trùng sheet id của đợt thi khác",
            ]
        );
        $model = new DotThi();
        $model->name = $request->name;
        $model->sheet_id = $request->sheet_id;
        $model->save();
        GetDataDotThiProcessed::dispatch($model);
//        $dotthi = DotThi::all();
//        return view('admin.dotthi.index')->compact('dotthi',)with('msg', 'Tạo đợt thi thành công, hệ thống đang đồng bộ dữ liệu từ google sheet');
            return redirect(route('dotthi.index'))->with('msg', 'Tạo đợt thi thành công, hệ thống đang đồng bộ dữ liệu từ google sheet');
    }

    public function editForm(Request $request){
        $id = $request->id;
        $data_dot_thi = DotThi::find($id);

        return view('admin.dotthi.edit-form',compact('data_dot_thi'));
    }

    public function updateForm(Request $request){
        $id = $request->id;

        $request->validate([
            'name_exam' => 'required',
            'sheet_id_exam' => 'required',
        ], [
            'required' => 'Vui lòng không để trống',
//            'integer' => 'Vui lòng nhập số'
        ]);
         DotThi::where('id',$id)->update(
            [
                'name' => $request->name_exam,
                'sheet_id' => $request->sheet_id_exam
            ]
        );

        return redirect(route('dotthi.index'))->with('msg', 'Sửa thành công, hệ thống đang đồng bộ dữ liệu từ google sheet');


//        $model = new DotThi();
//        $model->name = $request->name;
//        $model->sheet_id = $request->sheet_id;
//        $model->save();
//        GetDataDotThiProcessed::dispatch($model);
//        return view('admin.dotthi.index')->with('msg', 'Tạo đợt thi thành công, hệ thống đang đồng bộ dữ liệu từ google sheet');
//        return redirect(route('dotthi.index'))->with('msg', 'Tạo đợt thi thành công, hệ thống đang đồng bộ dữ liệu từ google sheet');
    }

    public function deleteForm(request $request){
        if(isset($request->id)){
            DotThi::where('id',$request->id)->delete();
            return redirect(route('dotthi.index'));
        }
    }



}
