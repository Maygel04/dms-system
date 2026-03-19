@extends('layouts.admin')

@section('content')
<div class="card p-4 shadow-sm">
<h4>System Notifications</h4>

@forelse($notifications as $n)
<div class="border p-2 mb-2">
<b>{{ $n->department }}</b> — {{ $n->remark }}
<br><small>{{ $n->created_at }}</small>
</div>
@empty
<p>No notifications.</p>
@endforelse

</div>
@endsection