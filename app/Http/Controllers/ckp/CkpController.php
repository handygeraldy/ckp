<?php

namespace App\Http\Controllers\ckp;

use Carbon\Carbon;
use App\Models\Tim;
use App\Models\Kredit;
use App\Models\ckp\Ckp;
use App\Models\ckp\Kegiatan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Pdf\Mpdf;
use Symfony\Component\HttpFoundation\StreamedResponse;

class CkpController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $dt = Ckp::where('is_delete', '!=', '1')
            // ->where('users_id',Auth::user()->id)
            ->orderBy('tahun', 'desc')
            ->orderBy('bulan', 'desc')
            ->get();
        return view('ckp.index', [
            'dt' => $dt,
            'title' => 'CKP Saya',
            'route_' => 'ckp',
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $tim = Tim::all();
        $butir = Kredit::all(['id', 'kode_perka', 'name', 'kegiatan', 'satuan']);
        return view('ckp.create', [
            "title" => "Input CKP",
            "tim" => $tim,
            "butir" => $butir,
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
            'bulan' => 'required',
        ]);
        $bulan = Carbon::createFromFormat('Y-m-d', $validated['bulan'] . '-01')->format('m');
        $tahun = Carbon::createFromFormat('Y-m-d', $validated['bulan'] . '-01')->format('Y');
        $jml_kegiatan = count($request->kegiatan);

        // sementara, nanti where user id
        $q = DB::table('ckps')
            ->where('bulan', $bulan)
            ->where('tahun', $tahun);
        $ckp_lama = $q->first();
        if ($ckp_lama == null) {
            $ckp = new Ckp();
            $ckp->bulan = $bulan;
            $ckp->tahun = $tahun;
            $ckp->satker_id = 3100; // sementara
            $ckp->user_id = '2b653b00-efdc-442e-8c96-b82e49f5b698'; //sementara
            $ckp->jml_kegiatan = $jml_kegiatan;
            $ckp->avg_kuantitas = array_sum(array_map(function ($a, $b) {
                return round($a / $b * 100, 2);
            }, $request->jml_realisasi, $request->jml_target)) / $jml_kegiatan;
            $ckp->angka_kredit = array_sum($request->angka_kredit);


            $ckp->save();
            for ($i = 0; $i < $jml_kegiatan; $i++) {
                $kegiatan = new Kegiatan();
                $kegiatan->jenis = $request->jenis[$i];
                $kegiatan->urut = $i + 1;
                $kegiatan->name = $request->kegiatan[$i];
                $kegiatan->tim_id = $request->tim_id[$i];
                $kegiatan->tgl_mulai = $request->tgl_mulai[$i];
                $kegiatan->tgl_selesai = $request->tgl_selesai[$i];
                $kegiatan->satuan = $request->satuan[$i];
                $kegiatan->jml_target = $request->jml_target[$i];
                $kegiatan->jml_realisasi = $request->jml_realisasi[$i];
                if ((int)$request->kredit_id[$i] > 0) {
                    $kegiatan->kredit_id = $request->kredit_id[$i];
                }
                $kegiatan->keterangan = $request->keterangan[$i];
                $kegiatan->angka_kredit = $request->angka_kredit[$i];
                $ckp->kegiatan()->save($kegiatan);
            }
        } else {
            if ((int)$ckp_lama->status > 1) {
                alert()->error('GAGAL', 'CKP yang telah diajukan tidak bisa diubah');
                return redirect()->route('ckp.index');
            }
            for ($i = 0; $i < $jml_kegiatan; $i++) {
                $kegiatan = new Kegiatan();
                $kegiatan->urut = $i + 1;
                $kegiatan->ckp_id = $ckp_lama->id;
                $kegiatan->name = $request->kegiatan[$i];
                $kegiatan->tim_id = $request->tim_id[$i];
                $kegiatan->tgl_mulai = $request->tgl_mulai[$i];
                $kegiatan->tgl_selesai = $request->tgl_selesai[$i];
                $kegiatan->satuan = $request->satuan[$i];
                $kegiatan->jml_target = $request->jml_target[$i];
                $kegiatan->jml_realisasi = $request->jml_realisasi[$i];
                if ((int)$request->kredit_id[$i] > 0) {
                    $kegiatan->kredit_id = $request->kredit_id[$i];
                }
                $kegiatan->keterangan = $request->keterangan[$i];
                $kegiatan->angka_kredit = $request->angka_kredit[$i];
                $kegiatan->save();
            }
            // hitung nilai
            $hitung = DB::table('kegiatans')
                ->select(DB::raw('COUNT(id) as jml_kegiatan, AVG(jml_realisasi / jml_target * 100) as avg_kuantitas, AVG(nilai_kegiatan) as avg_kualitas, SUM(angka_kredit) AS sum_angka_kredit'))
                ->where('ckp_id', $ckp_lama->id)
                ->groupBy('ckp_id')
                ->first();

            if ($hitung->avg_kualitas == null) {
                $q->update(
                    [
                        'jml_kegiatan' => $hitung->jml_kegiatan,
                        'avg_kuantitas' => $hitung->avg_kuantitas,
                        'angka_kredit' => $hitung->sum_angka_kredit,
                    ]
                );
            } else {
                $q->update(
                    [
                        'jml_kegiatan' => $hitung->jml_kegiatan,
                        'avg_kuantitas' => $hitung->avg_kuantitas,
                        'avg_kualitas' => $hitung->avg_kualitas,
                        'nilai_akhir' => ($hitung->avg_kuantitas + $hitung->avg_kualitas) / 2,
                        'angka_kredit' => $hitung->sum_angka_kredit,
                    ]
                );
            }
        }

        alert()->success('Sukses', 'ckp berhasil diinput');
        return redirect()->route('ckp.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $ckp = Ckp::where('id', $id)->first();
        $kegiatan = Kegiatan::where('ckp_id', $id)
            ->orderBy('urut')
            ->get();
        return view('ckp.show', [
            "title" => "Lihat CKP",
            "route_" => "kegiatan",
            "ckp" => $ckp,
            "kegiatan" => $kegiatan,
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
        $ckp = Ckp::where('id', $id)->first();
        if ((int)$ckp->status > 1) {
            alert()->error('Nakal yaa', 'CKP yang telah diajukan tidak bisa diubah');
            return redirect()->route('ckp.index');
        }
        $kegiatan = Kegiatan::where('ckp_id', $id)
            ->get();
        $tim = Tim::all();
        $butir = Kredit::all(['id', 'kode_perka', 'name', 'kegiatan', 'satuan']);
        return view('ckp.edit', [
            "title" => "Edit CKP",
            "ckp" => $ckp,
            "kegiatan" => $kegiatan,
            "tim" => $tim,
            "butir" => $butir
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
        Kegiatan::where('ckp_id', $id)->delete();
        $jml_kegiatan = count($request->kegiatan);
        for ($i = 0; $i < $jml_kegiatan; $i++) {
            $kegiatan = new Kegiatan();
            $kegiatan->ckp_id = $id;
            $kegiatan->name = $request->kegiatan[$i];
            $kegiatan->tim_id = $request->tim_id[$i];
            $kegiatan->tgl_mulai = $request->tgl_mulai[$i];
            $kegiatan->tgl_selesai = $request->tgl_selesai[$i];
            $kegiatan->satuan = $request->satuan[$i];
            $kegiatan->jml_target = $request->jml_target[$i];
            $kegiatan->jml_realisasi = $request->jml_realisasi[$i];
            if ((int)$request->kredit_id[$i] > 0) {
                $kegiatan->kredit_id = $request->kredit_id[$i];
            }
            $kegiatan->keterangan = $request->keterangan[$i];
            $kegiatan->angka_kredit = $request->angka_kredit[$i];
            $kegiatan->save();
        }
        // hitung nilai
        $hitung = DB::table('kegiatans')
            ->select(DB::raw('AVG(jml_realisasi / jml_target * 100) as avg_kuantitas, AVG(nilai_kegiatan) as avg_kualitas, SUM(angka_kredit) AS sum_angka_kredit'))
            ->where('ckp_id', $id)
            ->groupBy('ckp_id')
            ->first();

        $validated = $request->validate([
            'bulan' => 'required',
        ]);
        $bulan = Carbon::createFromFormat('Y-m-d', $validated['bulan'] . '-01')->format('m');
        $tahun = Carbon::createFromFormat('Y-m-d', $validated['bulan'] . '-01')->format('Y');
        // sementara, nanti where user id
        if ($hitung->avg_kualitas == null) {
            Ckp::where('bulan', $bulan)
                ->where('tahun', $tahun)
                ->update(
                    [
                        'jml_kegiatan' => $jml_kegiatan,
                        'avg_kuantitas' => $hitung->avg_kuantitas,
                        'angka_kredit' => $hitung->sum_angka_kredit,
                    ]
                );
        } else {
            Ckp::where('bulan', $bulan)
                ->where('tahun', $tahun)
                ->update(
                    [
                        'jml_kegiatan' => $jml_kegiatan,
                        'avg_kuantitas' => $hitung->avg_kuantitas,
                        'avg_kualitas' => $hitung->avg_kualitas,
                        'nilai_akhir' => ($hitung->avg_kuantitas + $hitung->avg_kualitas) / 2,
                        'angka_kredit' => $hitung->sum_angka_kredit,
                    ]
                );
        }


        alert()->success('Sukses', 'ckp berhasil diedit');
        return redirect()->route('ckp.index');
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

    public function softDelete(Request $request)
    {
        $id = $request->value_id;
        $res = Ckp::where('id', $id)->update(
            ['is_delete' => '1']
        );
        if ($res) {
            alert()->success('Sukses', 'Berhasil menghapus CKP');
        } else {
            alert()->error('ERROR', 'Gagal menghapus CKP');
        }
        return redirect()->route('ckp.index');
    }

    public function ajukan(Request $request)
    {

        $ckp_id = $request->ckp_id;
        Ckp::where('id', $ckp_id)
            ->update(
                [
                    'status' => '2'
                ]
            );

        alert()->success('Sukses', 'ckp berhasil diajukan');
        return redirect()->route('ckp.index');
    }

    public function showCatatan($id)
    {
        $catatan = DB::table('catatan_ckps')
            ->leftjoin('users', 'catatan_ckps.user_id', '=', 'users.id')
            ->select('catatan_ckps.catatan as catatan', 'users.name as name')
            ->where('catatan_ckps.ckp_id', $id)
            ->get();
        return response()->json([
            'catatan' => $catatan
        ]);
    }

    public function export($id)
    {
        $style1 = array(
            'borders' => array(
                'outline' => array(
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                    'color' => array('argb' => '000'),
                ),
            ),
            'font' => array('bold' => true),
            'alignment' => array(
                'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER
            ),

        );

        $spreadsheet = new Spreadsheet();
        $spreadsheet->getActiveSheet()->getPageSetup()->setOrientation(\PhpOffice\PhpSpreadsheet\Worksheet\PageSetup::ORIENTATION_LANDSCAPE);
        $spreadsheet->getActiveSheet()->getPageSetup()->setPaperSize(\PhpOffice\PhpSpreadsheet\Worksheet\PageSetup::PAPERSIZE_A4);
        $spreadsheet->getProperties()->setCreator('')
            ->setLastModifiedBy('')
            ->setTitle('')
            ->setSubject('')
            ->setDescription('');


        $spreadsheet->getActiveSheet()->getStyle('A')->getAlignment()->setWrapText(true);
        $spreadsheet->getActiveSheet()->getRowDimension(1)->setRowHeight(-1);
        foreach ($spreadsheet->getActiveSheet()->getRowDimensions() as $rowID) {
            $rowID->setRowHeight(-1);
        }
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->getColumnDimension('A')->setWidth(50);




        $sheet->setCellValue('A1', 'Hello World AKUADA ADAKADAJADA JADAD AJADAAJ ADAADADAJAJAJJAJA ADAD AAJ ADAD AJAJ ADA DJAJDA AJ!');
        $sheet->getStyle('A1')->applyFromArray($style1);

        $sheet->mergeCells('A15:D15');
        $sheet->getStyle('A15')->applyFromArray($style1);
        $drawing = new \PhpOffice\PhpSpreadsheet\Worksheet\Drawing();
        $drawing->setName('TTD');
        $drawing->setDescription('TTD');
        $drawing->setPath('ttd/TTD.jpeg'); // put your path and image here
        $drawing->setCoordinates('A15');
        $drawing->setHeight(100);
        $drawing->setWorksheet($spreadsheet->getActiveSheet());
        $writer = new Mpdf($spreadsheet);
        $writer = IOFactory::createWriter($spreadsheet, 'Mpdf');
        $response =  new StreamedResponse(
            function () use ($writer) {
                $writer->save('php://output');
            }
        );


        $response->headers->set('Content-Type', 'application/pdf');
        $response->headers->set('Content-Disposition', 'attachment;filename="ExportScan.pdf"');
        // $response->headers->set('Content-Type', 'application/application/vnd.ms-excel');
        // $response->headers->set('Content-Disposition', 'attachment;filename="ExportScan.xlsx"');
        $response->headers->set('Cache-Control', 'max-age=0');
        return $response;
    }
}
