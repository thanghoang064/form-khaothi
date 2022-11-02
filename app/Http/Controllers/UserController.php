<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\BoMon;
use App\Models\Roles;

class UserController extends Controller
{
    public function index(Request $request)
    {

        $bomon = new BoMon();
        $roles = new Roles();
        $users = User::paginate(5);
        $role_bomon_id = null;
        if(isset($request->name_search) ){
            $data_name = $request->name_search;
            $users = User::where('name','like','%'.$data_name.'%')->orWhere('email','like', '%'.$data_name.'%')->paginate(5);
        }else if(isset($request->bomon)){
                $role_bomon_id = $request->bomon;
                $users = User::where('role_bomon', $role_bomon_id)->paginate(2);
        }


        $datas = $request->all();
        $options = $bomon->all();
        return view('admin.giangvien.index', compact('users','datas','bomon','options','role_bomon_id','roles'));
    }

    public function quanlifuge()
    {
        $user = User::get();
//        dd($user);
        return view('admin.quanli.list', compact('user'));
    }

}
