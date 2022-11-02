<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Roles;
use App\Models\BoMon;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use App\Rules\CheckTailEmail;
use Illuminate\Support\Facades\Mail;
use App\Mail\MailAccount;
class AccountController extends Controller
{
    protected $user;
    protected $roles;
    function __construct(){
        $this->user = new User();
        $this->roles = new Roles();
        date_default_timezone_set("Asia/Ho_Chi_Minh");
    }
    public function index(){
        $role_id = null;
        $role_bomon = null;
        $name_bomon = null;
        $bomon = null;
        if(Auth::user()->role_id == 2){
            $role_id = 3;
        }else if(Auth::user()->role_id == 3){
            $role_id = 1;
            $role_bomon = Auth::user()->role_bomon;
            $name_bomon = BoMon::find($role_bomon)->name;
        }
        $a =[];
        foreach(User::all() as $value){
            $a[] = $value->role_bomon;
        }
        $Subject_Leftovers = BoMon::whereNotIn('id',$a)->get();

        $bomon = BoMon::all();


        $permission = $this->roles->find($role_id);
        return view('admin.account.add',compact('permission','Subject_Leftovers','role_bomon','name_bomon'));
    }

    public function add(Request $request){
//        dd($request->all());
        $condition = null;
       if(Auth::user()->role_id == 2){
            $condition = 'required|unique:users,role_bomon';
       }else {
           $condition = 'required|';
       }
        $request->validate(
            [
                'name_account' => 'required|min:12',
                'email_account' =>  ['required',new CheckTailEmail(),'unique:users,email'],
                'permission' => 'required|not_in:0',
                'bo_mon' => $condition
            ],
            [
                'name_account.required' =>'Không để trống name',
                'email_account.required' => "Không để trống email",
                'password_account.required' => 'Không để trống password',
                'password_account.min' => "Password Tối thiểu 8 kí tự",
                'unique' => 'Dữ liệu đã tồn tại xin nhập lại',
                'bo_mon.unique' => 'Đã tồn tại chủ nhiệm bộ môn của ngành học này ' ,
                'permission.not_in' => 'Hãy chọn quyền!'
            ]
        );

        $date = date('Y-m-d h:i:s');
        $password = $this->ramdom_password();
        $this->user->insert(
            [
                'name' => $request->name_account,
                'created_at' => $date,
                'email' => $request->email_account,
                'password' =>  Hash::make($password),
                'role_id' => $request->permission,
                'role_bomon' => $request->bo_mon
            ]
        );

        $chucvu = $request->permission == 2 ? 'Quản trị' : 'Chủ nhiệm bộ môn';
        $mailData = [
            'title' => 'Hello ! ' . $request->name_account ,
            'body' => 'Chúc Mừng bạn đã tạo tài khoản thành công !',
            'password' => $password,
            'chucvu' => $chucvu
        ];

        Mail::to($request->email_account)->send(new MailAccount($mailData));

        return redirect(route('account.add'))->with("msg","Tạo tài khoản thành công vui lòng kiểm tra email để nhận password");


    }

    function ramdom_password(){
        $data = '1234567890ABCDEFGHIJKLMNOPQRSTUVWXYZabcefghijklmnopqrstuvwxyz';
        return substr(str_shuffle($data), 0, 12);
    }


}
