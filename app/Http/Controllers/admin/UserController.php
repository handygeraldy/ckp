<?php

namespace App\Http\Controllers\admin;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Fungsional;
use App\Models\Golongan;
use App\Models\Role;
use App\Models\Satker;
use App\Models\Tim;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class UserController extends Controller
{
    public function index()
    {
        $sql = "select u.id, u.is_delete, u.name, u.nip, u.email, s.name as satker_name, u.tim_utama as tim_utama, f.name as fungsional_name, g.name as golongan_name, t.name as tim_name, count(ut.anggota_id) as jumlah_tim from users u 
        left join user_tims ut on u.id = ut.anggota_id 
        left join satkers s on u.satker_id = s.id 
        left join fungsionals f on u.fungsional_id = f.id 
        left join golongans g on u.golongan_id = g.id
        left join tims t on u.tim_utama = t.id
        group by u.id, u.is_delete, u.name, u.nip, u.email, s.name, u.tim_utama, f.name, g.name, t.name
        having u.is_delete = '0'
        order by u.nip";

        $dt = DB::select($sql);

        return view('admin.master.user.index', [
            'dt' => $dt,
            'title' => 'Master Pegawai',
            'text_' => 'Pegawai',
            'route_' => 'user',
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
        if (Auth::user()->role_id <= 8) {
            $tim = Tim::get(['id', 'name']);
        } else {
            $tim = Tim::where('id', Auth::user()->tim_utama)->get(['id', 'name']);
        }
        $golongan = Golongan::get(['id', 'name']);
        $fungsional = Fungsional::get(['id', 'name']);
        $role = Role::where('id', '>', Auth::user()->role_id)->get(['id', 'name']);
        return view('admin.master.user.create', [
            'title' => 'Tambah User',
            'satker' => $satker,
            'tim' => $tim,
            'golongan' => $golongan,
            'fungsional' => $fungsional,
            'role' => $role,
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
            'nip' => 'required|unique:users',
            'email' => 'required|unique:users',
            'password' => 'required',
            'satker_id' => 'required',
            'tim_utama' => 'required',
            'golongan_id' => 'required',
            'fungsional_id' => 'required',
            'role_id' => 'required',
            'ttd' => 'image|mimes:jpg,jpeg,png',
        ]);
        $extension = $request->file('ttd')->getClientOriginalExtension();
        $filename = $request->nip . '.' . $extension;
        $request->file('ttd')->move(base_path().'/public/storage//', $filename);
        $validated['ttd'] = $filename;
        $validated['password'] = bcrypt($validated['password']);
        User::create($validated);
        alert()->success('Sukses', 'Berhasil menambah user');
        return redirect()->route('user.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $user = User::where('id', $id)->first();
        $satker = Satker::get(['id', 'name']);
        if (Auth::user()->role_id <= 8) {
            $tim = Tim::get(['id', 'name']);
        } else {
            $tim = Tim::where('id', Auth::user()->tim_utama)->get(['id', 'name']);
        }
        $golongan = Golongan::get(['id', 'name']);
        $fungsional = Fungsional::get(['id', 'name']);
        $role = Role::where('id', '>=', Auth::user()->role_id)->get(['id', 'name']);
        return view('admin.master.user.edit', [
            'title' => 'Edit User',
            'user' => $user,
            'satker' => $satker,
            'tim' => $tim,
            'golongan' => $golongan,
            'fungsional' => $fungsional,
            'role' => $role,
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
            'nip' => 'required|unique:users,nip,' . $id,
            'email' => 'required|unique:users,email,' . $id,
            'satker_id' => 'required',
            'tim_utama' => 'required',
            'golongan_id' => 'required',
            'fungsional_id' => 'required',
            'role_id' => 'required',
            'ttd' => 'image|nullable|mimes:jpg,jpeg,png',
        ]);
        if ($request->hasFile('ttd')) {
            $extension = $request->file('ttd')->getClientOriginalExtension();
            $filename = $request->nip . '.' . $extension;
            $request->file('ttd')->move(base_path().'/public/storage//', $filename);
            $validated['ttd'] = $filename;
        }

        if ($request->password) {
            $validatedPass = $request->validate([
                'password' => 'min:6',
            ]);
            $validatedPass['password'] = bcrypt($validatedPass['password']);
            User::where('id', $id)
                ->update([
                    'password' => $validatedPass['password'],
                ]);
        }
        $res = User::where('id', $id)->update($validated);
        if ($res) {
            alert()->success('Sukses', 'Berhasil mengubah user');
        } else {
            alert()->error('ERROR', 'Gagal mengubah user');
        }
        return redirect()->route('user.index');
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
        $res = User::where('id', $id)->update(
            ['is_delete' => '1']
        );
        if ($res) {
            alert()->success('Sukses', 'Berhasil menghapus user');
        } else {
            alert()->error('ERROR', 'Gagal menghapus user');
        }
        return redirect()->route('user.index');
    }
}
