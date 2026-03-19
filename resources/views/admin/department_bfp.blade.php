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

<table class="table table-bordered table-hover">

<thead class="table-light">
<tr>
<th>ID</th>
<th>Applicant</th>
<th>Status</th>
<th>Date</th>
</tr>
</thead>

<tbody>

@foreach($applications as $app)

<tr>

<td>{{ $app->id }}</td>

<td>{{ $app->name }}</td>

<td>

@if($app->bfp_status == 'verified')
<span class="badge bg-success">Verified</span>

@elseif($app->bfp_status == 'issued')
<span class="badge bg-primary">Issued</span>

@elseif($app->bfp_status == 'pending')
<span class="badge bg-warning">Pending</span>

@else
<span class="badge bg-secondary">{{ $app->bfp_status }}</span>
@endif

</td>

<td>{{ $app->created_at }}</td>

</tr>

@endforeach

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