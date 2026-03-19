@extends('layouts.department')

@section('content')

<div class="container-fluid">

<h4 class="mb-3">🔔 MPDO Notifications</h4>

<div class="card shadow-sm">
<div class="card-body">

@if(!isset($notifications) || $notifications->isEmpty())

<div class="text-center text-muted py-4">
No notifications yet.
</div>

@else

<table class="table table-bordered table-hover">

<thead class="table-light">
<tr>
<th width="20%">Date</th>
<th width="20%">Applicant</th>
<th width="20%">Type</th>
<th>Message</th>
</tr>
</thead>

<tbody>

@foreach($notifications as $n)
<tr>

<td>{{ \Carbon\Carbon::parse($n->created_at)->format('Y-m-d H:i') }}</td>

<td>{{ $n->name ?? 'Unknown' }}</td>

<td>
<span class="badge 
@if($n->type=='upload') bg-primary
@elseif($n->type=='reupload') bg-warning
@else bg-secondary
@endif">
{{ strtoupper($n->type ?? 'info') }}
</span>
</td>

<td>{{ $n->message ?? 'New activity detected' }}</td>

</tr>
@endforeach

</tbody>
</table>

@endif

</div>
</div>

</div>

@endsection