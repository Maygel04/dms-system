@extends('adminlte::page')

@section('css')
<link rel="stylesheet" href="{{ asset('css/custom-adminlte.css') }}">
@endsection

@section('content')

<div class="container" style="display:flex; justify-content:center; margin-top:40px;">

<div class="card shadow" style="width:100%; max-width:600px;">

<div class="card-header bg-dark text-white">
Official Receipt
</div>

<div class="card-body">

<h4>Municipal Permit System</h4>

<hr>

<p><b>Applicant Name:</b> {{ $applicant->name }}</p>

<p><b>Amount Paid:</b> ₱{{ number_format($assessment->amount ?? 0,2) }}</p>

<p><b>Verified On:</b>
{{ $assessment->verified_on ? \Carbon\Carbon::parse($assessment->verified_on)->format('F d Y h:i A') : '-' }}</p>

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
