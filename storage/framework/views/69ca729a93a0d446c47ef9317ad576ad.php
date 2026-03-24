

<?php $__env->startSection('css'); ?>
<link rel="stylesheet" href="<?php echo e(asset('css/custom-adminlte.css')); ?>">
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>

<div class="container-fluid mt-4">

<div class="card shadow-sm">

<div class="card-header bg-primary text-white">
<h5 class="mb-0">MPDO Applications (Read Only)</h5>
</div>

<div class="card-body">

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

<td><?php echo e($app->name); ?></td>

<td>
<?php if($app->mpdo_status == 'verified'): ?>
<span class="badge bg-success">Verified</span>

<?php elseif($app->mpdo_status == 'pending'): ?>
<span class="badge bg-warning">Pending</span>

<?php else: ?>
<span class="badge bg-secondary"><?php echo e($app->mpdo_status); ?></span>
<?php endif; ?>
</td>

<td><?php echo e($app->created_at); ?></td>

</tr>

<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>

<tr>
<td colspan="4" class="text-center">
No MPDO applications found
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
        <i class="fas fa-arrow-left"></i> Back
    </a>
</div>

<?php $__env->stopSection(); ?>
<?php echo $__env->make('adminlte::page', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\laragon\www\bps-laravel\resources\views/admin/department_mpdo.blade.php ENDPATH**/ ?>