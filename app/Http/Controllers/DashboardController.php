<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;

class DashboardController extends Controller
{
    public function index(){
        return view('admin.dashboard.index');
    }

    public function quanlifuge(){
        $user = User::get();
//        dd($user);
        return view('admin.quanli.list',compact('user'));
    }

}
