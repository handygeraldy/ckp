@extends('layouts.main')

@section('container')
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <ol class="breadcrumb">
            <li class="breadcrumb-item">Tim Kerja</li>
            <li class="breadcrumb-item text-gray-800">Proyek {{ $title }}</li>
        </ol>
    </div>

    <div class="row">
        <div class="col-lg-12 mb-4">
            <div class="card">
                <h4 class="text-center my-4">
                    <b>Proyek {{ $projek->name }}</b>
                </h4>
                <div class="card-header">
                    <div class="row mt-3 mb-3">
                        <div class="col text-left ml-4">
                            <h5>
                                <b>Daftar Kegiatan</b>
                            </h5>
                        </div>
                    </div>
                    <form action="{{ route('kegiatantim.assign.post', $id) }}" method="POST">
                        @csrf
                        <div class="row">

                            <div class="col-6">
                                <table class="table table-hover table-striped">
                                    <thead>
                                        <tr>
                                            <th></th>
                                            <th>Nama Kegiatan</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($list_kegiatan as $key => $d)
                                            <tr>
                                                <td>
                                                    <input class="form-check-input mb-2" type="checkbox"
                                                        value="{{ $d->id }}" id="{{ $d->id }}"
                                                        name="kegiatan[]">

                                                </td>
                                                <td>
                                                    <label class="form-check-label" for="{{ $d->id }}"
                                                        style="font-size: 14pt">
                                                        {{ $d->name }}
                                                    </label>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                            <div class="col-6">
                                <table class="table table-hover table-striped">
                                    <thead>
                                        <tr>
                                            <th></th>
                                            <th>Nama Anggota</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($list_user as $key => $d)
                                            <tr>
                                                <td>
                                                    <input class="form-check-input mb-2" type="checkbox"
                                                        value="{{ $d->id }}" id="{{ $d->id }}"
                                                        name="anggota[]">

                                                </td>
                                                <td>
                                                    <label class="form-check-label" for="{{ $d->id }}"
                                                        style="font-size: 14pt">
                                                        {{ $d->name }}
                                                    </label>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <br>
                        <button type="submit" class="btn btn-success float-right"><i class="fa fa-save"></i>
                            Simpan</button>
                    </form>
                </div>

            </div>
        </div>
    </div>
@endsection
