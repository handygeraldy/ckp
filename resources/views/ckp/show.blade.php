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
                        <table id="tabel" class="table table-hover table-striped">
                            <thead class="text-center">
                                <tr>
                                    <th class="align-middle" rowspan="2">No</th>
                                    <th class="align-middle" rowspan="2">Tanggal Mulai</th>
                                    <th class="align-middle" rowspan="2">Tanggal Selesai</th>
                                    <th class="align-middle" rowspan="2">Tim</th>
                                    <th class="align-middle" rowspan="2">Uraian Kegiatan</th>
                                    <th class="align-middle" rowspan="2">Satuan</th>
                                    <th class="align-middle" colspan="3">Kuantitas</th>
                                    <th class="align-middle" rowspan="2">Tingkat Kualitas (%)</th>
                                    <th class="align-middle" rowspan="2">Kode Butir Kegiatan</th>
                                    {{-- <th class="align-middle" rowspan="2">Angka Kredit</th> --}}
                                    <th class="align-middle" rowspan="2">Keterangan</th>
                                    <th class="align-middle" rowspan="2"></th>
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
                                        <td>{{ $d->jml_target }}</td>
                                        <td>{{ $d->jml_realisasi }}</td>
                                        <td>{{ $d->jml_realisasi / $d->jml_target * 100 }}</td>
                                        <td>{{ $d->nilai_kegiatan }}</td>
                                        <td>{{ $d->kredit->kode_perka }}</td>
                                        {{-- <td>{{ $d->kredit }}</td> --}}
                                        <td>{{ $d->keterangan }}</td>
                                        <td class= "text-right" style="min-width: 100px;">
                                            <div class="row">
                                                @if ($d->kegiatan_tim_id == Null)
                                                <a href="{{ route($route_ . '.edit', $d->id) }}"
                                                    class="btn btn-success btn-sm"><i class="fas fa-edit"></i></a>
                                                @endif
                                                <a href="#deleteModal" class="btn btn-danger btn-sm hapusModal"
                                                    data-id="{{ $d->id }}" data-toggle="modal"><i
                                                        class="fas fa-trash-alt"></i></a>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="deleteModal" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered modal-sm">
            <div class="modal-content">
                <form action="{{ route($route_ . '.delete') }}" method="POST" class="d-inline">
                    @method('delete')
                    @csrf
                    <div class="modal-body">
                        <h3 class="text-center">Hapus kegiatan ini?</h3>
                        <input type="hidden" id="value_id" name="value_id">
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
        // $(document).ready(function() {
        //     $('#tabel').DataTable();
        // });

        $(document).on("click", ".hapusModal", function() {
            var value_id = $(this).data('id');
            $(".modal-body #value_id").val(value_id);
        });
    </script>
@endsection
