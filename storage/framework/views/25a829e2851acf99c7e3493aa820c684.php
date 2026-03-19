

<?php $__env->startSection('title', 'Departments'); ?>

<?php $__env->startSection('css'); ?>
<link rel="stylesheet" href="<?php echo e(asset('css/custom-adminlte.css')); ?>">
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content_header'); ?>
    <h1 class="m-0">🏢 Departments</h1>
            <p class="text-muted mb-0">Monitor department verification process</p>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>



    <div class="row">

        
        <div class="col-md-4 mb-4">
            <a href="<?php echo e(route('admin.departments.mpdo')); ?>" style="text-decoration: none;">
                <div class="card shadow-sm border-0 h-100">
                    <div class="card-body text-center p-4 rounded" style="background: linear-gradient(135deg, #6ea8fe, #9ec5fe);">
                        <div class="mb-3">
                            <i class="fas fa-city fa-3x"></i>
                        </div>
                        <h4 class="fw-bold mb-2">MPDO</h4>
                        <p class="mb-0">View MPDO Applications</p>
                    </div>
                </div>
            </a>
        </div>

        
        <div class="col-md-4 mb-4">
            <a href="<?php echo e(route('admin.departments.meo')); ?>" style="text-decoration: none;">
                <div class="card shadow-sm border-0 h-100">
                    <div class="card-body text-center p-4 rounded" style="background: linear-gradient(135deg, #6fcf97, #a8e6cf);">
                        <div class="mb-3">
                            <i class="fas fa-hard-hat fa-3x"></i>
                        </div>
                        <h4 class="fw-bold mb-2">MEO</h4>
                        <p class="mb-0">View MEO Applications</p>
                    </div>
                </div>
            </a>
        </div>

        
        <div class="col-md-4 mb-4">
            <a href="<?php echo e(route('admin.departments.bfp')); ?>" style="text-decoration: none;">
                <div class="card shadow-sm border-0 h-100">
                    <div class="card-body text-center p-4 rounded" style="background: linear-gradient(135deg, #f8a5a5, #fbc2c2);">
                        <div class="mb-3">
                            <i class="fas fa-fire-extinguisher fa-3x"></i>
                        </div>
                        <h4 class="fw-bold mb-2">BFP</h4>
                        <p class="mb-0">View BFP Applications</p>
                    </div>
                </div>
            </a>
        </div>

    </div>

</div>

<?php $__env->stopSection(); ?>
<?php echo $__env->make('adminlte::page', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\laragon\www\bps-laravel\resources\views/admin/department.blade.php ENDPATH**/ ?>