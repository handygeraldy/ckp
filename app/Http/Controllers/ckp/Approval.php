<?php

namespace App\Http\Controllers\ckp;

use App\Models\ckp\Ckp;
use App\Models\ckp\Kegiatan;
use Illuminate\Http\Request;
use App\Models\ckp\CatatanCkp;
use App\Http\Controllers\Controller;

class Approval extends Controller
{
    public function index()
    {
        $dt = Ckp::select(
            'ckps.id as id',
            'ckps.bulan as bulan',
            'ckps.tahun as tahun',
            'ckps.avg_kuantitas as avg_kuantitas',
            'ckps.avg_kualitas as avg_kualitas',
            'ckps.nilai_akhir as nilai_akhir',
            'users.name as user_name',
        )
        ->leftjoin('users', 'ckps.user_id', '=', 'users.id')
        ->where('ckps.is_delete', '!=', '1')
            ->where('ckps.status', '3')
            ->orderBy('tahun')
            ->orderBy('bulan')
            ->orderBy('user_name')
            ->get();
        return view('ckp.approval.index', [
            'dt' => $dt,
            'title' => 'Approval CKP',
            'route_' => 'approval',
        ]);
    }

    public function show($id)
    {
        $ckp = Ckp::where('id', $id)->first();
        $kegiatan = Kegiatan::where('ckp_id', $id)
            ->get();
        return view('ckp.show', [
            "title" => "Lihat CKP",
            "route_" => "approval",
            "ckp" => $ckp,
            "kegiatan" => $kegiatan
        ]);
    }

    public function approveReject(Request $request) {
        $status = array('reject' => '0', 'approve' => '4');
        $status_value = $request->input('action');
        $ckp_id = $request->ckp_id;
        $res = Ckp::where('id', $ckp_id)->update(['status' => $status[$status_value]]);
        if ($status_value == 'reject'){
            CatatanCkp::create([
                'ckp_id' => $ckp_id,
                'user_id' => '2b653b00-efdc-442e-8c96-b82e49f5b698', //sementara
                'catatan' => $request->catatan,
            ]);
            alert()->success('Sukses', 'Berhasil me-reject CKP');
        } elseif ($status_value == 'approve'){
            alert()->success('Sukses', 'Berhasil menyetujui CKP');
        } else {
            alert()->error('ERROR', 'Gagal menyetujui CKP');
        }
        return redirect()->route('approval.index');
    }

    public function approveChecked(Request $request) {
        $status = array('reject' => '1', 'approve' => '4');
        $res = Ckp::whereIn('id', $request->ckp_id)->update(['status' => $status[$request->input('action')]]);
        if ($res) {
            alert()->success('Sukses', 'Berhasil ' . $request->input('action') . ' CKP');
        } else {
            alert()->error('ERROR', 'Gagal ' . $request->input('action') . ' CKP');
        }
        
        return redirect()->route('approval.index');
    }
}