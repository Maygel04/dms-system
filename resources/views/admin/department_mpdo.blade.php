@extends('adminlte::page')

@section('css')
<link rel="stylesheet" href="{{ asset('css/custom-adminlte.css') }}">
@endsection

@section('content')

<div class="container-fluid mt-4">

<div class="card shadow-sm">

<div class="card-header bg-primary text-white">
<h5 class="mb-0">MPDO Applications (Read Only)</h5>
</div>

<div class="card-body">

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

<td>{{ $app->name }}</td>

<td>
@if($app->mpdo_status == 'verified')
<span class="badge bg-success">Verified</span>

@elseif($app->mpdo_status == 'pending')
<span class="badge bg-warning">Pending</span>

@else
<span class="badge bg-secondary">{{ $app->mpdo_status }}</span>
@endif
</td>

<td>{{ $app->created_at }}</td>

</tr>

@empty

<tr>
<td colspan="4" class="text-center">
No MPDO applications found
</td>
</tr>

@endforelse

</tbody>

</table>

</div>
</div>

</div>

<div class="mb-3">
    <a href="{{ route('admin.departments') }}" class="btn btn-secondary">
        <i class="fas fa-arrow-left"></i> Back
    </a>
</div>

@endsection