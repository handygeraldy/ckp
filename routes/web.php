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
use App\Http\Controllers\tim\KegiatanTimController;
use App\Http\Controllers\tim\ProjekController;
use App\Http\Controllers\tim\UserTimController;

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

Route::middleware('auth')->group(function () {
    // Route::get('/gantipassword', [LoginController::class, 'gantiPassword'])->name('ganti.password');
    // Route::post('/gantipassword', [LoginController::class, 'postGantiPassword'])->name('post.ganti.password');

    Route::post('/logout',  [LoginController::class, 'logout'])->name('logout');

    Route::get('/', [Controller::class, 'index'])->name('index');
    Route::get('/dashboard-ckp/{tahun}/{bulan}', [Controller::class, 'filterDashboard'])->name('index.ckp.filter');

    // Route::get('/dashboard-simtk', [Controller::class, 'indexSimtk'])->name('simtk.index');
    // Route::get('/dashboard-ckp', [DashboardCkp::class, 'indexCkp'])->name('ckp.index');
    // Route::get('/dashboard-ckp/{tahun}/{bulan}', [DashboardCkp::class, 'filterDashboard'])->name('index.ckp.filter');

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

    Route::delete('user/delete', [UserController::class, 'softDelete'])->name('user.delete');
    Route::resource('user', UserController::class);
});

// master - admin
Route::middleware(['role:5'])->group(function () {
    Route::delete('kredit/delete', [KreditController::class, 'softDelete'])->name('kredit.delete');
    Route::resource('kredit', KreditController::class);
    Route::resource('golongan', GolonganController::class);
    Route::resource('fungsional', FungsionalController::class);
    Route::resource('satker', SatkerController::class);
});

// approval direktur
Route::middleware(['role:8'])->group(function () {
    Route::get('approval', [Approval::class, 'index'])->name('approval.index');
    Route::get('approval/show/{id}', [Approval::class, 'show'])->name('approval.tampil');
    Route::post('approval/approve-reject', [Approval::class, 'approveReject'])->name('approval.approve.reject');
    Route::post('approval/approve-checked', [Approval::class, 'approveChecked'])->name('approval.approve.checked');
});

// penilaian ketua tim
Route::middleware(['role:11'])->group(function () {
    Route::get('nilai', [Penilaian::class, 'index'])->name('nilai.index');
    Route::get('nilai/show/{id}', [Penilaian::class, 'show'])->name('nilai.tampil');
    Route::get('nilai/input/{id}', [Penilaian::class, 'inputNilai'])->name('nilai.edit');
    Route::post('nilai/input', [Penilaian::class, 'inputNilaiPost'])->name('nilai.edit.post');
});


// direktur
Route::middleware(['role:8'])->group(function () {
    Route::delete('tim/delete', [TimController::class, 'softDelete'])->name('tim.delete');
    Route::get('tim_user/{id}', [UserTimController::class, 'show'])->name('usertim.show');
});

// ketua tim
Route::middleware(['role:11'])->group(function () {

    Route::post('tim_user/tambah_anggota', [UserTimController::class, 'store'])->name('usertim.store');
    Route::delete('projek/delete', [ProjekController::class, 'softDelete'])->name('projek.delete');
    Route::get('projek-create/{id}', [ProjekController::class, 'create_proyek'])->name('projek.tambah');
    Route::get('projek-tambah_kegiatan/{id}', [KegiatanTimController::class, 'tambah_kegiatan'])->name('kegiatantim.tambah_kegiatan');
    Route::delete('kegiatantim/delete', [KegiatanTimController::class, 'softDelete'])->name('projek.deletekegiatantim');
    Route::get('kegiatantim/assign{id}', [KegiatanTimController::class, 'assign'])->name('kegiatantim.assign');
    Route::post('kegiatantim/assign{id}', [KegiatanTimController::class, 'assign_post'])->name('kegiatantim.assign.post');
    Route::post('kegiatantim/simpan/{id}', [KegiatanTimController::class, 'storeWithId'])->name('kegiatantim.store.withid');
});

// semua
Route::middleware('auth')->group(function () {
    Route::resource('tim', TimController::class);
    Route::get('tim_user/user/{id}', [UserTimController::class, 'profil'])->name('usertim.profil');
    Route::resource('projek', ProjekController::class);
    Route::resource('kegiatantim', KegiatanTimController::class);
});
