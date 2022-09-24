@extends('layouts.main')

@section('container')
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0">{{ $title }}</h1>
        <ol class="breadcrumb">
            <li class="breadcrumb-item">Tim Kerja</li>
            <li class="breadcrumb-item text-gray-800">{{ $title }}</li>
        </ol>
    </div>

    <form action="{{ route('projek.update', $periode_tim_id) }}" method="post">
        @method('patch')
        <div class="row">
            <div class="col-lg-12 mb-1">
                <div class="card">
                    <div class="card-body">
                        @csrf
                        <input type="hidden" name="periode_tim_id" value="{{ $periode_tim_id }}">
                        <input type="hidden" name="project_id" value="{{ $projek->id }}">
                        {{-- Nama Proyek --}}
                        <div class="row mb-2">
                            <div class="col-md-2">
                                <label class="col-form-label" for="nama_proyek">Nama Proyek</label>
                            </div>
                            <div class="col-md-10">
                                <input type="text" id="nama_proyek" name="nama_proyek"
                                    value="{{ old('nama_proyek') ?? $projek->name }}"
                                    class="form-control @error('nama_proyek') is-invalid @enderror" required>
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
                @foreach ($kegiatan as $k)
                    <div class="card mt-2">
                        <div class="card-body">
                            <input type="hidden" name="idkegiatan[]" value="{{ $k->id }}">
                            {{-- Kode Sasaran Kinerja --}}
                            <div class="row mb-2">
                                <div class="col-md-2">
                                    <label class="col-form-label" for="sasaran">Sasaran Kinerja</label>
                                </div>
                                <div class="col-md-10">
                                    <select class="form-control select2 select_butir" name="sasaran[]" required>
                                        <option value="" disabled>== Pilih Sasaran Kinerja ==</option>
                                        @foreach ($iku as $i)
                                            <option value="{{ $i->id }}"
                                                {{ $i->id == $k->iku_id ? 'selected' : '' }}>
                                                {{ $i->sasaran }}
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
                                    <input type="text" name="kegiatan[]" value="{{ $k->name }}"
                                        class="form-control @error('kegiatan') is-invalid @enderror" required>
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
                                    <input type="date" name="tgl_mulai[]" value="{{ $k->periode_awal ?? '' }}"
                                        class="form-control">
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
                                    <input type="date" name="tgl_selesai[]" class="form-control"
                                        value="{{ $k->periode_akhir ?? '' }}">
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
                                    <input type="text" name="satuan[]" class="form-control" required
                                        value="{{ $k->satuan }}">
                                </div>
                            </div>
                            {{-- Target --}}
                            <div class="row mb-2">
                                <div class="col-md-2">
                                    <label class="col-form-label" for="jml_target">Jumlah Target</label>
                                </div>
                                <div class="col-md-10">
                                    <input type="number" name="jml_target[]" class="form-control jml_target"
                                        value="{{ $k->target }}" required>
                                </div>
                            </div>

                        </div>
                    </div>
                @endforeach
                <div class="add-more" style="display: none;">
                    <div id="addRow" class="addRow">
                    </div>
                </div>
            </div>
        </div>
        {{-- submit --}}
        <div class="row">
            <div class="col-12">
                <a href="{{ route('tim.show', $tim->id) }}" class="btn btn-secondary">Batalkan</a>
                <button type="submit" class="btn btn-success float-right"> <i class="fa fa-save"></i> Simpan</button>
            </div>
        </div>

    </form>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/handlebars.js/4.7.6/handlebars.min.js"></script>
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
