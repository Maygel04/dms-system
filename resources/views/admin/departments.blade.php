@extends('adminlte::page')
@section('css')
<link rel="stylesheet" href="{{ asset('css/custom-adminlte.css') }}">
@endsection

@section('content')
<div class="card p-4 shadow-sm">
<h4>Departments Status</h4>

<p>MPDO Verified: <b>{{ $mpdo }}</b></p>
<p>MEO Verified: <b>{{ $meo }}</b></p>
<p>BFP Verified: <b>{{ $bfp }}</b></p>
</div>
@endsection