<?php

namespace App\Http\Controllers\tim;

use App\Http\Controllers\Controller;
use App\Models\PeriodeTim;
use App\Models\simtk\UserTim;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class UserTimController extends Controller
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
        $jml_anggota = count($request->anggota);
        // dd($request);
        for ($i = 0; $i < $jml_anggota; $i++) {
            $anggota = new UserTim();
            $anggota->tim_id = $request->tim_id;
            $anggota->anggota_id = $request->anggota[$i];
            $anggota->save();
        }

        alert()->success('Sukses', 'Anggota berhasil ditambahkan');
        return redirect()->route('usertim.show', $request->tim_id);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $periodetim = PeriodeTim::with(['tim', 'user'])->where('id', $id)->first();
        $list_anggota = DB::table('user_tims')
            ->leftJoin('users', 'user_tims.anggota_id', 'users.id')
            ->select(
                'users.name as nama_anggota',
                'users.id as id'
            )
            ->where('user_tims.tim_id', $id)
            ->orderBy('nama_anggota')
            ->get();

        $sql = 'select * from users u where u.id not in (select ut.anggota_id from user_tims ut where ut.tim_id =' . $id . ') and u.role_id != 8 order by u.name';
        $calon_anggota = DB::select($sql);
        return view('simtk.user_tim.show', [
            "title" => $periodetim->tim->name,
            'calon_anggota' => $calon_anggota,
            "periodetim" => $periodetim,
            'list_anggota' => $list_anggota,
            'id' => $id,
            'route_' => 'tim'
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

    public function profil($id)
    {
        $dt = User::where('id', $id)->first();

        $list_kegiatan = DB::table('kegiatan__tim__users')
            ->leftJoin('kegiatan_tims', 'kegiatan__tim__users.kegiatan_tim_id', 'kegiatan_tims.id')
            ->leftJoin('projeks', 'kegiatan_tims.projek_id', 'projeks.id')
            ->leftJoin('periode_tims', 'projeks.periode_tim_id', 'periode_tims.id')
            ->leftJoin('tims', 'periode_tims.tim_id', 'tims.id')
            ->leftJoin('users', 'periode_tims.ketua_id', 'users.id')
            ->select(
                'projeks.name as nama_projek',
                'kegiatan_tims.name as nama_kegiatan',
                'tims.name as nama_tim',
                'users.name as nama_ketua',
            )
            ->where('kegiatan__tim__users.user_id', $id)
            ->get();

        return view('simtk.user_tim.profil', [
            'dt' => $dt,
            'list_kegiatan' => $list_kegiatan,
            'title' => $dt->name,
            'text_' => 'User',
            'route_' => 'user',
        ]);
    }
}
