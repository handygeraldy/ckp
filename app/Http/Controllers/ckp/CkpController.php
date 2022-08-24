<?php

namespace App\Http\Controllers\ckp;

use Carbon\Carbon;
use App\Models\ckp\Ckp;
use App\Models\Tim;
use App\Models\Kredit;
use App\Models\Satuan;
use App\Models\ckp\Kegiatan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;

class CkpController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $dt = Ckp::where('is_delete', '!=', '1')
            // ->where('users_id',Auth::user()->id)
            ->orderBy('tahun', 'desc')
            ->orderBy('bulan', 'desc')
            ->get();
        return view('ckp.index', [
            'dt' => $dt,
            'title' => 'CKP Saya',
            'route_' => 'ckp',
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $tim = Tim::all();
        $satuan = Satuan::all();
        $butir = Kredit::all();
        return view('ckp.create', [
            "title" => "Input CKP",
            "tim" => $tim,
            "satuan" => $satuan,
            "butir" => $butir,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        $validated = $request->validate([
            'bulan' => 'required',
        ]);
        $bulan = Carbon::createFromFormat('Y-m-d', $validated['bulan'] . '-01')->format('m');
        $tahun = Carbon::createFromFormat('Y-m-d', $validated['bulan'] . '-01')->format('Y');
        $jml_kegiatan = count($request->kegiatan);

        // sementara, nanti where user id
        $q = DB::table('ckps')
        ->where('bulan', $bulan)
        ->where('tahun', $tahun);

        $ckp_lama = $q->first();
        if ($ckp_lama == null){
            $ckp = new Ckp();
            $ckp->bulan = $bulan;
            $ckp->tahun = $tahun;
            $ckp->satker_id = 3100; // sementara
            $ckp->user_id = '2b653b00-efdc-442e-8c96-b82e49f5b698'; //sementara
            $ckp->jml_kegiatan = $jml_kegiatan;
            $ckp->avg_kuantitas = array_sum(array_map(function ($a, $b) { return round($a / $b * 100, 2); }, $request->jml_realisasi, $request->jml_target)) / $jml_kegiatan; 
            $ckp->angka_kredit = array_sum($request->angka_kredit); 


            $ckp->save();
            for ($i = 0; $i < $jml_kegiatan; $i++) {
                $kegiatan = new Kegiatan();
                $kegiatan->name = $request->kegiatan[$i];
                $kegiatan->tim_id = $request->tim_id[$i];
                $kegiatan->tgl_mulai = $request->tgl_mulai[$i];
                $kegiatan->tgl_selesai = $request->tgl_selesai[$i];
                $kegiatan->satuan_id = $request->satuan_id[$i];
                $kegiatan->jml_target = $request->jml_target[$i];
                $kegiatan->jml_realisasi = $request->jml_realisasi[$i];
                if ($request->kredit_id != null){
                    $kegiatan->kredit_id = $request->kredit_id[$i];
                }
                if ($request->keterangan != null){
                    $kegiatan->keterangan = $request->keterangan[$i];
                }
                $kegiatan->angka_kredit = $request->angka_kredit[$i];
                $ckp->kegiatan()->save($kegiatan);
            }
        } else {
            for ($i = 0; $i < $jml_kegiatan; $i++) {
                $kegiatan = new Kegiatan();
                $kegiatan->ckp_id = $ckp_lama->id;
                $kegiatan->name = $request->kegiatan[$i];
                $kegiatan->tim_id = $request->tim_id[$i];
                $kegiatan->tgl_mulai = $request->tgl_mulai[$i];
                $kegiatan->tgl_selesai = $request->tgl_selesai[$i];
                $kegiatan->satuan_id = $request->satuan_id[$i];
                $kegiatan->jml_target = $request->jml_target[$i];
                $kegiatan->jml_realisasi = $request->jml_realisasi[$i];
                if ($request->kredit_id != null){
                    $kegiatan->kredit_id = $request->kredit_id[$i];
                }
                if ($request->keterangan != null){
                    $kegiatan->keterangan = $request->keterangan[$i];
                }
                $kegiatan->angka_kredit = $request->angka_kredit[$i];
                $kegiatan->save();
            }
            // hitung nilai
            $hitung = DB::table('kegiatans')
            ->select(DB::raw('COUNT(id) as jml_kegiatan, AVG(jml_realisasi / jml_target * 100) as avg_kuantitas, AVG(nilai_kegiatan) as avg_kualitas, SUM(angka_kredit) AS sum_angka_kredit'))
            ->where('ckp_id', $ckp_lama->id)
            ->groupBy('ckp_id')
            ->first();
            $q->update(
                [
                    'jml_kegiatan'=> $hitung->jml_kegiatan,
                    'avg_kuantitas'=> $hitung->avg_kuantitas,
                    'avg_kualitas'=> $hitung->avg_kualitas,
                    'nilai_akhir'=> ($hitung->avg_kuantitas + $hitung->avg_kualitas) / 2,
                    'angka_kredit'=> $hitung->sum_angka_kredit,
                ]);
        }
    
        alert()->success('Sukses', 'ckp berhasil diinput');
        return redirect()->route('ckp.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $ckp = Ckp::where('id', $id)->first();
        $kegiatan = Kegiatan::where('ckp_id', $id)
        ->get();
        return view('ckp.show', [
            "title" => "Lihat CKP",
            "route_" => "kegiatan",
            "ckp" => $ckp,
            "kegiatan" => $kegiatan,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    public function softDelete(Request $request)
    {
        $id = $request->value_id;
        $res = Ckp::where('id', $id)->update(
            ['is_delete' => '1']
        );
        if ($res) {
            alert()->success('Sukses', 'Berhasil menghapus CKP');
        } else {
            alert()->error('ERROR', 'Gagal menghapus CKP');
        }
        return redirect()->route('user.index');
    }
}
