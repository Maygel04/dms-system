@extends('layouts.department')

@section('content')

<div class="card p-4 shadow-sm">
<h4>Notifications — {{ strtoupper($dept) }}</h4>

@if($notifications->isEmpty())
<div class="alert alert-info">No new uploads.</div>
@else

<ul class="list-group">
@foreach($notifications as $n)
<li class="list-group-item">
New document uploaded — {{ $n->created_at }}
</li>
@endforeach
</ul>

@endif

</div>

@endsection