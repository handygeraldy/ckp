@extends('layouts.main')

@section('container')
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0">{{ $title }}</h1>
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('index') }}">Dashboard</a></li>
            <li class="breadcrumb-item text-gray-800">CKP</li>
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
                                    <th style="min-width: 80px">Bulan</th>
                                    <th>Jumlah Kegiatan</th>
                                    <th>Nilai Kuantitas</th>
                                    <th>Nilai Kualitas</th>
                                    <th>Nilai Akhir</th>
                                    <th>Angka Kredit</th>
                                    <th style="min-width: 150px">Status</th>
                                    <th style="min-width: 150px"></th>
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
                                        <td>
                                            @if ($d->status == 0)
                                            Dikembalikan
                                            @elseif ($d->status == 1)
                                            Belum diajukan
                                            @elseif ($d->status == 2)
                                            Diperiksa ketua tim
                                            @elseif ($d->status == 3)
                                            Diperiksa Direktur
                                            @else
                                            Disetujui
                                            @endif
                                        </td>
                                            <td style="min-width: 100px;">
                                                <div class="row">
                                                    @if ($d->status == 0)
                                                    <a href="#catatanModal" id="Modalcatatan" class="btn btn-warning btn-sm"
                                                        data-id="{{ $d->id }}" data-toggle="modal"
                                                        title="catatan"><i class="fas fa-clipboard"></i></a>
                                                    @endif
                                                    <a href="{{ route($route_ . '.show', $d->id) }}"
                                                        class="btn btn-primary btn-sm">
                                                        <i class="fa fa-eye"></i></a>
                                                        
                                                    @if ($d->status <= 1)
                                                    <a href="{{ route($route_ . '.edit', $d->id) }}"
                                                        class="btn btn-success btn-sm"><i class="fas fa-edit"></i> Edit</a>
                                                    <a href="#deleteModal" class="btn btn-danger btn-sm hapusModal"
                                                        data-id="{{ $d->id }}" data-toggle="modal"><i
                                                            class="fas fa-trash-alt"></i></a>
                                                    @elseif ($d->status == 4)
                                                    <a href=""
                                                        class="btn btn-success btn-sm"><i class="fa fa-download"></i> Export</a>
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
    <div class="modal fade" id="catatanModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content shadow-sm">
                <div class="modal-head my-3 mx-3">
                    <button type="button" class="btn btn-danger float-right" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                    <h3 class="text-center">Catatan</h3>

                </div>
                <div class="modal-body" id="Bodycatatan">
                    <table class="table table-striped table-hover">
                        <thead class="text-center">
                            <tr>
                                <th style="width: 30%">Nama</th>
                                <th style="width: 70%">Catatan</th>
                            </tr>
                        </thead>
                        <tbody id="addRow" class="addRow">
                        </tbody>
                    </table>
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
        function get_catatan(obj) {
            var ckp_id = obj;
            $.ajax({
                url: '{{ env('APP_URL') }}' + 'ckp/catatan/' + ckp_id,
                type: "GET",
                dataType: "json",
                data: {
                    "_token": "{{ csrf_token() }}"
                },
                success: function(data) {
                    catatan = data.catatan;
                    $('#addRow').empty();
                    $.each(catatan, function(index,
                        c) {
                        $('#addRow').append('<tr><td>' + String(c.name) + '</td><td>' + c.catatan +
                            '</td></tr>');
                    })
                }

            });

        }
        $(document).ready(function() {
            $('#tabel').DataTable();
        });
        $(document).on('click', '#Modalcatatan', function(event) {
            event.preventDefault();
            var ckp_id = $(this).attr('data-id');
            catatan = get_catatan(ckp_id);

        });
        $(document).on("click", ".hapusModal", function() {
            var value_id = $(this).data('id');
            $(".modal-body #value_id").val(value_id);
        });
    </script>
@endsection
