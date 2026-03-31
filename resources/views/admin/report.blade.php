@extends('adminlte::page')

@section('css')
<link rel="stylesheet" href="{{ asset('css/custom-adminlte.css') }}">
@endsection

@section('content')

<div class="container">

    <div class="card p-5 shadow" id="printArea">

        <div class="text-center mb-4">
            <h3>Municipal Building Permit System</h3>
            <h5>Daily Financial Report</h5>
            <p>Date: {{ $today ?? now()->format('F d, Y') }}</p>
        </div>

        <hr>

        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Department</th>
                    <th>Revenue</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>MPDO</td>
                    <td>₱{{ number_format($mpdo ?? 0,2) }}</td>
                </tr>
                <tr>
                    <td>MEO</td>
                    <td>₱{{ number_format($meo ?? 0,2) }}</td>
                </tr>
                <tr>
                    <td>BFP</td>
                    <td>₱{{ number_format($bfp ?? 0,2) }}</td>
                </tr>
            </tbody>
        </table>

        <h4 class="mt-4">
            Total Revenue: ₱{{ number_format($total ?? 0,2) }}
        </h4>

        <br><br>

        <div class="row mt-5">
            <div class="col text-center">
                ___________________________<br>
                Prepared by
            </div>

            <div class="col text-center">
                ___________________________<br>
                Approved by
            </div>
        </div>

    </div>

    <div class="mt-3">
        <button onclick="window.print()" class="btn btn-primary">
            🖨 Print / Save as PDF
        </button>

        <a href="{{ url()->previous() }}" class="btn btn-secondary">
            ⬅ Back
        </a>
    </div>

</div>

@endsection