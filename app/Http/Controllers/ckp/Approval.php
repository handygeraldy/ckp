<?php

namespace App\Http\Controllers\ckp;

use App\Models\ckp\Ckp;
use App\Models\ckp\Kegiatan;
use Illuminate\Http\Request;
use App\Models\ckp\CatatanCkp;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class Approval extends Controller
{
    public function index()
    {
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
            ->where('ckps.status', '3')
            ->orderBy('tahun')
            ->orderBy('bulan')
            ->orderBy('user_name')
            ->get();
        return view('ckp.approval.index', [
            'dt' => $dt,
            'title' => 'Approval CKP',
            'route_' => 'approval',
        ]);
    }

    public function show($id)
    {
        $ckp = Ckp::where('id', $id)->first();
        $kegiatan = DB::table('kegiatans')
        ->leftjoin('kredits', 'kegiatans.kredit_id', 'kredits.id')
        ->select(
            'kegiatans.id as id',
            'kegiatans.ckp_id as ckp_id',
            'kegiatans.kegiatan_tim_id as kegiatan_tim_id',
            'kegiatans.name as name',
            'kegiatans.jenis as jenis',
            'kegiatans.tgl_mulai as tgl_mulai',
            'kegiatans.tgl_selesai as tgl_selesai',
            'kegiatans.satuan as satuan',
            'kegiatans.jml_target as jml_target',
            'kegiatans.jml_realisasi as jml_realisasi',
            'kegiatans.nilai_kegiatan as nilai_kegiatan',
            'kegiatans.angka_kredit as angka_kredit',
            'kegiatans.keterangan as keterangan',
            'kredits.kode_perka as kode_perka',
        )
            ->where('ckp_id', $id)
            ->orderBy('urut')
            ->get();

        $kegiatan_utama = $kegiatan->filter(function ($k) {
            return $k->jenis == 'utama';
        });
        $kegiatan_tambahan = $kegiatan->filter(function ($k) {
            return $k->jenis == 'tambahan';
        });
        return view('ckp.show', [
            "title" => "Lihat CKP",
            "route_" => "approval",
            "ckp" => $ckp,
            "kegiatan_utama" => $kegiatan_utama,
            "kegiatan_tambahan" => $kegiatan_tambahan,
        ]);
    }

    public function approveReject(Request $request)
    {
        $status = array('reject' => '0', 'approve' => '4');
        $status_value = $request->input('action');
        $ckp_id = $request->ckp_id;
        $res = Ckp::where('id', $ckp_id)->update(['status' => $status[$status_value]]);
        if ($status_value == 'reject') {
            CatatanCkp::create([
                'ckp_id' => $ckp_id,
                'user_id' =>Auth::user()->id,
                'catatan' => $request->catatan,
            ]);
            alert()->success('Sukses', 'Berhasil me-reject CKP');
        } elseif ($status_value == 'approve') {
            alert()->success('Sukses', 'Berhasil menyetujui CKP');
        } else {
            alert()->error('ERROR', 'Gagal menyetujui CKP');
        }
        return redirect()->route('approval.index');
    }

    public function approveChecked(Request $request)
    {
        $status = array('reject' => '1', 'approve' => '4');
        $res = Ckp::whereIn('id', $request->ckp_id)->update(['status' => $status[$request->input('action')]]);
        if ($res) {
            alert()->success('Sukses', 'Berhasil ' . $request->input('action') . ' CKP');
        } else {
            alert()->error('ERROR', 'Gagal ' . $request->input('action') . ' CKP');
        }

        return redirect()->route('approval.index');
    }
}
