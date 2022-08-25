<?php

namespace App\Http\Controllers\tim\ckp;

use App\Models\ckp\Ckp;
use App\Models\ckp\Kegiatan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;

class Penilaian extends Controller
{
    public function index()
    {
        $dt = Ckp::where('is_delete', '!=', '1')
            ->where('status', '2')
            ->orderBy('tahun', 'desc')
            ->orderBy('bulan', 'desc')
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
        $kegiatan = Kegiatan::where('ckp_id', $id)
            ->get();
        return view('ckp.show', [
            "title" => "Lihat CKP",
            "route_" => "nilai",
            "ckp" => $ckp,
            "kegiatan" => $kegiatan,
        ]);
    }

    public function inputNilai($id)
    {
        $ckp = Ckp::where('id', $id)->first();
        $kegiatan = Kegiatan::where('ckp_id', $id)
            ->get();
        return view('ckp.nilai.berinilai', [
            "title" => "Beri Nilai",
            "ckp" => $ckp,
            "kegiatan" => $kegiatan
        ]);
    }

    public function inputNilaiPost(Request $request)
    {
        $status = array('reject' => 1, 'save' => 2, 'send' => 3);
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
            ->where('ckp_id', $request->ckp_id)
            ->groupBy('ckp_id')
            ->first();

        Ckp::where('id', $ckp_id)->update(
            [
                'status' => $status[$request->input('action')],
                'jml_kegiatan' => $jml_kegiatan,
                'avg_kuantitas' => $hitung->avg_kuantitas,
                'avg_kualitas' => $hitung->avg_kualitas,
                'nilai_akhir' => ($hitung->avg_kuantitas + $hitung->avg_kualitas) / 2,
                'angka_kredit' => $hitung->sum_angka_kredit,
            ]
        );
        return redirect()->route('nilai.index');
    }

    public function reject()
    {
    }

    public function approve()
    {
    }
}
