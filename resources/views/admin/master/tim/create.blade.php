@extends('layouts.main')

@section('container')
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0">{{ $title }}</h1>
        <ol class="breadcrumb">
            <li class="breadcrumb-item">Tim Kerja</li>
            <li class="breadcrumb-item text-gray-800">{{ $title }}</li>
        </ol>
    </div>

    <div class="row">
        <div class="col-lg-12 mb-4">
            <div class="card">
                <div class="card-header">
                </div>
                <div class="card-body">
                    <form action="{{ route('tim.store') }}" method="post">
                        @csrf
                        {{-- Jenis Tim --}}
                        <div class="row mb-2">
                            <div class="col-md-2">
                                <label class="col-form-label" for="name">Pilih Jenis Tim</label>
                            </div>
                            <div class="col-md-10">
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input jenis_tim" type="radio" name="jenis_tim"
                                        id="inlineRadio1" value="baru" checked>
                                    <label class="form-check-label" for="inlineRadio1">Tim Baru</label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input jenis_tim" type="radio" name="jenis_tim"
                                        id="inlineRadio2" value="lama">
                                    <label class="form-check-label" for="inlineRadio2">Tim Lama</label>
                                </div>
                            </div>
                        </div>
                        {{-- Nama Tim Baru --}}
                        <div class="row mb-2" id="baru">
                            <div class="col-md-2">
                                <label class="col-form-label" for="name">Nama Tim</label>
                            </div>
                            <div class="col-md-10">
                                <input type="text" id="name" name="name" value="{{ old('name') }}"
                                    class="nama_baru form-control @error('name') is-invalid @enderror" required>
                                @error('name')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                        </div>
                        {{-- Nama Tim Lama --}}
                        <div class="row mb-2" id="lama" style="display:none">
                            <div class="col-md-2">
                                <label class="col-form-label" for="tim_id">Nama Tim</label>
                            </div>
                            <div class="col-md-10">
                                <select class="nama_lama form-control select2 @error('tim_id') is-invalid @enderror"
                                    name="tim_id" id="tim_id" required disabled>
                                    <option value="" disabled selected>== Pilih Tim ==</option>
                                    @foreach ($tim as $i)
                                        <option value="{{ $i->id }}" {{ $i->id == old('tim_id') ? 'selected' : '' }}>
                                            {{ $i->name }}</option>
                                    @endforeach
                                </select>
                                @error('tim_id')
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
                                    min="2022" value="{{ date('Y') }}" id="tahun" name="tahun" required>
                            </div>
                        </div>
                        {{-- Satker --}}
                        <div class="row mb-2" id="satker">
                            <div class="col-md-2">
                                <label class="col-form-label" for="satker_id">Satker</label>
                            </div>
                            <div class="col-md-10">
                                <select class="nama_satker form-control select2 @error('satker_id') is-invalid @enderror"
                                    name="satker_id" id="satker_id" required>
                                    <option value="" disabled selected>== Pilih Satker ==</option>
                                    @foreach ($satker as $i)
                                        <option value="{{ $i->id }}"
                                            {{ $i->id == old('satker_id') ? 'selected' : '' }}>{{ $i->name }}
                                        </option>
                                    @endforeach
                                </select>
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
                                            {{ $i->id == old('ketua_id') ? 'selected' : '' }}>{{ $i->name }}</option>
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
    <script>
        $(document).on('change', '.jenis_tim', function(e) {
            var jenis_tim = e.target.value;
            if (jenis_tim == 'baru') {
                $('#baru').show();
                $('#lama').hide();
                $('#satker').show();
                $('.nama_lama').attr("disabled", true);
                $('.nama_baru').attr("disabled", false);
                $('.nama_satker').attr("disabled", false);
            } else {
                $('#lama').show();
                $('#baru').hide();
                $('#satker').hide();
                $('.nama_baru').attr("disabled", true);
                $('.nama_lama').attr("disabled", false);
                $('.nama_satker').attr("disabled", true);

            }
        });
    </script>
@endsection
