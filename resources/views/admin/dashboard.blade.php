@extends('adminlte::page')

@section('title', 'Admin Dashboard')

@section('css')
<link rel="stylesheet" href="{{ asset('css/custom-adminlte.css') }}">
@endsection


@section('content')

<div class="container-fluid">

    <!-- ================= HEADER ================= -->
    <div class="mb-4">
        <h4 class="fw-bold">🔎 Search Files</h4>
        <p class="text-muted">Find documents by name, department, Content</p>
    </div>

    <!-- ================= SEARCH BAR ================= -->
    <div class="card mb-4 shadow-sm">
        <div class="card-body">

            <form method="GET" action="{{ route('admin.dashboard') }}">
                <div class="row g-2">

                    <div class="col-md-6">
                        <input type="text" name="search"
                            placeholder="Search document name..."
                            value="{{ request('search') }}"
                            class="form-control shadow-sm">
                    </div>

                    <div class="col-md-3">
                        <select name="type" class="form-control shadow-sm">
                            <option value="">All File Types</option>
                            <option value=".pdf">PDF</option>
                            <option value=".dwg">DWG</option>
                            <option value=".dxf">DXF</option>
                        </select>
                    </div>

                    <div class="col-md-3">
                        <button class="btn btn-primary w-100">
                            🔍 Search
                        </button>
                    </div>

                </div>
            </form>

        </div>
    </div>

    <!-- ================= STATS ================= -->
    <div class="row g-4 mb-4">

        <div class="col-md-3">
<div class="card text-white text-center p-3" style="background:#6FA8DC;">                <h6>MPDO Verified</h6>
                <h2>{{ $mpdoVerified }}</h2>
            </div>
        </div>

        <div class="col-md-3">
<div class="card text-white text-center p-3" style="background:#76C7A3;">                <h6>MEO Verified</h6>
                <h2>{{ $meoVerified }}</h2>
            </div>
        </div>

        <div class="col-md-3">
<div class="card text-white text-center p-3" style="background:#E57373;">                <h6>BFP Verified</h6>
                <h2>{{ $bfpVerified }}</h2>
            </div>
        </div>

        <div class="col-md-3">
<div class="card text-dark text-center p-3" style="background:#FFD54F;">                <h6>Pending Applications</h6>
                <h2>{{ $pending }}</h2>
            </div>
        </div>

    </div>

    <!-- ================= BAR CHART ================= -->
    <div class="row mb-4 justify-content-center">
        <div class="col-md-8">
            <div class="card p-3 shadow-sm">
                <h5>Applications Overview</h5>
                <canvas id="applicationsChart"></canvas>
            </div>
        </div>
    </div>

    <!-- ================= SEARCH RESULTS ================= -->
    @if(request('search'))

    <div class="card shadow-sm">
        <div class="card-body">

            <h5 class="mb-3">
                Results for: <b>{{ request('search') }}</b>
            </h5>

            <div class="table-responsive">

                <table class="table table-bordered table-hover align-middle">

                    <thead class="table-dark">
                        <tr>
                            <th>Applicant</th>
                            <th>File Name</th>
                            <th>Department</th>
                            <th>Date Uploaded</th>
                            <th width="180">Action</th>
                        </tr>
                    </thead>

                    <tbody>

                        @forelse($applications as $app)

                        <tr>
                            <td>{{ $app->name }}</td>

                            <td>
                                {{ $app->file_name ?? basename($app->file_path ?? '') }}
                            </td>

                            <td>
                                <span class="badge bg-secondary">
                                    {{ strtoupper($app->department) }}
                                </span>
                            </td>

                            <td>
                                {{ \Carbon\Carbon::parse($app->created_at)->format('Y-m-d') }}
                            </td>

                            <td>

                                @if(!empty($app->file_path))

                                <button type="button"
        class="btn btn-sm btn-primary viewFileBtn"
        data-file="{{ asset('storage/'.$app->file_path) }}">
        👁 View File
    </button>

                                <a href="{{ asset('storage/'.$app->file_path) }}"
                                   download
                                   class="btn btn-sm btn-secondary">
                                   Download
                                </a>

                                @else
                                <span class="text-muted">No file</span>
                                @endif

                            </td>
                        </tr>

                        @empty
                        <tr>
                            <td colspan="5" class="text-center text-muted">
                                No results found
                            </td>
                        </tr>
                        @endforelse

                    </tbody>

                </table>

            </div>

        </div>
    </div>

    @endif

</div>

<!-- File Preview Modal -->
<div class="modal fade" id="filePreviewModal" tabindex="-1">
    <div class="modal-dialog modal-xl modal-dialog-centered">
        <div class="modal-content">
            
            <div class="modal-header bg-primary text-white">
    <h5 class="modal-title">📄 File Preview</h5>
    <button type="button" class="close text-white" data-dismiss="modal">
        <span>&times;</span>
    </button>
</div>

            <div class="modal-body p-0" style="height:80vh;">
                <iframe id="filePreviewFrame"
                        width="100%"
                        height="100%"
                        style="border:none;"></iframe>
            </div>

        </div>
    </div>
</div>

@endsection


@section('js')

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
document.addEventListener("DOMContentLoaded", function () {

    const ctx = document.getElementById('applicationsChart');

    if (ctx) {
        new Chart(ctx, {
            type: 'bar',
            data: {
                labels: ['MPDO', 'MEO', 'BFP', 'Pending'],
                datasets: [{
                    label: 'Applications',
                    data: [
                        {{ $mpdoVerified }},
                        {{ $meoVerified }},
                        {{ $bfpVerified }},
                        {{ $pending }}
                    ],
                    backgroundColor: [
    '#6FA8DC',  // MPDO (soft blue)
    '#76C7A3',  // MEO (mint green)
    '#E57373',  // BFP (soft red)
    '#FFD54F'   // Pending (soft yellow)
],
                    borderRadius: 8
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        labels: {
                            color: '#444'
                        }
                    }
                },
                scales: {
                    y: {
                        grid: {
                            color: 'rgba(0,0,0,0.05)'
                        },
                        beginAtZero: true
                    }
                }
            }
        });
    }

});


</script>

<script>
document.addEventListener("DOMContentLoaded", function () {

    const buttons = document.querySelectorAll(".viewFileBtn");
    const frame = document.getElementById("filePreviewFrame");

    buttons.forEach(button => {
        button.addEventListener("click", function () {

            let fileUrl = this.getAttribute("data-file");

            frame.src = fileUrl;

            // BOOTSTRAP 4 (ADMINLTE)
            $('#filePreviewModal').modal('show');
        });
    });

    $('#filePreviewModal').on('hidden.bs.modal', function () {
        frame.src = '';
    });

});
</script>

@endsection