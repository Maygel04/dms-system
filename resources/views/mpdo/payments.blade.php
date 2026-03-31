@extends('adminlte::page')
@section('css')
<link rel="stylesheet" href="{{ asset('css/custom-adminlte.css') }}">
@endsection

@section('content')

<div class="container-fluid">

<h3 class="mb-4">💰 MPDO Payments / Assessment</h3>

{{-- TOTAL --}}
<div class="card shadow-sm border-0 mb-4 text-center p-3">
<h6>Total Assessed Amount</h6>
<h2 class="text-success">₱{{ number_format($total,2) }}</h2>
</div>

{{-- TABLE --}}
<div class="card shadow-sm">
<div class="card-body">

<h5 class="mb-3">Assessed Applications</h5>

<table class="table table-bordered table-hover">

<thead class="table-light">
<tr>
<th>#</th>
<th>Applicant</th>
<th>Amount</th>
<th>Verified On</th>
</tr>
</thead>

<tbody>

@forelse($payments as $i => $p)
<tr>
<td>{{ $i+1 }}</td>
<td>{{ $p->name }}</td>
<td class="text-success">₱{{ number_format($p->amount,2) }}</td>
<td>{{ $p->verified_on ?? '-' }}</td>
</tr>
@empty
<tr>
<td colspan="4" class="text-center text-muted">No assessments yet.</td>
</tr>
@endforelse

</tbody>
</table>

</div>
</div>

</div>

<div style="margin-bottom:15px;">
    <a href="{{ url('/mpdo/report?filter=today') }}" class="btn btn-primary">Today</a>
    <a href="{{ url('/mpdo/report?filter=week') }}" class="btn btn-success">Weekly</a>
    <a href="{{ url('/mpdo/report?filter=month') }}" class="btn btn-warning">Monthly</a>
    <a href="{{ url('/mpdo/report?filter=year') }}" class="btn btn-secondary">Yearly</a>
</div>

@endsection