@extends('adminlte::page')


@section('css')
<link rel="stylesheet" href="{{ asset('css/custom-adminlte.css') }}">
@endsection

@section('content')

<div class="container-fluid">

@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show">
        <i class="fas fa-check-circle mr-2"></i>
        <strong>Success</strong><br>
        {{ session('success') }}
        <button type="button" class="close" data-dismiss="alert">
            <span>&times;</span>
        </button>
    </div>
@endif

@if(session('warning'))
    <div class="alert alert-warning alert-dismissible fade show">
        <i class="fas fa-exclamation-triangle mr-2"></i>
        <strong>Warning</strong><br>
        {{ session('warning') }}
        <button type="button" class="close" data-dismiss="alert">
            <span>&times;</span>
        </button>
    </div>
@endif

@if(session('remark_success'))
    <div class="alert alert-success alert-dismissible fade show">
        <i class="fas fa-comment-dots mr-2"></i>
        <strong>Remarks Update</strong><br>
        {{ session('remark_success') }}
        <button type="button" class="close" data-dismiss="alert">
            <span>&times;</span>
        </button>
    </div>
@endif

@if(session('remark_warning'))
    <div class="alert alert-warning alert-dismissible fade show">
        <i class="fas fa-exclamation-triangle mr-2"></i>
        <strong>Remarks Warning</strong><br>
        {{ session('remark_warning') }}
        <button type="button" class="close" data-dismiss="alert">
            <span>&times;</span>
        </button>
    </div>
@endif

@if(session('verify_success'))
    <div class="alert alert-success alert-dismissible fade show">
        <i class="fas fa-check-circle mr-2"></i>
        <strong>Verification Update</strong><br>
        {{ session('verify_success') }}
        <button type="button" class="close" data-dismiss="alert">
            <span>&times;</span>
        </button>
    </div>
@endif

@if(session('verify_warning'))
    <div class="alert alert-warning alert-dismissible fade show">
        <i class="fas fa-exclamation-triangle mr-2"></i>
        <strong>Verification Warning</strong><br>
        {{ session('verify_warning') }}
        <button type="button" class="close" data-dismiss="alert">
            <span>&times;</span>
        </button>
    </div>
@endif

@if(session('error'))
    <div class="alert alert-danger alert-dismissible fade show">
        <i class="fas fa-times-circle mr-2"></i>
        <strong>Error</strong><br>
        {{ session('error') }}
        <button type="button" class="close" data-dismiss="alert">
            <span>&times;</span>
        </button>
    </div>
@endif

<h4 class="mb-3">📂 MPDO Applications</h4>

<div class="card shadow-sm p-3 mb-4">

<table class="table table-bordered table-hover">

<thead class="table-light">
<tr>
<th>#</th>
<th>Applicant</th>
<th>Submitted</th>
<th>Status</th>
<th>Action</th>
</tr>
</thead>

<tbody>

@forelse($applications as $a)

<tr>

<td>{{ $a->id }}</td>

<td>{{ $a->name }}</td>

<td>{{ \Carbon\Carbon::parse($a->created_at)->format('Y-m-d H:i') }}</td>

<td>
    @php
        $mpdoRemarkExists = \Illuminate\Support\Facades\DB::table('remarks')
            ->where('application_id', $a->id)
            ->where('department', 'mpdo')
            ->exists();

        $statusLabel = 'UNDER REVIEW';
        $statusClass = 'bg-warning text-dark';

        if (($a->mpdo_status ?? '') == 'verified') {
            $statusLabel = 'VERIFIED';
            $statusClass = 'bg-success';
        } elseif (($a->mpdo_status ?? '') == 'assessed') {
            $statusLabel = 'ASSESSED';
            $statusClass = 'bg-info';
        } elseif (($a->mpdo_status ?? '') == 'pending') {
            $statusLabel = 'UNDER REVIEW';
            $statusClass = 'bg-warning text-dark';
        } else {
            $statusLabel = strtoupper($a->mpdo_status ?? 'N/A');
            $statusClass = 'bg-secondary';
        }
    @endphp

    <span class="badge {{ $statusClass }}">
        {{ $statusLabel }}
    </span>
</td>

<td>
<a href="{{ url('/mpdo/applications?app_id='.$a->id) }}"
class="btn btn-sm btn-primary">
View
</a>
</td>

</tr>

@empty

<tr>
<td colspan="5" class="text-center text-muted">
No applications found
</td>
</tr>

@endforelse

</tbody>
</table>

</div>


{{-- ================= SHOW APPLICATION DETAILS ================= --}}
@if(request('app_id'))

@php

$application = \App\Models\Application::find(request('app_id'));

$applicant = \App\Models\User::find($application->applicant_id);

$files = \App\Models\Document::where('application_id',$application->id)
            ->where('department','mpdo')
            ->get();

$assessment = \App\Models\Assessment::where('application_id',$application->id)
            ->where('department','mpdo')
            ->first();

$assessmentAmount = $assessment->amount ?? null;
$verifiedOn = $assessment->verified_on ?? null;

$remark = DB::table('remarks')
    ->where('application_id', $application->id)
    ->where('department', 'mpdo')
    ->latest('created_at')
    ->value('remarks');

$remarks = DB::table('remarks')
    ->where('application_id', $application->id)
    ->where('department', 'mpdo')
    ->orderBy('created_at', 'desc')
    ->get();
@endphp


@php  

$hasRemark = !empty($remark);

$remarkTime = null;
$hasReupload = false;

if($hasRemark){

    $remarkTime = DB::table('remarks')
        ->where('application_id',$application->id)
        ->where('department','mpdo')
        ->value('updated_at');

    if($remarkTime && isset($files)){
        foreach($files as $f){
            if(\Carbon\Carbon::parse($f->created_at) > \Carbon\Carbon::parse($remarkTime)){
                $hasReupload = true;
                break;
            }
        }
    }
}

$lockAssessment = $hasRemark && !$hasReupload;

@endphp


<div class="container py-4">

<h2 class="mb-3">📂 MPDO Submitted Requirements</h2>


{{-- APPLICANT INFO --}}
@if($applicant)

<div class="card mb-4 shadow-sm">
<div class="card-header bg-dark text-white">Applicant Information</div>
<div class="card-body">

<p><b>Name:</b> {{ $applicant->name }}</p>
<p><b>Contact:</b> {{ $applicant->contact_number }}</p>
<p><b>Address:</b> {{ $applicant->address }}</p>
<p><b>Gender:</b> {{ $applicant->gender }}</p>
<p><b>Occupation:</b> {{ $applicant->occupation }}</p>

</div>
</div>

@endif



{{-- FILES --}}
<div class="card shadow-sm mb-4">
<div class="card-header bg-primary text-white">
{{ $hasReupload ? 'Re-uploaded Documents' : 'Uploaded Documents' }}
</div>

<div class="card-body p-0">

<table class="table table-bordered mb-0">

<thead class="table-light">
<tr>
<th>#</th>
<th>File</th>
<th>Submitted</th>
<th>Status</th>
<th>Action</th>
</tr>
</thead>

<tbody>

@foreach($files as $i => $f)

<tr>

<td>{{ $i+1 }}</td>

<td>
    <b>{{ optional($applicant)->name }}</b><br>
    {{ $f->file_name }}<br>

    @if(isset($f->is_old) && $f->is_old == 1)
        <span class="badge bg-warning text-dark mt-1">OLD FILE</span>
    @endif

    @if(isset($f->uploaded_by_admin) && $f->uploaded_by_admin == 1)
        <span class="badge bg-info mt-1">UPLOADED BY ADMIN</span>
    @endif
</td>

<td>{{ \Carbon\Carbon::parse($f->created_at)->format('Y-m-d H:i') }}</td>

<td>
    @if(isset($f->is_old) && $f->is_old == 1)
        <span class="badge bg-secondary">Old Record</span>
    @else
        <span class="badge bg-warning">Submitted</span>
    @endif
</td>

<td>
<button type="button"
    class="btn btn-sm btn-primary viewFileBtn"
    data-file="{{ asset('storage/mpdo_docs/'.$f->file_name) }}">
    👁 View File
</button>

</td>

</tr>

@endforeach

</tbody>

</table>

</div>
</div>



@if($lockAssessment)

<div class="alert alert-warning">
⛔ Applicant must re-upload corrected documents before continuing review.
</div>

@endif



{{-- CHECKLIST --}}
@if(!$lockAssessment && !$assessmentAmount)

<div class="card shadow-sm mb-4">
<div class="card-header bg-secondary text-white">
Checklist (Required before Assessment)
</div>

<div class="card-body">

<table class="table table-bordered">

<tr>
<th>#</th>
<th>Requirement</th>
<th>Check</th>
</tr>

<tr>
<td>1</td>
<td>Site Development Plan</td>
<td><input type="checkbox" class="chk"></td>
</tr>

<tr>
<td>2</td>
<td>Tax Declaration</td>
<td><input type="checkbox" class="chk"></td>
</tr>

<tr>
<td>3</td>
<td>Barangay Construction Certificate</td>
<td><input type="checkbox" class="chk"></td>
</tr>

</table>

</div>
</div>

@endif



@if(!$lockAssessment && !$assessmentAmount)

{{-- REMARK FORM --}}
<div class="card shadow-sm mb-4">

<div class="card-header bg-warning">
📝 Send Remark to Applicant
</div>

<div class="card-body">

<form method="POST" action="{{ route('mpdo.saveRemark') }}">

@csrf
<input type="hidden" name="application_id" value="{{ $application->id }}">

<label>Remark / Correction Needed</label>

<textarea
name="remark"
class="form-control mb-3"
rows="3"
required></textarea>

<button class="btn btn-danger">
Send Remark
</button>

</form>

</div>
</div>

@endif



{{-- REMARK HISTORY --}}
@if(isset($remarks) && count($remarks))

<div class="card shadow-sm mt-3">

<div class="card-header bg-dark text-white">
📜 Remarks History
</div>

<div class="card-body">

@foreach($remarks as $r)

<div class="border p-2 mb-2">

{{ $r->remarks }} <br>
<small>{{ $r->created_at }}</small>

</div>

@endforeach

</div>
</div>

@endif



{{-- SHOW ASSESSMENT AMOUNT --}}
@if($assessmentAmount)

<div class="card shadow-sm mb-3">

<div class="card-header bg-success text-white">
Assessment Saved
</div>

<div class="card-body">

<b>Assessment Amount:</b>

<span class="badge bg-success">
₱{{ number_format($assessmentAmount,2) }}
</span>

<br><br>

<a href="{{ url('/mpdo/receipt/'.$application->id) }}"
class="btn btn-primary">

🧾 View Receipt

</a>

</div>

</div>

@endif

{{-- SHOW VERIFIED STATUS --}}
@if($verifiedOn)

<div class="card shadow-sm mb-3">

<div class="card-header bg-success text-white">
✔ Documents Verified Successfully
</div>

<div class="card-body">

<p class="mb-1"><b>Status:</b> 
<span class="badge bg-success">VERIFIED</span>
</p>

<p class="mb-2">
<b>Verified On:</b>
{{ \Carbon\Carbon::parse($verifiedOn)->format('F d, Y h:i A') }}
</p>

<div class="alert alert-success mb-0">
Applicant documents have been officially verified by MPDO.
</div>

</div>

</div>

@endif

{{-- ASSESSMENT --}}
@if(!$assessmentAmount && !$lockAssessment)

<div class="card shadow-sm mb-4" id="assessmentCard" style="display:none;">

<div class="card-header bg-success text-white">
Assessment
</div>

<div class="card-body">

<form method="POST" action="{{ route('mpdo.saveAssessment') }}">
@csrf

<input type="hidden" name="application_id" value="{{ $application->id }}">

<label class="font-weight-bold">Select Assessment Fee</label>
<select id="preset_amount" class="form-control mb-3">
    <option value="">-- Select Fee --</option>
    <option value="100">₱100</option>
    <option value="200">₱200</option>
    <option value="300">₱300</option>
    <option value="500">₱500</option>
    <option value="1000">₱1000</option>
    <option value="custom">Other Amount</option>
</select>

<label class="font-weight-bold">Or Enter Custom Amount</label>
<input
    type="number"
    name="amount"
    id="custom_amount"
    class="form-control mb-3"
    min="1"
    step="0.01"
    placeholder="Enter amount here"
    required>

<button class="btn btn-primary">
💾 Save Assessment Fee
</button>

</form>

</div>
</div>

@elseif($assessmentAmount && !$verifiedOn)

<div class="card shadow-sm mb-4">
<div class="card-body">

<form method="POST" action="{{ route('mpdo.verify',$application->id) }}">
@csrf

<button type="submit" class="btn btn-success w-100">
✔ VERIFY DOCUMENTS
</button>

</form>

</div>
</div>

@endif

</div>


<!-- File Preview Modal -->
<div class="modal fade" id="filePreviewModal" tabindex="-1" aria-labelledby="filePreviewModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-centered">
        <div class="modal-content">
            
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title" id="filePreviewModalLabel">📄 File Preview</h5>
                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <div class="modal-body p-0" style="height: 80vh;">
                <iframe id="filePreviewFrame"
                    src=""
                    width="100%"
                    height="100%"
                    style="border:none;">
                </iframe>
            </div>

        </div>
    </div>
</div>


<script>

document.addEventListener("DOMContentLoaded", function(){

let checks = document.querySelectorAll('.chk');
let assessment = document.getElementById('assessmentCard');

function checkChecklist(){

    let anyChecked = false;

    checks.forEach(c=>{
        if(c.checked){
            anyChecked = true;
        }
    });

    if(assessment){
        assessment.style.display = anyChecked ? "block" : "none";
    }

}

checks.forEach(c=>{
    c.addEventListener('change',checkChecklist);
});

checkChecklist();

});

</script>

<script>
document.addEventListener("DOMContentLoaded", function () {
    const preset = document.getElementById("preset_amount");
    const custom = document.getElementById("custom_amount");

    if (preset && custom) {
        preset.addEventListener("change", function () {
            if (this.value !== "" && this.value !== "custom") {
                custom.value = this.value;
            } else if (this.value === "custom") {
                custom.value = "";
                custom.focus();
            } else {
                custom.value = "";
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

            $('#filePreviewModal').modal('show');
        });
    });

    $('#filePreviewModal').on('hidden.bs.modal', function () {
        frame.src = '';
    });
});
</script>

@endif {{-- THIS IS THE FIX (closing request('app_id')) --}}

@endsection