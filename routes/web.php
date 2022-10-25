<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::redirect('/', '/login');
Route::get('login', [\App\Http\Controllers\AuthController::class, 'login'])->name('login');
Route::get('redirect/google', [\App\Http\Controllers\AuthController::class, 'redirectGoogleAuth'])->name('login.google');
Route::get('auth/callback', [\App\Http\Controllers\AuthController::class, 'authCallback']);
Route::any('logout', [\App\Http\Controllers\AuthController::class, 'logout'])->name('logout');
Route::get('auth/fakelogin', [\App\Http\Controllers\AuthController::class, 'fakeLogin'])->name('login.fake');
Route::group(['middleware' => 'auth'], function(){
    Route::get('form-bao-cao-thi', [\App\Http\Controllers\FormBaoCaoThiController::class, 'index'])->name('form.baocaothi');
    Route::post('form-bao-cao-thi', [\App\Http\Controllers\FormBaoCaoThiController::class, 'postBaoCaoThi']);

    Route::get('form-bao-cao-thanh-cong', [\App\Http\Controllers\FormBaoCaoThiController::class, 'thanhCong'])->name('form.thanhcong');

    Route::get('lich-su-bao-cao-thi', [\App\Http\Controllers\FormBaoCaoThiController::class, 'lichSuBaoCao'])->name('form.lichsu');
    Route::get('tai-file-bao-cao-thi/{luotbaocao}', [\App\Http\Controllers\FormBaoCaoThiController::class, 'taiFileBaocao'])->name('form.taifilebaocao');
});

Route::view('layout', 'layouts.admin.master2');
Route::group(['middleware' => 'admin_role', 'prefix' => 'admin'], function (){
    Route::redirect('/', 'dashboard');
    Route::get('dashboard', [\App\Http\Controllers\DashboardController::class, 'index'])->name('dashboard');
    Route::group(['prefix' => 'dot-thi'], function (){
       Route::get('', [\App\Http\Controllers\DotThiController::class, 'index'])->name('dotthi.index');
       Route::get('tao-moi', [\App\Http\Controllers\DotThiController::class, 'addForm'])->name('dotthi.add');
       Route::post('tao-moi', [\App\Http\Controllers\DotThiController::class, 'saveAdd']);
    });
});
