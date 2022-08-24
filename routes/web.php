<?php

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ckp\CkpController;
use App\Http\Controllers\ckp\KegiatanController;
use App\Http\Controllers\tim\ckp\Penilaian;
use App\Http\Controllers\admin\TimController;
use App\Http\Controllers\admin\UserController;
use App\Http\Controllers\admin\KreditController;
use App\Http\Controllers\admin\SatkerController;
use App\Http\Controllers\admin\SatuanController;
use App\Http\Controllers\admin\GolonganController;
use App\Http\Controllers\admin\FungsionalController;

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
Route::get('/', [Controller::class, 'index'])->name('index');

Route::delete('kredit/delete', [KreditController::class, 'softDelete'])->name('kredit.delete');
Route::delete('user/delete', [UserController::class, 'softDelete'])->name('user.delete');
Route::delete('tim/delete', [TimController::class, 'softDelete'])->name('tim.delete');
Route::delete('ckp/delete', [CkpController::class, 'softDelete'])->name('ckp.delete');
Route::delete('kegiatan/delete', [KegiatanController::class, 'delete'])->name('kegiatan.delete');

Route::resource('kredit', KreditController::class);
Route::resource('golongan', GolonganController::class);
Route::resource('fungsional', FungsionalController::class);
Route::resource('satker', SatkerController::class);
Route::resource('satuan', SatuanController::class);
Route::resource('user', UserController::class);
Route::resource('tim', TimController::class);
Route::resource('ckp', CkpController::class);
Route::resource('kegiatan', KegiatanController::class);
Route::resource('nilai', Penilaian::class);