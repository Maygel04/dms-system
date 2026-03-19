@extends('layouts.department')

@section('content')

<div class="card p-4 shadow-sm">
<h4>Payments — {{ strtoupper($dept) }}</h4>

<table class="table table-bordered">
<tr>
<th>Applicant</th>
<th>Amount</th>
<th>Date</th>
</tr>

@foreach($payments as $p)
<tr>
<td>{{ $p->user_id ?? '-' }}</td>
<td>₱{{ number_format($p->amount,2) }}</td>
<td>{{ $p->created_at }}</td>
</tr>
@endforeach

</table>

<hr>
<h5>Total: ₱{{ number_format($total,2) }}</h5>

</div>

@endsection