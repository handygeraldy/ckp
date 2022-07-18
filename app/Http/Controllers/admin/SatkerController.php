<?php

namespace App\Http\Controllers\admin;

use App\Models\Satker;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\User;

class SatkerController extends Controller
{
    public function index()
    {        
        $dt = Satker::where('is_delete','!=','1')->get();
        return view('admin.master.satker.index', [
            'dt' => $dt,
            'title' => 'Master Satker',
            'text_' => 'Satker',
            'route_' => 'satker',
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $user = User::where('is_delete','!=','1')->get(['id','name']);
        return view('admin.master.satker.create', [
            'title' => 'Tambah Satker',
            'user' => $user
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
            'user_id' => 'required'
        ]);
        Satker::create($validated);
        alert()->success('Sukses', 'Berhasil menambah satker');
        return redirect()->route('satker.index');
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
        $satker = Satker::where('id', $id)->first();
        $user = User::where('is_delete','!=','1')
        ->where('satker_id',$id)
        ->get(['id','name']);
        return view('admin.master.satker.edit', [
            'title' => 'Edit Satker',
            'satker' => $satker,
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
            'name' => 'required',
            'user_id' => 'required'
        ]);
        $res = Satker::where('id', $id)->update($validated);
        if ($res) {
            alert()->success('Sukses', 'Berhasil mengubah satker');
        } else {
            alert()->error('ERROR', 'Gagal mengubah satker');
        }
        return redirect()->route('satker.index');
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
        $res = Satker::where('id', $id)->update(
            ['is_delete'=>'1']);
        if ($res) {
            alert()->success('Sukses', 'Berhasil menghapus satker');
        } else {
            alert()->error('ERROR', 'Gagal menghapus satker');
        }
        return redirect()->route('satker.index');
    }
}
