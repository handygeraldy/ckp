<?php

namespace App\Http\Controllers\admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Fungsional;

class FungsionalController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $dt = Fungsional::all();
        return view('admin.master.index', [
            'dt' => $dt,
            'title' => 'Master Fungsional',
            'text_' => 'Fungsional',
            'route_' => 'fungsional',
        ]);
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
        $validated = $request->validate([
            'name' => 'required',
            'jafung_id' => 'required',
        ]);
        Fungsional::create($validated);
        alert()->success('Sukses', 'Berhasil menambah fungsional');
        return redirect()->route('fungsional.index');
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
        $res = Fungsional::where('id', $id)->update(
            [
                'name'=> $request->name,
                'jafung_id'=> $request->jafung_id,
        ]);
        if ($res) {
            alert()->success('Sukses', 'Berhasil mengubah fungsional');
        } else {
            alert()->error('ERROR', 'Gagal mengubah fungsional');
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
        //
    }
}
