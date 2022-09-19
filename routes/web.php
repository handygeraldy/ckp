<?php

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ckp\CkpController;
use App\Http\Controllers\ckp\KegiatanController;
use App\Http\Controllers\ckp\Penilaian;
use App\Http\Controllers\admin\TimController;
use App\Http\Controllers\admin\UserController;
use App\Http\Controllers\admin\KreditController;
use App\Http\Controllers\admin\SatkerController;
use App\Http\Controllers\admin\GolonganController;
use App\Http\Controllers\admin\FungsionalController;
use App\Http\Controllers\ckp\Approval;
use App\Http\Controllers\ckp\DashboardCkp;
use App\Http\Controllers\tim\ProjekController;
use App\Models\Tim;

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

// Yunus
Route::get('/', [Controller::class, 'indexSimtk'])->name('index');

// Handy

Route::get('/sickp', [DashboardCkp::class, 'indexCkp'])->name('index');
Route::get('/sickp/{tahun}/{bulan}', [DashboardCkp::class, 'filterDashboard'])->name('index.filter');

Route::delete('kredit/delete', [KreditController::class, 'softDelete'])->name('kredit.delete');
Route::delete('user/delete', [UserController::class, 'softDelete'])->name('user.delete');
Route::delete('tim/delete', [TimController::class, 'softDelete'])->name('tim.delete');
Route::delete('ckp/delete', [CkpController::class, 'softDelete'])->name('ckp.delete');
Route::delete('kegiatan/delete', [KegiatanController::class, 'delete'])->name('kegiatan.delete');

Route::get('ckp/catatan/{id}', [CkpController::class, 'showCatatan'])->name('ckp.catatan');
Route::post('ckp/ajukan', [CkpController::class, 'ajukan'])->name('ckp.ajukan');
Route::get('ckp/export/{id}', [CkpController::class, 'export'])->name('ckp.export');

Route::resource('kredit', KreditController::class);
Route::resource('golongan', GolonganController::class);
Route::resource('fungsional', FungsionalController::class);
Route::resource('satker', SatkerController::class);
Route::resource('user', UserController::class);
Route::resource('tim', TimController::class);
Route::resource('ckp', CkpController::class);
Route::resource('kegiatan', KegiatanController::class);
Route::resource('projek', ProjekController::class);

Route::get('nilai', [Penilaian::class, 'index'])->name('nilai.index');
Route::get('nilai/show/{id}', [Penilaian::class, 'show'])->name('nilai.show');
Route::get('nilai/input/{id}', [Penilaian::class, 'inputNilai'])->name('nilai.edit');
Route::post('nilai/input', [Penilaian::class, 'inputNilaiPost'])->name('nilai.edit.post');

Route::get('approval', [Approval::class, 'index'])->name('approval.index');
Route::get('approval/show/{id}', [Approval::class, 'show'])->name('approval.show');
Route::post('approval/approve-reject', [Approval::class, 'approveReject'])->name('approval.approve.reject');
Route::post('approval/approve-checked', [Approval::class, 'approveChecked'])->name('approval.approve.checked');
