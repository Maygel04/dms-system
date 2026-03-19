

<?php $__env->startSection('css'); ?>
<link rel="stylesheet" href="<?php echo e(asset('css/custom-adminlte.css')); ?>">
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>

<div class="container-fluid">

    <div class="card shadow-sm border-0 p-4">

        <h4 class="mb-3">
            💰 Payments Overview
        </h4>

        
        <div class="mb-4">

            <a href="?filter=today"
               class="btn btn-sm <?php echo e(($filter ?? 'today') == 'today' ? 'btn-primary' : 'btn-outline-primary'); ?>">
                Today
            </a>

            <a href="?filter=week"
               class="btn btn-sm <?php echo e(($filter ?? '') == 'week' ? 'btn-success' : 'btn-outline-success'); ?>">
                Weekly
            </a>

            <a href="?filter=month"
               class="btn btn-sm <?php echo e(($filter ?? '') == 'month' ? 'btn-warning' : 'btn-outline-warning'); ?>">
                Monthly
            </a>

        </div>

        
        <div class="row g-4">

            <div class="col-md-4">
                <div class="card border-0 shadow-sm bg-light p-3">
                    <h6 class="text-muted">MPDO Revenue</h6>
                    <h3 class="text-primary">
                        ₱<?php echo e(number_format($mpdo ?? 0, 2)); ?>

                    </h3>
                </div>
            </div>

            <div class="col-md-4">
                <div class="card border-0 shadow-sm bg-light p-3">
                    <h6 class="text-muted">MEO Revenue</h6>
                    <h3 class="text-success">
                        ₱<?php echo e(number_format($meo ?? 0, 2)); ?>

                    </h3>
                </div>
            </div>

            <div class="col-md-4">
                <div class="card border-0 shadow-sm bg-light p-3">
                    <h6 class="text-muted">BFP Revenue</h6>
                    <h3 class="text-danger">
                        ₱<?php echo e(number_format($bfp ?? 0, 2)); ?>

                    </h3>
                </div>
            </div>

        </div>

        
        <hr class="my-4">

        <div class="p-3 rounded bg-light border-start border-4"
             style="border-color: #fd7e14 !important;">

            <h3 class="mb-0 text-dark">

                Total Revenue
                <?php if(($filter ?? 'today') == 'today'): ?>
                    Today
                <?php elseif(($filter ?? '') == 'week'): ?>
                    This Week
                <?php else: ?>
                    This Month
                <?php endif; ?>
                :

                <span class="fw-bold" style="color: #fd7e14;">
                    ₱<?php echo e(number_format($total ?? 0, 2)); ?>

                </span>

            </h3>

        </div>

    </div>
   <a href="/admin/report/generate" class="btn btn-dark">
    📄 Generate Report
</a>
</div>

<?php $__env->stopSection(); ?>
<?php echo $__env->make('adminlte::page', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\laragon\www\bps-laravel\resources\views/admin/payments.blade.php ENDPATH**/ ?>