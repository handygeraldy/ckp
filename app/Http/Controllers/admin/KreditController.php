<?php

namespace App\Http\Controllers\admin;

use App\Models\Kredit;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class KreditController extends Controller
{
    public function index()
    {        
        $dt = Kredit::where('is_delete','!=','1')->limit(10)->get();
        return view('admin.master.kredit.index', [
            'dt' => $dt,
            'title' => 'Master Angka Kredit',
            'text_' => 'Angka Kredit',
            'route_' => 'kredit',
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.master.kredit.create', [
            'title' => 'Tambah Kredit'
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
            'name' => 'required',
        ]);
        Kredit::create($validated);
        alert()->success('Sukses', 'Berhasil menambah kredit');
        return redirect()->route('kredit.index');
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
        $res = Kredit::where('id', $id)->update(
            ['name'=> $request->name]);
        if ($res) {
            alert()->success('Sukses', 'Berhasil mengubah kredit');
        } else {
            alert()->error('ERROR', 'Gagal mengubah kredit');
        }
        return response()->json(true);
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
        $res = Kredit::where('id', $id)->update(
            ['is_delete'=>'1']);
        if ($res) {
            alert()->success('Sukses', 'Berhasil menghapus kredit');
        } else {
            alert()->error('ERROR', 'Gagal menghapus kredit');
        }
        return redirect()->route('kredit.index');
    }
}
