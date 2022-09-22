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
            'list_anggota' => $list_anggota,
            'id' => $id
        ]);
    }

    public function tambah_kegiatan($id)
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
        $projek = Projek::where('id', $id)->first();
        return view('admin.master.tim.tambah_kegiatan', [
            'title' => $projek->name,
            'projek' => $projek,
            "tim" => $tim,
            "butir" => $butir,
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

        $df = DB::table('projeks')
            ->leftJoin('kegiatan_tims', 'projeks.id', 'kegiatan_tims.projek_id')
            ->leftJoin('ind_kinerjas', 'kegiatan_tims.iku_id', 'ind_kinerjas.id')
            ->select(
                'kegiatan_tims.name as nama_kegiatan',
                'kegiatan_tims.id as id',
                'ind_kinerjas.sasaran as sasaran'
            )
            ->where('projeks.id', $id)->where('kegiatan_tims.is_delete', '0')
            ->get();

        $periodetim = PeriodeTim::with(['tim', 'user'])->where('id', $infoprojek[0]->id_tim)->first();
        $projek = Projek::where('id', $id)->first();

        // dd($df);
        return view('admin.master.tim.list_kegiatan', [
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
        // dd($tim);
        return view('admin.master.tim.edit_proyek', [
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
        // dd($request->idkegiatan);
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
