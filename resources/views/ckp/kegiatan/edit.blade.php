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
                        {{-- Jenis kegiatan --}}
                        <div class="row mb-2">
                            <div class="col-md-2">
                                <label class="col-form-label" for="jenis">Jenis Kegiatan</label>
                            </div>
                            <div class="col-md-10">
                                <select class="form-control select2" name="jenis" required>
                                    <option value="utama" {{ $kegiatan->jenis == 'utama' ? 'selected' : '' }}>Utama
                                    </option>
                                    <option value="tambahan" {{ $kegiatan->jenis == 'tambahan' ? 'selected' : '' }}>
                                        Tambahan</option>
                                </select>
                            </div>
                        </div>
                        {{-- kegiatan --}}
                        <div class="row mb-2">
                            <div class="col-md-2">
                                <label class="col-form-label" for="name">Uraian</label>
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
                                        <option value="{{ $t->id }}"
                                            {{ $t->id == $kegiatan->tim_id ? 'selected' : '' }}>{{ $t->name }}
                                        </option>
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
                                <input type="date" name="tgl_selesai" value="{{ $kegiatan->tgl_selesai }}"
                                    class="form-control">
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
                                <select class="form-control select2" name="kredit_id">
                                    <option value="" disabled selected>== Pilih Butir ==</option>
                                    <option value="0" {{ $kegiatan->kredit_id ? '' : 'selected' }}>Lainnya</option>
                                    @foreach ($butir as $b)
                                        <option value="{{ $b->id }}"
                                            {{ $b->id == $kegiatan->kredit_id ? 'selected' : '' }}>
                                            {{ $b->kode_perka . ' - ' . $b->name . ($b->kegiatan ? ' - ' . $b->kegiatan : '') }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <input type="hidden" name="satuan[]" class="form-control"
                                    value="{{ $kegiatan->satuan }}" {{ $kegiatan->kredit_id ? '' : 'disabled' }}>
                            </div>
                        </div>
                        {{-- satuan --}}
                        <div class="row mb-2">
                            <div class="col-md-2">
                                <label class="col-form-label" for="satuan">Satuan</label>
                            </div>
                            <div class="col-md-10">
                                <input type="text" name="satuan" class="form-control" required value="{{ $kegiatan->satuan }}" {{ $kegiatan->kredit_id ? 'disabled' : '' }}>
                            </div>
                        </div>
                        {{-- Target --}}
                        <div class="row mb-2">
                            <div class="col-md-2">
                                <label class="col-form-label" for="jml_target">Jumlah Target</label>
                            </div>
                            <div class="col-md-10">
                                <input type="number" id="jml_target" name="jml_target" class="form-control" required
                                    value="{{ $kegiatan->jml_target ?? '' }}">
                            </div>
                        </div>
                        {{-- Realisasi --}}
                        <div class="row mb-2">
                            <div class="col-md-2">
                                <label class="col-form-label" for="jml_realisasi">Jumlah Realisasi</label>
                            </div>
                            <div class="col-md-10">
                                <input type="number" id="jml_realisasi" name="jml_realisasi" class="form-control" required
                                    value="{{ $kegiatan->jml_realisasi ?? '' }}" max="{{ $kegiatan->jml_target ?? '' }}">
                            </div>
                        </div>
                        {{-- angka kredit --}}
                        <div class="row mb-2">
                            <div class="col-md-2">
                                <label class="col-form-label" for="angka_kredit">Angka Kredit</label>
                            </div>
                            <div class="col-md-10">
                                <input type="number" name="angka_kredit" class="form-control" required
                                    value="{{ $kegiatan->angka_kredit ?? 0 }}" min="0" step=".0001">
                            </div>
                        </div>
                        {{-- Keterangan --}}
                        <div class="row mb-2">
                            <div class="col-md-2">
                                <label class="col-form-label" for="keterangan">Keterangan</label>
                            </div>
                            <div class="col-md-10">
                                <input type="text" class="form-control" name="keterangan"
                                    value="{{ $kegiatan->keterangan }}">
                            </div>
                        </div>
                        {{-- Usulan Nilai --}}
                        <div class="row mb-2">
                            <div class="col-md-2">
                                <label class="col-form-label" for="nilai_inputan">Usulan Nilai</label>
                            </div>
                            <div class="col-md-10">
                                <input type="number" name="nilai_inputan" class="form-control nilai_inputan" min=0 max=100 step="0.5" value="{{ $k->nilai_kegiatan ?? '' }}">
                            </div>
                            <div>
                                <input type="hidden" name="nilai_kegiatan" class="form-control" value="{{ $kegiatan->nilai_kegiatan ?? '-1' }}">
                            </div>
                        </div>
                        {{-- submit --}}
                        <div class="row mt-5">
                            <div class="col">
                                <a href="{{ URL::previous() }}" class="btn btn-secondary">Kembali</a>
                                <button type="submit" class="btn btn-success float-right"><i class="fa fa-save"></i>
                                    Simpan</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <script type="text/javascript">
    // document.getElementById('jml_realisasi').max = $("#jml_target").val();
    //     $(document).on('change', '#jml_target', function() {
    //         var x = $("#jml_target").val();
    //         document.getElementById('jml_realisasi').max = x;
    //     });

        $(document).on('change', '.select_butir', function(e) {
            var kredit_id = parseInt(e.target.value, 10)
            if (kredit_id > 0) {
                var butir = {!! json_encode($butir) !!};
                var as = $(butir).filter(function(i, n) {
                    return n.id === kredit_id
                });
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

        $(document).on('change', '#jml_target', function() {
            var x = $(this).val();
            console.log(x);
            $(this).parent().parent().next().find('input').attr('max', x);
        });
    </script>
@endsection
