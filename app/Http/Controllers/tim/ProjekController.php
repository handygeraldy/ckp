<?php

namespace App\Http\Controllers\tim;

use App\Http\Controllers\Controller;
use App\Models\Kredit;
use App\Models\PeriodeTim;
use App\Models\simtk\Ind_kinerja;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ProjekController extends Controller
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

    public function create()
    {
        //
    }
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */


    public function create_proyek($id)
    {
        $tim = PeriodeTim::with(['tim'])->get();
        $butir = Kredit::all(['id', 'kode_perka', 'name', 'kegiatan', 'satuan']);
        $iku = Ind_kinerja::all(['id', 'tujuan_id', 'sasaran', 'iku']);

        $list_anggota = DB::table('user_tims')
            ->leftJoin('users', 'user_tims.anggota_id', 'users.id')
            ->select(
                'users.name as name',
                'users.id as id'
            )
            ->where('user_tims.tim_id', $id)
            ->get();

        return view('admin.master.tim.create_proyek', [
            "title" => "Tambah Proyek",
            "tim" => $tim,
            "butir" => $butir,
            'iku' => $iku,
            'list_anggota' => $list_anggota
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
}
