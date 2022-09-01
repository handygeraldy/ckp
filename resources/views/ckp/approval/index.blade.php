@extends('layouts.main')

@section('container')
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0">{{ $title }}</h1>
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('index') }}">Dashboard</a></li>
            <li class="breadcrumb-item text-gray-800">CKP</li>
            <li class="breadcrumb-item text-gray-800">Approval</li>
        </ol>
    </div>

    <div class="row">
        <div class="col-lg-12 mb-4">
            <form id="formApprove" action="{{ route('approval.approve.checked') }}" method="POST">
                @csrf
                <div class="card">
                    <div class="card-header">
                        <div class="row">
                            <div class="col text-right">
                                {{-- <button id="uncheckAll" type="button" class="btn btn-outline-primary mr-2"><i
                                        class="fa-regular fa-square"></i></button>
                                <button id="checkAll" type="button" class="btn btn-outline-primary mr-2"><i
                                        class="fa-regular fa-square-check"></i></button> --}}
                                <button form="formApprove" type="submit" class="btn btn-warning mr-2" name="action"
                                    value="reject">
                                    <i class="fa fa-undo"></i> Reject</button>
                                <button form="formApprove" type="submit" class="btn btn-primary" name="action" id="approveBtn"
                                    value="approve">
                                    <i class="fa fa-check"></i> Approve</button>

                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table id="tabel" class="table table-hover table-striped">
                                <thead>
                                    <tr class="text-center align-middle">
                                        <th><input type="checkbox" id="checkAll"></th>
                                        <th>No</th>
                                        <th style="min-width: 80px">Bulan</th>
                                        <th style="min-width: 200px">Nama Pegawai</th>
                                        <th>Nilai Kuantitas</th>
                                        <th>Nilai Kualitas</th>
                                        <th>Nilai Akhir</th>
                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($dt as $key => $d)
                                        <tr>
                                            <td><input type="checkbox" name="ckp_id[]" value="{{ $d->id }}"></td>
                                            <td>{{ $key + 1 }}</td>
                                            <td>{{ $d->bulan . '-' . $d->tahun }}</td>
                                            <td>{{ $d->user_name }}</td>
                                            <td>{{ $d->avg_kuantitas }}</td>
                                            <td>{{ $d->avg_kualitas }}</td>
                                            <td>{{ $d->nilai_akhir }}</td>
                                            <td style="min-width: 100px;">
                                                <div class="row">
                                                    <a href="{{ route($route_ . '.show', $d->id) }}"
                                                        class="btn btn-primary btn-sm">
                                                        <i class="fa fa-eye"></i></a>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <script>
        $(document).ready(function() {
            $('#tabel').DataTable();
        });

        $("#checkAll").click(function() {
            $('input:checkbox').not(this).prop('checked', this.checked);
        });

        $("#approveBtn").click(function() {
            var empty = $(this).parent().parent().parent().find("input").filter(function() {
                return this.value === "";
            });
            if (empty.length) {
                alert("Pilih CKP yang akan disetujui");
                return false;
            }
        });
    </script>
@endsection
