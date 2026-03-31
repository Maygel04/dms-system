

<?php $__env->startSection('css'); ?>
<link rel="stylesheet" href="<?php echo e(asset('css/custom-adminlte.css')); ?>">
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>

<div class="container py-4">

    
    <div id="printArea" style="background:white; padding:40px; max-width:700px; margin:auto; border:1px solid #ccc;">

        
        <div style="text-align:center;">
            <h2 style="margin:0;">Municipal Planning & Development Office  </h2>
            <p style="margin:0;">Building Permit System</p>
            <h3 style="margin-top:10px;">Official Receipt</h3>
            <hr>
        </div>

        
        <table width="100%" style="margin-top:20px;">
            <tr>
                <td><b>Applicant Name:</b></td>
                <td><?php echo e($applicant->name ?? 'N/A'); ?></td>
            </tr>
            <tr>
                <td><b>Amount Paid:</b></td>
                <td>₱<?php echo e(number_format($assessmentAmount ?? 0,2)); ?></td>
            </tr>
            <tr>
                <td><b>Date Paid:</b></td>
                <td>
                    <?php echo e($verifiedOn 
                        ? \Carbon\Carbon::parse($verifiedOn)->format('F d, Y h:i A') 
                        : '-'); ?>

                </td>
            </tr>
        </table>

        <hr style="margin-top:25px;">

        
        <div style="margin-top:60px; text-align:right;">
            ___________________________<br>
            <b>Cashier / Collecting Officer</b>
        </div>

    </div>

    
    <div style="text-align:center; margin-top:20px;">
        <a href="<?php echo e(url()->previous()); ?>" class="btn btn-secondary">
            ← Back
        </a>

        <button onclick="window.print()" class="btn btn-success">
            🖨 Print / Save PDF
        </button>
    </div>

</div>


<style>
@media print {

    body {
        background: white;
    }

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
        border: none;
    }

    .btn, a {
        display: none !important;
    }
}
</style>

<?php $__env->stopSection(); ?>
<?php echo $__env->make('adminlte::page', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\laragon\www\bps-laravel\resources\views/mpdo/receipt.blade.php ENDPATH**/ ?>