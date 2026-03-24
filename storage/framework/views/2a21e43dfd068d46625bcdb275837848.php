




<?php $__env->startSection('css'); ?>
<link rel="stylesheet" href="<?php echo e(asset('css/custom-adminlte.css')); ?>">
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>

<div class="container-fluid">

    
    <?php if(session('remark_success')): ?>
        <div class="alert alert-success alert-dismissible fade show">
            <i class="fas fa-check-circle mr-2"></i>
            <strong>Success</strong><br>
            <?php echo e(session('remark_success')); ?>

            <button type="button" class="close" data-dismiss="alert">
                <span>&times;</span>
            </button>
        </div>
    <?php endif; ?>

    <?php if(session('remark_warning')): ?>
        <div class="alert alert-warning alert-dismissible fade show">
            <i class="fas fa-exclamation-triangle mr-2"></i>
            <strong>Warning</strong><br>
            <?php echo e(session('remark_warning')); ?>

            <button type="button" class="close" data-dismiss="alert">
                <span>&times;</span>
            </button>
        </div>
    <?php endif; ?>

    <?php if(session('verify_success')): ?>
        <div class="alert alert-success alert-dismissible fade show">
            <i class="fas fa-check-circle mr-2"></i>
            <strong>Success</strong><br>
            <?php echo e(session('verify_success')); ?>

            <button type="button" class="close" data-dismiss="alert">
                <span>&times;</span>
            </button>
        </div>
    <?php endif; ?>

    <?php if(session('verify_warning')): ?>
        <div class="alert alert-warning alert-dismissible fade show">
            <i class="fas fa-exclamation-triangle mr-2"></i>
            <strong>Warning</strong><br>
            <?php echo e(session('verify_warning')); ?>

            <button type="button" class="close" data-dismiss="alert">
                <span>&times;</span>
            </button>
        </div>
    <?php endif; ?>

    <?php if(session('success')): ?>
        <div class="alert alert-success alert-dismissible fade show">
            <i class="fas fa-check-circle mr-2"></i>
            <strong>Success</strong><br>
            <?php echo e(session('success')); ?>

            <button type="button" class="close" data-dismiss="alert">
                <span>&times;</span>
            </button>
        </div>
    <?php endif; ?>

    <?php if(session('warning')): ?>
        <div class="alert alert-warning alert-dismissible fade show">
            <i class="fas fa-exclamation-triangle mr-2"></i>
            <strong>Warning</strong><br>
            <?php echo e(session('warning')); ?>

            <button type="button" class="close" data-dismiss="alert">
                <span>&times;</span>
            </button>
        </div>
    <?php endif; ?>

    <?php if(session('error')): ?>
        <div class="alert alert-danger alert-dismissible fade show">
            <i class="fas fa-times-circle mr-2"></i>
            <strong>Error</strong><br>
            <?php echo e(session('error')); ?>

            <button type="button" class="close" data-dismiss="alert">
                <span>&times;</span>
            </button>
        </div>
    <?php endif; ?>


<h4 class="mb-3">📂 BFP Applications</h4>

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

<?php $__empty_1 = true; $__currentLoopData = $applications; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $a): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>

<tr>

<td><?php echo e($a->id); ?></td>

<td><?php echo e($a->name); ?></td>

<td><?php echo e(\Carbon\Carbon::parse($a->created_at)->format('Y-m-d H:i')); ?></td>

<td>
    <?php if($a->bfp_issued == 1): ?>

        <span class="badge bg-primary">
            ISSUED
        </span>

    <?php elseif($a->bfp_status == 'assessed'): ?>

        <span class="badge bg-info">
            ASSESSED
        </span>

    <?php elseif($a->bfp_status == 'verified'): ?>

        <span class="badge bg-success">
            VERIFIED
        </span>

    <?php elseif($a->bfp_status == 'pending'): ?>

        <span class="badge bg-warning text-dark">
            UNDER REVIEW
        </span>

    <?php else: ?>

        <span class="badge bg-secondary">
            <?php echo e(strtoupper($a->bfp_status ?? 'N/A')); ?>

        </span>

    <?php endif; ?>
</td>

<td>
<a href="<?php echo e(url('/bfp/applications?app_id='.$a->id)); ?>"
class="btn btn-sm btn-primary">
View
</a>
</td>

</tr>

<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>

<tr>
<td colspan="5" class="text-center text-muted">
No applications found
</td>
</tr>

<?php endif; ?>

</tbody>
</table>

</div>



<?php if(request('app_id')): ?>

<?php

$application = \App\Models\Application::find(request('app_id'));

$applicant = $application ? $application->user : null;

$files = \App\Models\Document::where('application_id', $application->id)
            ->where('department', 'bfp')
            ->get();

$assessment = \App\Models\Assessment::where('application_id', $application->id)
            ->where('department', 'bfp')
            ->first();

$assessmentAmount = $assessment->amount ?? null;
$verifiedOn = $assessment->verified_on ?? null;
$issued = $application->bfp_issued ?? 0;
$paid = $application->bfp_paid ?? 0;

$remarks = DB::table('remarks')
    ->where('application_id',$application->id)
    ->where('department','bfp')
    ->orderBy('created_at','desc')
    ->get();

?>


<?php  

$hasRemark = isset($remarks) && count($remarks) > 0;
$remarkTime = null;
$hasReupload = false;

if($hasRemark){

    $remarkTime = DB::table('remarks')
        ->where('application_id',$application->id)
        ->where('department','bfp')
        ->latest()
->value('created_at');

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

?>


<div class="container py-4">

<h2 class="mb-3">📂 BFP Submitted Requirements</h2>



<?php if($applicant): ?>

<div class="card mb-4 shadow-sm">
<div class="card-header bg-dark text-white">Applicant Information</div>
<div class="card-body">

<p><b>Name:</b> <?php echo e($applicant->name); ?></p>
<p><b>Contact:</b> <?php echo e($applicant->contact_number); ?></p>
<p><b>Address:</b> <?php echo e($applicant->address); ?></p>
<p><b>Gender:</b> <?php echo e($applicant->gender); ?></p>
<p><b>Occupation:</b> <?php echo e($applicant->occupation); ?></p>

</div>
</div>

<?php endif; ?>




<div class="card shadow-sm mb-4">
<div class="card-header bg-primary text-white">
<?php echo e($hasReupload ? 'Re-uploaded Documents' : 'Uploaded Documents'); ?>

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

<?php $__currentLoopData = $files; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $i => $f): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>

<tr>

<td><?php echo e($i+1); ?></td>

<td>
<b><?php echo e(optional($applicant)->name); ?></b><br>
<?php echo e($f->file_name); ?>

</td>

<td><?php echo e(\Carbon\Carbon::parse($f->created_at)->format('Y-m-d H:i')); ?></td>

<td>
<span class="badge bg-warning">Submitted</span>
</td>

<td>
    <button type="button"
        class="btn btn-sm btn-primary viewFileBtn"
        data-file="<?php echo e(asset('storage/bfp_docs/'.$f->file_name)); ?>">
        👁 View File
    </button>
</td>

</td>

</tr>

<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

</tbody>

</table>

</div>
</div>



<?php if($lockAssessment): ?>

<div class="alert alert-warning">
⛔ Applicant must re-upload corrected documents before continuing review.
</div>

<?php endif; ?>




<?php if(!$lockAssessment && !$assessmentAmount): ?>

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
<td>Architectural Plans</td>
<td><input type="checkbox" class="chk"></td>
</tr>

<tr>
<td>2</td>
<td>Structural Plans</td>
<td><input type="checkbox" class="chk"></td>
</tr>

<tr>
<td>3</td>
<td>Electrical Plans</td>
<td><input type="checkbox" class="chk"></td>
</tr>

<tr>
<td>4</td>
<td>Plumbing / Sanitary Plans</td>
<td><input type="checkbox" class="chk"></td>
</tr>

<tr>
<td>5</td>
<td>Mechanical Plans</td>
<td><input type="checkbox" class="chk"></td>
</tr>

<tr>
<td>6</td>
<td>Bill of Materials & Cost Estimates</td>
<td><input type="checkbox" class="chk"></td>
</tr>

<tr>
<td>7</td>
<td>Engineer Plans</td>
<td><input type="checkbox" class="chk"></td>
</tr>

<tr>
<td>8</td>
<td>Construction Schedule</td>
<td><input type="checkbox" class="chk"></td>
</tr>

<tr>
<td>9</td>
<td>Electronics Plan</td>
<td><input type="checkbox" class="chk"></td>
</tr>

<tr>
<td>10</td>
<td>Fire Safety Clearance</td>
<td><input type="checkbox" class="chk"></td>
</tr>

<tr>
<td>11</td>
<td>Fire Protection Plan</td>
<td><input type="checkbox" class="chk"></td>
</tr>

<tr>
<td>12</td>
<td>Fire  Alarm Layout</td>
<td><input type="checkbox" class="chk"></td>
</tr>


</table>

</div>
</div>

<?php endif; ?>



<?php if(!$lockAssessment && !$assessmentAmount): ?>


<div class="card shadow-sm mb-4">

<div class="card-header bg-warning">
📝 Send Remark to Applicant
</div>

<div class="card-body">

<form method="POST" action="<?php echo e(route('bfp.saveRemark')); ?>">

<?php echo csrf_field(); ?>
<input type="hidden" name="application_id" value="<?php echo e($application->id); ?>">

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

<?php endif; ?>




<?php if(isset($remarks) && count($remarks)): ?>

<div class="card shadow-sm mt-3">

<div class="card-header bg-dark text-white">
📜 Remarks History
</div>

<div class="card-body">

<?php $__currentLoopData = $remarks; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $r): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>

<div class="border p-2 mb-2">

<?php echo e($r->remarks); ?> <br>
<small class="text-muted">
<?php echo e(\Carbon\Carbon::parse($r->created_at)->format('F d, Y h:i A')); ?>

</small>
</div>

<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

</div>
</div>

<?php endif; ?>




<?php if($assessmentAmount): ?>

<div class="card shadow-sm mb-3">

<div class="card-header bg-success text-white">
Assessment Saved
</div>

<div class="card-body">

<b>Assessment Amount:</b>

<span class="badge bg-success">
₱<?php echo e(number_format($assessmentAmount,2)); ?>

</span>

<br><br>

<a href="<?php echo e(url('/bfp/receipt/'.$application->id)); ?>"
class="btn btn-primary">

🧾 View Receipt

</a>

</div>

</div>

<?php endif; ?>




<?php if($verifiedOn): ?>

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
<?php echo e(\Carbon\Carbon::parse($verifiedOn)->format('F d, Y h:i A')); ?>

</p>

<div class="alert alert-success mb-0">
Applicant documents have been officially verified by BFP.
</div>

</div>

</div>

<?php endif; ?>


<?php if($verifiedOn): ?>

<div class="card shadow-sm mb-4">

<div class="card-header bg-primary text-white">
Issuance
</div>

<div class="card-body">

<?php if(!$issued): ?>

<form method="POST" action="<?php echo e(route('bfp.issue')); ?>">
<?php echo csrf_field(); ?>
<input type="hidden" name="application_id" value="<?php echo e($application->id); ?>">

<button class="btn btn-success">
✔ YES ISSUE BFP CLEARANCE
</button>

</form>

<?php else: ?>

<div class="alert alert-success">
✔ BFP Clearance Issued Successfully
</div>

<?php if($paid == 0): ?>

<form method="POST" action="<?php echo e(route('bfp.mark.paid')); ?>">
<?php echo csrf_field(); ?>
<input type="hidden" name="application_id" value="<?php echo e($application->id); ?>">

<button class="btn btn-warning">
💳 PAY NOW
</button>

</form>

<?php else: ?>

<div class="alert alert-success">
✔ Payment Completed Successfully
</div>

<?php endif; ?>

<?php endif; ?>

</div>
</div>

<?php endif; ?>



<?php if(!$assessmentAmount && !$lockAssessment): ?>

<div class="card shadow-sm mb-4" id="assessmentCard" style="display:none;">

<div class="card-header bg-success text-white">
Assessment
</div>

<div class="card-body">

<form method="POST" action="<?php echo e(route('bfp.saveAssessment')); ?>">
<?php echo csrf_field(); ?>

<input type="hidden" name="application_id" value="<?php echo e($application->id); ?>">

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

<?php elseif($assessmentAmount && !$verifiedOn): ?>

<div class="card shadow-sm mb-4">
<div class="card-body">

<form method="POST" action="<?php echo e(route('bfp.verify',$application->id)); ?>">
<?php echo csrf_field(); ?>

<button type="submit" class="btn btn-success w-100">
✔ VERIFY DOCUMENTS
</button>

</form>

</div>
</div>

<?php endif; ?>

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

<?php endif; ?>

<?php $__env->stopSection(); ?>
<?php echo $__env->make('adminlte::page', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\laragon\www\bps-laravel\resources\views/bfp/applications.blade.php ENDPATH**/ ?>