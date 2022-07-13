@extends('layouts.main')

@section('container')
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0">Keterangan Petugas</h1>
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('index') }}">Dashboard</a></li>
            <li class="breadcrumb-item text-gray-800">Master</li>
            <li class="breadcrumb-item text-gray-800">Petugas</li>
            <li class="breadcrumb-item text-gray-800">Lihat</li>
        </ol>
    </div>

    <div class="row">
        <div class="col-lg-12 mb-4">
            <div class="card">
                <div class="card-header">
                    {{-- <h1>tes</h1> --}}
                    <div class="row">
                        <div class="col text-right">
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-borderless ">
                            <tr>
                                <th style="width: 25%"></th>
                                <th style="width: 75%"></th>
                            </tr>
                            <tbody>
                                <tr>
                                    <th>Nama</th>
                                    <td>{{ $penerima->name }}</td>
                                </tr>
                                <tr>
                                    <th>Golongan</th>
                                    <td>{{ $penerima->gol }}</td>
                                </tr>
                                <tr>
                                    <th>Nama Bank</th>
                                    <td>{{ $penerima->nama_bank }}</td>
                                </tr>
                                <tr>
                                    <th>No. Rekening</th>
                                    <td>{{ $penerima->no_rek }}</td>
                                </tr>
                                <tr>
                                    <th>Nama Pemilik Rekening</th>
                                    <td>{{ $penerima->nama_rek }}</td>
                                </tr>
                                <tr>
                                    <th>Alamat</th>
                                    <td>{{ $penerima->alamat }}</td>
                                </tr>                                
                            </tbody>
                        </table>

                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row mt-5">
        <div class="col-6">
            <a href="{{ route('master.petugas.index') }}" class="btn btn-primary">Kembali</a>
        </div>
        <div class="col-6 text-right">
            <a href="" class="btn btn-danger" data-value="{{ $penerima->id }}" data-toggle="modal" data-target="#deleteModal"><i class="fa fa-trash"></i> Hapus</a>
        </div>
    </div>
    <div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-sm">
            <div class="modal-content shadow-sm">
                <form action="{{ route('master.petugas.delete') }}" method="POST" class="d-inline">
                    @method('delete')
                    @csrf
                    <input type="hidden" name="p_id" value="{{ $penerima->id ?? '' }}">
                    <div class="modal-body">
                        <h3 class="text-center">Hapus petugas ini?</h3>
                    </div>
                    <div class="modal-footer justify-content-around pt-0 border-top-0">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-danger">Hapus</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection