

<?php $__env->startSection('title', 'Database Backup'); ?>

<?php $__env->startSection('css'); ?>
<link rel="stylesheet" href="<?php echo e(asset('css/custom-adminlte.css')); ?>">
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content_header'); ?>
    <h1 class="fw-bold">Database Backup</h1>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>

<div class="container-fluid">

    <!-- HEADER -->
    <div class="mb-4">
        <h4 class="fw-bold">🗄️ Backup</h4>
        <p class="text-muted">Create and download system database backup</p>
    </div>

    <!-- ALERTS -->
    <?php if(session('success')): ?>
        <div class="alert alert-success shadow-sm">
            <?php echo e(session('success')); ?>

        </div>
    <?php endif; ?>

    <?php if(session('error')): ?>
        <div class="alert alert-danger shadow-sm">
            <?php echo e(session('error')); ?>

        </div>
    <?php endif; ?>

    <!-- CARD -->
    <div class="card shadow border-0 rounded-4">

        <div class="card-body p-4">

            <h5 class="fw-semibold mb-3">Database Backup</h5>

            <p class="text-muted mb-4">
                Click the button below to generate and download a backup of the database.
            </p>

            <!-- FORM -->
            <form method="POST" action="<?php echo e(route('admin.backup.database')); ?>">
                <?php echo csrf_field(); ?>

                <button type="submit" class="btn btn-primary btn-lg px-4 shadow-sm">
                    ⬇ Download Database Backup
                </button>

            </form>

        </div>

    </div>

</div>

<?php $__env->stopSection(); ?>
<?php echo $__env->make('adminlte::page', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\laragon\www\bps-laravel\resources\views/admin/backup.blade.php ENDPATH**/ ?>