

<?php $__env->startSection('title', 'Admin Dashboard'); ?>

<?php $__env->startSection('css'); ?>
<link rel="stylesheet" href="<?php echo e(asset('css/custom-adminlte.css')); ?>">
<?php $__env->stopSection(); ?>


<?php $__env->startSection('content'); ?>

<div class="container-fluid">

    <!-- ================= HEADER ================= -->
    <div class="mb-4">
        <h4 class="fw-bold">🔎 Search Files</h4>
        <p class="text-muted">Find documents by name, department, Content</p>
    </div>

    <!-- ================= SEARCH BAR ================= -->
    <div class="card mb-4 shadow-sm">
        <div class="card-body">

            <form method="GET" action="<?php echo e(route('admin.dashboard')); ?>">
                <div class="row g-2">

                    <div class="col-md-6">
                        <input type="text" name="search"
                            placeholder="Search document name..."
                            value="<?php echo e(request('search')); ?>"
                            class="form-control shadow-sm">
                    </div>

                    <div class="col-md-3">
                        <select name="type" class="form-control shadow-sm">
                            <option value="">All File Types</option>
                            <option value=".pdf">PDF</option>
                            <option value=".dwg">DWG</option>
                            <option value=".dxf">DXF</option>
                        </select>
                    </div>

                    <div class="col-md-3">
                        <button class="btn btn-primary w-100">
                            🔍 Search
                        </button>
                    </div>

                </div>
            </form>

        </div>
    </div>

    <!-- ================= STATS ================= -->
    <div class="row g-4 mb-4">

        <div class="col-md-3">
            <div class="card bg-success text-white text-center p-3">
                <h6>MPDO Verified</h6>
                <h2><?php echo e($mpdoVerified); ?></h2>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card bg-info text-white text-center p-3">
                <h6>MEO Verified</h6>
                <h2><?php echo e($meoVerified); ?></h2>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card bg-primary text-white text-center p-3">
                <h6>BFP Verified</h6>
                <h2><?php echo e($bfpVerified); ?></h2>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card bg-warning text-dark text-center p-3">
                <h6>Pending Applications</h6>
                <h2><?php echo e($pending); ?></h2>
            </div>
        </div>

    </div>

    <!-- ================= BAR CHART ================= -->
    <div class="row mb-4 justify-content-center">
        <div class="col-md-8">
            <div class="card p-3 shadow-sm">
                <h5>Applications Overview</h5>
                <canvas id="applicationsChart"></canvas>
            </div>
        </div>
    </div>

    <!-- ================= SEARCH RESULTS ================= -->
    <?php if(request('search')): ?>

    <div class="card shadow-sm">
        <div class="card-body">

            <h5 class="mb-3">
                Results for: <b><?php echo e(request('search')); ?></b>
            </h5>

            <div class="table-responsive">

                <table class="table table-bordered table-hover align-middle">

                    <thead class="table-dark">
                        <tr>
                            <th>Applicant</th>
                            <th>File Name</th>
                            <th>Department</th>
                            <th>Date Uploaded</th>
                            <th width="180">Action</th>
                        </tr>
                    </thead>

                    <tbody>

                        <?php $__empty_1 = true; $__currentLoopData = $applications; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $app): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>

                        <tr>
                            <td><?php echo e($app->name); ?></td>

                            <td>
                                <?php echo e($app->file_name ?? basename($app->file_path ?? '')); ?>

                            </td>

                            <td>
                                <span class="badge bg-secondary">
                                    <?php echo e(strtoupper($app->department)); ?>

                                </span>
                            </td>

                            <td>
                                <?php echo e(\Carbon\Carbon::parse($app->created_at)->format('Y-m-d')); ?>

                            </td>

                            <td>

                                <?php if(!empty($app->file_path)): ?>

                                <button
                                    class="btn btn-sm btn-success"
                                    onclick="openFileModal(<?php echo json_encode(asset('storage/'.$app->file_path), 15, 512) ?>)">
                                    View
                                </button>

                                <a href="<?php echo e(asset('storage/'.$app->file_path)); ?>"
                                   download
                                   class="btn btn-sm btn-secondary">
                                   Download
                                </a>

                                <?php else: ?>
                                <span class="text-muted">No file</span>
                                <?php endif; ?>

                            </td>
                        </tr>

                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <tr>
                            <td colspan="5" class="text-center text-muted">
                                No results found
                            </td>
                        </tr>
                        <?php endif; ?>

                    </tbody>

                </table>

            </div>

        </div>
    </div>

    <?php endif; ?>

</div>

<!-- ================= MODAL ================= -->
<div class="modal fade" id="fileModal" tabindex="-1">
  <div class="modal-dialog modal-xl modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">📄 File Preview</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body p-0">
        <iframe id="fileFrame" src="" width="100%" height="600px" style="border: none;"></iframe>
      </div>
    </div>
  </div>
</div>

<?php $__env->stopSection(); ?>


<?php $__env->startSection('js'); ?>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
document.addEventListener("DOMContentLoaded", function () {

    const ctx = document.getElementById('applicationsChart');

    if (ctx) {
        new Chart(ctx, {
            type: 'bar',
            data: {
                labels: ['MPDO', 'MEO', 'BFP', 'Pending'],
                datasets: [{
                    label: 'Applications',
                    data: [
                        <?php echo e($mpdoVerified); ?>,
                        <?php echo e($meoVerified); ?>,
                        <?php echo e($bfpVerified); ?>,
                        <?php echo e($pending); ?>

                    ],
                    backgroundColor: [
                        '#28a745',
                        '#17a2b8',
                        '#a40e0e',
                        '#ffc107'
                    ],
                    borderRadius: 8
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        labels: {
                            color: '#444'
                        }
                    }
                },
                scales: {
                    y: {
                        grid: {
                            color: 'rgba(0,0,0,0.05)'
                        },
                        beginAtZero: true
                    }
                }
            }
        });
    }

});

/* MODAL FUNCTION */
function openFileModal(url) {
    const iframe = document.getElementById('fileFrame');
    iframe.src = url;

    const modal = new bootstrap.Modal(document.getElementById('fileModal'));
    modal.show();
}

/* CLEAR iframe when modal closes */
document.getElementById('fileModal').addEventListener('hidden.bs.modal', function () {
    document.getElementById('fileFrame').src = "";
});
</script>

<?php $__env->stopSection(); ?>
<?php echo $__env->make('adminlte::page', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\laragon\www\bps-laravel\resources\views/admin/dashboard.blade.php ENDPATH**/ ?>