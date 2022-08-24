@extends('layouts.main')

@section('container')
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0">{{ $title }}</h1>
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('index') }}">Dashboard</a></li>
            <li class="breadcrumb-item text-gray-800">Master</li>
            <li class="breadcrumb-item text-gray-800">CKP</li>
            <li class="breadcrumb-item text-gray-800">Kegiatan</li>
            <li class="breadcrumb-item text-gray-800">Edit</li>
        </ol>
    </div>

    <div class="row">
        <div class="col-lg-12 mb-4">
            <div class="card">
                <div class="card-header">
                </div>
                <div class="card-body">
                    <form action="{{ route('kegiatan.update', $kegiatan->id) }}" method="post">                 
                        @method('patch')
                        @csrf
                        {{-- ckp_id --}}
                        <input type="hidden" name="ckp_id" value="{{ $kegiatan->ckp_id }}">
                        {{-- kegiatan --}}
                        <div class="row mb-2">
                            <div class="col-md-2">
                                <label class="col-form-label" for="name">Kegiatan</label>
                            </div>
                            <div class="col-md-10">
                                <input type="text" name="name" value="{{ $kegiatan->name }}"
                                    class="form-control @error('name') is-invalid @enderror" required>
                                @error('name')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                        </div>                            
                        {{-- tim --}}
                        <div class="row mb-2">
                            <div class="col-md-2">
                                <label class="col-form-label" for="tim_id">Tim</label>
                            </div>
                            <div class="col-md-10">
                                <select class="form-control select2" name="tim_id" required>
                                    <option value="" disabled selected>== Pilih Tim ==</option>
                                    @foreach ($tim as $t)
                                        <option value="{{ $t->id }}" {{ $t->id == ($kegiatan->tim_id) ? 'selected' : '' }}>{{ $t->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>                     
                        {{-- tgl_mulai --}}
                        <div class="row mb-2">
                            <div class="col-md-2">
                                <label class="col-form-label" for="tgl_mulai">Tanggal Mulai</label>
                            </div>
                            <div class="col-md-10">
                                <input type="date" name="tgl_mulai" value="{{ $kegiatan->tgl_mulai }}"
                                    class="form-control" required>
                                @error('tgl_mulai')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                        </div>
                        {{-- tgl_selesai --}}
                        <div class="row mb-2">
                            <div class="col-md-2">
                                <label class="col-form-label" for="tgl_selesai">Tanggal Selesai</label>
                            </div>
                            <div class="col-md-10">
                                <input type="date" name="tgl_selesai" value="{{ $kegiatan->tgl_selesai }}"
                                    class="form-control" required>
                                @error('tgl_selesai')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                        </div>                            
                        {{-- satuan --}}
                        <div class="row mb-2">
                            <div class="col-md-2">
                                <label class="col-form-label" for="satuan_id">Satuan</label>
                            </div>
                            <div class="col-md-10">
                                <select class="form-control select2" name="satuan_id" required>
                                    <option value="" disabled selected>== Pilih Satuan ==</option>
                                    @foreach ($satuan as $s)
                                        <option value="{{ $s->id }}" {{ $s->id == ($kegiatan->satuan_id) ? 'selected' : '' }}>{{ $s->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        {{-- Target --}}
                        <div class="row mb-2">
                            <div class="col-md-2">
                                <label class="col-form-label" for="jml_target">Jumlah Target</label>
                            </div>
                            <div class="col-md-10">
                                <input type="number" name="jml_target"
                                    class="form-control" required
                                    value="{{ $kegiatan->jml_target ?? '' }}">
                            </div>
                        </div>
                        {{-- Realisasi --}}
                        <div class="row mb-2">
                            <div class="col-md-2">
                                <label class="col-form-label" for="jml_realisasi">Jumlah Realisasi</label>
                            </div>
                            <div class="col-md-10">
                                <input type="number" name="jml_realisasi"
                                    class="form-control" required
                                    value="{{ $kegiatan->jml_realisasi ?? '' }}">
                            </div>
                        </div>
                        {{-- Kode Butir Kegiatan --}}
                        <div class="row mb-2">
                            <div class="col-md-2">
                                <label class="col-form-label" for="kredit_id">Kode Butir Kegiatan</label>
                            </div>
                            <div class="col-md-10">
                                <select class="form-control select2" name="kredit_id">
                                    <option value="" disabled selected>== Pilih Butir ==</option>
                                    @foreach ($butir as $b)
                                        <option value="{{ $b->id }}" {{ $b->id == ($kegiatan->kredit_id) ? 'selected' : '' }}>{{ $b->kode_perka . ' - ' . $b->name . ($b->kegiatan ? ' - ' . $b->kegiatan : '') }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        {{-- angka kredit --}}
                        <div class="row mb-2">
                            <div class="col-md-2">
                                <label class="col-form-label" for="angka_kredit">Angka Kredit</label>
                            </div>
                            <div class="col-md-10">
                                <input type="number" name="angka_kredit"
                                    class="form-control" required
                                    value="{{ $kegiatan->angka_kredit ?? 0 }}" min="0" step=".0001">
                            </div>
                        </div>
                        {{-- Keterangan --}}
                        <div class="row mb-2">
                            <div class="col-md-2">
                                <label class="col-form-label" for="keterangan">Keterangan</label>
                            </div>
                            <div class="col-md-10">
                                <input type="text" class="form-control" name="keterangan" value="{{ $kegiatan->keterangan }}">
                                   
                            </div>
                        </div>
                        {{-- submit --}}
                        <div class="row mt-5">
                            <div class="col">
                                <a href="{{ URL::previous() }}" class="btn btn-primary">Kembali</a>
                                <button type="submit" class="btn btn-success float-right"><i class="fa fa-save"></i> Simpan</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
