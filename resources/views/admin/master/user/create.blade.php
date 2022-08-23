@extends('layouts.main')

@section('container')
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0">{{ $title }}</h1>
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('index') }}">Dashboard</a></li>
            <li class="breadcrumb-item text-gray-800">Master</li>
            <li class="breadcrumb-item text-gray-800">User</li>
            <li class="breadcrumb-item text-gray-800">Create</li>
        </ol>
    </div>

    <div class="row">
        <div class="col-lg-12 mb-4">
            <div class="card">
                <div class="card-header">
                </div>
                <div class="card-body">
                    <form action="{{ route('user.store') }}" method="post">
                        @csrf                       
                        {{-- nama --}}
                        <div class="row mb-2">
                            <div class="col-md-2">
                                <label class="col-form-label" for="name">Nama</label>
                            </div>
                            <div class="col-md-10">
                                <input type="text" id="name" name="name" value="{{ old('name') }}"
                                    class="form-control @error('name') is-invalid @enderror" required>
                                @error('name')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                        </div>
                        {{-- NIP --}}
                        <div class="row mb-2">
                            <div class="col-md-2">
                                <label class="col-form-label" for="nip">NIP</label>
                            </div>
                            <div class="col-md-10">
                                <input type="text" id="nip" name="nip" value="{{ old('nip') }}" minlength="18" maxlength="18"
                                    class="form-control @error('nip') is-invalid @enderror" required>
                                @error('nip')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                        </div>
                        {{-- Email --}}
                        <div class="row mb-2">
                            <div class="col-md-2">
                                <label class="col-form-label" for="email">Email</label>
                            </div>
                            <div class="col-md-10">
                                <input type="email" id="email" name="email" value="{{ old('email') }}"
                                    class="form-control @error('email') is-invalid @enderror" required>
                                @error('email')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                        </div>
                        {{-- password --}}
                        <div class="row mb-2">
                            <div class="col-md-2">
                                <label class="col-form-label" for="password">Password</label>
                            </div>
                            <div class="col-md-10">
                                <input type="password" id="password" name="password"
                                    class="form-control @error('password') is-invalid @enderror" required>
                                @error('password')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                        </div>
                        {{-- Satker --}}
                        <div class="row mb-2">
                            <div class="col-md-2">
                                <label class="col-form-label" for="satker_id">Satker</label>
                            </div>
                            <div class="col-md-10">
                                <select class="form-control select2 @error('satker_id') is-invalid @enderror" name="satker_id" id="satker_id" required>
                                    <option value="" disabled selected>== Pilih Satker ==</option>
                                    @foreach ($satker as $i)
                                        <option value="{{ $i->id }}" {{ $i->id == old('satker_id') ? 'selected' : '' }}>{{ $i->name }}</option>
                                    @endforeach
                                </select>
                                @error('satker_id')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                            </div>
                        </div>
                        {{-- Tim Utama --}}
                        <div class="row mb-2">
                            <div class="col-md-2">
                                <label class="col-form-label" for="tim_utama">Tim Utama</label>
                            </div>
                            <div class="col-md-10">
                                <select class="form-control select2 @error('tim_utama') is-invalid @enderror" name="tim_utama" id="satker_id" required>
                                    <option value="" disabled selected>== Pilih Tim Utama ==</option>
                                    @foreach ($tim as $i)
                                        <option value="{{ $i->id }}" {{ $i->id == old('tim_utama') ? 'selected' : '' }}>{{ $i->name }}</option>
                                    @endforeach
                                </select>
                                @error('tim_utama')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                            </div>
                        </div>
                        {{-- Golongan --}}
                        <div class="row mb-2">
                            <div class="col-md-2">
                                <label class="col-form-label" for="golongan_id">Golongan</label>
                            </div>
                            <div class="col-md-10">
                                <select class="form-control select2 @error('golongan_id') is-invalid @enderror" name="golongan_id" id="golongan_id" required>
                                    <option value="" disabled selected>== Pilih Golongan ==</option>
                                    @foreach ($golongan as $i)
                                        <option value="{{ $i->id }}" {{ $i->id == old('golongan_id') ? 'selected' : '' }}>{{ $i->name }}</option>
                                    @endforeach
                                </select>
                                @error('golongan_id')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                            </div>
                        </div>
                        {{-- Fungsional --}}
                        <div class="row mb-2">
                            <div class="col-md-2">
                                <label class="col-form-label" for="fungsional_id">Fungsional</label>
                            </div>
                            <div class="col-md-10">
                                <select class="form-control select2 @error('fungsional_id') is-invalid @enderror" name="fungsional_id" id="fungsional_id" required>
                                    <option value="" disabled selected>== Pilih Fungsional ==</option>
                                    @foreach ($fungsional as $i)
                                        <option value="{{ $i->id }}" {{ $i->id == old('fungsional_id') ? 'selected' : '' }}>{{ $i->name }}</option>
                                    @endforeach
                                </select>
                                @error('fungsional_id')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                            </div>
                        </div>
                        {{-- Role --}}
                        <div class="row mb-2">
                            <div class="col-md-2">
                                <label class="col-form-label" for="role_id">Role</label>
                            </div>
                            <div class="col-md-10">
                                <select class="form-control select2 @error('role_id') is-invalid @enderror" name="role_id" id="role_id" required>
                                    <option value="" disabled selected>== Pilih Fungsional ==</option>
                                    @foreach ($role as $i)
                                        <option value="{{ $i->id }}" {{ $i->id == old('role_id') ? 'selected' : '' }}>{{ $i->name }}</option>
                                    @endforeach
                                </select>
                                @error('role_id')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                            </div>
                        </div>
                        {{-- submit --}}
                        <div class="row mt-5">
                            <div class="col">
                                <a href="{{ route('user.index') }}" class="btn btn-primary">Kembali</a>
                                <button type="submit" class="btn btn-success float-right"><i class="fa fa-save"></i> Simpan</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
