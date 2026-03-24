
<?php $__env->startSection('css'); ?>
<link rel="stylesheet" href="<?php echo e(asset('css/custom-adminlte.css')); ?>">
<?php $__env->stopSection(); ?>
<?php $__env->startSection('content'); ?>

<div class="container py-4">

<div class="card shadow">

<div class="card-header bg-dark text-white">
Official Receipt
</div>

<div class="card-body">

<h4>Municipal Permit System</h4>

<hr>

<p><b>Applicant Name:</b> <?php echo e($applicant->name); ?></p>

<p><b>Amount Paid:</b> ₱<?php echo e(number_format($assessmentAmount ?? 0,2)); ?></p>

<p><b>Verified On:</b>
<?php echo e($verifiedOn ? \Carbon\Carbon::parse($verifiedOn)->format('F d Y h:i A') : '-'); ?></p>

<hr>

<button onclick="window.print()" class="btn btn-success">
🖨 Print Receipt
</button>


</div>
 <a href="<?php echo e(url()->previous()); ?>" class="btn btn-secondary">
        ← Back
    </a>

</div>

</div>

<?php $__env->stopSection(); ?>
<?php echo $__env->make('adminlte::page', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\laragon\www\bps-laravel\resources\views/meo/receipt.blade.php ENDPATH**/ ?>