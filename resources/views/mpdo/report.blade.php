@extends('adminlte::page')

@section('content')
@section('css')
<link rel="stylesheet" href="{{ asset('css/custom-adminlte.css') }}">
@endsection

<div class="container" id="printArea">

    <h3 style="text-align:center;">MPDO Financial Report</h3>
    <p style="text-align:center;">{{ $title }}</p>

    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Applicant</th>
                <th>Amount</th>
                <th>Date</th>
            </tr>
        </thead>
        <tbody>
            @foreach($records as $r)
            <tr>
                <td>{{ $r->name }}</td>
                <td>₱{{ number_format($r->amount,2) }}</td>
                <td>{{ $r->created_at }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <h4>Total: ₱{{ number_format($total,2) }}</h4>

    <br><br>

    <div style="display:flex;justify-content:space-between;">
        <div>____________________<br>Prepared by</div>
        <div>____________________<br>Approved by</div>
    </div>

</div>

<div style="margin-top:15px;">
    <a href="{{ url('/mpdo/payments') }}" class="btn btn-secondary">
        ⬅ Back to Payments
    </a>

    <button onclick="window.print()" class="btn btn-primary">
        🖨 Print / Save PDF
    </button>
</div>



<style>
@media print {
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
    }

    button {
        display: none;
    }
}
</style>
@endsection