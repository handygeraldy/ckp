<?php

namespace App\Http\Controllers\admin;

use App\Models\Tim;
use App\Models\User;
use App\Models\Satker;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Models\PeriodeTim;
use App\Models\simtk\UserTim;
use Illuminate\Support\Facades\Auth;

class TimController extends Controller
{
    public function index()
    {
        $dt = DB::table('periode_tims')
            ->leftjoin('tims', 'periode_tims.tim_id', '=', 'tims.id')
            ->leftjoin('users', 'periode_tims.ketua_id', '=', 'users.id')
            ->leftjoin('satkers', 'tims.satker_id', '=', 'satkers.id')
            ->select('periode_tims.id as id', 'periode_tims.tahun as tahun', 'satkers.name as satker', 'tims.name as name', 'users.name as ketua',)
            ->where('periode_tims.is_delete', '0');

        $dt = $dt->get();
        return view('simtk.tim.index', [
            'dt' => $dt,
            'title' => 'Master Tim',
            'text_' => 'Tim',
            'route_' => 'tim',
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        if (Auth::user()->role_id > 8) {
            alert()->error('Gagal', 'Anda tidak dapat menambah tim');
            return redirect()->route('tim.index');
        }
        $satker = Satker::get(['id', 'name']);
        $user = User::where('is_delete', '!=', '1')
            ->where('role_id', '11')
            ->orderBy('name', 'asc')
            ->get(['id', 'name']);
        $tim = Tim::all();
        return view('simtk.tim.create', [
            'title' => 'Tambah Tim',
            'user' => $user,
            'satker' => $satker,
            'tim' => $tim,
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
        if ($request->jenis_tim == "baru") {
            $validated = $request->validate([
                'name' => 'required', #name dari form
                'satker_id' => 'required',
                'tahun' => 'required',
                'ketua_id' => 'required'
            ]);
            $tim = new Tim();
            $tim->name = $validated['name']; #bagian kiri field name di database
            $tim->satker_id = $validated['satker_id'];
            $tim->save();

            $periode_tim = new PeriodeTim();
            $periode_tim->tahun = $validated['tahun'];
            $periode_tim->ketua_id = $validated['ketua_id'];

            $tim->periodetim()->save($periode_tim);
        } else {
            $validated = $request->validate([
                'tim_id' => 'required',
                'tahun' => 'required',
                'ketua_id' => 'required'
            ]);
            PeriodeTim::create($validated);
        }
        alert()->success('Sukses', 'Berhasil menambah tim');
        return redirect()->route('tim.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $dt = DB::table('periode_tims')
            ->rightJoin('projeks', 'periode_tims.id', 'projeks.periode_tim_id')
            ->select(
                'projeks.name as projek_name',
                'projeks.id as id'
            )
            ->where('periode_tims.id', $id)
            ->where('projeks.is_delete', '0')
            ->get();

        $periodetim = PeriodeTim::with(['tim', 'user'])->where('id', $id)->first();

        $df = DB::table('user_tims')
            ->leftJoin('users', 'user_tims.anggota_id', 'users.id')
            ->select(
                'users.name as nama_anggota'
            )
            ->where('user_tims.tim_id', $id)
            ->where('user_tims.anggota_id', '!=', $periodetim->ketua_id)
            ->get();

        return view('simtk.tim.show', [
            'title' => $periodetim->tim->name,
            'periodetim' => $periodetim,
            'df' => $df,
            'dt' => $dt,
            'route_' => 'projek',
            'id' => $id
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
        if (Auth::user()->role_id > 8) {
            alert()->error('Gagal', 'Anda tidak dapat menambah tim');
            return redirect()->route('tim.index');
        }
        $periodetim = PeriodeTim::with(['tim'])->where('id', $id)->first();
        $user = User::where('is_delete', '!=', '1')
            ->where('role_id', '11')
            ->orderBy('name', 'asc')
            ->get(['id', 'name']);

        return view('simtk.tim.edit', [
            'title' => 'Edit Tim',
            'periodetim' => $periodetim,
            'user' => $user,
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
            'ketua_id' => 'required',
            'tahun' => 'required'
        ]);
        $res = PeriodeTim::where('id', $id)->update($validated);
        if ($res) {
            alert()->success('Sukses', 'Berhasil mengubah tim');
        } else {
            alert()->error('ERROR', 'Gagal mengubah tim');
        }
        return redirect()->route('tim.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
    }

    public function softDelete(Request $request)
    {
        $id = $request->value_id;
        $res = PeriodeTim::where('id', $id)->update(
            ['is_delete' => '1']
        );
        if ($res) {
            alert()->success('Sukses', 'Berhasil menghapus tim');
        } else {
            alert()->error('ERROR', 'Gagal menghapus tim');
        }
        return redirect()->route('tim.show', $id);
    }
}
