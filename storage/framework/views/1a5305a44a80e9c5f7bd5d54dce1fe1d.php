


<?php $__env->startSection('css'); ?>
<link rel="stylesheet" href="<?php echo e(asset('css/custom-adminlte.css')); ?>">
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>

<div class="container-fluid mt-5">

<h4 class="mb-4">BFP Dashboard</h4>

<!-- SEARCH BAR -->
<div class="card mb-4">
<div class="card-body">

<form method="GET" action="/bfp/applications">
<div class="input-group">
<input type="text" name="search" class="form-control" placeholder="Search applicant name or permit number...">
<button class="btn btn-primary">Search</button>
</div>
</form>

</div>
</div>


<div class="row">

<!-- UNDER REVIEW -->
<div class="col-md-3">
<div class="card bg-warning text-white">
<div class="card-body">

<h5>Under Review</h5>
<h2><?php echo e($underReview); ?></h2>

</div>
</div>
</div>


<!-- VERIFIED -->
<div class="col-md-3">
<div class="card bg-success text-white">
<div class="card-body">

<h5>Verified</h5>
<h2><?php echo e($verified); ?></h2>

</div>
</div>
</div>


<!-- CLEARANCE ISSUED -->
<div class="col-md-3">
<div class="card bg-primary text-white">
<div class="card-body">

<h5>Verified</h5>
<h2><?php echo e($verified); ?></h2>

</div>
</div>
</div>


<!-- REVENUE -->
<div class="col-md-3">
<div class="card bg-info text-white">
<div class="card-body">

<h5>Total Revenue</h5>
<h2>₱<?php echo e(number_format($revenue,2)); ?></h2>

</div>
</div>
</div>

</div>

</div>

<?php $__env->stopSection(); ?>
<?php echo $__env->make('adminlte::page', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\laragon\www\bps-laravel\resources\views/bfp/dashboard.blade.php ENDPATH**/ ?>