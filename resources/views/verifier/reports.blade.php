@extends('layouts.department')

@section('content')

<div class="card p-4 shadow-sm">
<h4>Reports — {{ strtoupper($dept) }}</h4>

<p>Total Files: <b>{{ $total }}</b></p>
<p>Verified: <b>{{ $verified }}</b></p>
<p>Pending: <b>{{ $pending }}</b></p>

</div>

@endsection