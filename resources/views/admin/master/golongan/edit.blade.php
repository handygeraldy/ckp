@extends('layouts.main')

@section('container')
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0">{{ $title }}</h1>
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('index') }}">Dashboard</a></li>
            <li class="breadcrumb-item text-gray-800">Master</li>
            <li class="breadcrumb-item text-gray-800">Petugas</li>
            <li class="breadcrumb-item text-gray-800">Edit</li>
        </ol>
    </div>

    <div class="row">
        <div class="col-lg-12 mb-4">
            <div class="card">
                <div class="card-header">
                </div>
                <div class="card-body">
                    <form action="{{ route('master.petugas.edit.post', $p_id) }}" method="post">
                        @csrf
                        {{-- nama --}}
                        <div class="row mb-2">
                            <div class="col-md-2">
                                <label class="col-form-label" for="name">Nama Petugas</label>
                            </div>
                            <div class="col-md-10">
                                <input type="text" id="name" name="name"
                                    class="form-control @error('name') is-invalid @enderror" value="{{ $penerima->name }}" required>
                                @error('name')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                        </div>
                        {{-- gol --}}
                        <div class="row mb-2">
                            <div class="col-md-2">
                                <label class="col-form-label" for="gol">Golongan</label>
                            </div>
                            <div class="col-md-10">
                                <select class="form-control @error('gol') is-invalid @enderror" name="gol" id="gol" required>
                                    <option value="" disabled selected>== Pilih Golongan ==</option>
                                    @foreach ($golongans as $g)
                                        <option value="{{ $g->gol }}" {{ $g->gol == $penerima->gol ? 'selected' : '' }}>{{ $g->gol }}</option>
                                    @endforeach
                                </select>
                                @error('gol')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                        </div>
                        {{-- nama_bank --}}
                        <div class="row mb-2">
                            <div class="col-md-2">
                                <label class="col-form-label" for="nama_bank">Nama Bank</label>
                            </div>
                            <div class="col-md-10">
                                <input type="text" id="nama_bank" name="nama_bank"
                                    class="form-control @error('nama_bank') is-invalid @enderror" value="{{ $penerima->nama_bank }}">
                                @error('nama_bank')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                        </div>
                        {{-- no_rek --}}
                        <div class="row mb-2">
                            <div class="col-md-2">
                                <label class="col-form-label" for="no_rek">No. Rekening</label>
                            </div>
                            <div class="col-md-10">
                                <input type="text" id="no_rek" name="no_rek"
                                    class="form-control @error('no_rek') is-invalid @enderror" value="{{ $penerima->no_rek }}">
                                @error('no_rek')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                        </div>
                        {{-- nama_rek --}}
                        <div class="row mb-2">
                            <div class="col-md-2">
                                <label class="col-form-label" for="nama_rek">Nama Pemilik Rekening</label>
                            </div>
                            <div class="col-md-10">
                                <input type="text" id="nama_rek" name="nama_rek"
                                    class="form-control @error('nama_rek') is-invalid @enderror" value="{{ $penerima->nama_rek }}">
                                @error('nama_rek')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                        </div>
                        {{-- alamat --}}
                        <div class="row mb-2">
                            <div class="col-md-2">
                                <label class="col-form-label" for="alamat">Alamat</label>
                            </div>
                            <div class="col-md-10">
                                <textarea class="form-control @error('alamat') is-invalid @enderror" id="alamat" name="alamat" rows="2">{{ $penerima->alamat }}</textarea>
                                @error('alamat')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                        </div>

                        {{-- submit --}}
                        <div class="row mt-5">
                            <div class="col">
                                <a href="{{ route('master.petugas.index') }}" class="btn btn-primary">Kembali</a>
                                <button type="submit" class="btn btn-success float-right"><i class="fa fa-save"></i> Simpan</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
