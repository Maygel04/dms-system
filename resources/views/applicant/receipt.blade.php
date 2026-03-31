@extends('adminlte::page')

@section('content')

<div class="container" id="printArea">

<div class="card p-4 shadow" id="printArea">

    <div class="text-center">
        <h4>Municipal Building Permit System</h4>
        <h4 style="text-align:center; margin-bottom:15px;">
    OFFICIAL RECEIPT
</h4>
    </div>

    <hr>

    <p><strong>Applicant:</strong> {{ $application->name }}</p>
    <p><strong>Application ID:</strong> {{ $application->id }}</p>

    <table class="table table-bordered mt-3">
        <thead>
            <tr>
                <th>Department</th>
                <th>Amount</th>
                <th>Date</th>
            </tr>
        </thead>
        <tbody>
            @foreach($payments as $p)
            <tr>
                <td>{{ strtoupper($p->department) }}</td>
                <td>₱{{ number_format($p->amount,2) }}</td>
                <td>{{ \Carbon\Carbon::parse($p->created_at)->format('M d, Y h:i A') }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <h4 class="mt-3">
        Total: ₱{{ number_format($total,2) }}
    </h4>

    <br><br>

    <div class="row mt-5">
        <div class="col text-center">
            _______________________<br>
            Applicant Signature
        </div>

        <div class="col text-center">
            _______________________<br>
            Authorized Officer
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
        left: 0;
        top: 0;
        width: 100%;
    }

    button, a {
        display: none !important;
    }
}
</style>
@endsection