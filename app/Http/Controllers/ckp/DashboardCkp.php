<?php

namespace App\Http\Controllers\ckp;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;

class DashboardCkp extends Controller
{
    public function indexCkp()
    {
        $tahun = date('Y');
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
        return view('ckp.dashboard', [
            "title" => "Dashboard",
            "rekap_pegawai" => $rekap_pegawai,
            "sum_status" => $sum_status,
            "bulan" => $bulan,
            "tahun" => $tahun,
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
        return view('ckp.dashboard', [
            "title" => "Dashboard",
            "rekap_pegawai" => $rekap_pegawai,
            "sum_status" => $sum_status,
            "bulan" => $bulan,
            "tahun" => $tahun,
            "next_month" => $next->format('m'),
            "next_year" => $next->format('Y'),
            "prev_month" => $prev->format('m'),
            "prev_year" => $prev->format('Y'),
        ]);
    }
}
