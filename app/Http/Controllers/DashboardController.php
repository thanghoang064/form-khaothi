<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index(Request $request){
        $role_id = Auth::user()->role_id;
        session(['role_id' => $role_id]);
        return view('admin.dashboard.index')->with('role_id');
    }

    public function quanlifuge(){
        $user = User::get();
//        dd($user);
        return view('admin.quanli.list',compact('user'));
    }

}
