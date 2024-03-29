@extends('layouts.main')
<link rel="stylesheet" href="{{ asset('/css/argon.css?v=1.2.0') }}" type="text/css">
@section('container')
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0">Dashboard</h1>

    </div>
    <div class="text-right">
        <span>
            <a href="{{ route('index.ckp.filter', ['tahun' => $prev_year, 'bulan' => $prev_month]) }}"><i
                    class="fa-solid fa-caret-left"></i></a>
            {{ getMonth($bulan) . ' ' . $tahun }}
            <a href="{{ route('index.ckp.filter', ['tahun' => $next_year, 'bulan' => $next_month]) }}"><i
                    class="fa-solid fa-caret-right"></i></a>
        </span>
    </div>
    <div class="accordion" id="accordionEx">
        <div class="row">
            {{-- belum diajukan --}}
            <div class="col-xl-3 col-md-6 mt-3">
                <div class="card">
                    <div class="card-header mb-0" id="headingOne">
                        <a data-toggle="collapse" data-target="#collapseOne" aria-expanded="true"
                            onclick="changeIcon(this)">
                            <p class="mb-0">
                                BELUM DIAJUKAN <i class="fas fa-plus float-right"></i>
                            </p>
                        </a>
                    </div>
                    <div class="card-body pt-0">
                        <div class="row">
                            <div class="col">
                                <span class="h2 font-weight-bold mb-0">{{ $sum_status['0'] + $sum_status['1'] }}</span>
                            </div>
                            <div class="col-auto">
                                <div class="icon icon-shape bg-gradient-light text-white rounded-circle shadow">
                                    <i class="fa-solid fa-pen"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div id="collapseOne" class="collapse" aria-labelledby="headingOne" data-parent="#accordionEx">
                        <div class="card-body">
                            <table class="table table-borderless">
                                <tbody>
                                    <tr>
                                        @foreach ($rekap_pegawai[0] as $p)
                                            <td>{{ $p }}</td>
                                        @endforeach
                                        @foreach ($rekap_pegawai[1] as $p)
                                            <td>{{ $p }}</td>
                                        @endforeach
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            {{-- diperiksa ketua tim --}}
            <div class="col-xl-3 col-md-6 mt-3">
                <div class="card">
                    <div class="card-header mb-0" id="headingTwo">
                        <a data-toggle="collapse" data-target="#collapseTwo" aria-expanded="true"
                            onclick="changeIcon(this)">
                            <p class="mb-0">
                                DIPERIKSA KETUA TIM <i class="fas fa-plus float-right"></i>
                            </p>
                        </a>
                    </div>
                    <div class="card-body pt-0">
                        <div class="row">
                            <div class="col">
                                <span class="h2 font-weight-bold mb-0">{{ $sum_status['2'] }}</span>
                            </div>
                            <div class="col-auto">
                                <div class="icon icon-shape bg-gradient-blue text-white rounded-circle shadow">
                                    <i class="fa-solid fa-file-pen"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div id="collapseTwo" class="collapse" aria-labelledby="headingTwo" data-parent="#accordionEx">
                        <div class="card-body">
                            <table class="table table-borderless">
                                <tbody>
                                    <tr>
                                        @foreach ($rekap_pegawai[2] as $p)
                                            <td>{{ $p }}</td>
                                        @endforeach
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            {{-- diperiksa direktur --}}
            <div class="col-xl-3 col-md-6 mt-3">
                <div class="card">
                    <div class="card-header mb-0" id="headingThree">
                        <a data-toggle="collapse" data-target="#collapseThree" aria-expanded="true"
                            onclick="changeIcon(this)">
                            <p class="mb-0">
                                DIPERIKSA DIREKTUR <i class="fas fa-plus float-right"></i>
                            </p>
                        </a>
                    </div>
                    <div class="card-body pt-0">
                        <div class="row">
                            <div class="col">
                                <span class="h2 font-weight-bold mb-0">{{ $sum_status['3'] }}</span>
                            </div>
                            <div class="col-auto">
                                <div class="icon icon-shape bg-gradient-info text-white rounded-circle shadow">
                                    <i class="fa-solid fa-magnifying-glass"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div id="collapseThree" class="collapse" aria-labelledby="headingThree" data-parent="#accordionEx">
                        <div class="card-body">
                            <table class="table table-borderless">
                                <tbody>
                                    <tr>
                                        @foreach ($rekap_pegawai[3] as $p)
                                            <td>{{ $p }}</td>
                                        @endforeach
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Disetujui --}}
            <div class="col-xl-3 col-md-6 mt-3">
                <div class="card">
                    <div class="card-header mb-0" id="headingFour">
                        <a data-toggle="collapse" data-target="#collapseFour" aria-expanded="true"
                            onclick="changeIcon(this)">
                            <p class="mb-0">
                                DISETUJUI <i class="fas fa-plus float-right"></i>
                            </p>
                        </a>
                    </div>
                    <div class="card-body pt-0">
                        <div class="row">
                            <div class="col">
                                <span class="h2 font-weight-bold mb-0">{{ $sum_status['4'] }}</span>
                            </div>
                            <div class="col-auto">
                                <div class="icon icon-shape bg-gradient-success text-white rounded-circle shadow">
                                    <i class="fa-solid fa-clipboard-check"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div id="collapseFour" class="collapse" aria-labelledby="headingFour" data-parent="#accordionEx">
                        <div class="card-body">
                            <table class="table table-borderless">
                                <tbody>
                                    <tr>
                                        @foreach ($rekap_pegawai[4] as $p)
                                            <td>{{ $p }}</td>
                                        @endforeach
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
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
