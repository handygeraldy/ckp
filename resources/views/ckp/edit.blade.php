@extends('layouts.main')

@section('container')
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0">{{ $title }}</h1>
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('index') }}">Dashboard</a></li>
            <li class="breadcrumb-item text-gray-800">CKP</li>
            <li class="breadcrumb-item text-gray-800">Edit</li>
        </ol>
    </div>

    <form action="{{ route('ckp.update', $ckp->id) }}" method="post">
        @method('patch')
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
                                    value="{{ $ckp->tahun . '-' . $ckp->bulan }}"
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
                @foreach($kegiatan as $k)
                <div class="card mt-2">
                    <div class="card-body">
                        
                        <div class="delete_add_more_item">
                            {{-- kegiatan --}}
                            <div class="row mb-2">
                                <div class="col-md-2">
                                    <label class="col-form-label" for="kegiatan">Kegiatan</label>
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
                            {{-- tim --}}
                            <div class="row mb-2">
                                <div class="col-md-2">
                                    <label class="col-form-label" for="tim_id">Tim</label>
                                </div>
                                <div class="col-md-10">
                                    <select class="form-control select2" name="tim_id[]" required>
                                        <option value="" disabled selected>== Pilih Tim ==</option>
                                        @foreach ($tim as $t)
                                            <option value="{{ $t->id }}" {{ $t->id == $k->tim_id ? 'selected' : '' }}>>{{ $t->name }}</option>
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
                                    <input type="date" name="tgl_mulai[]" value="{{ $k->tgl_mulai }}"
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
                                    <label class="col-form-label" for="tgl_selesai">Tanggal Selesai</label>
                                </div>
                                <div class="col-md-10">
                                    <input type="date" name="tgl_selesai[]" value="{{ $k->tgl_selesai }}"
                                        class="form-control">
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
                                    <select class="form-control select2" name="satuan_id[]" required>
                                        <option value="" disabled selected>== Pilih Satuan ==</option>
                                        @foreach ($satuan as $s)
                                            <option value="{{ $s->id }}" {{ $s->id == $k->satuan_id ? 'selected' : '' }}>>{{ $s->name }}</option>
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
                                    <input type="number" name="jml_target[]" value="{{ $k->jml_target }}"
                                        class="form-control jml_target" required>
                                </div>
                            </div>
                            {{-- Realisasi --}}
                            <div class="row mb-2">
                                <div class="col-md-2">
                                    <label class="col-form-label" for="jml_realisasi">Jumlah Realisasi</label>
                                </div>
                                <div class="col-md-10">
                                    <input type="number" name="jml_realisasi[]" value="{{ $k->jml_realisasi }}"
                                        class="form-control jml_realisasi" required>
                                </div>
                            </div>
                            {{-- Kode Butir Kegiatan --}}
                            <div class="row mb-2">
                                <div class="col-md-2">
                                    <label class="col-form-label" for="kredit_id">Kode Butir Kegiatan</label>
                                </div>
                                <div class="col-md-10">
                                    <select class="form-control select2" name="kredit_id[]">
                                        <option value="" disabled selected>== Pilih Butir ==</option>
                                        @foreach ($butir as $b)
                                            <option value="{{ $b->id }}" {{ $b->id == $k->kredit_id ? 'selected' : '' }}>>{{ $b->kode_perka . ' - ' . $b->name . ($b->kegiatan ? ' - ' . $b->kegiatan : '') }}</option>
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
                                    <input type="number" name="angka_kredit[]" value="{{ $k->angka_kredit }}"
                                        class="form-control" required min="0" step=".0001">
                                </div>
                            </div>
                            {{-- Keterangan --}}
                            <div class="row mb-2">
                                <div class="col-md-2">
                                    <label class="col-form-label" for="keterangan">Keterangan</label>
                                </div>
                                <div class="col-md-10">
                                    <input type="text" class="form-control" name="keterangan[]" value="{{ $k->keterangan }}">    
                                </div>
                            </div>
                            <button class="btn btn-sm btn-danger removeaddmore float-right" type="button">Hapus <i
                                class="fa fa-times"></i></button>
                        </div>                       
                    </div>
                </div>
                @endforeach
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
                            <label class="col-form-label" for="kegiatan">Kegiatan</label>
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
                                    <option value="{{ $t->id }}">{{ $t->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>        
                    <div class="row mb-2">
                        <div class="col-md-2">
                            <label class="col-form-label" for="tgl_mulai">Tanggal Mulai</label>
                        </div>
                        <div class="col-md-10">
                            <input type="date" name="tgl_mulai[]"
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
                            <input type="date" name="tgl_selesai[]"
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
                            <label class="col-form-label" for="satuan_id">Satuan</label>
                        </div>
                        <div class="col-md-10">
                            <select class="form-control select2" name="satuan_id[]" required>
                                <option value="" disabled selected>== Pilih Satuan ==</option>
                                @foreach ($satuan as $s)
                                    <option value="{{ $s->id }}">{{ $s->name }}</option>
                                @endforeach
                            </select>
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
                            <label class="col-form-label" for="kredit_id">Kode Butir Kegiatan</label>
                        </div>
                        <div class="col-md-10">
                            <select class="form-control select2" name="kredit_id[]">
                                <option value="" disabled selected>== Pilih Butir ==</option>
                                @foreach ($butir as $b)
                                    <option value="{{ $b->id }}">{{$b->kode_perka . ' - ' . $b->name . ($b->kegiatan ? ' - ' . $b->kegiatan : '') }}</option>
                                @endforeach
                            </select>
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
                            <input type="text" class="form-control" name="keterangan[]">
                               
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
            console.log(x);
            $(this).parent().parent().next().find('input').attr('max', x);
        });
    </script>
@endsection
