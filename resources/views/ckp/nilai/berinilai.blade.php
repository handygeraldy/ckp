@extends('layouts.main')

@section('container')
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0">{{ $title }}</h1>
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('index') }}">Dashboard</a></li>
            <li class="breadcrumb-item text-gray-800">Nilai</li>
        </ol>
    </div>

    <div class="row">
        <div class="col-lg-12 mb-4">
            <div class="card">
                <form id="formNilai" action="{{ route('nilai.edit.post') }}" method="post">
                    @csrf
                    <input type="hidden" name="ckp_id" value="{{ $ckp->id }}">
                    <h4 class="text-center my-4"><b>CAPAIAN KINERJA PEGAWAI TAHUN
                            {{ $ckp->tahun }}</b></h4>
                    <div class="card-header">
                        <table id="CKP-header" class="table table-borderless">
                            <tbody>
                                <tr>
                                    <td width="20%">Satuan Organisasi</td>
                                    <td colspan="9">: {{ $ckp->satker->name }}</td>
                                </tr>
                                <tr>
                                    <td width="20%">Nama</td>
                                    <td colspan="9">: {{ $ckp->user->name }}</td>
                                </tr>
                                <tr>
                                    <td width="20%">Jabatan</td>
                                    <td colspan="9">: {{ $ckp->user->fungsional->name }}</td>
                                </tr>
                                <tr>
                                    <td width="20%">Periode</td>
                                    <td colspan="9">: {{ getMonth($ckp->bulan) . ' ' . $ckp->tahun }}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            @if ($kegiatan_utama->isEmpty() & $kegiatan_tambahan->isEmpty())
                                <table class="table">
                                    <thead>
                                        <tr>
                                            <th class="text-center">
                                                <i>Tidak ada kegiatan</i>
                                            </th>
                                        </tr>
                                    </thead>
                                </table>
                            @else
                                <table id="tabel" class="table table-hover table-striped table-bordered">
                                    <thead class="text-center">
                                        <tr>
                                            <th class="align-middle" rowspan="2">No</th>
                                            <th class="align-middle" rowspan="2" style="min-width: 500px">Uraian Kegiatan
                                            </th>
                                            <th class="align-middle" rowspan="2" style="min-width: 200px">Satuan</th>
                                            <th class="align-middle" colspan="3">Kuantitas</th>
                                            <th class="align-middle" rowspan="2">Tingkat Kualitas (%)</th>
                                            <th class="align-middle" rowspan="2">Kode Butir Kegiatan</th>
                                            <th class="align-middle" rowspan="2">Angka Kredit</th>
                                            <th class="align-middle" rowspan="2">Keterangan</th>
                                        </tr>
                                        <tr>
                                            <th>Target</th>
                                            <th>Realisasi</th>
                                            <th>%</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td class="align-middle" colspan="10"><b>Utama</b></td>
                                        </tr>
                                        @foreach ($kegiatan_utama as $key => $d)
                                            <tr>
                                                <td>{{ $key + 1 }}</td>
                                                <td>{{ $d->name }}</td>
                                                <td>{{ $d->satuan }}</td>
                                                <td class="text-right">{{ $d->jml_target }}</td>
                                                <td class="text-right">{{ $d->jml_realisasi }}</td>
                                                <td class="text-right">{{ ($d->jml_realisasi / $d->jml_target) * 100 }}
                                                </td>
                                                <td class="text-right">
                                                    <input type="hidden" name="id[]" value="{{ $d->id }}">
                                                    <input type="number" class="form-control nilai_kegiatan"
                                                        name="nilai_kegiatan[]" value="{{ $d->nilai_kegiatan }}"
                                                        min="0" max="100" step="1">
                                                </td>
                                                <td>{{ $d->kode_perka }}</td>
                                                <td class="text-right">{{ $d->angka_kredit }}</td>
                                                <td>{{ $d->tgl_mulai }}{{ $d->tgl_selesai ? ' - ' . $d->tgl_selesai : '' }}{{ $d->tgl_mulai || $d->tgl_selesai ? ', ' . $d->keterangan : '' }}</td>
                                            </tr>
                                        @endforeach
                                        <tr>
                                            <td class="align-middle" colspan="10"><b>Tambahan</b></td>
                                        </tr>
                                        @foreach ($kegiatan_tambahan as $key => $d)
                                            <tr>
                                                <td>{{ $key + 1 }}</td>
                                                <td>{{ $d->name }}</td>
                                                <td>{{ $d->satuan }}</td>
                                                <td class="text-right">{{ $d->jml_target }}</td>
                                                <td class="text-right">{{ $d->jml_realisasi }}</td>
                                                <td class="text-right">{{ ($d->jml_realisasi / $d->jml_target) * 100 }}
                                                </td>
                                                <td class="text-right">
                                                    <input type="hidden" name="id[]" value="{{ $d->id }}">
                                                    <input type="number" class="form-control nilai_kegiatan"
                                                        name="nilai_kegiatan[]" value="{{ $d->nilai_kegiatan }}"
                                                        min="0" max="100" step="1">
                                                </td>
                                                <td>{{ $d->kode_perka }}</td>
                                                <td class="text-right">{{ $d->angka_kredit }}</td>
                                                <td>{{ $d->tgl_mulai }}{{ $d->tgl_selesai ? ' - ' . $d->tgl_selesai : '' }}{{ $d->tgl_mulai || $d->tgl_selesai ? ', ' . $d->keterangan : '' }}</td>
                                            </tr>
                                        @endforeach
                                        <tr>
                                            <td colspan="8" class="text-center"><b></b></td>
                                            <td class="text-right"><b>{{ $ckp->angka_kredit }}</b></td>
                                            <td></td>
                                        </tr>
                                        <tr>
                                            <td colspan="5" class="text-center"><b>RATA-RATA</b></td>
                                            <td class="text-right"><input type="number" name="avg_kuantitas"
                                                    value="{{ number_format($ckp->avg_kuantitas, 2) }}" disabled></td>
                                            <td class="text-right"><input type="number" name="avg_kualitas" disabled></td>
                                            <td colspan="3"></td>
                                        </tr>
                                        <tr>
                                            <td colspan="5" class="text-center"><b>CAPAIAN KINERJA PEGAWAI (CKP)</b></td>
                                            <td colspan="2" class="text-center"><input type="number" name="nilai_akhir"
                                                    disabled></td>
                                            <td colspan="3"></td>
                                        </tr>
                                    </tbody>
                                </table>
                            @endif
                        </div>
                    </div>
                    <div id="selain-reject">
                        <div class="row my-3 mx-3">
                            <div class="col">
                                <a href="{{ route('nilai.index') }}" class="btn btn-secondary">Kembali</a>
                            </div>
                            <div class="col text-right">
                                <button type="button" id="rejectBtn1" class="btn btn-warning mr-2">
                                    <i class="fa fa-undo"></i> Reject</button>
                                <button form="formNilai" type="submit" class="btn btn-success mr-2" name="action"
                                    value="save">
                                    <i class="fa fa-save"></i> Simpan</button>
                                <button form="formNilai" type="submit" class="btn btn-primary" id="approveBtn"
                                    name="action" value="send">
                                    <i class="fa fa-check"></i> Simpan & Setujui</button>
                            </div>
                        </div>
                    </div>
                    <div id="rejectDiv" style="display:none">
                        <div class="row my-3 mx-3">
                            <div class="col">
                                <textarea id="catatan" name="catatan" class="form-control" placeholder="Isikan catatan" rows="5"></textarea>
                            </div>
                        </div>
                        <div class="row my-3 mx-3">
                            <div class="col text-right">
                                <button type="button" id="batalReject" class="btn btn-secondary mr-2"> Batal</button>
                                <button form="formNilai" type="submit" class="btn btn-warning mr-2" name="action"
                                    id="rejectBtn2" value="reject">
                                    <i class="fa fa-undo"></i> Reject</button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        function hitungKualitas() {
            var sum_kualitas = 0;
            $(".nilai_kegiatan").each(function() {
                if ($(this).val() !== "")
                    sum_kualitas += parseInt($(this).val(), 10)
            });
            $("input[name='avg_kualitas']").val((sum_kualitas / $(".nilai_kegiatan").length).toFixed(2))
        }

        function hitungNilaiAkhir() {
            var kuantitas = parseFloat($("input[name='avg_kuantitas']").val())
            var kualitas = parseFloat($("input[name='avg_kualitas']").val())
            var nilai_akhir = (kuantitas + kualitas) / 2
            $("input[name='nilai_akhir']").val(nilai_akhir.toFixed(2))
        }
        $(document).ready(function() {
            hitungKualitas();
            hitungNilaiAkhir();
            $(window).keydown(function(event) {
                if (event.keyCode == 13) {
                    event.preventDefault();
                    return false;
                }
            });
        });

        $(document).on("change", ".nilai_kegiatan", function() {
            hitungKualitas();
            hitungNilaiAkhir();
        });

        $("#approveBtn").click(function() {
            var count_kualitas = 1
            $(".nilai_kegiatan").each(function() {
                if ($(this).val() !== "")
                    count_kualitas += 1
            });
            if (count_kualitas == $(".nilai_kegiatan").length) {
                alert("Semua kegiatan harus tleah disetujui");
                return false;
            } else {
                alert("Semua kegiatan harus dinilai sebelum menyetujui");
                return false;
            }
        });


        $("#rejectBtn1").click(function() {
            $("#rejectDiv").show();
            $("#selain-reject").hide();
        });

        $("#batalReject").click(function() {
            $("#rejectDiv").hide();
            $("#selain-reject").show();
        });

        $("#rejectBtn2").click(function() {
            var empty = $('textarea#catatan').val()
            if (empty == '') {
                alert("Masukkan catatan jika akan me-reject CKP");
                return false;
            }
        });
    </script>
@endsection
