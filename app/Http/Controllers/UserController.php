<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;

class UserController extends Controller
{
    public function index()
    {
        $users = User::all();
        return view('admin.giangvien.index', compact('users'));
    }

    public function quanlifuge()
    {
        $user = User::get();
//        dd($user);
        return view('admin.quanli.list', compact('user'));
    }

}
