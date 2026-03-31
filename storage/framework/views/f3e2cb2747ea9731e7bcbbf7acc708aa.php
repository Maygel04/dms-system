
<?php $__env->startSection('css'); ?>
<link rel="stylesheet" href="<?php echo e(asset('css/custom-adminlte.css')); ?>">
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>

<div class="container-fluid">

<h3 class="mb-4">💰 MPDO Payments / Assessment</h3>


<div class="card shadow-sm border-0 mb-4 text-center p-3">
<h6>Total Assessed Amount</h6>
<h2 class="text-success">₱<?php echo e(number_format($total,2)); ?></h2>
</div>


<div class="card shadow-sm">
<div class="card-body">

<h5 class="mb-3">Assessed Applications</h5>

<table class="table table-bordered table-hover">

<thead class="table-light">
<tr>
<th>#</th>
<th>Applicant</th>
<th>Amount</th>
<th>Verified On</th>
</tr>
</thead>

<tbody>

<?php $__empty_1 = true; $__currentLoopData = $payments; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $i => $p): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
<tr>
<td><?php echo e($i+1); ?></td>
<td><?php echo e($p->name); ?></td>
<td class="text-success">₱<?php echo e(number_format($p->amount,2)); ?></td>
<td><?php echo e($p->verified_on ?? '-'); ?></td>
</tr>
<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
<tr>
<td colspan="4" class="text-center text-muted">No assessments yet.</td>
</tr>
<?php endif; ?>

</tbody>
</table>

</div>
</div>

</div>

<div style="margin-bottom:15px;">
    <a href="<?php echo e(url('/mpdo/report?filter=today')); ?>" class="btn btn-primary">Today</a>
    <a href="<?php echo e(url('/mpdo/report?filter=week')); ?>" class="btn btn-success">Weekly</a>
    <a href="<?php echo e(url('/mpdo/report?filter=month')); ?>" class="btn btn-warning">Monthly</a>
    <a href="<?php echo e(url('/mpdo/report?filter=year')); ?>" class="btn btn-secondary">Yearly</a>
</div>

<?php $__env->stopSection(); ?>
<?php echo $__env->make('adminlte::page', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\laragon\www\bps-laravel\resources\views/mpdo/payments.blade.php ENDPATH**/ ?>