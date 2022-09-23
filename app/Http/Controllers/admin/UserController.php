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
        if (Auth::user()->role_id <= 8) {
            $dt = User::where('is_delete', '!=', '1');
        } elseif (Auth::user()->role_id <= 11) {
            $dt = User::where('is_delete', '!=', '1')
                ->where('tim_utama', Auth::user()->tim_utama);
        } else {
            $dt = User::where('id', Auth::user()->id);
        }
        $dt = $dt->get();
        return view('admin.master.user.index', [
            'dt' => $dt,
            'title' => 'Master User',
            'text_' => 'User',
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
        $dt = User::where('id', $id)->first();

        $list_kegiatan = DB::table('kegiatan__tim__users')
            ->leftJoin('kegiatan_tims', 'kegiatan__tim__users.kegiatan_tim_id', 'kegiatan_tims.id')
            ->leftJoin('projeks', 'kegiatan_tims.projek_id', 'projeks.id')
            ->leftJoin('periode_tims', 'projeks.periode_tim_id', 'periode_tims.id')
            ->leftJoin('tims', 'periode_tims.tim_id', 'tims.id')
            ->leftJoin('users', 'periode_tims.ketua_id', 'users.id')
            ->select(
                'projeks.name as nama_projek',
                'kegiatan_tims.name as nama_kegiatan',
                'tims.name as nama_tim',
                'users.name as nama_ketua',
            )
            ->where('kegiatan__tim__users.user_id', $id)
            ->get();

        return view('admin.master.user.profil', [
            'dt' => $dt,
            'list_kegiatan' => $list_kegiatan,
            'title' => $dt->name,
            'text_' => 'User',
            'route_' => 'user',
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
