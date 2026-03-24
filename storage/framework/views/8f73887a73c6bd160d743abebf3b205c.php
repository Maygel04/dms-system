



<?php $__env->startSection('css'); ?>
<link rel="stylesheet" href="<?php echo e(asset('css/custom-adminlte.css')); ?>">
<?php $__env->stopSection(); ?>
<?php $__env->startSection('content'); ?>

<div class="container">

    <div class="card p-5 shadow" id="printArea">

        <div class="text-center mb-4">
            <h3>Municipal Building Permit System</h3>
            <h5>Daily Financial Report</h5>
            <p>Date: <?php echo e($today); ?></p>
        </div>

        <hr>

        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Department</th>
                    <th>Revenue</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>MPDO</td>
                    <td>₱<?php echo e(number_format($mpdo,2)); ?></td>
                </tr>
                <tr>
                    <td>MEO</td>
                    <td>₱<?php echo e(number_format($meo,2)); ?></td>
                </tr>
                <tr>
                    <td>BFP</td>
                    <td>₱<?php echo e(number_format($bfp,2)); ?></td>
                </tr>
            </tbody>
        </table>

        <h4 class="mt-4">
            Total Revenue: ₱<?php echo e(number_format($total,2)); ?>

        </h4>

        <br><br>

        <div class="row mt-5">
            <div class="col text-center">
                ___________________________<br>
                Prepared by
            </div>

            <div class="col text-center">
                ___________________________<br>
                Approved by
            </div>
        </div>

    </div>

    <div class="mt-3">
        <button onclick="window.print()" class="btn btn-primary">
            🖨 Print / Save as PDF
        </button>

        <a href="<?php echo e(url()->previous()); ?>" class="btn btn-secondary">
            ⬅ Back
        </a>
    </div>

</div>

<?php $__env->stopSection(); ?>
<?php echo $__env->make('adminlte::page', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\laragon\www\bps-laravel\resources\views/admin/report.blade.php ENDPATH**/ ?>