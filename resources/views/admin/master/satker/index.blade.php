@extends('layouts.main')

@section('container')
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0">{{ $title }}</h1>
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('index') }}">Dashboard</a></li>
            <li class="breadcrumb-item text-gray-800">Master</li>
            <li class="breadcrumb-item text-gray-800">{{ $text_ }}</li>
        </ol>
    </div>

    <div class="row">
        <div class="col-lg-12 mb-4">
            <div class="card">
                <div class="card-header">
                    <div class="row">
                        <div class="col text-right">
                            <a href="{{ route('satker.create') }}" class="btn btn-primary"><i
                                    class="fa fa-plus-circle mr-2"></i>Tambah {{ $text_ }}</a>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table id="tabel" class="table table-hover table-striped">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th scope="col">{{ $text_ }}</th>
                                    <th>Pimpinan</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($dt as $key => $d)
                                    <tr>
                                        <td>{{ $key + 1 }}</td>
                                        <td class="text-left">{{ $d->name }}</td>
                                        <td>{{ $d->user ? $d->user->name : '' }}</td>
                                        <td style="min-width: 100px;">
                                            <div class="row">
                                                <a href="{{ route($route_ . '.edit', $d->id) }}"
                                                    class="btn btn-success btn-sm"><i class="fas fa-edit"></i></a>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <script>
        $(document).ready(function() {
            $('#tabel').DataTable();
        });
    </script>
@endsection
