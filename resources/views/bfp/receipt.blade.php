@extends('adminlte::page')

@section('css')
<link rel="stylesheet" href="{{ asset('css/custom-adminlte.css') }}">
@endsection

@section('content')

<div class="container py-4">

    {{-- PRINT AREA --}}
    <div id="printArea" style="background:white; padding:40px; max-width:700px; margin:auto; border:1px solid #ccc;">

        {{-- HEADER --}}
        <div style="text-align:center;">
            <h2 style="margin:0;">Bureau of Fire Protection</h2>
            <p style="margin:0;">Building Permit System</p>
            <h3 style="margin-top:10px;">Official Receipt</h3>
            <hr>
        </div>

        {{-- DETAILS --}}
        <table width="100%" style="margin-top:20px;">
            <tr>
                <td><b>Applicant Name:</b></td>
                <td>{{ $applicant->name ?? 'N/A' }}</td>
            </tr>
            <tr>
                <td><b>Amount Paid:</b></td>
                <td>₱{{ number_format($assessmentAmount ?? 0,2) }}</td>
            </tr>
            <tr>
                <td><b>Date Paid:</b></td>
                <td>
                    {{ $verifiedOn 
                        ? \Carbon\Carbon::parse($verifiedOn)->format('F d, Y h:i A') 
                        : '-' 
                    }}
                </td>
            </tr>
        </table>

        <hr style="margin-top:25px;">

        {{-- SIGNATURE (REALISTIC) --}}
        <div style="margin-top:60px; text-align:right;">
            ___________________________<br>
            <b>Cashier / Collecting Officer</b>
        </div>

    </div>

    {{-- BUTTONS --}}
    <div style="text-align:center; margin-top:20px;">
        <a href="{{ url()->previous() }}" class="btn btn-secondary">
            ← Back
        </a>

        <button onclick="window.print()" class="btn btn-success">
            🖨 Print / Save PDF
        </button>
    </div>

</div>

{{-- PRINT STYLE --}}
<style>
@media print {

    body {
        background: white;
    }

    body * {
        visibility: hidden;
    }

    #printArea, #printArea * {
        visibility: visible;
    }

    #printArea {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        border: none;
    }

    .btn, a {
        display: none !important;
    }
}
</style>

@endsection