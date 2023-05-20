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

    public function index()
    {
        $tahun = date('Y');
        $daftar_pegawai = User::where('is_delete','0')->get(['id']);
        $daftar_tim = PeriodeTim::where('is_delete','0')->get(['id']);
        $daftar_kegiatan = KegiatanTim::where('is_delete','0')->get(['id']);
        $sum_status_simtk = collect([
            0 => count($daftar_pegawai),
            1 => count($daftar_tim),
            2 => count($daftar_kegiatan)
        ]);

        // ckp
        $bulan = date('m');
        $daftar_pegawai = DB::table('ckps')
        ->select(DB::raw('ckps.status, users.name as user_name', 'ckps.nilai_akhir as nilai'))
        ->leftJoin('users', 'ckps.user_id', 'users.id')
        ->where('ckps.bulan', $bulan)
        ->where('ckps.tahun', $tahun);
        $daftar_pegawai = $daftar_pegawai->get();

        $rekap_pegawai = collect([
            0 => $daftar_pegawai->where('status', '0')->pluck('user_name'),
            1 => $daftar_pegawai->where('status', '1')->pluck('user_name'),
            2 => $daftar_pegawai->where('status', '2')->pluck('user_name'),
            3 => $daftar_pegawai->where('status', '3')->pluck('user_name'),
            4 => $daftar_pegawai->where('status', '4')->pluck('user_name'),
        ]);

        $sum_status = collect([
            0 => count($rekap_pegawai[0]),
            1 => count($rekap_pegawai[1]),
            2 => count($rekap_pegawai[2]),
            3 => count($rekap_pegawai[3]),
            4 => count($rekap_pegawai[4])
        ]);

        $next = Carbon::createFromFormat('Y-m-d', "$tahun-$bulan-1")->addMonth();
        $prev = Carbon::createFromFormat('Y-m-d', "$tahun-$bulan-1")->subMonth();

        return view('dashboard', [
            "title" => "Dashboard RB PMSS",
            "sum_status_simtk" => $sum_status_simtk,
            "tahun" => $tahun,
            "bulan" => $bulan,
            "rekap_pegawai" => $rekap_pegawai,
            "sum_status" => $sum_status,
            "next_month" => $next->format('m'),
            "next_year" => $next->format('Y'),
            "prev_month" => $prev->format('m'),
            "prev_year" => $prev->format('Y'),
        ]);
    }

    public function filterDashboard($tahun, $bulan)
    {
        $daftar_pegawai = DB::table('ckps')
        ->select(DB::raw('ckps.status, users.name as user_name', 'ckps.nilai_akhir as nilai'))
        ->leftJoin('users', 'ckps.user_id', 'users.id')
        ->where('ckps.bulan', $bulan)
        ->where('ckps.tahun', $tahun);
        $daftar_pegawai = $daftar_pegawai->get();

        $rekap_pegawai = collect([
            0 => $daftar_pegawai->where('status', '0')->pluck('user_name'),
            1 => $daftar_pegawai->where('status', '1')->pluck('user_name'),
            2 => $daftar_pegawai->where('status', '2')->pluck('user_name'),
            3 => $daftar_pegawai->where('status', '3')->pluck('user_name'),
            4 => $daftar_pegawai->where('status', '4')->pluck('user_name'),
        ]);

        $sum_status = collect([
            0 => count($rekap_pegawai[0]),
            1 => count($rekap_pegawai[1]),
            2 => count($rekap_pegawai[2]),
            3 => count($rekap_pegawai[3]),
            4 => count($rekap_pegawai[4])
        ]);
       
        $next = Carbon::createFromFormat('Y-m-d', "$tahun-$bulan-1")->addMonth();
        $prev = Carbon::createFromFormat('Y-m-d', "$tahun-$bulan-1")->subMonth();

        // simtk
        $daftar_pegawai = User::where('is_delete','0')->get(['id']);
        $daftar_tim = PeriodeTim::where('is_delete','0')->get(['id']);
        $daftar_kegiatan = KegiatanTim::where('is_delete','0')->get(['id']);
        $sum_status_simtk = collect([
            0 => count($daftar_pegawai),
            1 => count($daftar_tim),
            2 => count($daftar_kegiatan)
        ]);
        return view('dashboard', [
            "title" => "Dashboard",
            "rekap_pegawai" => $rekap_pegawai,
            "sum_status" => $sum_status,
            "sum_status_simtk" => $sum_status_simtk,
            "bulan" => $bulan,
            "tahun" => $tahun,
            "next_month" => $next->format('m'),
            "next_year" => $next->format('Y'),
            "prev_month" => $prev->format('m'),
            "prev_year" => $prev->format('Y'),
        ]);
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
