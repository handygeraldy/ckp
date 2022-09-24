@extends('layouts.main')
<link rel="stylesheet" href="{{ asset('/css/argon.css?v=1.2.0') }}" type="text/css">
@section('container')
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0"> <b> Dashboard SiMTK Direktorat PMSS</b></h1>

    </div>
    <div class="text-right">
        <span>
            <b> Tahun {{ $tahun }}</b>
        </span>
    </div>
    <div class="accordion" id="accordionEx">
        <div class="row">
            {{-- Jumlah Pegawai --}}
            <div class="col-xl-4 col-md-6 mt-3">
                <div class="card text-white bg-info mb-3" style="max-width: 40rem;">
                    <div class="card-header" style="color: #3ABAF4; text-align:center;">
                        <span style="color: #3ABAF4; font-size: 1.5em"><i class="fa-solid fa-user-tie"></i>
                            Jumlah Pegawai
                        </span>
                    </div>
                    <div class="card-body" style="text-align: center;">
                        <h2 style="color:#fff" class="card-title">{{ $sum_status['0'] }} Orang Pegawai</h2>
                    </div>
                </div>
            </div>
            {{-- Jumlah Tim Kerja --}}
            <div class="col-xl-4 col-md-6 mt-3">
                <div class="card text-white bg-success mb-3" style="max-width: 40rem;">
                    <div class="card-header" style="color: #66BB6A; text-align:center;">
                        <span style="color: #66BB6A; font-size: 1.5em"><i class="fas fa-users"></i>
                            Jumlah Tim Kerja
                        </span>
                    </div>
                    <div class="card-body" style="text-align: center;">
                        <h2 style="color:#fff" class="card-title">{{ $sum_status['1'] }} Tim Kerja</h2>
                    </div>
                </div>
            </div>
            {{-- Jumlah Kegiatan --}}
            <div class="col-xl-4 col-md-6 mt-3">
                <div class="card text-white bg-warning mb-3" style="max-width: 40rem;">
                    <div class="card-header" style="color: #FFA426; text-align:center;">
                        <span style="color: #FFA426; font-size: 1.5em"><i class="fa-solid fa-book"></i>
                            Jumlah Kegiatan
                        </span>
                    </div>
                    <div class="card-body" style="text-align: center;">
                        <h2 style="color:#fff" class="card-title">{{ $sum_status['2'] }} Kegiatan</h2>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">

        </div>
    </div>
    <div>
        {{-- histogram --}}
        {{-- antar ketua tim --}}
        {{-- seluruh pegawai --}}
    </div>
    <script>
        function changeIcon(anchor) {
            var icon = anchor.querySelector("i");
            icon.classList.toggle('fa-plus');
            icon.classList.toggle('fa-minus');

            anchor.querySelector("span").textContent = icon.classList.contains('fa-plus') ? "Read more" : "Read less";
        }
    </script>
@endsection
