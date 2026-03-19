@extends('adminlte::page')

@section('css')
<link rel="stylesheet" href="{{ asset('css/custom-adminlte.css') }}">
@endsection

@section('title', 'MEO Requirements')

@section('content_header')
    <div class="d-flex align-items-center justify-content-between">
        <div>
            <h1 class="mb-0 text-navy">
                <i class="fas fa-landmark mr-2"></i> MEO Requirements
            </h1>
            <small class="text-muted">Municipal Engineering Office - Building Permit Application</small>
        </div>
    </div>
@stop

@section('content')

@php
    $latestRemarkRow = null;
    $remarkText = null;
    $remarkTime = null;
    $hasReuploadAfterRemark = false;
    $needsReupload = false;

    if (!empty($app)) {
        $latestRemarkRow = \Illuminate\Support\Facades\DB::table('remarks')
            ->where('application_id', $app->id)
            ->where('department', 'meo')
            ->latest('created_at')
            ->first();

        if ($latestRemarkRow) {
            $remarkText = $latestRemarkRow->remarks ?? $latestRemarkRow->remark ?? null;
            $remarkTime = $latestRemarkRow->updated_at ?? $latestRemarkRow->created_at;

            $meoDocs = \App\Models\Document::where('application_id', $app->id)
                ->where('department', 'meo')
                ->get();

            foreach ($meoDocs as $doc) {
                if (\Carbon\Carbon::parse($doc->created_at)->gt(\Carbon\Carbon::parse($remarkTime))) {
                    $hasReuploadAfterRemark = true;
                    break;
                }
            }

            $needsReupload = !$hasReuploadAfterRemark;
        }
    }
@endphp

<div class="container-fluid">

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show shadow-sm" role="alert">
            <i class="fas fa-check-circle mr-1"></i> {{ session('success') }}
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    @endif

    @if(!$uploaded || $needsReupload)

        @if($needsReupload && $remarkText)
            <div class="alert alert-danger shadow-sm">
                <h5 class="mb-2"><i class="fas fa-exclamation-triangle mr-1"></i> MEO Remark</h5>
                <div>{{ $remarkText }}</div>
                <div class="mt-2 font-weight-bold">Please re-upload the corrected documents.</div>
            </div>
        @endif

        <div class="card card-outline card-primary shadow">
            <div class="card-header bg-navy text-white">
                <h3 class="card-title">
                    <i class="fas fa-folder-open mr-2"></i> Required Supporting Documents
                </h3>
            </div>

            <div class="card-body">
                <div class="mb-3">
                    <div class="border rounded p-3 bg-light">
                        <div class="font-weight-bold text-secondary mb-2">
                            Submission Guidelines
                        </div>
                        <div class="small text-muted">
                            Accepted formats: <strong>PDF, DWG, DXF</strong><br>
                            Maximum file size: <strong>150MB</strong> per file for AutoCAD/PDF attachments.<br>
                            Please ensure that all uploaded plans are clear, complete, and readable.
                        </div>
                    </div>
                </div>

                @if($errors->has('upload'))
                    <div class="alert alert-danger">
                        {{ $errors->first('upload') }}
                    </div>
                @endif

                <form method="POST" action="{{ route('applicant.upload_meo.store') }}" enctype="multipart/form-data">
                    @csrf

                    <div class="table-responsive">
                        <table class="table table-bordered table-striped table-hover mb-0">
                            <thead class="bg-light">
                                <tr class="text-center">
                                    <th style="width: 70px;">#</th>
                                    <th class="text-left">Required Document</th>
                                    <th style="width: 320px;">Upload</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php
                                    $reqs = [
                                        ['name' => 'architectural_plan', 'label' => 'Architectural Plans'],
                                        ['name' => 'structural_plan', 'label' => 'Structural Plans'],
                                        ['name' => 'electrical_plan', 'label' => 'Electrical Plans'],
                                        ['name' => 'plumbing_plan', 'label' => 'Plumbing / Sanitary Plans'],
                                        ['name' => 'mechanical_plan', 'label' => 'Mechanical Plans'],
                                        ['name' => 'bill_materials', 'label' => 'Bill of Materials & Cost Estimates'],
                                        ['name' => 'engineer_plan', 'label' => 'Engineer’s Plans'],
                                        ['name' => 'construction_schedule', 'label' => 'Construction Schedule'],
                                        ['name' => 'electronics_plan', 'label' => 'Electronics Plans']
                                    ];
                                    $i = 1;
                                @endphp

                                @foreach($reqs as $r)
                                    <tr>
                                        <td class="text-center align-middle font-weight-bold">
                                            {{ $i++ }}
                                        </td>

                                        <td class="align-middle">
                                            <div class="font-weight-bold text-dark">{{ $r['label'] }}</div>
                                        </td>

                                        <td class="align-middle text-center">
                                            <label class="btn btn-danger btn-sm mb-1 file-label">
                                                <i class="fas fa-upload mr-1"></i> Choose File
                                                <input
                                                    type="file"
                                                    name="{{ $r['name'] }}"
                                                    class="d-none doc-input"
                                                    accept=".pdf,.dwg,.dxf">
                                            </label>

                                            <div class="file-status small text-danger font-weight-bold">
                                                No file selected
                                            </div>

                                            <div class="file-name small text-muted mt-1"></div>

                                            @error($r['name'])
                                                <div class="text-danger small mt-1">{{ $message }}</div>
                                            @enderror
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <div class="mt-4 d-flex justify-content-between align-items-center flex-wrap">
                        <div class="text-muted small mb-2">
                            Municipal Engineering Office - Digital Submission Portal
                        </div>

                        <button type="submit" class="btn btn-primary px-4">
                            <i class="fas fa-paper-plane mr-1"></i>
                            {{ $needsReupload ? 'Re-upload MEO Requirements' : 'Submit MEO Requirements' }}
                        </button>
                    </div>
                </form>
            </div>
        </div>

    @else

        <div class="card card-outline card-success shadow">
            <div class="card-header bg-success text-white">
                <h3 class="card-title">
                    <i class="fas fa-clipboard-check mr-2"></i> Submission Status
                </h3>
            </div>

            <div class="card-body text-center py-5">
                <div class="mb-3" style="font-size: 55px;">📄</div>

                <h4 class="text-navy font-weight-bold">Requirements Already Submitted</h4>

                <p class="text-muted mb-4">
                    Your documents are currently under evaluation by the Municipal Engineering's Office.
                </p>

                @if(($app->meo_paid ?? 0) == 1)
                    <span class="badge badge-success p-3" style="font-size: 15px;">
                        Status: MEO Payment Completed
                    </span>
                @elseif(($status ?? '') == 'endorsed')
                    <span class="badge badge-primary p-3" style="font-size: 15px;">
                        Status: Endorsed by MEO
                    </span>
                @elseif(($status ?? '') == 'assessed')
                    <span class="badge badge-info p-3" style="font-size: 15px;">
                        Status: Assessed by MEO
                    </span>
                @elseif(($status ?? '') == 'verified')
                    <span class="badge badge-success p-3" style="font-size: 15px;">
                        Status: Documents Verified by MEO
                    </span>
                @elseif(($status ?? '') == 'pending')
                    <span class="badge badge-warning p-3" style="font-size: 15px;">
                        Status: Under Technical Review
                    </span>
                @else
                    <span class="badge badge-secondary p-3" style="font-size: 15px;">
                        Status: Waiting for Review
                    </span>
                @endif
            </div>
        </div>

    @endif

    <div class="text-center mt-3 mb-4">
        <a href="/applicant/dashboard" class="btn btn-default">
            <i class="fas fa-arrow-left mr-1"></i> Return to Dashboard
        </a>
    </div>

</div>
@stop

@section('js')
<script>
    document.querySelectorAll('.doc-input').forEach(function(input) {
        input.addEventListener('change', function() {
            const td = this.closest('td');
            const label = td.querySelector('.file-label');
            const status = td.querySelector('.file-status');
            const nameBox = td.querySelector('.file-name');

            if (this.files.length > 0) {
                label.classList.remove('btn-danger');
                label.classList.add('btn-success');
                label.innerHTML = '<i class="fas fa-check mr-1"></i> File Selected';
                label.appendChild(this);

                status.classList.remove('text-danger');
                status.classList.add('text-success');
                status.textContent = 'Ready for upload';

                nameBox.textContent = this.files[0].name;
            } else {
                label.classList.remove('btn-success');
                label.classList.add('btn-danger');
                label.innerHTML = '<i class="fas fa-upload mr-1"></i> Choose File';
                label.appendChild(this);

                status.classList.remove('text-success');
                status.classList.add('text-danger');
                status.textContent = 'No file selected';

                nameBox.textContent = '';
            }
        });
    });
</script>
@stop