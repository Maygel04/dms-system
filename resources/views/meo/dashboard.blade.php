@extends('adminlte::page')


@section('css')
<link rel="stylesheet" href="{{ asset('css/custom-adminlte.css') }}">
@endsection

@section('content')

<div class="container-fluid mt-5">

<h4 class="mb-4">MEO Dashboard</h4>

<!-- SEARCH BAR -->
<div class="card mb-4">
<div class="card-body">

<form method="GET" action="/meo/applications">
<div class="input-group">
<input type="text" name="search" class="form-control" placeholder="Search applicant name or permit number...">
<button class="btn btn-primary">Search</button>
</div>
</form>

</div>
</div>


<div class="row">

<!-- UNDER REVIEW -->
<div class="col-md-3">
<div class="card bg-warning text-white">
<div class="card-body">

<h5>Under Review</h5>
<h2>{{ $underReview }}</h2>

</div>
</div>
</div>


<!-- VERIFIED -->
<div class="col-md-3">
<div class="card bg-success text-white">
<div class="card-body">

<h5>Verified</h5>
<h2>{{ $verified }}</h2>

</div>
</div>
</div>


<!-- ENDORSED -->
<div class="col-md-3">
<div class="card bg-primary text-white">
<div class="card-body">

<h5>Endorsed</h5>
<h2>{{ $endorsed }}</h2>

</div>
</div>
</div>


<!-- REVENUE -->
<div class="col-md-3">
<div class="card bg-info text-white">
<div class="card-body">

<h5>Total Revenue</h5>
<h2>₱{{ number_format($revenue,2) }}</h2>

</div>
</div>
</div>

</div>

</div>

@endsection