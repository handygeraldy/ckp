@extends('layouts.main')

@section('container')
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
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
                    <div class="table-responsive">


                        <table id="info-tim" class="table table-borderless">
                            <tbody>
                                <tr>
                                    <td width="20%">Ketua Tim</td>
                                    <td colspan="9">: {{ $periodetim->user->name }}</td>
                                </tr>

                                @foreach ($df as $key => $t)
                                    @if ($key == 0)
                                        <tr>
                                            <td width="20%">Anggota</td>
                                            <td>: {{ $t->nama_anggota }}</td>
                                        </tr>
                                    @else
                                        <tr>
                                            <td></td>
                                            <td> {{ $t->nama_anggota }}</td>
                                        </tr>
                                    @endif
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <div class="row mt-3 mb-3">
                        <div class="col text-left ml-4">
                            <h5>
                                <b>Daftar Proyek</b>
                            </h5>
                        </div>
                        @if ((auth()->user()->role_id <= 8) | (auth()->user()->id == $periodetim->ketua_id))
                            <a href="{{ route('projek.tambah', $id) }}" class="btn btn-primary mr-3"><i
                                    class="fa fa-plus-circle mr-2"></i>Tambah Proyek</a>
                            <a href="{{ route('usertim.show', $id) }}" class="btn btn-warning mr-3"><i
                                    class="fa fa-plus-circle mr-2"></i>Kelola Anggota</a>
                        @endif


                    </div>
                    <div class="table-responsive">
                        <table id="tabel" class="table table-hover table-striped">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Nama Proyek</th>
                                    @if ((auth()->user()->role_id <= 8) | (auth()->user()->id == $periodetim->ketua_id))
                                        <th>Tindakan</th>
                                    @endif
                                </tr>
                            </thead>
                            <tbody>
                                @if ($dt->isEmpty())
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
                                    @foreach ($dt as $key => $d)
                                        <tr>
                                            <td>{{ $key + 1 }}</td>
                                            <td>
                                                <a href="{{ route($route_ . '.show', $d->id) }}">
                                                    <b>{{ $d->projek_name }}</b>
                                                </a>
                                            </td>
                                            @if ((auth()->user()->role_id <= 8) | (auth()->user()->id == $periodetim->ketua_id))
                                                <td>
                                                    <a href="{{ route($route_ . '.edit', $d->id) }}"
                                                        class="btn btn-success btn-sm"><i class="fas fa-edit"></i></a>
                                                    <a href="#deleteModal" class="btn btn-danger btn-sm ml-2 hapusModal"
                                                        data-id="{{ $d->id }}" data-toggle="modal"><i
                                                            class="fas fa-trash-alt"></i></a>
                                                </td>
                                            @endif
                                        </tr>
                                    @endforeach
                                @endif
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
                        <h3 class="text-center">Hapus proyek ini?</h3>
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
