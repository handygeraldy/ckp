<?php

namespace App\Http\Controllers\tim;

use App\Http\Controllers\Controller;
use App\Models\simtk\KegiatanTim;
use App\Models\simtk\Projek;
use Illuminate\Http\Request;

class KegiatanTimController extends Controller
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
    public function storeWithId(Request $request, $id)
    {
        $validated = $request->validate([
            'sasaran' => 'required',
            'kegiatan' => 'required',
            'satuan' => 'required',
            'periode_tim_id' => 'required'
        ]);
        $jml_kegiatan = count($request->kegiatan);
        // dd($request);
        for ($i = 0; $i < $jml_kegiatan; $i++) {
            $kegiatan = new KegiatanTim();
            $kegiatan->projek_id = $id;
            $kegiatan->name = $request->kegiatan[$i];
            $kegiatan->iku_id = $request->sasaran[$i];
            $kegiatan->periode_awal = $request->tgl_mulai[$i];
            $kegiatan->periode_akhir = $request->tgl_selesai[$i];
            $kegiatan->save();
        }

        alert()->success('Sukses', 'Kegiatan berhasil ditambahkan');
        return redirect()->route('projek.show', $id);
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
        $res = KegiatanTim::where('id', $id)->update(
            ['is_delete' => '1']
        );
        $variabel = KegiatanTim::where('id', $id)->get(['projek_id']);
        if ($res) {
            alert()->success('Sukses', 'Berhasil menghapus kegiatan');
        } else {
            alert()->error('ERROR', 'Gagal menghapus kegiatan');
        }
        return redirect()->route('projek.show', $variabel[0]->projek_id);
    }
}
