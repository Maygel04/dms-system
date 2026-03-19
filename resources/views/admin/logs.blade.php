@extends('adminlte::page')

@section('css')
<link rel="stylesheet" href="{{ asset('css/custom-adminlte.css') }}">
@endsection

@section('content')

<div class="container-fluid">

    <div class="card shadow-sm border-0">
        
        {{-- HEADER --}}
        <div class="card-header bg-primary text-white">
            <h5 class="mb-0">
                <i class='bx bx-data'></i> System Logs
            </h5>
        </div>

        <div class="card-body">

            @if(empty($logs) || count($logs) == 0)
                <div class="alert alert-info text-center mb-0">
                    No logs available.
                </div>
            @else

            <div class="table-responsive">
                <table class="table table-bordered table-hover align-middle">

                    <thead class="table-light">
                        <tr>
                            <th width="35%">User</th>
                            <th width="20%">Role</th>
                            <th width="45%">Created</th>
                        </tr>
                    </thead>

                    <tbody>
                        @foreach($logs as $l)
                        <tr>
                            <td>
                                <strong>{{ $l->name }}</strong><br>
                                <small class="text-muted">{{ $l->email ?? '' }}</small>
                            </td>

                            <td>
                                <span class="badge bg-secondary">
                                    {{ strtoupper($l->role) }}
                                </span>
                            </td>

                            <td>
                                {{ \Carbon\Carbon::parse($l->created_at)->format('F d, Y h:i A') }}
                            </td>
                        </tr>
                        @endforeach
                    </tbody>

                </table>
            </div>

            @endif

        </div>
    </div>

</div>

@endsection