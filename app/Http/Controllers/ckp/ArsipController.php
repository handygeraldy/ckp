<?php

namespace App\Http\Controllers\ckp;

use Carbon\Carbon;
use App\Models\ckp\Ckp;
use App\Models\ckp\Kegiatan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;

class ArsipController extends Controller
{
    public function index()
    {
        $tahun = date('Y');
        $bulan = date('m');
        $next = Carbon::createFromFormat('Y-m-d', "$tahun-$bulan-1")->addMonth();
        $prev = Carbon::createFromFormat('Y-m-d', "$tahun-$bulan-1")->subMonth();
        $dt = Ckp::select(
            'ckps.id as id',
            'ckps.bulan as bulan',
            'ckps.tahun as tahun',
            'ckps.avg_kuantitas as avg_kuantitas',
            'ckps.avg_kualitas as avg_kualitas',
            'ckps.nilai_akhir as nilai_akhir',
            'ckps.jml_kegiatan as jml_kegiatan',
            'users.name as user_name',
        )
            ->leftjoin('users', 'ckps.user_id', '=', 'users.id')
            ->where('ckps.is_delete', '!=', '1')
            ->where('ckps.status', '4')
            ->where('ckps.bulan', $bulan)
            ->where('ckps.tahun', $tahun)
            ->orderBy('tahun')
            ->orderBy('bulan')
            ->orderBy('user_name')
            ->get();
        return view('ckp.arsip.index', [
            'dt' => $dt,
            'title' => 'Arsip',
            'route_' => 'arsip',
            "bulan" => $bulan,
            "tahun" => $tahun,
            "next_month" => $next->format('m'),
            "next_year" => $next->format('Y'),
            "prev_month" => $prev->format('m'),
            "prev_year" => $prev->format('Y'),
        ]);
    }

    public function filterIndex($tahun, $bulan)
    {

        $next = Carbon::createFromFormat('Y-m-d', "$tahun-$bulan-1")->addMonth();
        $prev = Carbon::createFromFormat('Y-m-d', "$tahun-$bulan-1")->subMonth();
        $dt = Ckp::select(
            'ckps.id as id',
            'ckps.bulan as bulan',
            'ckps.tahun as tahun',
            'ckps.avg_kuantitas as avg_kuantitas',
            'ckps.avg_kualitas as avg_kualitas',
            'ckps.nilai_akhir as nilai_akhir',
            'ckps.jml_kegiatan as jml_kegiatan',
            'users.name as user_name',
        )
            ->leftjoin('users', 'ckps.user_id', '=', 'users.id')
            ->where('ckps.is_delete', '!=', '1')
            ->where('ckps.status', '4')
            ->where('ckps.bulan', $bulan)
            ->where('ckps.tahun', $tahun)
            ->orderBy('tahun')
            ->orderBy('bulan')
            ->orderBy('user_name')
            ->get();
        return view('ckp.arsip.index', [
            'dt' => $dt,
            'title' => 'Arsip',
            'route_' => 'arsip',
            "bulan" => $bulan,
            "tahun" => $tahun,
            "next_month" => $next->format('m'),
            "next_year" => $next->format('Y'),
            "prev_month" => $prev->format('m'),
            "prev_year" => $prev->format('Y'),
        ]);
    }

    public function show($id)
    {
        $ckp = Ckp::where('id', $id)->first();
        $kegiatan = Kegiatan::where('ckp_id', $id)
            ->orderBy('urut')
            ->get();
        return view('ckp.show', [
            "title" => "Lihat CKP",
            "route_" => "arsip",
            "ckp" => $ckp,
            "kegiatan" => $kegiatan,
        ]);
    }
}
