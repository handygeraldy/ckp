@extends('layouts.main')

@section('container')
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0">{{ $title }}</h1>
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('index') }}">Dashboard</a></li>
            <li class="breadcrumb-item text-gray-800">Ckp</li>
        </ol>
    </div>

    <div class="row">
        <div class="col-lg-12 mb-4">
            <div class="card">
                <div class="card-header">
                    <div class="row">
                        <div class="col text-right">
                            <a href="{{ route('ckp.create') }}" class="btn btn-primary"><i
                                    class="fa fa-plus-circle mr-2"></i>Buat CKP</a>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table id="tabel" class="table table-hover table-striped">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Bulan</th>
                                    <th>Jumlah Kegiatan</th>
                                    <th>Nilai Kuantitas</th>
                                    <th>Nilai Kualitas</th>
                                    <th>Nilai Akhir</th>
                                    <th>Angka Kredit</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($dt as $key => $d)
                                    <tr>
                                        <td>{{ $key + 1 }}</td>
                                        <td>{{ $d->bulan . '-' . $d->tahun }}</td>
                                        <td>{{ $d->jml_kegiatan }}</td>
                                        <td>{{ $d->avg_kuantitas }}</td>
                                        <td>{{ $d->avg_kualitas }}</td>
                                        <td>{{ $d->nilai_akhir }}</td>
                                        <td>{{ $d->angka_kredit }}</td>
                                        <td style="min-width: 100px;">
                                            <div class="row">
                                                <a href="{{ route($route_ . '.edit', $d->id) }}"
                                                    class="btn btn-success btn-sm"><i class="fas fa-edit"></i></a>
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
                        <h3 class="text-center">Hapus CKP ini?</h3>
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
        $(document).ready(function() {
            $('#tabel').DataTable();
        });

        $(document).on("click", ".hapusModal", function() {
            var value_id = $(this).data('id');
            $(".modal-body #value_id").val(value_id);
        });
    </script>
@endsection
