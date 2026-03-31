@extends('adminlte::page')

@section('css')
<link rel="stylesheet" href="{{ asset('css/custom-adminlte.css') }}">
@endsection

@section('content')

<div class="container-fluid mt-4">

<div class="card shadow-sm">

<div class="card-header bg-primary text-white">
<h5 class="mb-0">BFP Applications (Read Only)</h5>
</div>

<div class="card-body">

{{-- SEARCH + FILTER --}}
<form method="GET" action="" class="mb-3">
    <div class="row">

        {{-- SEARCH --}}
        <div class="col-md-4">
            <input type="text" name="search" class="form-control"
                   placeholder="🔍 Search applicant..."
                   value="{{ request('search') }}">
        </div>

        {{-- FILTER --}}
        <div class="col-md-3">
            <select name="status" class="form-control">
                <option value="">All Status</option>
<option value="pending" {{ request('status')=='pending'?'selected':'' }}>Pending</option>
<option value="verified" {{ request('status')=='verified'?'selected':'' }}>Verified</option>
<option value="issued" {{ request('status')=='issued'?'selected':'' }}>Issued</option>
            </select>
        </div>

        {{-- BUTTON --}}
        <div class="col-md-5">
            <button type="submit" class="btn btn-primary">🔍 Filter</button>
            <a href="{{ url()->current() }}" class="btn btn-secondary">Reset</a>
        </div>

    </div>
</form>

<table class="table table-bordered table-hover">

<thead class="table-light">
<tr>
<th>Application ID</th>
<th>Applicant Name</th>
<th>MPDO Status</th>
<th>Date Submitted</th>
</tr>
</thead>

<tbody>

@forelse($applications as $app)

<tr>
<td>{{ $app->id }}</td>

<td>
    {{-- ✅ FINAL FIX (SAFE) --}}
    <a href="{{ route('admin.applicant_documents', ['id' => $app->id]) }}"
       class="text-primary fw-bold">
        {{ $app->name ?? $app->applicant_name }}
    </a>
</td>
<td>

    @php
        if ($app->bfp_issued == 1) {
            $status = 'issued';
        } else {
            $status = strtolower($app->bfp_status);
        }
    @endphp

    @if($status == 'pending')
        <span class="badge bg-warning text-dark">Pending</span>

    @elseif($status == 'verified')
        <span class="badge bg-success">Verified</span>

    @elseif($status == 'endorsed')
        <span class="badge bg-info">Endorsed</span>

    @elseif($status == 'issued')
        <span class="badge bg-primary">Issued</span>

    @else
        <span class="badge bg-secondary">{{ $status }}</span>
    @endif

</td>

<td>{{ $app->created_at }}</td>

</tr>

@empty

<tr>
<td colspan="4" class="text-center">
No BFP applications found
</td>
</tr>

@endforelse

</tbody>

</table>

</div>
</div>

</div>

<div class="mb-3">
    {{-- ✅ FIXED BACK BUTTON --}}
    <a href="{{ route('admin.departments') }}" class="btn btn-secondary">
        ← Back
    </a>
</div>

@endsection