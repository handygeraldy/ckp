@extends('layouts.main')
<link rel="stylesheet" href="{{ asset('/css/argon.css?v=1.2.0') }}" type="text/css">
<script src="https://cdnjs.cloudflare.com/ajax/libs/echarts/5.2.2/echarts.min.js" integrity="sha512-ivdGNkeO+FTZH5ZoVC4gS4ovGSiWc+6v60/hvHkccaMN2BXchfKdvEZtviy5L4xSpF8NPsfS0EVNSGf+EsUdxA==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
@section('container')
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0">Dashboard</h1>
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('index') }}">Dashboard</a></li>
        </ol>
    </div>
    <div class="row">
        <div class="col-xl-3 col-md-6 mt-3">
            <div class="card card-stats h-100">
                <!-- Card body -->
                <div class="card-body">
                    <div class="row">
                        <div class="col">
                            <h5 class="card-title text-uppercase text-muted mb-0">Belum diusulkan</h5>
                            <span class="h2 font-weight-bold mb-0"></span>
                        </div>
                        <div class="col-auto">
                            <div class="icon icon-shape bg-gradient-red text-white rounded-circle shadow">
                                <i class="fas fa-edit"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
    
@endsection
