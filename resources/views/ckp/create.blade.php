@extends('layouts.main')

@section('container')
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0">{{ $title }}</h1>
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('index') }}">Dashboard</a></li>
            <li class="breadcrumb-item text-gray-800">CKP</li>
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
                                    id="bulan" name="bulan" required>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-12 mb-4">
                <h4 class="mt-5"><b>Daftar Kegiatan</b></h4>
                {{-- Kegiatan --}}
                <div class="card mt-2">
                    <div class="card-body">
                        {{-- Jenis kegiatan --}}
                        <div class="row mb-2">
                            <div class="col-md-2">
                                <label class="col-form-label" for="jenis">Jenis Kegiatan</label>
                            </div>
                            <div class="col-md-10">
                                <select class="form-control select2" name="jenis[]" required>
                                    <option value="utama" selected>Utama</option>
                                    <option value="tambahan">Tambahan</option>
                                </select>
                            </div>
                        </div>
                        {{-- kegiatan --}}
                        <div class="row mb-2">
                            <div class="col-md-2">
                                <label class="col-form-label" for="kegiatan">Uraian</label>
                            </div>
                            <div class="col-md-10">
                                <input type="text" name="kegiatan[]"
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
                                <label class="col-form-label" for="tim_id">Tim</label>
                            </div>
                            <div class="col-md-10">
                                <select class="form-control select2" name="tim_id[]" required>
                                    <option value="" disabled selected>== Pilih Tim ==</option>
                                    @foreach ($tim as $t)
                                        <option value="{{ $t->id }}">{{ $t->tim->name }} ({{ $t->tahun }})</option>
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
                                <input type="date" name="tgl_mulai[]" class="form-control" value="">
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
                                <input type="date" name="tgl_selesai[]" class="form-control" value="">
                                @error('tgl_selesai')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                        </div>
                        {{-- Kode Butir Kegiatan --}}
                        <div class="row mb-2">
                            <div class="col-md-2">
                                <label class="col-form-label" for="kredit_id">Kode Butir Kegiatan</label>
                            </div>
                            <div class="col-md-10">
                                <select class="form-control select2 select_butir" name="kredit_id[]" required>
                                    <option value="" selected>== Pilih Butir ==</option>   
                                    <option value="0" >Lainnya</option>
                                    @foreach ($butir as $b)
                                        <option value="{{ $b->id }}">
                                            {{ $b->kode_perka . ' - ' . $b->name . ($b->kegiatan ? ' - ' . $b->kegiatan : '') }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <input type="hidden" name="satuan[]" class="form-control" value="">
                            </div>
                        </div>
                        {{-- satuan --}}
                        <div class="row mb-2">
                            <div class="col-md-2">
                                <label class="col-form-label" for="satuan">Satuan</label>
                            </div>
                            <div class="col-md-10">
                                <input type="text" name="satuan[]" class="form-control" required value="">
                            </div>
                        </div>
                        {{-- Target --}}
                        <div class="row mb-2">
                            <div class="col-md-2">
                                <label class="col-form-label" for="jml_target">Jumlah Target</label>
                            </div>
                            <div class="col-md-10">
                                <input type="number" name="jml_target[]" class="form-control jml_target" required>
                            </div>
                        </div>
                        {{-- Realisasi --}}
                        <div class="row mb-2">
                            <div class="col-md-2">
                                <label class="col-form-label" for="jml_realisasi">Jumlah Realisasi</label>
                            </div>
                            <div class="col-md-10">
                                <input type="number" name="jml_realisasi[]" class="form-control jml_realisasi"
                                    required>
                            </div>
                        </div>
                        {{-- angka kredit --}}
                        <div class="row mb-2">
                            <div class="col-md-2">
                                <label class="col-form-label" for="angka_kredit">Angka Kredit</label>
                            </div>
                            <div class="col-md-10">
                                <input type="number" name="angka_kredit[]" value="0" class="form-control"
                                    required min="0" step=".0001">
                            </div>
                        </div>
                        {{-- Keterangan --}}
                        <div class="row mb-2">
                            <div class="col-md-2">
                                <label class="col-form-label" for="keterangan">Keterangan</label>
                            </div>
                            <div class="col-md-10">
                                <input type="text" class="form-control" name="keterangan[]" value="">
                            </div>
                        </div>
                         {{-- Usulan Nilai --}}
                         <div class="row mb-2">
                            <div class="col-md-2">
                                <label class="col-form-label" for="nilai_inputan">Usulan Nilai</label>
                            </div>
                            <div class="col-md-10">
                                <input type="number" name="nilai_inputan[]" class="form-control nilai_inputan" min=0 max=100 step="0.5">
                            </div>
                            <div>
                                <input type="hidden" name="nilai_kegiatan[]" class="form-control" value="-1">
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
                <a href="{{ route('ckp.index') }}" class="btn btn-secondary">Batalkan</a>
                <button type="submit" class="btn btn-success float-right"> <i class="fa fa-save"></i> Simpan</button>
            </div>
        </div>

    </form>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/handlebars.js/4.7.6/handlebars.min.js"></script>
    <script id="document-template" type="text/x-handlebars-template">
        <div class="delete_add_more_item">
        <div class="card mt-2">
            <div class="card-body">
                <div>
                    <div class="row mb-2">
                        <div class="col-md-2">
                            <label class="col-form-label" for="jenis">Jenis Kegiatan</label>
                        </div>
                        <div class="col-md-10">
                            <select class="form-control select2" name="jenis[]" required>
                                <option value="utama" selected>Utama</option>
                                <option value="tambahan">Tambahan</option>
                            </select>
                        </div>
                    </div>
                    <div class="row mb-2">
                        <div class="col-md-2">
                            <label class="col-form-label" for="kegiatan">Uraian</label>
                        </div>
                        <div class="col-md-10">
                            <input type="text" name="kegiatan[]"
                                class="form-control @error('kegiatan') is-invalid @enderror" required>
                            @error('kegiatan')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                    </div>
                    <div class="row mb-2">
                        <div class="col-md-2">
                            <label class="col-form-label" for="tim_id">Tim</label>
                        </div>
                        <div class="col-md-10">
                            <select class="form-control select2" name="tim_id[]" required>
                                <option value="" disabled selected>== Pilih Tim ==</option>
                                @foreach ($tim as $t)
                                    <option value="{{ $t->id }}">{{ $t->tim->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>        
                    <div class="row mb-2">
                        <div class="col-md-2">
                            <label class="col-form-label" for="tgl_mulai">Tanggal Mulai</label>
                        </div>
                        <div class="col-md-10">
                            <input type="date" name="tgl_mulai[]" value=""
                                class="form-control">
                            @error('tgl_mulai')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                    </div>
                    <div class="row mb-2">
                        <div class="col-md-2">
                            <label class="col-form-label" for="tgl_selesai">Tanggal Selesai</label>
                        </div>
                        <div class="col-md-10">
                            <input type="date" name="tgl_selesai[]" value=""
                                class="form-control">
                            @error('tgl_selesai')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                    </div>
                    <div class="row mb-2">
                        <div class="col-md-2">
                            <label class="col-form-label" for="kredit_id">Kode Butir Kegiatan</label>
                        </div>
                        <div class="col-md-10">
                            <select class="form-control select2 select_butir" name="kredit_id[]" required>
                                <option value="" selected>== Pilih Butir ==</option>
                                <option value="0" >Lainnya</option>
                                @foreach ($butir as $b)
                                    <option value="{{ $b->id }}">{{$b->kode_perka . ' - ' . $b->name . ($b->kegiatan ? ' - ' . $b->kegiatan : '') }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <input type="hidden" name="satuan[]" class="form-control">
                        </div>
                    </div>
                    
                    <div class="row mb-2">
                        <div class="col-md-2">
                            <label class="col-form-label" for="satuan">Satuan</label>
                        </div>
                        <div class="col-md-10">
                            <input type="text" name="satuan[]" class="form-control" required value="">
                        </div>
                    </div>
                    <div class="row mb-2">
                        <div class="col-md-2">
                            <label class="col-form-label" for="jml_target">Jumlah Target</label>
                        </div>
                        <div class="col-md-10">
                            <input type="number" name="jml_target[]"
                                class="form-control jml_target" required
                                value="{{ $ckp->target ?? '' }}">
                        </div>
                    </div>
                    <div class="row mb-2">
                        <div class="col-md-2">
                            <label class="col-form-label" for="jml_realisasi">Jumlah Realisasi</label>
                        </div>
                        <div class="col-md-10">
                            <input type="number" name="jml_realisasi[]"
                                class="form-control jml_realisasi" required
                                value="{{ $ckp->realisasi ?? '' }}">
                        </div>
                    </div>
                    <div class="row mb-2">
                        <div class="col-md-2">
                            <label class="col-form-label" for="angka_kredit">Angka Kredit</label>
                        </div>
                        <div class="col-md-10">
                            <input type="number" name="angka_kredit[]" value="0"
                                class="form-control" required min="0" step=".0001">
                        </div>
                    </div>
                    <div class="row mb-2">
                        <div class="col-md-2">
                            <label class="col-form-label" for="keterangan">Keterangan</label>
                        </div>
                        <div class="col-md-10">
                            <input type="text" class="form-control" name="keterangan[]" value="">
                               
                        </div>
                    </div>
                    <div class="row mb-2">
                        <div class="col-md-2">
                            <label class="col-form-label" for="nilai_kegiatan">Usulan Nilai</label>
                        </div>
                        <div class="col-md-10">
                            <input type="number" name="nilai_inputan[]" class="form-control nilai_inputan" min=0 max=100 step="0.5">
                        </div>
                        <div>
                            <input type="hidden" name="nilai_kegiatan[]" class="form-control" value="-1">
                        </div>
                    </div>
                </div>
                
                <button class="btn btn-sm btn-danger removeaddmore float-right" type="button">Hapus <i
                class="fa fa-times"></i></button>   
            </div>
        </div>
        </div>     
    </script>
    <script type="text/javascript">
        $('.select2').select2();
        $(document).ready(function() {
            $(window).keydown(function(event) {
                if (event.keyCode == 13) {
                    event.preventDefault();
                    return false;
                }
            });
        });
        $(document).on('change', '.select_butir', function(e) {
            var kredit_id = parseInt(e.target.value, 10)
                if (kredit_id > 0){
                    var butir = {!! json_encode($butir) !!};
                    var as= $(butir).filter(function (i,n){return n.id === kredit_id});
                    var satuan = as[0].satuan;
                    $(this).parent().next().find('input').val(satuan);
                    $(this).parent().next().find('input').attr("disabled", false);
                    $(this).parent().parent().next().find('input').val(satuan);
                    $(this).parent().parent().next().find('input').attr("disabled", true);
                } else {
                    $(this).parent().next().find('input').val("");
                    $(this).parent().next().find('input').attr("disabled", true);
                    $(this).parent().parent().next().find('input').val("");
                    $(this).parent().parent().next().find('input').attr("disabled", false);
                    $(this).parent().parent().next().find('input').attr("placeholder", "Satuan wajib diisi");
                }
        });

        $(document).on('change', '.nilai_inputan', function(e) {
            var nilai = parseInt(e.target.value, 10)
                if (nilai > 0){
                    $(this).parent().next().find('input').val(nilai);
                } else {
                    $(this).parent().next().find('input').val(-1);
                }
        });

        $(document).on('click', '#addMore', function() {
            $('.add-more').show();
            var source = $("#document-template").html();
            $("#addRow").append(source);
        });
        $(document).on('click', '.removeaddmore', function(event) {
            $(this).closest('.delete_add_more_item').remove();
        });

        $(document).on('change', '.jml_target', function() {
            var x = $(this).val();
            $(this).parent().parent().next().find('input').attr('max', x);
        });
    </script>
@endsection
