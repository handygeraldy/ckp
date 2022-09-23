<?php

namespace App\Http\Controllers\ckp;

use Carbon\Carbon;
use App\Models\Kredit;
use App\Models\ckp\Ckp;
use App\Models\PeriodeTim;
use App\Models\ckp\Kegiatan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Worksheet\Drawing;
use Symfony\Component\HttpFoundation\StreamedResponse;


class CkpController extends Controller
{
    public function index()
    {
        $dt = Ckp::where('is_delete', '!=', '1')
            ->where('user_id',Auth::user()->id)
            ->orderBy('tahun', 'desc')
            ->orderBy('bulan', 'desc')
            ->get();
        return view('ckp.index', [
            'dt' => $dt,
            'title' => 'CKP Saya',
            'route_' => 'ckp',
        ]);
    }

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

        $q = DB::table('ckps')
            ->where('bulan', $bulan)
            ->where('tahun', $tahun)
            ->where('user_id',Auth::user()->id);
        $ckp_lama = $q->first();
        if ($ckp_lama == null) {
            $ckp = new Ckp();
            $ckp->bulan = $bulan;
            $ckp->tahun = $tahun;
            $ckp->satker_id = Auth::user()->satker_id;
            $ckp->user_id = Auth::user()->id;
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
        $kegiatan = DB::table('kegiatans')
        ->leftjoin('kredits', 'kegiatans.kredit_id', 'kredits.id')
        ->select(
            'kegiatans.id as id',
            'kegiatans.ckp_id as ckp_id',
            'kegiatans.kegiatan_tim_id as kegiatan_tim_id',
            'kegiatans.name as name',
            'kegiatans.jenis as jenis',
            'kegiatans.tgl_mulai as tgl_mulai',
            'kegiatans.tgl_selesai as tgl_selesai',
            'kegiatans.satuan as satuan',
            'kegiatans.jml_target as jml_target',
            'kegiatans.jml_realisasi as jml_realisasi',
            'kegiatans.nilai_kegiatan as nilai_kegiatan',
            'kegiatans.angka_kredit as angka_kredit',
            'kegiatans.keterangan as keterangan',
            'kredits.kode_perka as kode_perka',
        )
            ->where('ckp_id', $id)
            ->orderBy('urut')
            ->get();

        $kegiatan_utama = $kegiatan->filter(function ($k) {
            return $k->jenis == 'utama';
        });
        $kegiatan_tambahan = $kegiatan->filter(function ($k) {
            return $k->jenis == 'tambahan';
        });
        return view('ckp.show', [
            "title" => "Lihat CKP",
            "route_" => "kegiatan",
            "ckp" => $ckp,
            "kegiatan_utama" => $kegiatan_utama,
            "kegiatan_tambahan" => $kegiatan_tambahan,
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
            alert()->error('Nakal ya', 'CKP yang telah diajukan tidak bisa diubah');
            return redirect()->route('ckp.index');
        }
        if ($ckp->user_id != Auth::user()->id){
            alert()->error('Nakal ya', 'Ini bukan CKP anda');
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
        if ($hitung->sum_kualitas == null) {
            Ckp::where('id', $id)
                ->update(
                    [
                        'jml_kegiatan' => $jml_kegiatan,
                        'avg_kuantitas' => $hitung->avg_kuantitas,
                        'angka_kredit' => $hitung->sum_angka_kredit,
                    ]
                );
        } else {
            $avg_kualitas = $hitung->sum_kualitas / $jml_kegiatan;
            Ckp::where('id', $id)
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
            ->leftjoin('users', 'catatan_ckps.user_id', 'users.id')
            ->select('catatan_ckps.catatan as catatan', 'users.name as name')
            ->where('catatan_ckps.ckp_id', $id)
            ->get();
        return response()->json([
            'catatan' => $catatan
        ]);
    }

    public function export($id)
    {
        // GET DATA
        $ckp = DB::table('ckps')
            ->leftjoin('users', 'ckps.user_id', 'users.id')
            ->leftjoin('satkers', 'ckps.satker_id', 'satkers.id')
            ->leftjoin('fungsionals', 'users.fungsional_id', 'fungsionals.id')
            ->leftjoin('golongans', 'users.golongan_id', 'golongans.id')
            ->select(
                'ckps.tahun as tahun',
                'ckps.bulan as bulan',
                'ckps.avg_kuantitas as avg_kuantitas',
                'ckps.avg_kualitas as avg_kualitas',
                'ckps.nilai_akhir as nilai_akhir',
                'ckps.angka_kredit as angka_kredit',
                'satkers.name as satker_name',
                'users.name as user_name',
                'users.nip as nip',
                'users.ttd as ttd',
                'fungsionals.name as fungsional_name',
                'golongans.name as golongan_name',
            )
            ->where('ckps.id', $id)
            ->first();
        $tgl_akhir = Carbon::createFromFormat('m-d', "$ckp->bulan-1")->addMonth()->format('m');

        $kegiatan = DB::table('kegiatans')
        ->leftjoin('kredits', 'kegiatans.kredit_id', 'kredits.id')
        ->select(
            'kegiatans.name as name',
            'kegiatans.jenis as jenis',
            'kegiatans.tgl_mulai as tgl_mulai',
            'kegiatans.tgl_selesai as tgl_selesai',
            'kegiatans.satuan as satuan',
            'kegiatans.jml_target as jml_target',
            'kegiatans.jml_realisasi as jml_realisasi',
            'kegiatans.nilai_kegiatan as nilai_kegiatan',
            'kegiatans.angka_kredit as angka_kredit',
            'kegiatans.keterangan as keterangan',
            'kredits.kode_perka as kode_perka',
        )
            ->where('ckp_id', $id)
            ->orderBy('urut')
            ->get();

        $kegiatan_utama = $kegiatan->filter(function ($k) {
            return $k->jenis == 'utama';
        });
        $kegiatan_tambahan = $kegiatan->filter(function ($k) {
            return $k->jenis == 'tambahan';
        });

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
        $style_kegiatan_left = array(
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
                'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT,
                'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_TOP
            ),
        );

        $style_kegiatan_center = array(
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
                'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_TOP
            ),
        );

        $style_gray = array(
            'borders' => array(
                'outline' => array(
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                    'color' => array('argb' => '000'),
                ),
            ),
            'fill' => array(
                'fillType' => Fill::FILL_SOLID,
                'startColor' => array('argb' => '808080')
            )

        );

        $style_ttd = array(
            'alignment' => array(
                'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER
            ),

        );

        $spreadsheet = new Spreadsheet();
        $spreadsheet->getActiveSheet()->getPageSetup()->setOrientation(\PhpOffice\PhpSpreadsheet\Worksheet\PageSetup::ORIENTATION_LANDSCAPE);
        $spreadsheet->getActiveSheet()->getPageSetup()->setPaperSize(\PhpOffice\PhpSpreadsheet\Worksheet\PageSetup::PAPERSIZE_A4);
        $spreadsheet->getProperties()->setCreator('Handy')
            ->setLastModifiedBy('Handy')
            ->setTitle('CKP')
            ->setSubject('CKP')
            ->setDescription('CKP');

        $spreadsheet->getDefaultStyle()->getFont()->setName('Segoe UI');
        $spreadsheet->getDefaultStyle()->getFont()->setSize(10);
        $spreadsheet->getActiveSheet()->getPageSetup()->setRowsToRepeatAtTopByStartAndEnd(9,11);

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

        $numrow = 1;

        $sheet->setCellValue('K' . $numrow, 'CKP-R');
        $sheet->getStyle('K' . $numrow)->applyFromArray($style1);
        // $sheet->getStyle('K1')->getFont()->setName('Arrus Blk BT');
        $sheet->getStyle('K' . $numrow)->getFont()->setSize(16);
        $numrow++;
        $sheet->mergeCells('A' . $numrow . ':K' . $numrow);
        $sheet->setCellValue('A' . $numrow, 'CAPAIAN KINERJA PEGAWAI TAHUN ' . $ckp->tahun);
        $sheet->getStyle('A' . $numrow)->applyFromArray($style_no_border);
        $sheet->getStyle('A' . $numrow)->getFont()->setSize(14);

        $numrow++;
        $numrow++;
        $sheet->mergeCells('A' . $numrow . ':B' . $numrow);
        $sheet->setCellValue('A' . $numrow, 'Satuan Organisasi');
        $sheet->mergeCells('C' . $numrow . ':K' . $numrow);
        $sheet->setCellValue('C' . $numrow, ': ' . $ckp->satker_name);

        $numrow++;
        $sheet->mergeCells('A' . $numrow . ':B' . $numrow);
        $sheet->setCellValue('A' . $numrow, 'Nama');
        $sheet->mergeCells('C' . $numrow . ':K' . $numrow);
        $sheet->setCellValue('C' . $numrow, ': ' . $ckp->user_name);

        $numrow++;
        $sheet->mergeCells('A' . $numrow . ':B' . $numrow);
        $sheet->setCellValue('A' . $numrow, 'Jabatan');
        $sheet->mergeCells('C' . $numrow . ':K' . $numrow);
        $sheet->setCellValue('C' . $numrow, ': ' . $ckp->fungsional_name);

        $numrow++;
        $sheet->mergeCells('A' . $numrow . ':B' . $numrow);
        $sheet->setCellValue('A' . $numrow, 'Periode');
        $sheet->mergeCells('C' . $numrow . ':K' . $numrow);
        $sheet->setCellValue('C' . $numrow, ': ' . getMonth($ckp->bulan) . ' ' . $ckp->tahun);

        $numrow++;
        $numrow++;
        $sheet->mergeCells('A' . $numrow . ':A' . strval($numrow + 1));
        $sheet->setCellValue('A' . $numrow, 'No');

        $sheet->mergeCells('B' . $numrow . ':C' . strval($numrow + 1));
        $sheet->setCellValue('B' . $numrow, 'Uraian Kegiatan');

        $sheet->mergeCells('D' . $numrow . ':D' . strval($numrow + 1));
        $sheet->setCellValue('D' . $numrow, 'Satuan');

        $sheet->mergeCells('E' . $numrow . ':G' . $numrow);
        $sheet->setCellValue('E' . $numrow, 'Kuantitas');

        $sheet->mergeCells('H' . $numrow . ':H' . strval($numrow + 1));
        $sheet->setCellValue('H' . $numrow, "Tingkat Kualitas\n(%)");

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
        $sheet->getStyle('A' . $numrow)->applyFromArray($style_kegiatan_left);
        $sheet->getStyle('B' . $numrow)->applyFromArray($style_kegiatan_left);
        $sheet->getStyle('C' . $numrow)->applyFromArray($style_kegiatan_left);
        $sheet->getStyle('D' . $numrow)->applyFromArray($style_kegiatan_left);
        $sheet->getStyle('E' . $numrow)->applyFromArray($style_kegiatan_left);
        $sheet->getStyle('F' . $numrow)->applyFromArray($style_kegiatan_left);
        $sheet->getStyle('G' . $numrow)->applyFromArray($style_kegiatan_left);
        $sheet->getStyle('H' . $numrow)->applyFromArray($style_kegiatan_left);
        $sheet->getStyle('I' . $numrow)->applyFromArray($style_kegiatan_left);
        $sheet->getStyle('J' . $numrow)->applyFromArray($style_kegiatan_left);
        $sheet->getStyle('K' . $numrow)->applyFromArray($style_kegiatan_left);

        // LOOP kegiatan
        $no_baris = 1;
        foreach ($kegiatan_utama as $utama){
            $numrow++;
            $sheet->setCellValue('A' . $numrow, $no_baris);
            $sheet->mergeCells('B' . $numrow . ':C' . $numrow);
            $sheet->setCellValue('B' . $numrow, $utama->name);
            $sheet->setCellValue('D' . $numrow, $utama->satuan);
            $sheet->setCellValue('E' . $numrow, $utama->jml_target);
            $sheet->setCellValue('F' . $numrow, $utama->jml_realisasi);
            $sheet->setCellValue('G' . $numrow, $utama->jml_realisasi / $utama->jml_target * 100);
            $sheet->setCellValue('H' . $numrow, $utama->nilai_kegiatan);
            $sheet->setCellValue('I' . $numrow, $utama->kode_perka);
            $sheet->setCellValue('J' . $numrow, $utama->angka_kredit != 0 ? $utama->angka_kredit : '');
            $sheet->setCellValue('K' . $numrow, $utama->keterangan);
    
            $sheet->getStyle('A' . $numrow)->applyFromArray($style_kegiatan_center);
            $sheet->getStyle('B' . $numrow)->applyFromArray($style_kegiatan_left);
            $sheet->getStyle('D' . $numrow)->applyFromArray($style_kegiatan_left);
            $sheet->getStyle('E' . $numrow)->applyFromArray($style_kegiatan_center);
            $sheet->getStyle('F' . $numrow)->applyFromArray($style_kegiatan_center);
            $sheet->getStyle('G' . $numrow)->applyFromArray($style_kegiatan_center);
            $sheet->getStyle('H' . $numrow)->applyFromArray($style_kegiatan_center);
            $sheet->getStyle('I' . $numrow)->applyFromArray($style_kegiatan_center);
            $sheet->getStyle('J' . $numrow)->applyFromArray($style_kegiatan_center);
            $sheet->getStyle('K' . $numrow)->applyFromArray($style_kegiatan_left);

            $sheet->getStyle('B' . $numrow)->getAlignment()->setIndent(1);
            $sheet->getStyle('D' . $numrow)->getAlignment()->setIndent(1);
            $sheet->getStyle('K' . $numrow)->getAlignment()->setIndent(1);
            $no_baris++;
        }
        
        $numrow++;
        $sheet->mergeCells('A' . $numrow . ':C' . $numrow);
        $sheet->setCellValue('A' . $numrow, 'TAMBAHAN');
        $sheet->getStyle('A' . $numrow)->getFont()->setBold(true);
        $sheet->getStyle('A' . $numrow)->applyFromArray($style_kegiatan_left);
        $sheet->getStyle('B' . $numrow)->applyFromArray($style_kegiatan_left);
        $sheet->getStyle('C' . $numrow)->applyFromArray($style_kegiatan_left);
        $sheet->getStyle('D' . $numrow)->applyFromArray($style_kegiatan_left);
        $sheet->getStyle('E' . $numrow)->applyFromArray($style_kegiatan_left);
        $sheet->getStyle('F' . $numrow)->applyFromArray($style_kegiatan_left);
        $sheet->getStyle('G' . $numrow)->applyFromArray($style_kegiatan_left);
        $sheet->getStyle('H' . $numrow)->applyFromArray($style_kegiatan_left);
        $sheet->getStyle('I' . $numrow)->applyFromArray($style_kegiatan_left);
        $sheet->getStyle('J' . $numrow)->applyFromArray($style_kegiatan_left);
        $sheet->getStyle('K' . $numrow)->applyFromArray($style_kegiatan_left);
       
        if ($kegiatan_tambahan->count()){
            $no_baris = 1;
            foreach ($kegiatan_tambahan as $tambahan){
                $numrow++;
                $sheet->setCellValue('A' . $numrow, $no_baris);
                $sheet->mergeCells('B' . $numrow . ':C' . $numrow);
                $sheet->setCellValue('B' . $numrow, $tambahan->name);
                $sheet->setCellValue('D' . $numrow, $tambahan->satuan);
                $sheet->setCellValue('E' . $numrow, $tambahan->jml_target);
                $sheet->setCellValue('F' . $numrow, $tambahan->jml_realisasi);
                $sheet->setCellValue('G' . $numrow, $tambahan->jml_realisasi / $tambahan->jml_target * 100);
                $sheet->setCellValue('H' . $numrow, $tambahan->nilai_kegiatan);
                $sheet->setCellValue('I' . $numrow, $tambahan->kode_perka);
                $sheet->setCellValue('J' . $numrow, $tambahan->angka_kredit != 0 ? $utama->angka_kredit : '');
                $sheet->setCellValue('K' . $numrow, $tambahan->keterangan);
        
                $sheet->getStyle('A' . $numrow)->applyFromArray($style_kegiatan_center);
                $sheet->getStyle('B' . $numrow)->applyFromArray($style_kegiatan_left);
                $sheet->getStyle('D' . $numrow)->applyFromArray($style_kegiatan_left);
                $sheet->getStyle('E' . $numrow)->applyFromArray($style_kegiatan_center);
                $sheet->getStyle('F' . $numrow)->applyFromArray($style_kegiatan_center);
                $sheet->getStyle('G' . $numrow)->applyFromArray($style_kegiatan_center);
                $sheet->getStyle('H' . $numrow)->applyFromArray($style_kegiatan_center);
                $sheet->getStyle('I' . $numrow)->applyFromArray($style_kegiatan_center);
                $sheet->getStyle('J' . $numrow)->applyFromArray($style_kegiatan_center);
                $sheet->getStyle('K' . $numrow)->applyFromArray($style_kegiatan_left);
    
                $sheet->getStyle('B' . $numrow)->getAlignment()->setIndent(1);
                $sheet->getStyle('D' . $numrow)->getAlignment()->setIndent(1);
                $sheet->getStyle('K' . $numrow)->getAlignment()->setIndent(1);
                $no_baris++;
            }
        } else {
            $numrow++;
            $sheet->mergeCells('B' . $numrow . ':C' . $numrow);
            $sheet->getStyle('A' . $numrow)->applyFromArray($style_kegiatan_center);
            $sheet->getStyle('B' . $numrow)->applyFromArray($style_kegiatan_left);
            $sheet->getStyle('D' . $numrow)->applyFromArray($style_kegiatan_left);
            $sheet->getStyle('E' . $numrow)->applyFromArray($style_kegiatan_center);
            $sheet->getStyle('F' . $numrow)->applyFromArray($style_kegiatan_center);
            $sheet->getStyle('G' . $numrow)->applyFromArray($style_kegiatan_center);
            $sheet->getStyle('H' . $numrow)->applyFromArray($style_kegiatan_center);
            $sheet->getStyle('I' . $numrow)->applyFromArray($style_kegiatan_center);
            $sheet->getStyle('J' . $numrow)->applyFromArray($style_kegiatan_center);
            $sheet->getStyle('K' . $numrow)->applyFromArray($style_kegiatan_left);
        }
        
        foreach (range(12, $numrow) as $row) {
            $sheet->getRowDimension($row)->setRowHeight(15);
        }

        // angka kredit
        $numrow++;
        $sheet->mergeCells('A' . $numrow . ':I' . $numrow);
        $sheet->setCellValue('J' . $numrow, number_format($ckp->angka_kredit, 2));
        $sheet->getStyle('J' . $numrow)->getNumberFormat()->setFormatCode('0.00'); 
        $sheet->getStyle('A' . $numrow)->applyFromArray($style_col_header);
        $sheet->getStyle('J' . $numrow)->applyFromArray($style_col_header);
        $sheet->getStyle('K' . $numrow)->applyFromArray($style_gray);
        $sheet->getRowDimension($numrow)->setRowHeight(18);

        // rata2
        $numrow++;
        $sheet->mergeCells('A' . $numrow . ':F' . $numrow);
        $sheet->setCellValue('A' . $numrow, "RATA-RATA");
        $sheet->setCellValue('G' . $numrow, number_format($ckp->avg_kuantitas, 2));
        $sheet->setCellValue('H' . $numrow, number_format($ckp->avg_kualitas, 2));
        $sheet->getStyle('G' . $numrow)->getNumberFormat()->setFormatCode('0.00'); 
        $sheet->getStyle('H' . $numrow)->getNumberFormat()->setFormatCode('0.00'); 
        $sheet->getStyle('A' . $numrow)->applyFromArray($style_col_header);
        $sheet->getStyle('G' . $numrow)->applyFromArray($style_col_header);
        $sheet->getStyle('H' . $numrow)->applyFromArray($style_col_header);
        $sheet->getStyle('I' . $numrow)->applyFromArray($style_col_header);
        $sheet->getStyle('J' . $numrow)->applyFromArray($style_gray);
        $sheet->getStyle('K' . $numrow)->applyFromArray($style_gray);
        $sheet->getRowDimension($numrow)->setRowHeight(18);

        // CKP
        $numrow++;
        $sheet->mergeCells('A' . $numrow . ':F' . $numrow);
        $sheet->setCellValue('A' . $numrow, "CAPAIAN KINERJA PEGAWAI (CKP)");
        $sheet->mergeCells('G' . $numrow . ':H' . $numrow);
        $sheet->setCellValue('G' . $numrow, number_format($ckp->nilai_akhir, 2));
        $sheet->getStyle('G' . $numrow)->getNumberFormat()->setFormatCode('0.00'); 
        $sheet->getStyle('A' . $numrow)->applyFromArray($style_col_header);
        $sheet->getStyle('G' . $numrow)->applyFromArray($style_col_header);
        $sheet->getStyle('I' . $numrow)->applyFromArray($style_col_header);
        $sheet->getStyle('J' . $numrow)->applyFromArray($style_gray);
        $sheet->getStyle('K' . $numrow)->applyFromArray($style_gray);
        $sheet->getRowDimension($numrow)->setRowHeight(18);

        $numrow++;
        $numrow++;
        $sheet->setCellValue('B' . $numrow, "Penilaian Kinerja");
        $sheet->getStyle('B' . $numrow)->getFont()->setBold(true);
        $numrow++;
        $sheet->setCellValue('B' . $numrow, "Tanggal: 1 " . getMonth($tgl_akhir) . ' ' . $ckp->tahun);

        $numrow++;
        $numrow++;
        $sheet->mergeCells('B' . $numrow . ':D' . $numrow);
        $sheet->mergeCells('F' . $numrow . ':H' . $numrow);
        $sheet->setCellValue('B' . $numrow, "Pegawai Yang Dinilai");
        $sheet->setCellValue('F' . $numrow, "Pejabat Penilai");
        $sheet->getStyle('B' . $numrow)->applyFromArray($style_ttd);
        $sheet->getStyle('F' . $numrow)->applyFromArray($style_ttd);

        $numrow++;
        $drawing = new Drawing();
        $drawing->setName('TTD Pegawai');
        $drawing->setDescription('TTD Pegawai');
        $drawing->setPath('storage/' . $ckp->ttd);
        $sheet->mergeCells('B' . $numrow . ':D' . $numrow);
        $drawing->setCoordinates('B' . $numrow);
        $drawing->setHeight(80);
        $drawing->setWorksheet($sheet);

        $drawing2 = new Drawing();
        $drawing2->setName('TTD Pejabat');
        $drawing2->setDescription('TTD Pejabat');
        $drawing2->setPath('storage/pak sarpono.png');
        $sheet->mergeCells('F' . $numrow . ':H' . $numrow);
        $drawing2->setCoordinates('F' . $numrow);
        $drawing2->setHeight(80);
        $drawing2->setWorksheet($sheet);
        $sheet->getStyle('B' . $numrow)->applyFromArray($style_ttd);
        $sheet->getStyle('F' . $numrow)->applyFromArray($style_ttd);
        $sheet->getRowDimension($numrow)->setRowHeight(80);

        $numrow++;
        $sheet->mergeCells('B' . $numrow . ':D' . $numrow);
        $sheet->mergeCells('F' . $numrow . ':H' . $numrow);
        $sheet->setCellValue('B' . $numrow, $ckp->user_name);
        $sheet->setCellValue('F' . $numrow, "Dr. Sarpono S.Si, M.Sc");
        $sheet->getStyle('B' . $numrow)->applyFromArray($style_ttd);
        $sheet->getStyle('F' . $numrow)->applyFromArray($style_ttd);

        $numrow++;
        $sheet->mergeCells('B' . $numrow . ':D' . $numrow);
        $sheet->mergeCells('F' . $numrow . ':H' . $numrow);
        $sheet->setCellValue('B' . $numrow, "NIP. " . $ckp->nip);
        $sheet->setCellValue('F' . $numrow, "NIP. 196908281992111001");
        $sheet->getStyle('B' . $numrow)->applyFromArray($style_ttd);
        $sheet->getStyle('F' . $numrow)->applyFromArray($style_ttd);

        // $writer = new \PhpOffice\PhpSpreadsheet\Writer\Pdf\Mpdf($spreadsheet);
        $writer = IOFactory::createWriter($spreadsheet, 'Mpdf');
        $response =  new StreamedResponse(
            function () use ($writer) {
                $writer->save('php://output');
            }
        );

        $filename = "3100_" . $ckp->bulan . $ckp->tahun . '_CKP_' . $ckp->user_name . '.pdf';
        $response->headers->set('Content-Type', 'application/pdf');
        $response->headers->set('Content-Disposition', 'attachment;filename=' . $filename );

        // $response->headers->set('Content-Type', 'application/application/vnd.ms-excel');
        // $response->headers->set('Content-Disposition', 'attachment;filename="ExportScan.xlsx"');
        $response->headers->set('Cache-Control', 'max-age=0');
        return $response;
    }
}
