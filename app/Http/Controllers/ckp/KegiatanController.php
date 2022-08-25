<?php

namespace App\Http\Controllers\ckp;

use App\Models\Tim;
use App\Models\Kredit;
use App\Models\Satuan;
use App\Models\ckp\Kegiatan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;

class KegiatanController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $kegiatan = Kegiatan::where('id', $id)->first();
        $tim = Tim::all();
        $satuan = Satuan::all();
        $butir = Kredit::all(['id','kode_perka','name','kegiatan']);

        return view('ckp.kegiatan.edit', [
            'title' => 'Edit Kegiatan',
            'kegiatan' => $kegiatan,
            'tim' => $tim,
            'satuan' => $satuan,
            'butir' => $butir,
        ]);
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
        $validated = $request->validate([
            'name' => 'required',
            'tim_id' => 'required',
            'tgl_mulai' => 'nullable',
            'tgl_selesai' => 'nullable',
            'satuan_id' => 'required',
            'jml_target' => 'required',
            'jml_realisasi' => 'required',
            'kredit_id' => 'nullable',
            'keterangan' => 'nullable',
            'angka_kredit' => 'required',
        ]);
        $res = Kegiatan::where('id', $id)->update($validated);

        // hitung ckp
        $hitung = DB::table('kegiatans')
            ->select(DB::raw('COUNT(id) as jml_kegiatan, AVG(jml_realisasi / jml_target * 100) as avg_kuantitas, AVG(nilai_kegiatan) as avg_kualitas, SUM(angka_kredit) AS sum_angka_kredit'))
            ->where('ckp_id', $request->ckp_id)
            ->groupBy('ckp_id')
            ->first();

        DB::table('ckps')
            ->where('id', $request->ckp_id)
            ->update(
                [
                    'jml_kegiatan' => $hitung->jml_kegiatan,
                    'avg_kuantitas' => $hitung->avg_kuantitas,
                    'avg_kualitas' => $hitung->avg_kualitas,
                    'nilai_akhir' => ($hitung->avg_kuantitas + $hitung->avg_kualitas) / 2,
                    'angka_kredit' => $hitung->sum_angka_kredit,
                ]
            );
        if ($res) {
            alert()->success('Sukses', 'Berhasil mengubah kegiatan');
        } else {
            alert()->error('ERROR', 'Gagal mengubah kegiatan');
        }
        return redirect()->route('ckp.index');
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

    public function delete(Request $request)
    {
        $id = $request->value_id;
        $res = Kegiatan::where('id', $id)->delete();
        // hitung ckp
        $hitung = DB::table('kegiatans')
            ->select(DB::raw('COUNT(id) as jml_kegiatan, AVG(jml_realisasi / jml_target * 100) as avg_kuantitas, AVG(nilai_kegiatan) as avg_kualitas, SUM(angka_kredit) AS sum_angka_kredit'))
            ->where('ckp_id', $request->ckp_id)
            ->groupBy('ckp_id')
            ->first();
        if ($hitung != null) {
            DB::table('ckps')
                ->where('id', $request->ckp_id)
                ->update(
                    [
                        'jml_kegiatan' => $hitung->jml_kegiatan,
                        'avg_kuantitas' => $hitung->avg_kuantitas,
                        'avg_kualitas' => $hitung->avg_kualitas,
                        'nilai_akhir' => ($hitung->avg_kuantitas + $hitung->avg_kualitas) / 2,
                        'angka_kredit' => $hitung->sum_angka_kredit,
                    ]
                );
        } else{
            DB::table('ckps')
                ->where('id', $request->ckp_id)
                ->update(
                    [
                        'jml_kegiatan' => 0,
                        'avg_kuantitas' => 0,
                        'avg_kualitas' => 0,
                        'nilai_akhir' => 0,
                        'angka_kredit' => 0,
                    ]
                );
        }

        if ($res) {
            alert()->success('Sukses', 'Berhasil menghapus Kegiatan');
        } else {
            alert()->error('ERROR', 'Gagal menghapus Kegiatan');
        }
        return redirect()->route('ckp.index');
    }
}
