
<?php $__env->startSection('css'); ?>
<link rel="stylesheet" href="<?php echo e(asset('css/custom-adminlte.css')); ?>">
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>

<div class="container">

<h2 style="margin-bottom:25px;">📦 Application Tracking</h2>
















<?php if(!empty($reuploaded) && $reuploaded): ?>
<div style="background:#d1e7dd;border:1px solid #badbcc;padding:15px;border-radius:8px;margin-bottom:20px;color:#0f5132;font-weight:600;">
✔ Re-uploaded successfully.
</div>
<?php endif; ?>



<div style="display:flex;gap:20px;margin-bottom:30px;flex-wrap:wrap;">

    
    <div style="flex:1;min-width:200px;background:#f8f9fa;padding:20px;border-radius:10px;border:1px solid #dee2e6;text-align:center;">
        <h4>MPDO</h4>

        <div style="font-weight:600;color:<?php echo e($mpdo['status']=='done' ? '#198754' : '#6c757d'); ?>;">
            <?php echo e($mpdo['label']); ?>

        </div>

        <?php if(!empty($mpdoRemark) && $mpdo['status']!='done'): ?>
            <a href="<?php echo e(url('/applicant/upload_mpdo')); ?>" class="btn btn-sm btn-warning mt-2">
                Re-upload MPDO Requirements
            </a>
        <?php endif; ?>
    </div>


    
    <div style="flex:1;min-width:200px;background:#f8f9fa;padding:20px;border-radius:10px;border:1px solid #dee2e6;text-align:center;">
        <h4>MEO</h4>

        <div style="font-weight:600;color:<?php echo e($meo['status']=='done' ? '#198754' : '#6c757d'); ?>;">
            <?php echo e($meo['label']); ?>

        </div>

        <?php if(!empty($meoRemark) && $meo['status']!='done'): ?>
            <a href="<?php echo e(url('/applicant/upload_meo')); ?>" class="btn btn-sm btn-warning mt-2">
                Re-upload MEO Requirements
            </a>
        <?php endif; ?>
    </div>


    
    <div style="flex:1;min-width:200px;background:#f8f9fa;padding:20px;border-radius:10px;border:1px solid #dee2e6;text-align:center;">
        <h4>BFP</h4>

        <div style="font-weight:600;color:<?php echo e($bfp['status']=='done' ? '#198754' : '#6c757d'); ?>;">
            <?php echo e($bfp['label']); ?>

        </div>

        <?php if(!empty($bfpRemark) && $bfp['status']!='done'): ?>
            <a href="<?php echo e(url('/applicant/upload_bfp')); ?>" class="btn btn-sm btn-warning mt-2">
                Re-upload BFP Requirements
            </a>
        <?php endif; ?>
    </div>

</div>




<?php if(!empty($mpdoRemark) || !empty($meoRemark) || !empty($bfpRemark)): ?>

<div style="background:#f8d7da;border:1px solid #f5c2c7;padding:20px;border-radius:8px;margin-bottom:30px;">

<h4 style="margin-bottom:15px;">⚠ Department Remarks</h4>

<?php if(!empty($mpdoRemark) && $mpdo['status']!='done'): ?>
<p>
<strong>MPDO:</strong><br>
<?php echo e($mpdoRemark); ?>

</p>
<hr>
<?php endif; ?>

<?php if(!empty($meoRemark) && $meo['status']!='done'): ?>
<p>
<strong>MEO:</strong><br>
<?php echo e($meoRemark); ?>

</p>
<hr>
<?php endif; ?>

<?php if(!empty($bfpRemark) && $bfp['status']!='done'): ?>
<p>
<strong>BFP:</strong><br>
<?php echo e($bfpRemark); ?>

</p>
<?php endif; ?>

</div>

<?php endif; ?>



<div style="background:#ffffff;border:1px solid #dee2e6;padding:20px;border-radius:8px;margin-bottom:20px;">
    <h4>MPDO Assessment Fee</h4>
    ₱<?php echo e(number_format($mpdoAmt,2)); ?>

</div>



<div style="background:#ffffff;border:1px solid #dee2e6;padding:20px;border-radius:8px;margin-bottom:20px;">
    <h4>MEO Assessment Fee</h4>
    ₱<?php echo e(number_format($meoAmt,2)); ?>

</div>



<div style="background:#e2f5e9;border:1px solid #badbcc;padding:22px;border-radius:8px;margin-bottom:30px;">

<h4>Total Fee: ₱<?php echo e(number_format($total,2)); ?></h4>

<?php if(($mpdoPaid ?? 0) == 1 && ($meoPaid ?? 0) == 1): ?>

<div style="color:#198754;font-weight:600;margin-top:10px;">
✔ Payment successful. MPDO & MEO fees are already PAID.
</div>

<div style="margin-top:8px;">

<span style="background:#198754;color:#fff;padding:4px 10px;border-radius:6px;font-size:13px;">
MPDO Paid
</span>

<span style="background:#198754;color:#fff;padding:4px 10px;border-radius:6px;font-size:13px;margin-left:6px;">
MEO Paid
</span>

</div>

<?php elseif(isset($isIssuedMEO) && (int)$isIssuedMEO === 1): ?>

<div style="color:#0d6efd;font-weight:600;margin-top:10px;">
👉 You are already ENDORSED by MEO. Please proceed to BFP Office.
</div>

<?php elseif(($meoAmt ?? 0) > 0): ?>

<div style="color:#b45309;font-weight:600;margin-top:10px;">
📢 Go to MEO Office, bring your hard copies of submitted documents and bring your MPDO and MEO Payments Please.
</div>

<?php else: ?>

<div style="color:#6c757d;margin-top:10px;">
Waiting for next department processing...
</div>

<?php endif; ?>

</div>



<div style="background:#fff3cd;border:1px solid #ffecb5;padding:22px;border-radius:8px;">

<h4>BFP Fee: ₱<?php echo e(number_format($bfpAmt,2)); ?></h4>

<?php if(!empty($bfpPaid) && $bfpPaid): ?>

<div style="color:#198754;font-weight:600;margin-top:10px;">
✔ Payment successful. 🔥 Fire Safety Clearance ISSUED — Application completed.
</div>

<?php elseif(!empty($isIssuedBFP) && $isIssuedBFP): ?>

<div style="color:#0d6efd;font-weight:600;margin-top:10px;">
👉 You are already ENDORSED by BFP. Please proceed to BFP office to pay.
</div>

<?php elseif(($bfpAmt ?? 0) > 0): ?>

<div style="color:#b45309;font-weight:600;margin-top:10px;">
📢 Go to BFP Office, bring your hard copies of submitted documents.
</div>

<?php else: ?>

<div style="color:#6c757d;margin-top:10px;">
Waiting for BFP processing and assessment...
</div>

<?php endif; ?>

</div>

</div>

<?php $__env->stopSection(); ?>
<?php echo $__env->make('adminlte::page', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\laragon\www\bps-laravel\resources\views/applicant/track.blade.php ENDPATH**/ ?>