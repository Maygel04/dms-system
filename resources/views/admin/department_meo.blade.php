@extends('adminlte::page')

@section('css')
<link rel="stylesheet" href="{{ asset('css/custom-adminlte.css') }}">
@endsection

@section('content')

<div class="container-fluid mt-4">

<div class="card shadow-sm">

<div class="card-header bg-primary text-white">
<h5 class="mb-0">MEO Applications (Read Only)</h5>
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
<option value="endorsed" {{ request('status')=='endorsed'?'selected':'' }}>Endorsed</option>

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
@if(strtolower($app->meo_status) == 'verified')
<span class="badge bg-success">Verified</span>

@elseif(strtolower($app->meo_status) == 'pending')
<span class="badge bg-warning text-dark">Pending</span>

@else
<span class="badge bg-secondary">{{ $app->meo_status }}</span>
@endif
</td>

<td>{{ $app->created_at }}</td>

</tr>

@empty

<tr>
<td colspan="4" class="text-center">
No MEO applications found
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