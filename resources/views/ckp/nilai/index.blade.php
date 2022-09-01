@extends('layouts.main')

@section('container')
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0">{{ $title }}</h1>
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('index') }}">Dashboard</a></li>
            <li class="breadcrumb-item text-gray-800">CKP</li>
            <li class="breadcrumb-item text-gray-800">Penilaian</li>
        </ol>
    </div>

    <div class="row">
        <div class="col-lg-12 mb-4">
            <div class="card">
                <div class="card-header">
                    <div class="row">
                        <div class="col text-right">
                           
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table id="tabel" class="table table-hover table-striped">
                            <thead>
                                <tr class="text-center align-middle">
                                    <th>No</th>
                                    <th style="min-width: 80px">Bulan</th>
                                    <th style="min-width: 200px">Nama Pegawai</th>
                                    <th>Nilai Kuantitas</th>
                                    <th>Nilai Kualitas</th>
                                    <th>Nilai Akhir</th>
                                    <th style="min-width: 150px">Jumlah kegiatan belum dinilai</th>
                                    <th style="min-width: 150px"></th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($dt as $key => $d)
                                    <tr>
                                        <td>{{ $key + 1 }}</td>
                                        <td>{{ $d->bulan . '-' . $d->tahun }}</td>
                                        <td>{{ $d->user_name }}</td>
                                        <td>{{ $d->avg_kuantitas }}</td>
                                        <td>{{ $d->avg_kualitas }}</td>
                                        <td>{{ $d->nilai_akhir }}</td>
                                        <td>{{ $d->jml_kegiatan }}</td>
                                        <td style="min-width: 100px;">
                                            <div class="row">
                                                <a href="{{ route($route_ . '.show',  $d->id) }}" class="btn btn-primary btn-sm">
                                                    <i class="fa fa-eye"></i></a>
                                                <a href="{{ route($route_ . '.edit', $d->id) }}"
                                                    class="btn btn-success btn-sm"><i class="fa-solid fa-file-pen"></i> Beri Nilai</a>
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
    
    <script>
        $(document).ready(function() {
            $('#tabel').DataTable();
        });
    </script>
@endsection
