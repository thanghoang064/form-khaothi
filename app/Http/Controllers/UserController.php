<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\BoMon;
use App\Models\Roles;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    public function index(Request $request)
    {
//        $status = null;
//        if(isset($request->bomon_set)){
//            $a = User::where('id',$request->id_set)
//                ->update(
//                    [
//                        'role_bomon' => $request->bomon_set
//                    ]
//                );
//
//            echo "<script >alert('Cập nhật chức vụ thành công')</script>'";
//        }
        $bomon =  new BoMon;
        $roles = new Roles();
        $users = User::paginate(5);
        $role_bomon_id = null;
        if(isset($request->name_search) ){
            $data_name = $request->name_search;
            $users = User::where('name','like','%'.$data_name.'%')->orWhere('email','like', '%'.$data_name.'%')->paginate(5);
        }else if(isset($request->bomon)){
            if(!$request->bomon == 0){
                $role_bomon_id = $request->bomon;
                $users = User::where('role_bomon', $role_bomon_id)->paginate(5);
            }else {
                $users = User::paginate(5);
            }
        }
        $user_account = Auth::user();

        $datas = $request->all();
        $options = $bomon->all();
        return view('admin.giangvien.index', compact('users','datas','bomon','options','role_bomon_id','roles','user_account'));

    }

    public function status(Request $request){
        if(!empty($request->all())){
            $data = User::where("id",$request->item_id)
                ->update(
                    [
                        'status' => $request->dataStatus
                    ]
                );
        }
        if ($data)
        {
            $dataRes['statusButton'] = $request->item_id;
            $dataRes['status'] = 1;
            return response()->json($dataRes, 200);
        }
        else
        {
            $dataRes['status'] = 0;
            return response()->json($dataRes, 200);
        }
    }


    public function quanlifuge()
    {
        $user = User::get();
//        dd($user);
        return view('admin.quanli.list', compact('user'));
    }

}
