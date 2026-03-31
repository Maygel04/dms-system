

<?php $__env->startSection('content'); ?>
<?php $__env->startSection('css'); ?>
<link rel="stylesheet" href="<?php echo e(asset('css/custom-adminlte.css')); ?>">
<?php $__env->stopSection(); ?>

<div class="container" id="printArea">

    <h3 style="text-align:center;">MPDO Financial Report</h3>
    <p style="text-align:center;"><?php echo e($title); ?></p>

    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Applicant</th>
                <th>Amount</th>
                <th>Date</th>
            </tr>
        </thead>
        <tbody>
            <?php $__currentLoopData = $records; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $r): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <tr>
                <td><?php echo e($r->name); ?></td>
                <td>₱<?php echo e(number_format($r->amount,2)); ?></td>
                <td><?php echo e($r->created_at); ?></td>
            </tr>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </tbody>
    </table>

    <h4>Total: ₱<?php echo e(number_format($total,2)); ?></h4>

    <br><br>

    <div style="display:flex;justify-content:space-between;">
        <div>____________________<br>Prepared by</div>
        <div>____________________<br>Approved by</div>
    </div>

</div>

<div style="margin-top:15px;">
    <a href="<?php echo e(url('/mpdo/payments')); ?>" class="btn btn-secondary">
        ⬅ Back to Payments
    </a>

    <button onclick="window.print()" class="btn btn-primary">
        🖨 Print / Save PDF
    </button>
</div>



<style>
@media print {
    body * {
        visibility: hidden;
    }

    #printArea, #printArea * {
        visibility: visible;
    }

    #printArea {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
    }

    button {
        display: none;
    }
}
</style>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('adminlte::page', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\laragon\www\bps-laravel\resources\views/mpdo/report.blade.php ENDPATH**/ ?>