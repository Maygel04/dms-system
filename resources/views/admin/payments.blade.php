@extends('adminlte::page')

@section('css')
<link rel="stylesheet" href="{{ asset('css/custom-adminlte.css') }}">
@endsection

@section('content')

@php
    $filter = request('filter', 'today');
@endphp

<div class="container-fluid">

    <div class="card shadow-sm border-0 p-4">

        <h4 class="mb-3">💰 Payments Overview</h4>

        {{-- ================= FILTER BUTTONS ================= --}}
        <div class="mb-4">

            <a href="?filter=today"
               class="btn btn-sm {{ $filter == 'today' ? 'btn-primary' : 'btn-outline-primary' }}">
                Today
            </a>

            <a href="?filter=week"
               class="btn btn-sm {{ $filter == 'week' ? 'btn-success' : 'btn-outline-success' }}">
                Weekly
            </a>

            <a href="?filter=month"
               class="btn btn-sm {{ $filter == 'month' ? 'btn-warning' : 'btn-outline-warning' }}">
                Monthly
            </a>

            <a href="?filter=year"
               class="btn btn-sm {{ $filter == 'year' ? 'btn-dark' : 'btn-outline-dark' }}">
               Yearly
            </a>

        </div>

        {{-- ================= REVENUE CARDS ================= --}}
        <div class="row g-4">

            {{-- MPDO --}}
            <div class="col-md-4">
                <div class="card border-0 shadow-sm bg-light p-3"
                     style="cursor:pointer"
                     data-toggle="modal"
                     data-target="#mpdoModal">

                    <h6 class="text-muted">MPDO Revenue</h6>
                    <h3 class="text-primary">
                        ₱{{ number_format($mpdo ?? 0, 2) }}
                    </h3>

                </div>
            </div>

            {{-- MEO --}}
            <div class="col-md-4">
                <div class="card border-0 shadow-sm bg-light p-3"
                     style="cursor:pointer"
                     data-toggle="modal"
                     data-target="#meoModal">

                    <h6 class="text-muted">MEO Revenue</h6>
                    <h3 class="text-success">
                        ₱{{ number_format($meo ?? 0, 2) }}
                    </h3>

                </div>
            </div>

            {{-- BFP --}}
            <div class="col-md-4">
                <div class="card border-0 shadow-sm bg-light p-3"
                     style="cursor:pointer"
                     data-toggle="modal"
                     data-target="#bfpModal">

                    <h6 class="text-muted">BFP Revenue</h6>
                    <h3 class="text-danger">
                        ₱{{ number_format($bfp ?? 0, 2) }}
                    </h3>

                </div>
            </div>

        </div>

        {{-- ================= TOTAL ================= --}}
        <hr class="my-4">

        <div class="p-3 rounded bg-light border-start border-4"
             style="border-color: #fd7e14 !important;">

            <h3 class="mb-0 text-dark">

                Total Revenue
                @if($filter == 'today')
                    Today
                @elseif($filter == 'week')
                    This Week
                @elseif($filter == 'month')
                    This Month
                @elseif($filter == 'year')
                    This Year
                @endif
                :

                <span class="fw-bold" style="color: #fd7e14;">
                    ₱{{ number_format($total ?? 0, 2) }}
                </span>

            </h3>

        </div>

    </div>

<a href="{{ url('/admin/report/generate?filter=' . ($filter ?? 'today')) }}" 
   class="btn btn-dark mt-3">
    📄 Generate Report
</a>

</div>

{{-- ================= MPDO MODAL ================= --}}
<div class="modal fade" id="mpdoModal" tabindex="-1">
<div class="modal-dialog modal-lg">
<div class="modal-content">

<div class="modal-header bg-primary text-white">
<h5 class="modal-title">MPDO Payments</h5>
<button type="button" class="close" data-dismiss="modal">
    <span>&times;</span>
</button>
</div>

<div class="modal-body">

<table class="table table-bordered">
<thead>
<tr>
<th>Applicant</th>
<th>Amount</th>
<th>Date</th>
</tr>
</thead>

<tbody>
@forelse($mpdoList as $item)
<tr>
<td>{{ $item->name }}</td>
<td>₱{{ number_format($item->amount,2) }}</td>
<td>{{ \Carbon\Carbon::parse($item->created_at)->format('M d, Y h:i A') }}</td>
</tr>
@empty
<tr>
<td colspan="3" class="text-center">No payments</td>
</tr>
@endforelse
</tbody>

</table>

</div>
</div>
</div>
</div>

{{-- ================= MEO MODAL ================= --}}
<div class="modal fade" id="meoModal" tabindex="-1">
<div class="modal-dialog modal-lg">
<div class="modal-content">

<div class="modal-header bg-success text-white">
<h5 class="modal-title">MEO Payments</h5>
<button type="button" class="close" data-dismiss="modal">
    <span>&times;</span>
</button>
</div>

<div class="modal-body">

<table class="table table-bordered">
<thead>
<tr>
<th>Applicant</th>
<th>Amount</th>
<th>Date</th>
</tr>
</thead>

<tbody>
@forelse($meoList as $item)
<tr>
<td>{{ $item->name }}</td>
<td>₱{{ number_format($item->amount,2) }}</td>
<td>{{ \Carbon\Carbon::parse($item->created_at)->format('M d, Y h:i A') }}</td>
</tr>
@empty
<tr>
<td colspan="3" class="text-center">No payments</td>
</tr>
@endforelse
</tbody>

</table>

</div>
</div>
</div>
</div>

{{-- ================= BFP MODAL ================= --}}
<div class="modal fade" id="bfpModal" tabindex="-1">
<div class="modal-dialog modal-lg">
<div class="modal-content">

<div class="modal-header bg-danger text-white">
<h5 class="modal-title">BFP Payments</h5>
<button type="button" class="close" data-dismiss="modal">
    <span>&times;</span>
</button>
</div>

<div class="modal-body">

<table class="table table-bordered">
<thead>
<tr>
<th>Applicant</th>
<th>Amount</th>
<th>Date</th>
</tr>
</thead>

<tbody>
@forelse($bfpList as $item)
<tr>
<td>{{ $item->name }}</td>
<td>₱{{ number_format($item->amount,2) }}</td>
<td>{{ \Carbon\Carbon::parse($item->created_at)->format('M d, Y h:i A') }}</td>
</tr>
@empty
<tr>
<td colspan="3" class="text-center">No payments</td>
</tr>
@endforelse
</tbody>

</table>

</div>
</div>
</div>
</div>

@endsection