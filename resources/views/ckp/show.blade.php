@extends('layouts.main')

@section('container')
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0">{{ $title }}</h1>
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('index') }}">Dashboard</a></li>
            <li class="breadcrumb-item text-gray-800">Lihat CKP</li>
        </ol>
    </div>

    <div class="row">
        <div class="col-lg-12 mb-4">
            <div class="card">
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
                        @if ($kegiatan->isEmpty())
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
                                        <th class="align-middle" rowspan="2" style="min-width: 150px">Tanggal Mulai</th>
                                        <th class="align-middle" rowspan="2" style="min-width: 150px">Tanggal Selesai
                                        </th>
                                        <th class="align-middle" rowspan="2">Tim</th>
                                        <th class="align-middle" rowspan="2" style="min-width: 500px">Uraian Kegiatan
                                        </th>
                                        <th class="align-middle" rowspan="2" style="min-width: 200px">Satuan</th>
                                        <th class="align-middle" colspan="3">Kuantitas</th>
                                        <th class="align-middle" rowspan="2">Tingkat Kualitas (%)</th>
                                        <th class="align-middle" rowspan="2">Kode Butir Kegiatan</th>
                                        <th class="align-middle" rowspan="2">Angka Kredit</th>
                                        <th class="align-middle" rowspan="2">Keterangan</th>
                                        @if ($ckp->status == 1)
                                            <th class="align-middle" rowspan="2"></th>
                                        @endif
                                    </tr>
                                    <tr>
                                        <th>Target</th>
                                        <th>Realisasi</th>
                                        <th>%</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($kegiatan as $key => $d)
                                        <tr>
                                            <td>{{ $key + 1 }}</td>
                                            <td>{{ $d->tgl_mulai }}</td>
                                            <td>{{ $d->tgl_selesai }}</td>
                                            <td>{{ $d->tim->name }}</td>
                                            <td>{{ $d->name }}</td>
                                            <td>{{ $d->satuan->name }}</td>
                                            <td class="text-right">{{ $d->jml_target }}</td>
                                            <td class="text-right">{{ $d->jml_realisasi }}</td>
                                            <td class="text-right">{{ ($d->jml_realisasi / $d->jml_target) * 100 }}</td>
                                            <td class="text-right">{{ $d->nilai_kegiatan }}</td>
                                            <td>{{ $d->kredit_id ? $d->kredit->kode_perka : '-' }}</td>
                                            <td class="text-right">{{ $d->angka_kredit }}</td>
                                            <td>{{ $d->keterangan }}</td>
                                            @if ($ckp->status == 1)
                                                <td class="text-right" style="min-width: 100px;">
                                                    <div class="row">
                                                        @if ($d->kegiatan_tim_id == null)
                                                            <a href="{{ route($route_ . '.edit', $d->id) }}"
                                                                class="btn btn-success btn-sm"><i
                                                                    class="fas fa-edit"></i></a>
                                                        @endif
                                                        <a href="#deleteModal" class="btn btn-danger btn-sm hapusModal"
                                                            data-id="{{ $d->id }}" data-ckp="{{ $d->ckp_id }}"
                                                            data-toggle="modal"><i class="fas fa-trash-alt"></i></a>
                                                    </div>
                                                </td>
                                            @endif
                                        </tr>
                                    @endforeach
                                    <tr>
                                        <td colspan="8" class="text-center"><b></b></td>
                                        <td colspan="3"></td>
                                        <td class="text-right"><b>{{ $ckp->angka_kredit }}</b></td>
                                        <td colspan="2"></td>
                                    </tr>
                                    <tr>
                                        <td colspan="8" class="text-center"><b>RATA-RATA</b></td>
                                        <td class="text-right"><b>{{ $ckp->avg_kuantitas }}</b></td>
                                        <td class="text-right"><b>{{ $ckp->avg_kualitas }}</b></td>
                                        <td colspan="4"></td>
                                    </tr>
                                    <tr>
                                        <td colspan="8" class="text-center"><b>CAPAIAN KINERJA PEGAWAI (CKP)</b></td>
                                        <td colspan="2" class="text-center"><b>{{ $ckp->nilai_akhir }}</b></td>
                                        <td colspan="4"></td>
                                    </tr>
                                </tbody>
                            </table>
                        @endif
                    </div>

                    @if ($route_ == 'kegiatan' && $ckp->status <= 1)
                        <div class="row mt-5">
                            <div class="col">
                                <a href="{{ URL::previous() }}" class="btn btn-secondary ml-3 mb-3">Kembali</a>
                            </div>
                            <div class="col mr-3">
                                <form action="{{ route('ckp.ajukan') }}" method="POST">
                                    @csrf
                                    <input type="hidden" name="ckp_id" value="{{ $ckp->id }}">
                                    <button type="submit" class="btn btn-primary float-right">
                                        <i class="fa fa-check"></i> Ajukan CKP
                                    </button>
                                </form>
                            </div>
                        </div>
                    @elseif ($route_ == 'approval' && $ckp->status == 3)
                        <form id="formApprove" action="{{ route('approval.approve.reject') }}" method="POST">
                            @csrf
                            <input type="hidden" name="ckp_id" value="{{ $ckp->id }}">
                            <div id="selain-reject" class="row mt-5">
                                <div class="col">
                                    <a href="{{ URL::previous() }}" class="btn btn-secondary ml-3 mb-3">Kembali</a>
                                </div>
                                <div class="col mr-3 text-right">
                                    <button type="button" id="rejectBtn1" class="btn btn-warning mr-2">
                                        <i class="fa fa-undo"></i> Reject</button>
                                    <button form="formApprove" type="submit" class="btn btn-primary" name="action"
                                        value="approve">
                                        <i class="fa fa-check"></i> Approve</button>
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
                                        <button type="button" id="batalReject" class="btn btn-secondary mr-2">
                                            Batal</button>
                                        <button form="formApprove" type="submit" class="btn btn-warning mr-2"
                                            name="action" id="rejectBtn2" value="reject">
                                            <i class="fa fa-undo"></i> Reject</button>
                                    </div>
                                </div>
                            </div>
                        </form>
                    @endif
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="deleteModal" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered modal-sm">
            <div class="modal-content">
                <form action="{{ route('kegiatan.delete') }}" method="POST" class="d-inline">
                    @method('delete')
                    @csrf
                    <div class="modal-body">
                        <h3 class="text-center">Hapus kegiatan ini?</h3>
                        <input type="hidden" id="value_id" name="value_id">
                        <input type="hidden" id="ckp_id" name="ckp_id">
                    </div>
                    <div class="modal-footer justify-content-around pt-0 border-top-0">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-danger">Hapus</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <script>
        $(document).on("click", ".hapusModal", function() {
            var value_id = $(this).data('id');
            $(".modal-body #value_id").val(value_id);
            var ckp_id = $(this).data('ckp');
            $(".modal-body #ckp_id").val(ckp_id);
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
