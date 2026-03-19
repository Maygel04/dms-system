@extends('layouts.department')

@section('content')

<div class="card p-4 shadow-sm">
<h4>Applications — {{ strtoupper($dept) }}</h4>

<table class="table table-bordered">
<tr>
<th>ID</th>
<th>Applicant</th>
<th>Date</th>
</tr>

@foreach($apps as $a)
<tr>
<td>{{ $a->id }}</td>
<td>{{ $a->name }}</td>
<td>{{ $a->created_at }}</td>
</tr>
@endforeach

</table>
</div>

@endsection