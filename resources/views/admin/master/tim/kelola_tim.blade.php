@extends('layouts.main')

@section('container')
<div class="d-sm-flex align-items-center justify-content-between mb-4">
    <h1 class="h3 mb-0">{{ $title }}</h1>
    <ol class="breadcrumb">
        <li class="breadcrumb-item">Tim Kerja</li>
        <li class="breadcrumb-item text-gray-800">{{ $title }}</li>
    </ol>
</div>

<form action="{{ route('projek.store') }}" method="post">
    <div class="row">
        <div class="col-lg-12 mb-1">
            <div class="card">
                <div class="card-body">
                    @csrf
                    <input type="hidden" name="periode_tim_id" value="{{ $id }}">
                    <h2><b> Kerja</b></h2>
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
                    {{-- Kode Sasaran Kinerja --}}
                    <div class="row mb-2">
                        <div class="col-md-2">
                            <label class="col-form-label" for="sasaran">Sasaran Kinerja</label>
                        </div>
                        <div class="col-md-10">
                            <select class="form-control select2 select_butir" name="sasaran[]" required>
                                <option value="" selected>== Pilih Sasaran Kinerja ==</option>
                                @foreach ($iku as $i)
                                <option value="{{ $i->id }}">
                                    {{ $i->sasaran}}
                                </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    {{-- kegiatan --}}
                    <div class="row mb-2">
                        <div class="col-md-2">
                            <label class="col-form-label" for="kegiatan">Uraian</label>
                        </div>
                        <div class="col-md-10">
                            <input type="text" name="kegiatan[]" class="form-control @error('kegiatan') is-invalid @enderror" required>
                            @error('kegiatan')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                            @enderror
                        </div>
                    </div>
                    {{-- tgl_mulai --}}
                    <div class="row mb-2">
                        <div class="col-md-2">
                            <label class="col-form-label" for="tgl_mulai">Periode Awal</label>
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
                            <label class="col-form-label" for="tgl_selesai">Periode Akhir</label>
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
                    {{-- Kode Penanggung Jawab --}}
                    {{-- <div class="row mb-2">
                            <div class="col-md-2">
                                <label class="col-form-label" for="kredit_id">Penanggung Jawab</label>
                            </div>
                            <div class="col-md-10">
                                <select class="form-control select2 select_butir" name="kredit_id[]" required>
                                    <option value="" selected>== Penanggung Jawab ==</option>   
                                    @foreach ($list_anggota as $i)
                                        <option value="{{ $i->id }}">
                    {{ $i->name}}
                    </option>
                    @endforeach
                    </select>
                </div>
                <div>
                    <input type="hidden" name="satuan[]" class="form-control" value="">
                </div>
            </div> --}}
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
            <a href="{{ route('tim.show', $id) }}" class="btn btn-secondary">Batalkan</a>
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
                            <label class="col-form-label" for="sasaran">Sasaran Kinerja</label>
                        </div>
                        <div class="col-md-10">
                            <select class="form-control select2 select_butir" name="sasaran[]" required>
                                <option value="" selected>== Pilih Sasaran Kinerja ==</option>   
                                @foreach ($iku as $i)
                                    <option value="{{ $i->id }}">
                                        {{ $i->sasaran}}
                                    </option>
                                @endforeach
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
                            <label class="col-form-label" for="tgl_mulai">Periode Awal</label>
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
                            <label class="col-form-label" for="tgl_selesai">Periode Akhir</label>
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

    $(document).on('change', '.nilai_inputan', function(e) {
        var nilai = parseInt(e.target.value, 10)
        if (nilai > 0) {
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