<?php

namespace App\Http\Controllers\admin;

use App\Models\Golongan;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class GolonganController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {        
        $dt = Golongan::all();
        return view('admin.master.index', [
            'dt' => $dt,
            'title' => 'Master Golongan',
            'text_' => 'Golongan',
            'route_' => 'golongan',
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
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
        Golongan::create($validated);
        alert()->success('Sukses', 'Berhasil menambah golongan');
        return redirect()->route('golongan.index');
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
        $res = Golongan::where('id', $id)->update(
            ['name'=> $request->name]);
        if ($res) {
            alert()->success('Sukses', 'Berhasil mengubah golongan');
        } else {
            alert()->error('ERROR', 'Gagal mengubah golongan');
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
}
