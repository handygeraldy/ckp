@extends('layouts.main')

@section('container')
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0">{{ $title }}</h1>
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('index') }}">Dashboard</a></li>
            <li class="breadcrumb-item">Ganti Password</li>
        </ol>
    </div>

    <div class="row">
        <div class="col-lg-12 mb-4">
            <div class="card">
                <div class="card-body">
                    <form action="{{ route('post.ganti.password') }}" method="post">
                        @csrf
                        <div class="row mb-2">
                            <div class="col-md-2">
                                <label class="col-form-label" for="pass_lama">Password Lama</label>
                            </div>
                            <div class="col-md-10">
                                <input type="password" id="pass_lama" name="pass_lama"
                                    class="form-control @error('pass_lama') is-invalid @enderror" required
                                    placeholder="Pasword Lama">
                                @error('pass_lama')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                        </div>
                        <div class="row mb-2">
                            <div class="col-md-2">
                                <label class="col-form-label" for="pass_baru">Password Baru</label>
                            </div>
                            <div class="col-md-10">
                                <input type="password" id="pass_baru" name="pass_baru"
                                    class="form-control @error('pass_baru') is-invalid @enderror" required
                                    placeholder="Pasword Baru">
                                @error('pass_baru')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                        </div>
                        <div class="row mb-2">
                            <div class="col-md-2">
                                <label class="col-form-label" for="confirm_pass_baru">Konfirmasi Password Baru</label>
                            </div>
                            <div class="col-md-10">
                                <input type="password" id="confirm_pass_baru" name="confirm_pass_baru"
                                    class="form-control @error('confirm_pass_baru') is-invalid @enderror" required
                                    placeholder="Konfirmasi Password Baru">
                                @error('confirm_pass_baru')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                        </div>
                        <div class="row mt-5">
                            <div class="col">
                                <a href="{{ route('index') }}" class="btn btn-secondary">Kembali</a>
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
