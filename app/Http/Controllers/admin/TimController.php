<?php

namespace App\Http\Controllers\admin;

use App\Models\Tim;
use App\Models\User;
use App\Models\Satker;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\PeriodeTim;
use Illuminate\Support\Facades\DB;

class TimController extends Controller
{
    public function index()
    {
        $dt = DB::table('periode_tims')
            ->leftjoin('tims', 'periode_tims.tim_id', '=', 'tims.id')
            ->leftjoin('users', 'periode_tims.ketua_id', '=', 'users.id')
            ->leftjoin('satkers', 'tims.satker_id', '=', 'satkers.id')
            ->select('periode_tims.id as id', 'periode_tims.tahun as tahun', 'satkers.name as satker', 'tims.name as name', 'users.name as ketua',)
            ->where('tims.is_delete', '0')
            ->get();
        return view('admin.master.tim.index', [
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
        $satker = Satker::get(['id', 'name']);
        $user = User::where('is_delete', '!=', '1')->get(['id', 'name']);
        $tim = Tim::all();
        return view('admin.master.tim.create', [
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
        $periodetim = PeriodeTim::with(['tim'])->where('id', $id)->first();
        // $satker = Satker::get(['id', 'name']);
        $user = User::where('is_delete', '!=', '1')->get(['id', 'name']);

        return view('admin.master.tim.edit', [
            'title' => 'Edit Tim',
            'periodetim' => $periodetim,
            // 'satker' => $satker,
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
        $res = PeriodeTim::where('id', $id)->delete();
        if ($res) {
            alert()->success('Sukses', 'Berhasil menghapus tim');
        } else {
            alert()->error('ERROR', 'Gagal menghapus tim');
        }
        return redirect()->route('tim.index');
    }
}
