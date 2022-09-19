<?php

namespace App\Http\Controllers\ckp;

use App\Models\ckp\Ckp;
use App\Models\ckp\Kegiatan;
use Illuminate\Http\Request;
use App\Models\ckp\CatatanCkp;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class Penilaian extends Controller
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
            'users.name as user_name',
            DB::raw('(SELECT COUNT(id) FROM kegiatans
                                WHERE ckps.id = kegiatans.ckp_id
                                AND nilai_kegiatan IS NULL
                                GROUP BY ckps.id) as jml_kegiatan')
        )
            ->leftjoin('users', 'ckps.user_id', '=', 'users.id')
            ->where('ckps.is_delete', '!=', '1')
            ->where('ckps.status', '2')
            ->where('users.tim_utama', Auth::user()->tim_utama)
            ->orderBy('tahun')
            ->orderBy('bulan')
            ->orderBy('user_name')
            ->get();
        return view('ckp.nilai.index', [
            'dt' => $dt,
            'title' => 'Penilaian',
            'route_' => 'nilai',
        ]);
    }

    public function show($id)
    {
        $ckp = Ckp::where('id', $id)->first();
        $kegiatan = DB::table('kegiatans')
        ->leftjoin('kredits', 'kegiatans.kredit_id', 'kredits.id')
        ->select(
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
            "route_" => "nilai",
            "ckp" => $ckp,
            "kegiatan_utama" => $kegiatan_utama,
            "kegiatan_tambahan" => $kegiatan_tambahan,
        ]);
    }

    public function inputNilai($id)
    {
        $ckp = Ckp::where('id', $id)->first();
        $kegiatan = DB::table('kegiatans')
        ->leftjoin('kredits', 'kegiatans.kredit_id', 'kredits.id')
        ->select(
            'kegiatans.id as id',
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
        return view('ckp.nilai.berinilai', [
            "title" => "Beri Nilai",
            "ckp" => $ckp,
            "kegiatan_utama" => $kegiatan_utama,
            "kegiatan_tambahan" => $kegiatan_tambahan,
        ]);
    }

    public function inputNilaiPost(Request $request)
    {
        $status = array('reject' => '0', 'save' => '2', 'send' => '3');
        $status_value = $request->input('action');
        $ckp_id = $request->ckp_id;
        $jml_kegiatan = count($request->id);
        for ($i = 0; $i < $jml_kegiatan; $i++) {
            Kegiatan::where('id', $request->id[$i])->update([
                'nilai_kegiatan' => $request->nilai_kegiatan[$i]
            ]);
        }
        // hitung nilai 
        $hitung = DB::table('kegiatans')
            ->select(DB::raw('AVG(jml_realisasi / jml_target * 100) as avg_kuantitas, AVG(nilai_kegiatan) as avg_kualitas, SUM(angka_kredit) AS sum_angka_kredit'))
            ->where('ckp_id', $ckp_id)
            ->groupBy('ckp_id')
            ->first();

        $res = Ckp::where('id', $ckp_id)->update(
            [
                'status' => $status[$status_value],
                'jml_kegiatan' => $jml_kegiatan,
                'avg_kuantitas' => $hitung->avg_kuantitas,
                'avg_kualitas' => $hitung->avg_kualitas,
                'nilai_akhir' => ($hitung->avg_kuantitas + $hitung->avg_kualitas) / 2,
                'angka_kredit' => $hitung->sum_angka_kredit,
            ]
        );

        if ($status_value == 'reject') {
            CatatanCkp::create([
                'ckp_id' => $ckp_id,
                'user_id' => Auth::user()->id,
                'catatan' => $request->catatan,
            ]);
            alert()->success('Sukses', 'Berhasil me-reject CKP');
        } elseif ($status_value == 'save') {
            alert()->success('Sukses', 'Berhasil menyimpan nilai CKP');
        } elseif ($status_value == 'send') {
            alert()->success('Sukses', 'Berhasil menilai dan menyetujui CKP');
        } else {
            alert()->error('ERROR', 'Gagal memberi nilai CKP');
        }
        return redirect()->route('nilai.index');
    }
}
