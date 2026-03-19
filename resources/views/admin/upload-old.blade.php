@extends('adminlte::page')

@section('css')
<link rel="stylesheet" href="{{ asset('css/custom-adminlte.css') }}">
@endsection

@section('content')

<div class="container-fluid">

    <div class="mb-4">
        <h4 class="fw-bold">📂 Upload Old Files</h4>
        <p class="text-muted">Admin can upload old documents for record keeping</p>
    </div>

    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger">
            {{ session('error') }}
        </div>
    @endif

    @if($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="card shadow-sm border-0 rounded-4">
        <div class="card-body p-4">

            <form action="{{ route('admin.upload.old') }}" method="POST" enctype="multipart/form-data">
                @csrf

                <div class="row g-3">

                    <div class="col-md-6">
    <label class="form-label">Applicant Name</label>
    <input
        type="text"
        name="applicant_name"
        class="form-control"
        placeholder="Enter applicant name"
        required>
</div>

                    <div class="col-md-6">
                        <label class="form-label">Department</label>
                        <select name="department" class="form-select" required>
                            <option value="">Select Department</option>
                            <option value="mpdo">MPDO</option>
                            <option value="meo">MEO</option>
                            <option value="bfp">BFP</option>
                        </select>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">Document Name</label>
                        <input
                            type="text"
                            name="document_name"
                            class="form-control"
                            placeholder="Enter document name"
                            required>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">Year Uploaded</label>
                        <input
                            type="number"
                            name="year_uploaded"
                            class="form-control"
                            min="2000"
                            max="{{ date('Y') }}"
                            placeholder="Enter year">
                    </div>

                    <div class="col-md-12">
                        <label class="form-label">Choose File</label>
                        <input
                            type="file"
                            name="file"
                            class="form-control"
                            required>
                    </div>

                </div>

                <div class="mt-4 text-end">
                    <button type="submit" class="btn btn-primary px-4">
                        ⬆ Upload Old File
                    </button>
                </div>
            </form>

        </div>
    </div>

</div>

@endsection