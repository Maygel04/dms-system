

<?php $__env->startSection('content'); ?>

<div class="container" id="printArea">

<div class="card p-4 shadow" id="printArea">

    <div class="text-center">
        <h4>Municipal Building Permit System</h4>
        <h4 style="text-align:center; margin-bottom:15px;">
    OFFICIAL RECEIPT
</h4>
    </div>

    <hr>

    <p><strong>Applicant:</strong> <?php echo e($application->name); ?></p>
    <p><strong>Application ID:</strong> <?php echo e($application->id); ?></p>

    <table class="table table-bordered mt-3">
        <thead>
            <tr>
                <th>Department</th>
                <th>Amount</th>
                <th>Date</th>
            </tr>
        </thead>
        <tbody>
            <?php $__currentLoopData = $payments; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $p): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <tr>
                <td><?php echo e(strtoupper($p->department)); ?></td>
                <td>₱<?php echo e(number_format($p->amount,2)); ?></td>
                <td><?php echo e(\Carbon\Carbon::parse($p->created_at)->format('M d, Y h:i A')); ?></td>
            </tr>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </tbody>
    </table>

    <h4 class="mt-3">
        Total: ₱<?php echo e(number_format($total,2)); ?>

    </h4>

    <br><br>

    <div class="row mt-5">
        <div class="col text-center">
            _______________________<br>
            Applicant Signature
        </div>

        <div class="col text-center">
            _______________________<br>
            Authorized Officer
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
        left: 0;
        top: 0;
        width: 100%;
    }

    button, a {
        display: none !important;
    }
}
</style>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('adminlte::page', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\laragon\www\bps-laravel\resources\views/applicant/receipt.blade.php ENDPATH**/ ?>