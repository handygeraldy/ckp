<?php

namespace App\Http\Controllers\ckp;

use Carbon\Carbon;
use App\Models\Kredit;
use App\Models\ckp\Ckp;
use App\Models\ckp\Kegiatan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Models\PeriodeTim;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Reader\Xlsx;
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
        $tim = PeriodeTim::with(['tim'])->get();
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
            // sumif
            $jml_kualitas = array_sum(array_map(function ($a) {
                if ($a != -1) {
                    return $a;
                } else {
                    return 0;
                }
            }, $request->nilai_kegiatan));
            if ($jml_kualitas > 0) {
                $ckp->avg_kualitas = $jml_kualitas / $jml_kegiatan;
                $ckp->nilai_akhir = ($ckp->avg_kuantitas + $ckp->avg_kualitas) / 2;
            }
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
                if ($request->nilai_kegiatan[$i] != '-1') {
                    $kegiatan->nilai_kegiatan = $request->nilai_kegiatan[$i];
                }
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
                if ($request->nilai_kegiatan[$i] != '-1') {
                    $kegiatan->nilai_kegiatan = $request->nilai_kegiatan[$i];
                }
                $kegiatan->save();
            }
            // hitung nilai
            $hitung = DB::table('kegiatans')
                ->select(DB::raw('COUNT(id) as jml_kegiatan, AVG(jml_realisasi / jml_target * 100) as avg_kuantitas, SUM(nilai_kegiatan) as sum_kualitas, SUM(angka_kredit) AS sum_angka_kredit'))
                ->where('ckp_id', $ckp_lama->id)
                ->groupBy('ckp_id')
                ->first();

            if ($hitung->sum_kualitas == null) {
                $q->update(
                    [
                        'jml_kegiatan' => $hitung->jml_kegiatan,
                        'avg_kuantitas' => $hitung->avg_kuantitas,
                        'angka_kredit' => $hitung->sum_angka_kredit,
                    ]
                );
            } else {
                $avg_kualitas = $hitung->sum_kualitas / $hitung->jml_kegiatan;
                $q->update(
                    [
                        'jml_kegiatan' => $hitung->jml_kegiatan,
                        'avg_kuantitas' => $hitung->avg_kuantitas,
                        'avg_kualitas' => $avg_kualitas,
                        'nilai_akhir' => ($hitung->avg_kuantitas + $avg_kualitas) / 2,
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
            ->orderBy('urut')
            ->get();
        $tim = PeriodeTim::with(['tim'])->get();
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
            if ($request->nilai_kegiatan[$i] != '-1') {
                $kegiatan->nilai_kegiatan = $request->nilai_kegiatan[$i];
            }
            $kegiatan->save();
        }
        // hitung nilai
        $hitung = DB::table('kegiatans')
            ->select(DB::raw('AVG(jml_realisasi / jml_target * 100) as avg_kuantitas, SUM(nilai_kegiatan) as sum_kualitas, SUM(angka_kredit) AS sum_angka_kredit'))
            ->where('ckp_id', $id)
            ->groupBy('ckp_id')
            ->first();

        $validated = $request->validate([
            'bulan' => 'required',
        ]);
        $bulan = Carbon::createFromFormat('Y-m-d', $validated['bulan'] . '-01')->format('m');
        $tahun = Carbon::createFromFormat('Y-m-d', $validated['bulan'] . '-01')->format('Y');
        // sementara, nanti where user id
        if ($hitung->sum_kualitas == null) {
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
            $avg_kualitas = $hitung->sum_kualitas / $jml_kegiatan;
            Ckp::where('bulan', $bulan)
                ->where('tahun', $tahun)
                ->update(
                    [
                        'jml_kegiatan' => $jml_kegiatan,
                        'avg_kuantitas' => $hitung->avg_kuantitas,
                        'avg_kualitas' => $avg_kualitas,
                        'nilai_akhir' => ($hitung->avg_kuantitas + $avg_kualitas) / 2,
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
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THICK,
                    'color' => array('argb' => '000'),
                ),
            ),
            'font' => array('bold' => true),
            'alignment' => array(
                'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER
            ),

        );

        $style_no_border = array(
            'font' => array(
                'bold' => true,
                'size' => 14
        ),
            'alignment' => array(
                'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER
            ),
        );

        $style_col_header = array(
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

        $style_no_header = array(
            'borders' => array(
                'outline' => array(
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                    'color' => array('argb' => '000'),
                ),
            ),
            'alignment' => array(
                'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER
            ),

        );
        $style_kegiatan = array(
            'borders' => array(
                'left' => array(
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                    'color' => array('argb' => '000'),
                ),
                'right' => array(
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                    'color' => array('argb' => '000'),
                ),
            ),
            'alignment' => array(
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

        $spreadsheet->getDefaultStyle()->getFont()->setName('Segoe UI');
        $spreadsheet->getDefaultStyle()->getFont()->setSize(10);
        $spreadsheet->getActiveSheet()->getStyle('B')->getAlignment()->setWrapText(true);
        $spreadsheet->getActiveSheet()->getStyle('D')->getAlignment()->setWrapText(true);
        $spreadsheet->getActiveSheet()->getStyle('H9')->getAlignment()->setWrapText(true);
        $spreadsheet->getActiveSheet()->getStyle('I9')->getAlignment()->setWrapText(true);
        $spreadsheet->getActiveSheet()->getStyle('J9')->getAlignment()->setWrapText(true);
        
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->getColumnDimension('A')->setWidth(5);
        $sheet->getColumnDimension('B')->setWidth(30);
        $sheet->getColumnDimension('C')->setWidth(30);
        $sheet->getColumnDimension('D')->setWidth(15);
        $sheet->getColumnDimension('E')->setWidth(15);
        $sheet->getColumnDimension('F')->setWidth(15);
        $sheet->getColumnDimension('G')->setWidth(15);
        $sheet->getColumnDimension('H')->setWidth(15);
        $sheet->getColumnDimension('I')->setWidth(12);
        $sheet->getColumnDimension('J')->setWidth(10);
        $sheet->getColumnDimension('K')->setWidth(20);



        // GET DATA
        $numrow = 1;
        
        $sheet->setCellValue('K' . $numrow, 'CKP-R');
        $sheet->getStyle('K' . $numrow)->applyFromArray($style1);
        // $sheet->getStyle('K1')->getFont()->setName('Arrus Blk BT');
        $sheet->getStyle('K' . $numrow)->getFont()->setSize(16);
        $numrow++;
        $sheet->mergeCells('A' . $numrow . ':K' . $numrow);
        $sheet->setCellValue('A' . $numrow, 'CAPAIAN KINERJA PEGAWAI TAHUN ??');
        $sheet->getStyle('A' . $numrow)->applyFromArray($style_no_border);
        $sheet->getStyle('A' . $numrow)->getFont()->setSize(14);

        $numrow++;
        $numrow++;
        $sheet->mergeCells('A' . $numrow . ':B' . $numrow);
        $sheet->setCellValue('A' . $numrow, 'Satuan Organisasi');
        $sheet->setCellValue('C' . $numrow, ':');

        $numrow++;
        $sheet->mergeCells('A' . $numrow . ':B' . $numrow);
        $sheet->setCellValue('A' . $numrow, 'Nama');
        $sheet->setCellValue('C' . $numrow, ':');

        $numrow++;
        $sheet->mergeCells('A' . $numrow . ':B' . $numrow);
        $sheet->setCellValue('A' . $numrow, 'Jabatan');
        $sheet->setCellValue('C' . $numrow, ':');

        $numrow++;
        $sheet->mergeCells('A' . $numrow . ':B' . $numrow);
        $sheet->setCellValue('A' . $numrow, 'Periode');
        $sheet->setCellValue('C' . $numrow, ':');

        $numrow++;
        $numrow++;
        $sheet->mergeCells('A' . $numrow . ':A' . strval($numrow + 1));
        $sheet->setCellValue('A' . $numrow, 'No');
        
        $sheet->mergeCells('B' . $numrow . ':C' . strval($numrow + 1));
        $sheet->setCellValue('B' . $numrow, 'Uraian Kegiatan');
        
        $sheet->mergeCells('D' . $numrow . ':D' . strval($numrow + 1));
        $sheet->setCellValue('D' . $numrow, 'Satuan');
        
        $sheet->mergeCells('E' . $numrow . ':G' . $numrow );
        $sheet->setCellValue('E' . $numrow, 'Kuantitas');
        
        $sheet->mergeCells('H' . $numrow . ':H' . strval($numrow + 1));
        $sheet->setCellValue('H' . $numrow, "Tingkat Kualitas\n(%)");
        $sheet->getStyle('H' . $numrow)->getAlignment()->setWrapText(true);
        
        $sheet->mergeCells('I' . $numrow . ':I' . strval($numrow + 1));
        $sheet->setCellValue('I' . $numrow, "Kode\nButir\nKegiatan");

        $sheet->mergeCells('J' . $numrow . ':J' . strval($numrow + 1));
        $sheet->setCellValue('J' . $numrow, 'Angka Kredit');
        
        $sheet->mergeCells('K' . $numrow . ':K' . strval($numrow + 1));
        $sheet->setCellValue('K' . $numrow, 'Keterangan');
        $sheet->getStyle('A' . $numrow)->applyFromArray($style_col_header);
        $sheet->getStyle('B' . $numrow)->applyFromArray($style_col_header);
        $sheet->getStyle('C' . $numrow)->applyFromArray($style_col_header);
        $sheet->getStyle('D' . $numrow)->applyFromArray($style_col_header);
        $sheet->getStyle('E' . $numrow)->applyFromArray($style_col_header);
        $sheet->getStyle('F' . $numrow)->applyFromArray($style_col_header);
        $sheet->getStyle('G' . $numrow)->applyFromArray($style_col_header);
        $sheet->getStyle('H' . $numrow)->applyFromArray($style_col_header);
        $sheet->getStyle('I' . $numrow)->applyFromArray($style_col_header);
        $sheet->getStyle('J' . $numrow)->applyFromArray($style_col_header);
        $sheet->getStyle('K' . $numrow)->applyFromArray($style_col_header);
        
        $numrow++;
        $sheet->setCellValue('E' . $numrow, 'Target');
        $sheet->setCellValue('F' . $numrow, 'Realisasi');
        $sheet->setCellValue('G' . $numrow, '%');
        $sheet->getStyle('E' . $numrow)->applyFromArray($style_col_header);
        $sheet->getStyle('F' . $numrow)->applyFromArray($style_col_header);
        $sheet->getStyle('G' . $numrow)->applyFromArray($style_col_header);

        $numrow++;
        $sheet->setCellValue('A' . $numrow, '(1)');
        $sheet->mergeCells('B' . $numrow . ':C' . $numrow);
        $sheet->setCellValue('B' . $numrow, '(2)');
        $sheet->setCellValue('D' . $numrow, '(3)');
        $sheet->setCellValue('E' . $numrow, '(4)');
        $sheet->setCellValue('F' . $numrow, '(5)');
        $sheet->setCellValue('G' . $numrow, '(6)');
        $sheet->setCellValue('H' . $numrow, "(7)");
        $sheet->setCellValue('I' . $numrow, "(8)");
        $sheet->setCellValue('J' . $numrow, '(9');
        $sheet->setCellValue('K' . $numrow, '(10');
        $sheet->getStyle('A' . $numrow)->applyFromArray($style_no_header);
        $sheet->getStyle('B' . $numrow)->applyFromArray($style_no_header);
        $sheet->getStyle('C' . $numrow)->applyFromArray($style_no_header);
        $sheet->getStyle('D' . $numrow)->applyFromArray($style_no_header);
        $sheet->getStyle('E' . $numrow)->applyFromArray($style_no_header);
        $sheet->getStyle('F' . $numrow)->applyFromArray($style_no_header);
        $sheet->getStyle('G' . $numrow)->applyFromArray($style_no_header);
        $sheet->getStyle('H' . $numrow)->applyFromArray($style_no_header);
        $sheet->getStyle('I' . $numrow)->applyFromArray($style_no_header);
        $sheet->getStyle('J' . $numrow)->applyFromArray($style_no_header);
        $sheet->getStyle('K' . $numrow)->applyFromArray($style_no_header);

        $numrow++;
        
        $sheet->mergeCells('A' . $numrow . ':C' . $numrow);
        $sheet->setCellValue('A' . $numrow, 'UTAMA');
        $sheet->getStyle('A' . $numrow)->getFont()->setBold(true);
        $sheet->getStyle('A' . $numrow)->applyFromArray($style_kegiatan);
        $sheet->getStyle('B' . $numrow)->applyFromArray($style_kegiatan);
        $sheet->getStyle('C' . $numrow)->applyFromArray($style_kegiatan);
        $sheet->getStyle('D' . $numrow)->applyFromArray($style_kegiatan);
        $sheet->getStyle('E' . $numrow)->applyFromArray($style_kegiatan);
        $sheet->getStyle('F' . $numrow)->applyFromArray($style_kegiatan);
        $sheet->getStyle('G' . $numrow)->applyFromArray($style_kegiatan);
        $sheet->getStyle('H' . $numrow)->applyFromArray($style_kegiatan);
        $sheet->getStyle('I' . $numrow)->applyFromArray($style_kegiatan);
        $sheet->getStyle('J' . $numrow)->applyFromArray($style_kegiatan);
        $sheet->getStyle('K' . $numrow)->applyFromArray($style_kegiatan);

        // LOOP kegiatan
        $sheet->setCellValue('A' . $numrow, '1');
        $sheet->mergeCells('B' . $numrow . ':C' . $numrow);
        $sheet->setCellValue('B' . $numrow, '1');
        $sheet->setCellValue('D' . $numrow, '1');
        $sheet->setCellValue('E' . $numrow, '1');
        $sheet->setCellValue('F' . $numrow, '1');
        $sheet->setCellValue('G' . $numrow, '1');
        $sheet->setCellValue('H' . $numrow, '1');
        $sheet->setCellValue('I' . $numrow, '1');
        $sheet->setCellValue('J' . $numrow, '1');
        $sheet->setCellValue('K' . $numrow, '1');

        $sheet->getStyle('A' . $numrow)->applyFromArray($style_kegiatan);
        $sheet->getStyle('B' . $numrow)->applyFromArray($style_kegiatan);
        $sheet->getStyle('C' . $numrow)->applyFromArray($style_kegiatan);
        $sheet->getStyle('D' . $numrow)->applyFromArray($style_kegiatan);
        $sheet->getStyle('E' . $numrow)->applyFromArray($style_kegiatan);
        $sheet->getStyle('F' . $numrow)->applyFromArray($style_kegiatan);
        $sheet->getStyle('G' . $numrow)->applyFromArray($style_kegiatan);
        $sheet->getStyle('H' . $numrow)->applyFromArray($style_kegiatan);
        $sheet->getStyle('I' . $numrow)->applyFromArray($style_kegiatan);
        $sheet->getStyle('J' . $numrow)->applyFromArray($style_kegiatan);
        $sheet->getStyle('K' . $numrow)->applyFromArray($style_kegiatan);

        $drawing = new \PhpOffice\PhpSpreadsheet\Worksheet\Drawing();
        $drawing->setName('TTD');
        $drawing->setDescription('TTD');
        $drawing->setPath('ttd/TTD.jpeg'); // put your path and image here
        $drawing->setCoordinates('B15');
        $drawing->setHeight(100);
        $drawing->setWorksheet($spreadsheet->getActiveSheet());

        
        foreach (range(9,$sheet->getHighestRow()) as $row) {
            $spreadsheet->getActiveSheet()->getRowDimension($row)->setRowHeight(-1);
        }

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
