<?php

namespace App\Http\Controllers;

use App\Models\PeriodeTim;
use App\Models\simtk\KegiatanTim;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use RealRashid\SweetAlert\Facades\Alert;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            if (session('success')) {
                Alert::success(session('success'));
            }

            if (session('error')) {
                Alert::error(session('error'));
            }

            return $next($request);
        });
    }


    public function indexSimtk()
    {
        $tahun = date('Y');
        $daftar_pegawai = User::all();
        $daftar_tim = PeriodeTim::all();
        $daftar_kegiatan = KegiatanTim::all();
        $sum_status = collect([
            0 => count($daftar_pegawai),
            1 => count($daftar_tim),
            2 => count($daftar_kegiatan)
        ]);

        return view('simtk.dashboard', [
            "title" => "Dashboard",
            "sum_status" => $sum_status,
            "tahun" => $tahun,
        ]);
    }
}
