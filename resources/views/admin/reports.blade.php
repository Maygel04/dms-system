@extends('adminlte::page')
@section('css')
<link rel="stylesheet" href="{{ asset('css/custom-adminlte.css') }}">
@endsection

@section('content')

<div class="container-fluid">

    <div class="card shadow-sm border-0 p-4">

        <h4 class="mb-4">📊 Reports / Analytics</h4>

        {{-- ================= SUMMARY CARDS ================= --}}
        <div class="row g-4">

            <div class="col-md-4">
                <div class="card bg-light border-0 shadow-sm p-3">
                    <h6 class="text-muted">Total Applications</h6>
                    <h3 class="text-primary">
                        {{ $totalApplications ?? 0 }}
                    </h3>
                </div>
            </div>

            <div class="col-md-4">
                <div class="card bg-light border-0 shadow-sm p-3">
                    <h6 class="text-muted">Verified / Completed</h6>
                    <h3 class="text-success">
                        {{ $verified ?? 0 }}
                    </h3>
                </div>
            </div>

            <div class="col-md-4">
                <div class="card bg-light border-0 shadow-sm p-3">
                    <h6 class="text-muted">Pending Applications</h6>
                    <h3 class="text-danger">
                        {{ $pending ?? 0 }}
                    </h3>
                </div>
            </div>

        </div>

        {{-- ================= SIMPLE TABLE ================= --}}
        <hr class="my-4">

        <h5 class="mb-3">System Summary</h5>

        <table class="table table-bordered">
            <tr>
                <th>Total Applications</th>
                <td>{{ $totalApplications ?? 0 }}</td>
            </tr>
            <tr>
                <th>Completed</th>
                <td>{{ $verified ?? 0 }}</td>
            </tr>
            <tr>
                <th>Pending</th>
                <td>{{ $pending ?? 0 }}</td>
            </tr>
        </table>

    </div>

</div>

@endsection