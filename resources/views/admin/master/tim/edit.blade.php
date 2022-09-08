@extends('layouts.main')

@section('container')
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0">{{ $title }}</h1>
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('index') }}">Dashboard</a></li>
            <li class="breadcrumb-item text-gray-800">Master</li>
            <li class="breadcrumb-item text-gray-800">User</li>
            <li class="breadcrumb-item text-gray-800">Edit</li>
        </ol>
    </div>

    <div class="row">
        <div class="col-lg-12 mb-4">
            <div class="card">
                <div class="card-header">
                </div>
                <div class="card-body">
                    <form action="{{ route('tim.update', $periodetim->id) }}" method="post">
                        @method('patch')
                        @csrf
                        {{-- nama --}}
                        <div class="row mb-2">
                            <div class="col-md-2">
                                <label class="col-form-label" for="name">Nama</label>
                            </div>
                            <div class="col-md-10">
                                <input type="text" id="name" name="name"
                                    value="{{ old('name') ?? $periodetim->tim->name }}"
                                    class="form-control @error('name') is-invalid @enderror" disabled required>
                                @error('name')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                        </div>
                        {{-- Periode Tim --}}
                        <div class="row mb-2">
                            <div class="col-md-2">
                                <label class="col-form-label" for="tahun">Periode Tim</label>
                            </div>
                            <div class="col-md-10">
                                <input class="form-control @error('tahun') is-invalid @enderror" type="number"
                                    min="2022" value="{{ $periodetim->tahun }}" id="tahun" name="tahun" required>
                                @error('satker_id')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                        </div>
                        {{-- User --}}
                        <div class="row mb-2">
                            <div class="col-md-2">
                                <label class="col-form-label" for="ketua_id">Ketua Tim</label>
                            </div>
                            <div class="col-md-10">
                                <select class="form-control select2 @error('ketua_id') is-invalid @enderror" name="ketua_id"
                                    id="ketua_id" required>
                                    <option value="" disabled selected>== Pilih Ketua Tim ==</option>
                                    @foreach ($user as $i)
                                        <option value="{{ $i->id }}"
                                            {{ $i->id == (old('ketua_id') ?? $periodetim->ketua_id) ? 'selected' : '' }}>
                                            {{ $i->name }}</option>
                                    @endforeach
                                </select>
                                @error('ketua_id')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                        </div>
                        {{-- submit --}}
                        <div class="row mt-5">
                            <div class="col">
                                <a href="{{ route('tim.index') }}" class="btn btn-secondary">Kembali</a>
                                <button type="submit" class="btn btn-success float-right"><i class="fa fa-save"></i>
                                    Simpan</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
