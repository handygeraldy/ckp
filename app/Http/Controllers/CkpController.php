<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Ckp;
use App\Models\Tim;
use App\Models\Kredit;
use App\Models\Satker;
use App\Models\Satuan;
use App\Models\Kegiatan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\View;

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
        return view('CKP.index', [
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
        // sementara, filter user id
        $ckp_lama = DB::table('ckps')
        ->where('bulan', $bulan)
        ->where('tahun', $tahun)
        ->first();
        $sas = count($request->target);
        dd($sas);
        if ($ckp_lama == null){
            $ckp = new Ckp();
            $ckp->bulan = $bulan;
            $ckp->tahun = $tahun;
            $ckp->satker_id = 3100;
            $ckp->user_id = 1; //sementara
    
            $ckp->save();
            for ($i = 0; $i < $jml_kegiatan; $i++) {
                $kegiatan = new Kegiatan();
                $kegiatan->name = $request->kegiatan[$i];
                $kegiatan->tim_id = $request->tim[$i];
                $kegiatan->tgl_mulai = $request->tgl_mulai[$i];
                $kegiatan->tgl_selesai = $request->tgl_selesai[$i];
                $kegiatan->satuan_id = $request->satuan[$i];
                $kegiatan->jml_target = $request->target[$i];
                $kegiatan->jml_realisasi = $request->realisasi[$i];
                if ($request->kredit_id != null){
                    $kegiatan->kredit_id = $request->kredit_id[$i];
                }
                if ($request->ket != null){
                    $kegiatan->keterangan = $request->ket[$i];
                }
                $ckp->kegiatan()->save($kegiatan);
            }
            // hitung nilai
        } else {
            for ($i = 0; $i < $jml_kegiatan; $i++) {
                $kegiatan = new Kegiatan();
                $kegiatan->ckp_id = $ckp_lama->id;
                $kegiatan->name = $request->kegiatan[$i];
                $kegiatan->tim_id = $request->tim[$i];
                $kegiatan->tgl_mulai = $request->tgl_mulai[$i];
                $kegiatan->tgl_selesai = $request->tgl_selesai[$i];
                $kegiatan->satuan_id = $request->satuan[$i];
                $kegiatan->jml_target = $request->target[$i];
                $kegiatan->jml_realisasi = $request->realisasi[$i];
                if ($request->kredit_id != null){
                    $kegiatan->kredit_id = $request->kredit_id[$i];
                }
                if ($request->ket != null){
                    $kegiatan->keterangan = $request->ket[$i];
                }
                $kegiatan->save();
            }
            // hitung nilai
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
