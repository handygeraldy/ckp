@extends('layouts.main')

@section('container')
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0">Kelola Anggota</h1>
        <ol class="breadcrumb">
            <li class="breadcrumb-item">Tim Kerja</li>
            <li class="breadcrumb-item text-gray-800">{{ $title }}</li>
        </ol>
    </div>

    <div class="row">
        <div class="col-lg-12 mb-4">
            <div class="card">
                <h4 class="text-center my-4">
                    <b>{{ $title }}</b>
                </h4>
                <div class="card-header">
                    <div class="row mt-3 mb-3">
                        <div class="col text-left ml-4">
                            <h5>
                                <b>Daftar Anggota</b>
                            </h5>
                        </div>
                        <a href="#createModal" class="btn btn-primary" data-toggle="modal"><i
                                class="fa fa-plus-circle mr-2"></i>Tambah Anggota</a>
                    </div>
                    <div class="table-responsive">
                        <table id="tabel" class="table table-hover table-striped">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Nama Anggota</th>
                                    <th>Tindakan</th>
                                </tr>
                            </thead>
                            <tbody>
                                @if ($list_anggota->isEmpty())
                                    <table class="table">
                                        <thead>
                                            <tr>
                                                <th class="text-center">
                                                    <i>Belum Ada Anggota</i>
                                                </th>
                                            </tr>
                                        </thead>
                                    </table>
                                @else
                                    @foreach ($list_anggota as $key => $d)
                                        <tr>
                                            <td>{{ $key + 1 }}</td>
                                            <td>

                                                <b>{{ $d->nama_anggota }}</b>
                                            </td>
                                            <td>
                                                <a href="#deleteModal" class="btn btn-danger btn-sm ml-2 hapusModal"
                                                    data-id="{{ $d->id }}" data-toggle="modal"><i
                                                        class="fas fa-trash-alt"></i></a>
                                            </td>
                                        </tr>
                                    @endforeach
                                @endif
                            </tbody>
                        </table>

                        <div class="row mt-5">
                            <div class="col">
                                <a href="{{ URL::previous() }}" class="btn btn-secondary ml-3 mb-3">Kembali</a>
                            </div>
                        </div>
                    </div>

                </div>

            </div>
        </div>
    </div>
    <div class="modal fade" id="createModal" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered modal-md">
            <div class="modal-content">
                <form action="{{ route('usertim.store') }}" method="POST" class="d-inline">
                    @csrf
                    <div class="modal-body">
                        <input type="hidden" value="{{ $id }}" name="tim_id">
                        @foreach ($calon_anggota as $key => $d)
                            <div class="form-check">
                                <input class="form-check-input mb-2" type="checkbox" value="{{ $d->id }}"
                                    id="{{ $d->id }}" name="anggota[]">
                                <label class="form-check-label" for="{{ $d->id }}" style="font-size: 14pt">
                                    {{ $d->name }}
                                </label>
                            </div>
                        @endforeach
                    </div>
                    <div class="modal-footer justify-content-around pt-0 border-top-0">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-success"><i class="fa fa-save mr-2"></i>Simpan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <div class="modal fade" id="deleteModal" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered modal-sm">
            <div class="modal-content">
                <form action="{{ route('usertim.delete') }}" method="POST" class="d-inline">
                    @method('delete')
                    @csrf
                    <div class="modal-body">
                        <h3 class="text-center">Hapus anggota ini?</h3>
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
