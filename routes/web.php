<?php

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ckp\Approval;
use App\Http\Controllers\ckp\Penilaian;
use Illuminate\Support\Facades\Artisan;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\ckp\DashboardCkp;
use App\Http\Controllers\ckp\CkpController;
use App\Http\Controllers\admin\TimController;
use App\Http\Controllers\ckp\ArsipController;
use App\Http\Controllers\admin\UserController;
use App\Http\Controllers\admin\KreditController;
use App\Http\Controllers\admin\SatkerController;

use App\Http\Controllers\admin\GolonganController;
use App\Http\Controllers\ckp\KegiatanController;
use App\Http\Controllers\admin\FungsionalController;
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
// Route::get('/', [Controller::class, 'indexSimtk'])->name('index');

// Handy
Route::get('/flushconfig', function () {
    $output = [];
    Artisan::call('cache:clear', [], $output);
    print_r($output);

    $output = [];
    Artisan::call('route:clear', [], $output);
    print_r($output);

    $output = [];
    Artisan::call('view:clear', [], $output);
    print_r($output);

    $output = [];
    Artisan::call('config:cache', [], $output);
    print_r($output);
});


Route::get('/login',  [LoginController::class, 'index'])->name('login')->middleware('guest');
Route::post('/login',  [LoginController::class, 'authenticate'])->name('login.post')->middleware('guest');
Route::delete('user/delete', [UserController::class, 'softDelete'])->name('user.delete');
Route::resource('user', UserController::class);
Route::middleware('auth')->group(function () {
    // Route::get('/gantipassword', [LoginController::class, 'gantiPassword'])->name('ganti.password');
    // Route::post('/gantipassword', [LoginController::class, 'postGantiPassword'])->name('post.ganti.password');

    Route::post('/logout',  [LoginController::class, 'logout'])->name('logout');

    Route::get('/', [DashboardCkp::class, 'indexCkp'])->name('index');
    Route::get('/dashboard-ckp/{tahun}/{bulan}', [DashboardCkp::class, 'filterDashboard'])->name('index.ckp.filter');

    Route::delete('ckp/delete', [CkpController::class, 'softDelete'])->name('ckp.delete');
    Route::delete('kegiatan/delete', [KegiatanController::class, 'delete'])->name('kegiatan.delete');

    Route::get('ckp/catatan/{id}', [CkpController::class, 'showCatatan'])->name('ckp.catatan');
    Route::post('ckp/ajukan', [CkpController::class, 'ajukan'])->name('ckp.ajukan');
    Route::get('ckp/export/{id}', [CkpController::class, 'export'])->name('ckp.export');
    Route::get('ckp/show/{id}', [CkpController::class, 'show'])->name('ckp.tampil');

    Route::resource('ckp', CkpController::class);
    Route::resource('kegiatan', KegiatanController::class);

    Route::get('arsip', [ArsipController::class, 'index'])->name('arsip.index');
    Route::get('/arsip/filter/{tahun}/{bulan}', [ArsipController::class, 'filterIndex'])->name('arsip.filter');
    Route::get('arsip/show/{id}', [ArsipController::class, 'show'])->name('arsip.tampil');
});

Route::middleware(['role:5'])->group(function () {
    Route::delete('kredit/delete', [KreditController::class, 'softDelete'])->name('kredit.delete');
    Route::resource('kredit', KreditController::class);
    Route::resource('golongan', GolonganController::class);
    Route::resource('fungsional', FungsionalController::class);
    Route::resource('satker', SatkerController::class);
});

Route::middleware(['role:8'])->group(function () {


    Route::get('approval', [Approval::class, 'index'])->name('approval.index');
    Route::get('approval/show/{id}', [Approval::class, 'show'])->name('approval.tampil');
    Route::post('approval/approve-reject', [Approval::class, 'approveReject'])->name('approval.approve.reject');
    Route::post('approval/approve-checked', [Approval::class, 'approveChecked'])->name('approval.approve.checked');
});

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
Route::get('projek-create/{id}', [ProjekController::class, 'create_proyek'])->name('projek.tambah');
Route::resource('projek', ProjekController::class);

Route::get('nilai', [Penilaian::class, 'index'])->name('nilai.index');
Route::get('nilai/show/{id}', [Penilaian::class, 'show'])->name('nilai.show');
Route::get('nilai/input/{id}', [Penilaian::class, 'inputNilai'])->name('nilai.edit');
Route::post('nilai/input', [Penilaian::class, 'inputNilaiPost'])->name('nilai.edit.post');

Route::get('approval', [Approval::class, 'index'])->name('approval.index');
Route::get('approval/show/{id}', [Approval::class, 'show'])->name('approval.show');
Route::post('approval/approve-reject', [Approval::class, 'approveReject'])->name('approval.approve.reject');
Route::post('approval/approve-checked', [Approval::class, 'approveChecked'])->name('approval.approve.checked');

Route::middleware(['role:11'])->group(function () {
    Route::delete('tim/delete', [TimController::class, 'softDelete'])->name('tim.delete');
    Route::resource('tim', TimController::class);
    Route::get('nilai', [Penilaian::class, 'index'])->name('nilai.index');
    Route::get('nilai/show/{id}', [Penilaian::class, 'show'])->name('nilai.tampil');
    Route::get('nilai/input/{id}', [Penilaian::class, 'inputNilai'])->name('nilai.edit');
    Route::post('nilai/input', [Penilaian::class, 'inputNilaiPost'])->name('nilai.edit.post');
});
