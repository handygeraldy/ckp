@extends('layouts.main')

@section('container')
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0">{{ $title }}</h1>
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('index') }}">Dashboard</a></li>
            <li class="breadcrumb-item text-gray-800">Master</li>
            <li class="breadcrumb-item text-gray-800">{{ $text_ }}</li>
        </ol>
    </div>

    <div class="row">
        <div class="col-lg-12 mb-4">
            <div class="card">
                <div class="card-header">
                    <div class="row">
                        <div class="col text-right">
                            <a href="#createModal" class="btn btn-primary" data-toggle="modal"><i
                                    class="fa fa-plus-circle mr-2"></i>Tambah {{ $text_ }}</a>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table id="tabel" class="table table-hover table-striped">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    @if ($route_ == 'fungsional')
                                        <th>Jabatan Fungsional</th>
                                    @endif
                                    <th scope="col">{{ $text_ }}</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($dt as $key => $d)
                                    <tr>
                                        <td>{{ $key + 1 }}</td>
                                        @if ($route_ == 'fungsional')
                                            <td>{{ $d->jafung->name }}</td>
                                        @endif
                                        <td class="text-left" style="min-width: 250px;">{{ $d->name }}</td>
                                        <td style="min-width: 100px;">
                                            <div class="row">
                                                @if ($route_ == 'fungsional')
                                                    <a href="#editModal" data-toggle="modal" data-id="{{ $d->id }}"
                                                        data-name="{{ $d->name }}"
                                                        data-jafung="{{ $d->jafung->id }}"
                                                        class="btn btn-success btn-sm editModal"><i
                                                            class="fas fa-edit"></i></a>
                                                @else
                                                    <a href="#editModal" data-toggle="modal" data-id="{{ $d->id }}"
                                                        data-name="{{ $d->name }}"
                                                        class="btn btn-success btn-sm editModal"><i
                                                            class="fas fa-edit"></i></a>
                                                @endif

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
    <div class="modal fade" id="createModal" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered modal-md">
            <div class="modal-content">
                <form action="{{ route($route_ . '.store') }}" method="POST" class="d-inline">
                    @csrf
                    <div class="modal-body">
                        <input class="form-control" type="text" id="name" name="name"
                            placeholder="Isikan nama {{ $text_ }}">
                        @if ($route_ == 'fungsional')
                            <select class="form-control select2 mt-2" name="jafung_id" id="jafung_id" required>
                                <option value="" disabled selected>== Pilih Jafung ==</option>
                                <option value="1">Statistisi</option>
                                <option value="2">Prakom</option>
                            </select>
                        @endif
                    </div>
                    <div class="modal-footer justify-content-around pt-0 border-top-0">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-success"><i class="fa fa-save mr-2"></i>Simpan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <div class="modal fade" id="editModal" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered modal-md">
            <div class="modal-content">
                <form id="formedit" class="d-inline">
                    <div class="modal-body">

                        <input type="hidden" id="id_edit" name="id">
                        <input type="text" name="name" id="name_edit" class="form-control">
                        @if ($route_ == 'fungsional')
                            <select class="form-control select2 mt-2" name="jafung_id" id="jafung_edit" required>
                                <option value="" disabled>== Pilih Jafung ==</option>
                                <option value="1">Statistisi</option>
                                <option value="2">Prakom</option>
                            </select>
                        @endif
                    </div>
                    <div class="modal-footer justify-content-around pt-0 border-top-0">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                        <button type="submit" id="submitEditForm" class="btn btn-success"><i
                                class="fa fa-save mr-2"></i>Simpan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        $(document).ready(function() {
            $('#tabel').DataTable();
        });

        $('body').on('click', '#submitEditForm', function(event) {
            event.preventDefault()
            var id = $("#id_edit").val();
            var name = $("#name_edit").val();
            $.ajax({
                url: '{{ $route_ }}' + '/' + id,
                type: "PATCH",
                data: {
                    name: name,
                    _token: "{{ csrf_token() }}",
                },

                dataType: 'json',
                success: function(data) {
                    window.location.reload(true);
                }
            });
        });

        $(document).on("click", ".editModal", function() {

            var id_edit = $(this).data('id');
            $(".modal-body #id_edit").val(id_edit);
            var name_edit = $(this).data('name');
            $(".modal-body #name_edit").val(name_edit);
            var jafung_edit = $(this).data('jafung');
            $(".modal-body #jafung_edit").val(jafung_edit).change();
        });
    </script>
@endsection
