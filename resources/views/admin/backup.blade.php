@extends('adminlte::page')

@section('title', 'Database Backup')

@section('css')
<link rel="stylesheet" href="{{ asset('css/custom-adminlte.css') }}">
@endsection

@section('content_header')
    <h1 class="fw-bold">Database Backup</h1>
@stop

@section('content')

<div class="container-fluid">

    <!-- HEADER -->
    <div class="mb-4">
        <h4 class="fw-bold">🗄️ Backup</h4>
        <p class="text-muted">Create and download system database backup</p>
    </div>

    <!-- ALERTS -->
    @if(session('success'))
        <div class="alert alert-success shadow-sm">
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger shadow-sm">
            {{ session('error') }}
        </div>
    @endif

    <!-- CARD -->
    <div class="card shadow border-0 rounded-4">

        <div class="card-body p-4">

            <h5 class="fw-semibold mb-3">Database Backup</h5>

            <p class="text-muted mb-4">
                Click the button below to generate and download a backup of the database.
            </p>

            <!-- FORM -->
            <form method="POST" action="{{ route('admin.backup.database') }}">
                @csrf

                <button type="submit" class="btn btn-primary btn-lg px-4 shadow-sm">
                    ⬇ Download Database Backup
                </button>

            </form>

        </div>

    </div>

</div>

@endsection