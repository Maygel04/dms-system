

<?php $__env->startSection('css'); ?>
<link rel="stylesheet" href="<?php echo e(asset('css/custom-adminlte.css')); ?>">
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>

<div class="container-fluid mt-4">

<div class="card shadow-sm">

<div class="card-header bg-primary text-white">
<h5 class="mb-0">MEO Applications (Read Only)</h5>
</div>

<div class="card-body">


<form method="GET" action="" class="mb-3">
    <div class="row">

        
        <div class="col-md-4">
            <input type="text" name="search" class="form-control"
                   placeholder="🔍 Search applicant..."
                   value="<?php echo e(request('search')); ?>">
        </div>

        
        <div class="col-md-3">
            <select name="status" class="form-control">
                <option value="">All Status</option>
<option value="pending" <?php echo e(request('status')=='pending'?'selected':''); ?>>Pending</option>
<option value="verified" <?php echo e(request('status')=='verified'?'selected':''); ?>>Verified</option>
<option value="endorsed" <?php echo e(request('status')=='endorsed'?'selected':''); ?>>Endorsed</option>

            </select>
        </div>

        
        <div class="col-md-5">
            <button type="submit" class="btn btn-primary">🔍 Filter</button>
            <a href="<?php echo e(url()->current()); ?>" class="btn btn-secondary">Reset</a>
        </div>

    </div>
</form>

<table class="table table-bordered table-hover">

<thead class="table-light">
<tr>
<th>Application ID</th>
<th>Applicant Name</th>
<th>MPDO Status</th>
<th>Date Submitted</th>
</tr>
</thead>

<tbody>

<?php $__empty_1 = true; $__currentLoopData = $applications; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $app): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>

<tr>
<td><?php echo e($app->id); ?></td>

<td>
    
    <a href="<?php echo e(route('admin.applicant_documents', ['id' => $app->id])); ?>"
       class="text-primary fw-bold">
        <?php echo e($app->name ?? $app->applicant_name); ?>

    </a>
</td>

<td>
<?php if(strtolower($app->meo_status) == 'verified'): ?>
<span class="badge bg-success">Verified</span>

<?php elseif(strtolower($app->meo_status) == 'pending'): ?>
<span class="badge bg-warning text-dark">Pending</span>

<?php else: ?>
<span class="badge bg-secondary"><?php echo e($app->meo_status); ?></span>
<?php endif; ?>
</td>

<td><?php echo e($app->created_at); ?></td>

</tr>

<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>

<tr>
<td colspan="4" class="text-center">
No MEO applications found
</td>
</tr>

<?php endif; ?>

</tbody>

</table>

</div>
</div>

</div>

<div class="mb-3">
    
    <a href="<?php echo e(route('admin.departments')); ?>" class="btn btn-secondary">
        ← Back
    </a>
</div>

<?php $__env->stopSection(); ?>
<?php echo $__env->make('adminlte::page', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\laragon\www\bps-laravel\resources\views/admin/department_meo.blade.php ENDPATH**/ ?>