

<?php $__env->startSection('content'); ?>
<?php $__env->startSection('css'); ?>
<link rel="stylesheet" href="<?php echo e(asset('css/custom-adminlte.css')); ?>">
<?php $__env->stopSection(); ?>

<div class="container-fluid">

    
    <div class="row">
        <div class="col-12">

            
            <div id="printArea" style="background:white; padding:30px; border:1px solid #ccc;">

                <div style="max-width:900px; margin:auto;">

                    <div style="text-align:center;">
                        <h2>Municipal Engineering Office</h2>
                        <p>Financial Report</p>
                        <small><?php echo e($title); ?></small>
                        <hr>
                    </div>

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
                                <td><?php echo e(\Carbon\Carbon::parse($r->created_at)->format('M d, Y h:i A')); ?></td>
                            </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </tbody>
                    </table>

                    <h4 class="text-right">Total: ₱<?php echo e(number_format($total,2)); ?></h4>

                    <div style="margin-top:50px; text-align:right;">
                        ____________________<br>
                        Cashier
                    </div>

                </div>

            </div>

        </div>
    </div>

    
    <div class="text-center mt-3">
        <a href="<?php echo e(url('/meo/payments')); ?>" class="btn btn-secondary">Back</a>
        <button onclick="window.print()" class="btn btn-success">Print</button>
    </div>

</div>

<?php $__env->stopSection(); ?>
<?php echo $__env->make('adminlte::page', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\laragon\www\bps-laravel\resources\views/meo/report.blade.php ENDPATH**/ ?>