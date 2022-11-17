<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Laravel\Socialite\Facades\Socialite;

class AuthController extends Controller
{

    public function login(){
        return view('auth.login');
    }
    public function redirectGoogleAuth(){
        return Socialite::driver('google')->redirect();
    }
    public function fakeLogin() {
        $email = "thienth@fpt.edu.vn";
        $user = User::where('email',$email )->first();
        Auth::login($user);
        return redirect(route('form.baocaothi'));
    }
    public function authCallback(){
        $guser = Socialite::driver('google')->user();

        $user = User::where('email', $guser->email)->first();
        if(!$user){
            $user = new User();
            $user->email = $guser->getEmail();
            $user->password = Hash::make(uniqid());
            $user->name = $guser->name;
            $user->save();
        }

        Auth::login($user);

        if(Auth::user()->role_id == 1){
            return redirect(route('form.baocaothi'));
        }else {
            return redirect(route('dotthi.index'));
        }
    }

    public function logout(){
        Auth::logout();
        return redirect(route('login'));
    }
}
