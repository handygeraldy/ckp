<?php

namespace App\Http\Controllers\tim;

use App\Http\Controllers\Controller;
use App\Models\Kredit;
use App\Models\PeriodeTim;
use App\Models\simtk\Projek;
use App\Models\simtk\Ind_kinerja;
use App\Models\simtk\KegiatanTim;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ProjekController extends Controller
{

    public function create_proyek($id)
    {
        $tim = PeriodeTim::with(['tim'])->get();
        $iku = Ind_kinerja::all(['id', 'tujuan_id', 'sasaran', 'iku']);

        $list_anggota = DB::table('user_tims')
            ->leftJoin('users', 'user_tims.anggota_id', 'users.id')
            ->select(
                'users.name as name',
                'users.id as id'
            )
            ->where('user_tims.tim_id', $id)
            ->get();

        return view('simtk.projek.create', [
            "title" => "Tambah Proyek",
            "tim" => $tim,
            'iku' => $iku,
            'list_anggota' => $list_anggota,
            'id' => $id
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
            'nama_proyek' => 'required', #name dari form
            'sasaran' => 'required',
            'kegiatan' => 'required',
            'satuan' => 'required',
            'periode_tim_id' => 'required'
        ]);
        $jml_kegiatan = count($request->kegiatan);

        $projek = new Projek();
        $projek->name = $validated['nama_proyek']; #bagian kiri field name di database
        $projek->periode_tim_id = $validated['periode_tim_id'];
        $projek->save();

        for ($i = 0; $i < $jml_kegiatan; $i++) {
            $kegiatan = new KegiatanTim();
            $kegiatan->name = $request->kegiatan[$i];
            $kegiatan->iku_id = $request->sasaran[$i];
            $kegiatan->periode_awal = $request->tgl_mulai[$i];
            $kegiatan->periode_akhir = $request->tgl_selesai[$i];
            $kegiatan->satuan = $request->satuan[$i];
            $kegiatan->target = $request->jml_target[$i];
            $projek->kegiatan_tim()->save($kegiatan);
        }

        alert()->success('Sukses', 'Projek dan Kegiatan berhasil ditambahkan');
        return redirect()->route('tim.show', $validated['periode_tim_id']);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $infoprojek = DB::table('projeks')
            ->leftJoin('periode_tims', 'projeks.periode_tim_id', 'periode_tims.id')
            ->leftJoin('tims', 'periode_tims.id', 'tims.id')
            ->leftJoin('user_tims', 'tims.id', 'user_tims.tim_id')
            ->leftJoin('users', 'user_tims.anggota_id', 'users.id')
            ->select(
                'periode_tims.id as id_tim',
                'tims.name as name',
                'user_tims.anggota_id as anggota',
                'users.name as name_anggota'
            )
            ->where('projeks.id', $id)
            ->get();
        $sql = "select kt.is_delete, kt.created_at, kt.projek_id, kt.id, kt.name , group_concat(u.nickname  separator '; <br>') as nick 
        from projeks p 
        left join kegiatan_tims kt on p.id = kt.projek_id 
        left join kegiatan__tim__users ktu on kt.id = ktu.kegiatan_tim_id 
        left join users u on ktu.user_id = u.id 
        group by kt.is_delete, kt.projek_id, kt.id, kt.name, kt.created_at
        having kt.projek_id  = " . $id . " and kt.is_delete = '0'
        order by kt.created_at";
        $df = DB::select($sql);

        $periodetim = PeriodeTim::with(['tim', 'user'])->where('id', $infoprojek[0]->id_tim)->first();
        $projek = Projek::where('id', $id)->first();

        // dd($df);
        return view('simtk.projek.show', [
            'title' => $projek->name,
            'periodetim' => $periodetim,
            'route_' => 'projek',
            'id' => $id,
            'df' => $df,
            'projek' => $projek
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
        if (Auth::user()->role_id > 11) {
            alert()->error('Gagal', 'Anda tidak dapat mengedit projek');
            return redirect()->route('tim.index');
        }
        $tim = PeriodeTim::with(['tim'])->first();
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

        $projek = Projek::where('id', $id)->first();
        $kegiatan = KegiatanTim::where('projek_id', $id)->get();
        return view('simtk.projek.edit', [
            "title" => "Edit Proyek",
            "projek" => $projek,
            'kegiatan' => $kegiatan,
            "tim" => $tim,
            "butir" => $butir,
            'iku' => $iku,
            'list_anggota' => $list_anggota,
            'periode_tim_id' => $projek->periode_tim_id
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

            'project_id' => 'required',
            'nama_proyek' => 'required', #name dari form
            'periode_tim_id' => 'required'
        ]);
        // edit project
        Projek::where('id', $validated['project_id'])->update(
            [
                'name' => $validated['nama_proyek']
            ]
        );
        // edit kegiatantim
        $jml_kegiatan = count($request->kegiatan);
        for ($i = 0; $i < $jml_kegiatan; $i++) {
            $res = KegiatanTim::where('id', $request->idkegiatan[$i])->update(
                [
                    'name' => $request->kegiatan[$i],
                    'iku_id' => $request->sasaran[$i],
                    'periode_awal' => $request->tgl_mulai[$i],
                    'periode_akhir' => $request->tgl_selesai[$i],
                    'satuan' => $request->satuan[$i],
                    'target' => $request->jml_target[$i],
                ]
            );
        }

        alert()->success('Sukses', 'Projek dan Kegiatan berhasil ditambahkan');
        return redirect()->route('tim.show', $id);
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
        $res = Projek::where('id', $id)->update(
            ['is_delete' => '1']
        );
        $variabel = Projek::where('id', $id)->get(['periode_tim_id']);
        if ($res) {
            alert()->success('Sukses', 'Berhasil menghapus proyek');
        } else {
            alert()->error('ERROR', 'Gagal menghapus proyek');
        }
        return redirect()->route('tim.show', $variabel[0]->periode_tim_id);
    }
}
