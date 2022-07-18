<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\Satuan;
use Illuminate\Http\Request;

class SatuanController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $dt = Satuan::where('is_delete','!=','1')->get();
        return view('admin.master.index', [
            'dt' => $dt,
            'title' => 'Master Satuan',
            'text_' => 'Satuan',
            'route_' => 'satuan',
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
        Satuan::create($validated);
        alert()->success('Sukses', 'Berhasil menambah satuan');
        return redirect()->route('satuan.index');
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
        $res = Satuan::where('id', $id)->update(
            ['name'=> $request->name]);
        if ($res) {
            alert()->success('Sukses', 'Berhasil mengubah satuan');
        } else {
            alert()->error('ERROR', 'Gagal mengubah satuan');
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
        $res = Satuan::where('id', $id)->update(
            ['is_delete'=>'1']);
        if ($res) {
            alert()->success('Sukses', 'Berhasil menghapus satuan');
        } else {
            alert()->error('ERROR', 'Gagal menghapus satuan');
        }
        return redirect()->route('satuan.index');
    }
}
