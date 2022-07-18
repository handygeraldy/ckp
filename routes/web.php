<?php

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CkpController;
use App\Http\Controllers\admin\FungsionalController;
use App\Http\Controllers\admin\SatkerController;
use App\Http\Controllers\admin\SatuanController;
use App\Http\Controllers\admin\GolonganController;
use App\Http\Controllers\admin\KreditController;
use App\Http\Controllers\admin\UserController;

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

Route::resource('ckp', CkpController::class);
Route::delete('kredit/delete', [KreditController::class, 'softDelete'])->name('kredit.delete');
Route::delete('golongan/delete', [GolonganController::class, 'softDelete'])->name('golongan.delete');
Route::delete('fungsional/delete', [FungsionalController::class, 'softDelete'])->name('fungsional.delete');
Route::delete('satker/delete', [SatkerController::class, 'softDelete'])->name('satker.delete');
Route::delete('satuan/delete', [SatuanController::class, 'softDelete'])->name('satuan.delete');
Route::delete('user/delete', [UserController::class, 'softDelete'])->name('user.delete');

Route::resource('kredit', KreditController::class);
Route::resource('golongan', GolonganController::class);
Route::resource('fungsional', FungsionalController::class);
Route::resource('satker', SatkerController::class);
Route::resource('satuan', SatuanController::class);
Route::resource('user', UserController::class);
