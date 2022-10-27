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
    Route::get('fuge', [\App\Http\Controllers\FugeController::class, 'index'])->name('form.uploadfuge');
    Route::post('fuge', [\App\Http\Controllers\FugeController::class, 'postFugeFile']);
    Route::get('form-bao-cao-thanh-cong-fuge', [\App\Http\Controllers\FugeController::class, 'thanhCong'])->name('form.thanhcongFuge');
    Route::get('fuge/lich-su-bao-cao', [\App\Http\Controllers\FugeController::class, 'lichSuUpload'])->name('fuge.lichsu');
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

    Route::group(['prefix' => 'ky-hoc'], function (){
        Route::get('', [\App\Http\Controllers\HocKyController::class, 'index_ky_hoc'])->name('ky-hoc.index');
        Route::get('add', [\App\Http\Controllers\HocKyController::class, 'add_ky_hoc'])->name('ky_hoc.add');
        Route::post('add', [\App\Http\Controllers\HocKyController::class, 'new_ky_hoc']);
        Route::get('edit', [\App\Http\Controllers\HocKyController::class, 'edit'])->name('ky_hoc.edit');
        Route::post('edit', [\App\Http\Controllers\HocKyController::class, 'update_ky_hoc']);
        Route::delete('delete/{id}', [\App\Http\Controllers\HocKyController::class, 'delete'])->name('ky_hoc.delete');
    });

    Route::group(['prefix' => 'dot-thi'], function (){
       Route::get('', [\App\Http\Controllers\DotThiController::class, 'index'])->name('dotthi.index');
       Route::get('tao-moi', [\App\Http\Controllers\DotThiController::class, 'addForm'])->name('dotthi.add');
       Route::post('tao-moi', [\App\Http\Controllers\DotThiController::class, 'saveAdd']);
       Route::get('sua', [\App\Http\Controllers\DotThiController::class, 'editForm'])->name('dotthi.edit');
       Route::post('sua', [\App\Http\Controllers\DotThiController::class, 'updateForm']);
       Route::delete('xoa', [\App\Http\Controllers\DotThiController::class, 'deleteForm'])->name('dotthi.delete');
    });

    Route::get('giangvien', [\App\Http\Controllers\UserController::class, 'index'])->name('giangvien.list');

    Route::group(['prefix' => 'fuge'], function (){
        Route::get('danhsachupload', [\App\Http\Controllers\FugeController::class, 'danhSachUpload'])->name('fuge.danhsachupload');
        Route::get('file-fuge/{id}', [\App\Http\Controllers\FugeController::class, 'taiFileFuge'])
            ->name('fuge.download');
    });
});
