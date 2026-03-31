

<?php $__env->startSection('css'); ?>
<link rel="stylesheet" href="<?php echo e(asset('css/custom-adminlte.css')); ?>">
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>

<?php
    $filter = request('filter', 'today');
?>

<div class="container-fluid">

    <div class="card shadow-sm border-0 p-4">

        <h4 class="mb-3">💰 Payments Overview</h4>

        
        <div class="mb-4">

            <a href="?filter=today"
               class="btn btn-sm <?php echo e($filter == 'today' ? 'btn-primary' : 'btn-outline-primary'); ?>">
                Today
            </a>

            <a href="?filter=week"
               class="btn btn-sm <?php echo e($filter == 'week' ? 'btn-success' : 'btn-outline-success'); ?>">
                Weekly
            </a>

            <a href="?filter=month"
               class="btn btn-sm <?php echo e($filter == 'month' ? 'btn-warning' : 'btn-outline-warning'); ?>">
                Monthly
            </a>

            <a href="?filter=year"
               class="btn btn-sm <?php echo e($filter == 'year' ? 'btn-dark' : 'btn-outline-dark'); ?>">
               Yearly
            </a>

        </div>

        
        <div class="row g-4">

            
            <div class="col-md-4">
                <div class="card border-0 shadow-sm bg-light p-3"
                     style="cursor:pointer"
                     data-toggle="modal"
                     data-target="#mpdoModal">

                    <h6 class="text-muted">MPDO Revenue</h6>
                    <h3 class="text-primary">
                        ₱<?php echo e(number_format($mpdo ?? 0, 2)); ?>

                    </h3>

                </div>
            </div>

            
            <div class="col-md-4">
                <div class="card border-0 shadow-sm bg-light p-3"
                     style="cursor:pointer"
                     data-toggle="modal"
                     data-target="#meoModal">

                    <h6 class="text-muted">MEO Revenue</h6>
                    <h3 class="text-success">
                        ₱<?php echo e(number_format($meo ?? 0, 2)); ?>

                    </h3>

                </div>
            </div>

            
            <div class="col-md-4">
                <div class="card border-0 shadow-sm bg-light p-3"
                     style="cursor:pointer"
                     data-toggle="modal"
                     data-target="#bfpModal">

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
                <?php if($filter == 'today'): ?>
                    Today
                <?php elseif($filter == 'week'): ?>
                    This Week
                <?php elseif($filter == 'month'): ?>
                    This Month
                <?php elseif($filter == 'year'): ?>
                    This Year
                <?php endif; ?>
                :

                <span class="fw-bold" style="color: #fd7e14;">
                    ₱<?php echo e(number_format($total ?? 0, 2)); ?>

                </span>

            </h3>

        </div>

    </div>

<a href="<?php echo e(url('/admin/report/generate?filter=' . ($filter ?? 'today'))); ?>" 
   class="btn btn-dark mt-3">
    📄 Generate Report
</a>

</div>


<div class="modal fade" id="mpdoModal" tabindex="-1">
<div class="modal-dialog modal-lg">
<div class="modal-content">

<div class="modal-header bg-primary text-white">
<h5 class="modal-title">MPDO Payments</h5>
<button type="button" class="close" data-dismiss="modal">
    <span>&times;</span>
</button>
</div>

<div class="modal-body">

<table class="table table-bordered">
<thead>
<tr>
<th>Applicant</th>
<th>Amount</th>
<th>Date</th>
</tr>
</thead>

<tbody>
<?php $__empty_1 = true; $__currentLoopData = $mpdoList; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
<tr>
<td><?php echo e($item->name); ?></td>
<td>₱<?php echo e(number_format($item->amount,2)); ?></td>
<td><?php echo e(\Carbon\Carbon::parse($item->created_at)->format('M d, Y h:i A')); ?></td>
</tr>
<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
<tr>
<td colspan="3" class="text-center">No payments</td>
</tr>
<?php endif; ?>
</tbody>

</table>

</div>
</div>
</div>
</div>


<div class="modal fade" id="meoModal" tabindex="-1">
<div class="modal-dialog modal-lg">
<div class="modal-content">

<div class="modal-header bg-success text-white">
<h5 class="modal-title">MEO Payments</h5>
<button type="button" class="close" data-dismiss="modal">
    <span>&times;</span>
</button>
</div>

<div class="modal-body">

<table class="table table-bordered">
<thead>
<tr>
<th>Applicant</th>
<th>Amount</th>
<th>Date</th>
</tr>
</thead>

<tbody>
<?php $__empty_1 = true; $__currentLoopData = $meoList; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
<tr>
<td><?php echo e($item->name); ?></td>
<td>₱<?php echo e(number_format($item->amount,2)); ?></td>
<td><?php echo e(\Carbon\Carbon::parse($item->created_at)->format('M d, Y h:i A')); ?></td>
</tr>
<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
<tr>
<td colspan="3" class="text-center">No payments</td>
</tr>
<?php endif; ?>
</tbody>

</table>

</div>
</div>
</div>
</div>


<div class="modal fade" id="bfpModal" tabindex="-1">
<div class="modal-dialog modal-lg">
<div class="modal-content">

<div class="modal-header bg-danger text-white">
<h5 class="modal-title">BFP Payments</h5>
<button type="button" class="close" data-dismiss="modal">
    <span>&times;</span>
</button>
</div>

<div class="modal-body">

<table class="table table-bordered">
<thead>
<tr>
<th>Applicant</th>
<th>Amount</th>
<th>Date</th>
</tr>
</thead>

<tbody>
<?php $__empty_1 = true; $__currentLoopData = $bfpList; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
<tr>
<td><?php echo e($item->name); ?></td>
<td>₱<?php echo e(number_format($item->amount,2)); ?></td>
<td><?php echo e(\Carbon\Carbon::parse($item->created_at)->format('M d, Y h:i A')); ?></td>
</tr>
<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
<tr>
<td colspan="3" class="text-center">No payments</td>
</tr>
<?php endif; ?>
</tbody>

</table>

</div>
</div>
</div>
</div>

<?php $__env->stopSection(); ?>
<?php echo $__env->make('adminlte::page', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\laragon\www\bps-laravel\resources\views/admin/payments.blade.php ENDPATH**/ ?>