

<?php $__env->startSection('css'); ?>
<link rel="stylesheet" href="<?php echo e(asset('css/custom-adminlte.css')); ?>">
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>

<div class="container-fluid">

    <div class="card shadow-sm border-0">
        
        
        <div class="card-header bg-primary text-white">
            <h5 class="mb-0">
                <i class='bx bx-data'></i> System Logs
            </h5>
        </div>

        <div class="card-body">

            <?php if(empty($logs) || count($logs) == 0): ?>
                <div class="alert alert-info text-center mb-0">
                    No logs available.
                </div>
            <?php else: ?>

            <div class="table-responsive">
                <table class="table table-bordered table-hover align-middle">

                    <thead class="table-light">
                        <tr>
                            <th width="35%">User</th>
                            <th width="20%">Role</th>
                            <th width="45%">Created</th>
                        </tr>
                    </thead>

                    <tbody>
                        <?php $__currentLoopData = $logs; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $l): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <tr>
                            <td>
                                <strong><?php echo e($l->name); ?></strong><br>
                                <small class="text-muted"><?php echo e($l->email ?? ''); ?></small>
                            </td>

                            <td>
                                <span class="badge bg-secondary">
                                    <?php echo e(strtoupper($l->role)); ?>

                                </span>
                            </td>

                            <td>
                                <?php echo e(\Carbon\Carbon::parse($l->created_at)->format('F d, Y h:i A')); ?>

                            </td>
                        </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </tbody>

                </table>
            </div>

            <?php endif; ?>

        </div>
    </div>

</div>

<?php $__env->stopSection(); ?>
<?php echo $__env->make('adminlte::page', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\laragon\www\bps-laravel\resources\views/admin/logs.blade.php ENDPATH**/ ?>