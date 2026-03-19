@extends('adminlte::page')

@section('content')

<div class="container py-4">

<div class="card shadow">

<div class="card-header bg-dark text-white">
Official Receipt
</div>

<div class="card-body">

<h4>Municipal Permit System</h4>

<hr>

@section('css')
<link rel="stylesheet" href="{{ asset('css/custom-adminlte.css') }}">
@endsection

<p><b>Applicant Name:</b> {{ $applicant->name }}</p>

<p><b>Amount Paid:</b> ₱{{ number_format($assessmentAmount ?? 0,2) }}</p>

<p><b>Verified On:</b>
{{ $verifiedOn ? \Carbon\Carbon::parse($verifiedOn)->format('F d Y h:i A') : '-' }}</p>

<hr>

<button onclick="window.print()" class="btn btn-success">
🖨 Print Receipt
</button>


</div>
 <a href="{{ url()->previous() }}" class="btn btn-secondary">
        ← Back
    </a>

</div>

</div>

@endsection