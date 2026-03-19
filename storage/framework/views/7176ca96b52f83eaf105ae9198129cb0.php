

<?php $__env->startSection('css'); ?>
<link rel="stylesheet" href="<?php echo e(asset('css/custom-adminlte.css')); ?>">
<?php $__env->stopSection(); ?>


<?php $__env->startSection('content'); ?>

<div class="max-w-7xl mx-auto px-6 py-6">

<div class="flex justify-between items-center mb-5">

<h2 class="text-2xl font-semibold flex items-center gap-2">
📂 Document Repository
</h2>

<span class="text-sm text-gray-500">
Municipal Building Permit System
</span>

</div>

<!-- SEARCH BAR -->

<div class="flex gap-3 mb-5">

<input type="text"
id="searchName"
placeholder="Search document name..."
class="border rounded-lg px-4 py-2 w-full shadow-sm focus:ring focus:ring-blue-200">

<select id="searchType"
class="border rounded-lg px-3 py-2 shadow-sm">

<option value="">All File Types</option>
<option value=".pdf">PDF</option>
<option value=".dwg">DWG</option>
<option value=".dxf">DXF</option>

</select>

</div>

<!-- DOCUMENT TABLE -->

<div class="bg-white shadow-lg rounded-lg overflow-hidden">

<table class="table table-bordered table-striped align-middle mb-0" id="docTable">

<thead class="table-dark">
<tr>
<th width="120">Department</th>
<th>File Name</th>
<th width="180">Uploaded</th>
<th width="180">Action</th>
</tr>
</thead>

<tbody>


<?php
    $mpdoGrouped = collect($mpdoDocs)->groupBy(function ($doc) {
        $name = $doc->file_name ?? basename($doc->file_path);
        return preg_replace('/_\d+(\.[a-zA-Z0-9]+)$/', '$1', $name);
    });
?>

<?php $__currentLoopData = $mpdoGrouped; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $group): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
    <?php $__currentLoopData = $group->sortBy('created_at')->values(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $d): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <tr>
            <td><span class="badge bg-primary">MPDO</span></td>

            <td class="file-name">
                <div class="fw-semibold">
                    <?php echo e($d->file_name ?? basename($d->file_path)); ?>

                </div>

                <div class="text-muted small">Document File</div>

                <?php if($index == 0): ?>
                    <span class="badge bg-info mt-1">FIRST UPLOAD</span>
                <?php else: ?>
                    <span class="badge bg-warning text-dark mt-1">RE-UPLOAD <?php echo e($index); ?></span>
                <?php endif; ?>
            </td>

            <td>
                <?php echo e(\Carbon\Carbon::parse($d->created_at)->format('Y-m-d H:i')); ?>

            </td>

            <td>
                <button class="btn btn-sm btn-success openFile"
                    data-file="<?php echo e(asset('storage/'.$d->file_path)); ?>">
                    View
                </button>
            </td>
        </tr>
    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>



<?php
    $meoGrouped = collect($meoDocs)->groupBy(function ($doc) {
        $name = $doc->file_name ?? basename($doc->file_path);
        return preg_replace('/_\d+(\.[a-zA-Z0-9]+)$/', '$1', $name);
    });
?>

<?php $__currentLoopData = $meoGrouped; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $group): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
    <?php $__currentLoopData = $group->sortBy('created_at')->values(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $d): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <tr>
            <td><span class="badge bg-success">MEO</span></td>

            <td class="file-name">
                <div class="fw-semibold">
                    <?php echo e($d->file_name ?? basename($d->file_path)); ?>

                </div>

                <div class="text-muted small">Document File</div>

                <?php if($index == 0): ?>
                    <span class="badge bg-info mt-1">FIRST UPLOAD</span>
                <?php else: ?>
                    <span class="badge bg-warning text-dark mt-1">RE-UPLOAD <?php echo e($index); ?></span>
                <?php endif; ?>
            </td>

            <td>
                <?php echo e(\Carbon\Carbon::parse($d->created_at)->format('Y-m-d H:i')); ?>

            </td>

            <td>
                <button class="btn btn-sm btn-success openFile"
                    data-file="<?php echo e(asset('storage/'.$d->file_path)); ?>">
                    View
                </button>
            </td>
        </tr>
    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>



<?php
    $bfpGrouped = collect($bfpDocs)->groupBy(function ($doc) {
        $name = $doc->file_name ?? basename($doc->file_path);
        return preg_replace('/_\d+(\.[a-zA-Z0-9]+)$/', '$1', $name);
    });
?>

<?php $__currentLoopData = $bfpGrouped; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $group): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
    <?php $__currentLoopData = $group->sortBy('created_at')->values(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $d): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <tr>
            <td><span class="badge bg-danger">BFP</span></td>

            <td class="file-name">
                <div class="fw-semibold">
                    <?php echo e($d->file_name ?? basename($d->file_path)); ?>

                </div>

                <div class="text-muted small">Document File</div>

                <?php if($index == 0): ?>
                    <span class="badge bg-info mt-1">FIRST UPLOAD</span>
                <?php else: ?>
                    <span class="badge bg-warning text-dark mt-1">RE-UPLOAD <?php echo e($index); ?></span>
                <?php endif; ?>
            </td>

            <td>
                <?php echo e(\Carbon\Carbon::parse($d->created_at)->format('Y-m-d H:i')); ?>

            </td>

            <td>
                <button class="btn btn-sm btn-success openFile"
                    data-file="<?php echo e(asset('storage/'.$d->file_path)); ?>">
                    View
                </button>
            </td>
        </tr>
    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

</tbody>

</table>

</div>

</div>

<!-- FILE MODAL -->

<div id="fileModal"
style="display:none; position:fixed; top:0; left:0; width:100%; height:100%; background:black; z-index:9999;">

<button id="closeModal"
style="position:absolute; top:20px; right:30px; font-size:40px; color:white; background:none; border:none;">
✖
</button>

<iframe id="fileFrame"
style="width:100%; height:100%; border:none; background:white;">
</iframe>

</div>

<script>

const nameInput = document.getElementById('searchName');
const typeSelect = document.getElementById('searchType');

[nameInput, typeSelect].forEach(el=>{
el.addEventListener('input', filterTable);
});

function filterTable(){
const name = nameInput.value.toLowerCase();
const type = typeSelect.value.toLowerCase();

document.querySelectorAll("#docTable tbody tr").forEach(row=>{
const file = row.querySelector(".file-name")?.innerText.toLowerCase() || "";
const matchName = file.includes(name);
const matchType = type === "" || file.endsWith(type);
row.style.display = (matchName && matchType) ? "" : "none";
});
}

const modal = document.getElementById('fileModal');
const frame = document.getElementById('fileFrame');
const closeModal = document.getElementById('closeModal');

document.querySelectorAll('.openFile').forEach(btn => {
btn.addEventListener('click', function(){
frame.src = this.dataset.file;
modal.style.display = "block";
});
});

closeModal.addEventListener('click', function(){
modal.style.display = "none";
frame.src = "";
});

</script>

<?php $__env->stopSection(); ?>
<?php echo $__env->make('adminlte::page', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\laragon\www\bps-laravel\resources\views/applicant/view_documents.blade.php ENDPATH**/ ?>