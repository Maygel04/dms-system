@extends('adminlte::page')

@section('content')
@section('css')
<link rel="stylesheet" href="{{ asset('css/custom-adminlte.css') }}">
@endsection

<div class="container-fluid">

    {{-- NORMAL LAYOUT (FULL WIDTH) --}}
    <div class="row">
        <div class="col-12">

            {{-- PRINT AREA (ONLY THIS CENTERED) --}}
            <div id="printArea" style="background:white; padding:30px; border:1px solid #ccc;">

                <div style="max-width:900px; margin:auto;">

                    <div style="text-align:center;">
                        <h2>Bureau of Fire Protection  </h2>
                        <p>Financial Report</p>
                        <small>{{ $title }}</small>
                        <hr>
                    </div>

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
                                <td>{{ \Carbon\Carbon::parse($r->created_at)->format('M d, Y h:i A') }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>

                    <h4 class="text-right">Total: ₱{{ number_format($total,2) }}</h4>

                    <div style="margin-top:50px; text-align:right;">
                        ____________________<br>
                        Cashier
                    </div>

                </div>

            </div>

        </div>
    </div>

    {{-- BUTTONS --}}
    <div class="text-center mt-3">
        <a href="{{ url('/bfp/payments') }}" class="btn btn-secondary">Back</a>
        <button onclick="window.print()" class="btn btn-success">Print</button>
    </div>

</div>

@endsection