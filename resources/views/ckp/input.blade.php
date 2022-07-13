@extends('layouts.main')

@section('container')
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0">{{ $title }}</h1>
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('index') }}">Dashboard</a></li>
            <li class="breadcrumb-item text-gray-800">input</li>
        </ol>
    </div>

    <form action="{{ route('ckp.store') }}" method="post">
        <div class="row">
            <div class="col-lg-12 mb-1">
                <div class="card">
                    <div class="card-header">
                        <p class="alert alert-warning" role="alert">Gunakan Google Chrome / Microsoft Edge untuk mengisi
                            form
                            CKP</p>
                    </div>
                    <div class="card-body">
                        @csrf
                        {{-- bulan --}}
                        <div class="row mb-2">
                            <div class="col-md-2">
                                <label class="col-form-label" for="bulan">Bulan</label>
                            </div>
                            <div class="col-md-10">
                                <input class="form-control @error('bulan') is-invalid @enderror" type="month"
                                    id="bulan" name="bulan" required value="{{ $spj->bulan ?? '' }}">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-12 mb-4">

                {{-- Kegiatan --}}
                <div class="card mt-1">
                    <div class="card-header">
                        <h5>Daftar Kegiatan</h5>
                    </div>
                    <div class="card-body">
                        <div>
                            {{-- kegiatan --}}
                            <div class="row mb-2">
                                <div class="col-md-2">
                                    <label class="col-form-label" for="kegiatan">Kegiatan</label>
                                </div>
                                <div class="col-md-10">
                                    <input type="text" id="kegiatan" name="kegiatan"
                                        class="form-control @error('kegiatan') is-invalid @enderror" required>
                                    @error('kegiatan')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                            </div>                            
                            {{-- tim --}}
                            <div class="row mb-2">
                                <div class="col-md-2">
                                    <label class="col-form-label" for="tim">Tim</label>
                                </div>
                                <div class="col-md-10">
                                    <select class="form-control select2" name="tim" id="tim" required>
                                        <option value="" disabled selected>== Pilih Tim ==</option>
                                        @foreach ($tim as $t)
                                            <option value="{{ $t->id }}">{{ $t->tim }}</option>
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
                                    <input type="date" id="tgl_mulai" name="tgl_mulai"
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
                                    <input type="date" id="tgl_selesai" name="tgl_selesai"
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
                                    <label class="col-form-label" for="satuan">Satuan</label>
                                </div>
                                <div class="col-md-10">
                                    <select class="form-control select2" name="satuan" id="satuan" required>
                                        <option value="" disabled selected>== Pilih Satuan ==</option>
                                        @foreach ($satuan as $s)
                                            <option value="{{ $s->id }}">{{ $s->nama_satuan }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            {{-- Target --}}
                            <div class="row mb-2">
                                <div class="col-md-2">
                                    <label class="col-form-label" for="target">Jumlah Target</label>
                                </div>
                                <div class="col-md-10">
                                    <input type="number" id="target" name="target"
                                        class="form-control" required
                                        value="{{ $ckp->target ?? '' }}">
                                </div>
                            </div>
                            {{-- Realisasi --}}
                            <div class="row mb-2">
                                <div class="col-md-2">
                                    <label class="col-form-label" for="realisasi">Jumlah Realisasi</label>
                                </div>
                                <div class="col-md-10">
                                    <input type="number" id="realisasi" name="realisasi"
                                        class="form-control" required
                                        value="{{ $ckp->realisasi ?? '' }}">
                                </div>
                            </div>
                            {{-- Kode Butir Kegiatan --}}
                            <div class="row mb-2">
                                <div class="col-md-2">
                                    <label class="col-form-label" for="kredit_id">Kode Butir Kegiatan</label>
                                </div>
                                <div class="col-md-10">
                                    <select class="form-control select2" name="kredit_id" id="kredit_id" required>
                                        <option value="" disabled selected>== Pilih Butir ==</option>
                                        @foreach ($butir as $b)
                                            <option value="{{ $b->id }}">{{ $b->uraian }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            {{-- Keterangan --}}
                            <div class="row mb-2">
                                <div class="col-md-2">
                                    <label class="col-form-label" for="ket">Keterangan</label>
                                </div>
                                <div class="col-md-10">
                                    <input type="text" class="form-control" name="ket" id="ket" required>
                                       
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="add-more" style="display: none;">
                    <div id="addRow" class="addRow">
                    </div>
                </div>
                <button id="addMore" type="button" class="btn btn-primary float-right my-2">
                    Tambah Kegiatan <i class="fa fa-plus"></i>
                </button>


        
                
                
            </div>
        </div>
        {{-- submit --}}
        <div class="row">
            <div class="col-12">
                <a href="{{ route('index') }}" class="btn btn-secondary">Batalkan</a>
                <button type="submit" class="btn btn-success float-right"> <i class="fa fa-save"></i> Simpan</button>
            </div>
        </div>

    </form>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/handlebars.js/4.7.6/handlebars.min.js"></script>
    <script id="document-template" type="text/x-handlebars-template"><div class="delete_add_more_item" id="delete_add_more_item">
        <div class="card mt-2">
            <div class="card-body">
                <div>
                    {{-- kegiatan --}}
                    <div class="row mb-2">
                        <div class="col-md-2">
                            <label class="col-form-label" for="kegiatan">Kegiatan</label>
                        </div>
                        <div class="col-md-10">
                            <input type="text" id="kegiatan" name="kegiatan"
                                class="form-control @error('kegiatan') is-invalid @enderror" required>
                            @error('kegiatan')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                    </div>                            
                    {{-- tim --}}
                    <div class="row mb-2">
                        <div class="col-md-2">
                            <label class="col-form-label" for="tim">Tim</label>
                        </div>
                        <div class="col-md-10">
                            <select class="form-control select2" name="tim" id="tim" required>
                                <option value="" disabled selected>== Pilih Tim ==</option>
                                @foreach ($tim as $t)
                                    <option value="{{ $t->id }}">{{ $t->tim }}</option>
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
                            <input type="date" id="tgl_mulai" name="tgl_mulai"
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
                            <input type="date" id="tgl_selesai" name="tgl_selesai"
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
                            <label class="col-form-label" for="satuan">Satuan</label>
                        </div>
                        <div class="col-md-10">
                            <select class="form-control select2" name="satuan" id="satuan" required>
                                <option value="" disabled selected>== Pilih Satuan ==</option>
                                @foreach ($satuan as $s)
                                    <option value="{{ $s->id }}">{{ $s->nama_satuan }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    {{-- Target --}}
                    <div class="row mb-2">
                        <div class="col-md-2">
                            <label class="col-form-label" for="target">Jumlah Target</label>
                        </div>
                        <div class="col-md-10">
                            <input type="number" id="target" name="target"
                                class="form-control" required
                                value="{{ $ckp->target ?? '' }}">
                        </div>
                    </div>
                    {{-- Realisasi --}}
                    <div class="row mb-2">
                        <div class="col-md-2">
                            <label class="col-form-label" for="realisasi">Jumlah Realisasi</label>
                        </div>
                        <div class="col-md-10">
                            <input type="number" id="realisasi" name="realisasi"
                                class="form-control" required
                                value="{{ $ckp->realisasi ?? '' }}">
                        </div>
                    </div>
                    {{-- Kode Butir Kegiatan --}}
                    <div class="row mb-2">
                        <div class="col-md-2">
                            <label class="col-form-label" for="kredit_id">Kode Butir Kegiatan</label>
                        </div>
                        <div class="col-md-10">
                            <select class="form-control select2" name="kredit_id" id="kredit_id" required>
                                <option value="" disabled selected>== Pilih Butir ==</option>
                                @foreach ($butir as $b)
                                    <option value="{{ $b->id }}">{{ $b->uraian }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>
                
            <button class="btn btn-sm btn-danger removeaddmore float-right" type="button">Hapus <i
                class="fa fa-times"></i></button>   
            </div>
        </div>
                           
    </script>
    <script type="text/javascript">
        $('.select2').select2();
        $(document).on('click', '#addMore', function() {
            $('.add-more').show();
            var source = $("#document-template").html();
            $("#addRow").append(source);
        });

        $(document).on('click', '.removeaddmore', function(event) {
            $(this).closest('.delete_add_more_item').remove();
        });
    </script>
@endsection
