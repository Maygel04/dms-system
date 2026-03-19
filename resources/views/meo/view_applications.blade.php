@php  
$layout = 'layouts.department';

$hasRemark = !empty($remark);

/*
REAL REUPLOAD CHECK:
If may remark, check if any file was uploaded AFTER remark timestamp.
*/
$remarkTime = null;
$hasReupload = false;

if($hasRemark){
    $remarkTime = DB::table('remarks')
        ->where('application_id', $application->id ?? 0)
        ->where('department','meo')
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

/*
LOCK RULE:
If may remark AND wala pa reupload → lock
If may remark AND may reupload → unlock
*/
$lockAssessment = $hasRemark && !$hasReupload;
@endphp

@extends($layout)

@section('content')

<form method="GET" class="mb-3">
    <input type="text"
           name="search"
           value="{{ request('search') }}"
           placeholder="🔎 Search inside documents..."
           class="form-control">
</form>

<div class="container py-4">

{{-- ================= ALERTS ================= --}}
@if(session('success'))
<div class="alert alert-success">{{ session('success') }}</div>
@endif

@if(session('error'))
<div class="alert alert-danger">{{ session('error') }}</div>
@endif

@if ($errors->any())
<div class="alert alert-danger">
<ul class="mb-0">
@foreach ($errors->all() as $error)
<li>{{ $error }}</li>
@endforeach
</ul>
</div>
@endif

<h2 class="mb-3">📂 MEO Submitted Requirements</h2>

@if(empty($application))
<div class="alert alert-info">No application submitted yet.</div>
@php return; @endphp
@endif


{{-- ================= APPLICANT INFO ================= --}}
@if($applicant)
<div class="card mb-4 shadow-sm">
<div class="card-header bg-dark text-white">Applicant Information</div>
<div class="card-body">
<p><b>Name:</b> {{ $applicant->name ?? 'N/A' }}</p>
<p><b>Contact:</b> {{ $applicant->contact_number ?? 'N/A' }}</p>
<p><b>Address:</b> {{ $applicant->address ?? 'N/A' }}</p>
<p><b>Gender:</b> {{ $applicant->gender ?? 'N/A' }}</p>
<p><b>Occupation:</b> {{ $applicant->occupation ?? 'N/A' }}</p>
</div>
</div>
@endif


{{-- ================= FILES ================= --}}
<div class="card shadow-sm mb-4">
<div class="card-header bg-primary text-white">
{{ $hasReupload ? 'Re-uploaded Documents' : 'Uploaded Documents' }}
</div>

<div class="card-body p-0">
@if(empty($files) || $files->isEmpty())
<div class="p-3">No submitted files yet.</div>
@else
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


@php
$isReupload = false;
if($remarkTime){
    $isReupload = \Carbon\Carbon::parse($f->created_at) > \Carbon\Carbon::parse($remarkTime);
}
@endphp

<tr>
<td>{{ $i+1 }}</td>

<td>
{{ $f->file_name }}

@if($isReupload)
<span class="badge bg-info">Re-upload</span>
@else
<span class="badge bg-secondary">Original</span>
@endif
</td>

<td>
{{ $f->created_at ? \Carbon\Carbon::parse($f->created_at)->format('Y-m-d H:i') : '-' }}
</td>

<td>
<span class="badge bg-{{ $f->viewed ? 'success' : 'warning' }}">
{{ $f->viewed ? 'Viewed' : 'Submitted' }}
</span>
</td>
<td>



@if($f->viewed)
<span class="badge bg-success viewed-badge-{{ $f->id }}">Viewed</span>
@else
<span class="badge bg-secondary viewed-badge-{{ $f->id }}">Not Viewed</span>
@endif
</td>
</tr>
@endforeach

</tbody>
</table>
@endif
</div>
</div>
<div class="modal fade" id="fileModal" tabindex="-1">
  <div class="modal-dialog modal-xl modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header bg-dark text-white">
        <h5 class="modal-title">📄 File Viewer</h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body" style="height:80vh">
        <iframe id="fileFrame" src="" width="100%" height="100%" style="border:none;"></iframe>
      </div>
    </div>
  </div>
</div>

{{-- ================= REMARK ================= --}}
@if($hasRemark)
<div class="card shadow-sm mb-4">
<div class="card-header bg-warning">📝 MEO Latest Remark</div>
<div class="card-body">
<div class="border p-2 rounded">{{ $remark }}</div>

@if(!$hasReupload)
<div class="text-danger mt-2">
⛔ Waiting for applicant to re-upload corrected documents.
</div>
@else
<div class="text-success mt-2">
✔ Applicant has re-uploaded corrected documents.
</div>
@endif
</div>
</div>
@endif



{{-- ================= ASSESSMENT ================= --}}
@if(!$assessed)

<div class="card shadow-sm mb-4">
<div class="card-header bg-success text-white">Assessment</div>

<div class="card-body">

<h5 class="mb-2">Checklist (Required before Verify)</h5>

<table class="table table-bordered">
<tr>
<th>#</th>
<th>Requirement</th>
<th>Check</th>
</tr>

@php
$items = [
"Site Development Plan",
"Tax Declaration",
"Barangay Construction Certificate"
];
@endphp

@foreach($items as $i => $item)
<tr>
<td>{{ $i+1 }}</td>
<td>{{ $item }}</td>
<td><input type="checkbox" class="chk"></td>
</tr>
@endforeach
</table>


{{-- SAVE ASSESSMENT --}}
<form method="POST" action="{{ route('meo.saveAssessment') }}">
@csrf
<input type="hidden" name="application_id" value="{{ $application->id }}">

<label>Select Assessment Fee</label>

<select name="amount"
class="form-control mb-3"
id="assessmentSelect"
{{ $lockAssessment ? 'disabled' : '' }}
required>

<option value="">-- Select Fee --</option>
<option value="100">₱100</option>
<option value="200">₱200</option>
<option value="300">₱300</option>
<option value="500">₱500</option>
<option value="1000">₱1000</option>
</select>

<button class="btn btn-primary"
id="verifyBtn"
{{ $lockAssessment ? 'disabled' : '' }}>
Save & Verify
</button>

@if($lockAssessment)
<div class="text-danger mt-2">
🔒 Locked until applicant re-uploads corrected documents.
</div>
@endif



{{-- ================= FILE MODAL ================= --}}
<div id="fileModal"
     class="fixed inset-0 bg-black bg-opacity-60 hidden items-center justify-center z-50">

    <div class="bg-white w-11/12 h-5/6 rounded shadow-lg flex flex-col">

        <div class="flex justify-between items-center p-3 border-b">
            <h3 class="font-semibold">📄 File Viewer</h3>
            <button id="closeModal" class="text-red-600 font-bold text-lg">✖</button>
        </div>

        <iframe id="fileFrame" class="flex-1 w-full"></iframe>

    </div>
</div>


</form>


{{-- FINAL VERIFY --}}
<form method="POST" action="{{ route('meo.verify', $application->id) }}" class="mt-3">
@csrf
<button type="submit" class="btn btn-success w-100">
✔ FINAL VERIFY (Send Email)
</button>
</form>


{{-- REMARK --}}
<form method="POST" action="{{ route('meo.saveRemark') }}" class="mt-3">
@csrf
<input type="hidden" name="application_id" value="{{ $application->id }}">

<label class="form-label">Remarks</label>
<textarea name="remark" class="form-control" rows="3" required></textarea>

<button type="submit" class="btn btn-secondary mt-2">
Save Remark
</button>
</form>

</div>
</div>

@else

<div class="alert alert-success shadow-sm mb-4">
✔ <b>Verified</b><br><br>
<b>Amount:</b> ₱{{ number_format($assessmentAmount ?? 0, 2) }} <br>
<b>Date:</b> {{ $verifiedOn ? \Carbon\Carbon::parse($verifiedOn)->format('Y-m-d H:i') : '-' }}
</div>

@endif

</div>


{{-- CHECKLIST SCRIPT --}}
<script>
document.addEventListener("DOMContentLoaded", function(){

const checks = document.querySelectorAll('.chk');
const verifyBtn = document.getElementById('verifyBtn');
const feeSelect = document.getElementById('assessmentSelect');

function toggleVerify(){
    if(!verifyBtn) return;

    let allChecked = true;
    checks.forEach(c=>{
        if(!c.checked) allChecked = false;
    });

    let feeSelected = feeSelect && feeSelect.value !== "";

    verifyBtn.disabled = !(allChecked && feeSelected);
}

checks.forEach(c=>c.addEventListener('change',toggleVerify));
if(feeSelect) feeSelect.addEventListener('change',toggleVerify);

toggleVerify();

});
</script>

<script>
document.addEventListener("DOMContentLoaded", function () {

    const modalElement = document.getElementById('fileModal');
    const frame = document.getElementById('fileFrame');

    if (!modalElement) return;

    const modal = new bootstrap.Modal(modalElement);

    document.querySelectorAll(".openFile").forEach(btn => {
        btn.addEventListener("click", function (e) {

            e.preventDefault();
            e.stopPropagation();

            let fileUrl = this.getAttribute("data-file");
            let fileId  = this.getAttribute("data-id");

            frame.src = fileUrl;
            modal.show();

            fetch(`{{ url('/mark-viewed') }}/${fileId}`, {
                method: "POST",
                headers: {
                    "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    "Accept": "application/json"
                }
            })
            .then(response => response.json())
            .then(data => {
                let badge = document.querySelector(".viewed-badge-" + fileId);
                if (badge) {
                    badge.classList.remove("bg-secondary");
                    badge.classList.add("bg-success");
                    badge.innerText = "Viewed";
                }
            })
            .catch(error => console.log("Error:", error));

        });
    });

});


/* ================= MODAL + VIEWED ================= */

const modal = document.getElementById('fileModal');
const frame = document.getElementById('fileFrame');
const closeModal = document.getElementById('closeModal');

document.querySelectorAll('.openFile').forEach(btn => {

    btn.addEventListener('click', function(){

        const fileUrl = this.dataset.file;
        const fileId  = this.dataset.id;

        frame.src = fileUrl;
        modal.classList.remove('hidden');
        modal.classList.add('flex');

        fetch("/applicant/mark-viewed/" + fileId, {
            method: "POST",
            headers: {
                "X-CSRF-TOKEN": "{{ csrf_token() }}",
                "Accept": "application/json"
            }
        })
        .then(res => res.json())
        .then(() => {

            const badge = document.querySelector('.viewed-badge-' + fileId);
            if(badge){
                badge.classList.remove('bg-gray-400');
                badge.classList.add('bg-green-500');
                badge.innerText = 'Viewed';
            }

        });

    });

});

closeModal.addEventListener('click', function(){
    modal.classList.add('hidden');
    frame.src = "";
});

</script>

</script>

@endsection 