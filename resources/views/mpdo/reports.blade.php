@extends('adminlte::page')

@section('content')

<div class="card p-4 shadow-sm">

<h3 class="mb-3">📊 Reports / Analytics</h3>

<p><b>Total Applications:</b> {{ $totalApplications }}</p>
<p><b>Verified:</b> {{ $verified }}</p>
<p><b>Pending:</b> {{ $pending }}</p>

<hr>

<h5>💰 Total Revenue: ₱{{ number_format($total,2) }}</h5>

</div>

@endsection