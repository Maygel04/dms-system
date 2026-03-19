@extends('adminlte::page')

@section('title', 'Departments')

@section('css')
<link rel="stylesheet" href="{{ asset('css/custom-adminlte.css') }}">
@endsection

@section('content_header')
    <h1 class="m-0">🏢 Departments</h1>
            <p class="text-muted mb-0">Monitor department verification process</p>
@stop

@section('content')



    <div class="row">

        {{-- MPDO --}}
        <div class="col-md-4 mb-4">
            <a href="{{ route('admin.departments.mpdo') }}" style="text-decoration: none;">
                <div class="card shadow-sm border-0 h-100">
                    <div class="card-body text-center p-4 rounded" style="background: linear-gradient(135deg, #6ea8fe, #9ec5fe);">
                        <div class="mb-3">
                            <i class="fas fa-city fa-3x"></i>
                        </div>
                        <h4 class="fw-bold mb-2">MPDO</h4>
                        <p class="mb-0">View MPDO Applications</p>
                    </div>
                </div>
            </a>
        </div>

        {{-- MEO --}}
        <div class="col-md-4 mb-4">
            <a href="{{ route('admin.departments.meo') }}" style="text-decoration: none;">
                <div class="card shadow-sm border-0 h-100">
                    <div class="card-body text-center p-4 rounded" style="background: linear-gradient(135deg, #6fcf97, #a8e6cf);">
                        <div class="mb-3">
                            <i class="fas fa-hard-hat fa-3x"></i>
                        </div>
                        <h4 class="fw-bold mb-2">MEO</h4>
                        <p class="mb-0">View MEO Applications</p>
                    </div>
                </div>
            </a>
        </div>

        {{-- BFP --}}
        <div class="col-md-4 mb-4">
            <a href="{{ route('admin.departments.bfp') }}" style="text-decoration: none;">
                <div class="card shadow-sm border-0 h-100">
                    <div class="card-body text-center p-4 rounded" style="background: linear-gradient(135deg, #f8a5a5, #fbc2c2);">
                        <div class="mb-3">
                            <i class="fas fa-fire-extinguisher fa-3x"></i>
                        </div>
                        <h4 class="fw-bold mb-2">BFP</h4>
                        <p class="mb-0">View BFP Applications</p>
                    </div>
                </div>
            </a>
        </div>

    </div>

</div>

@stop