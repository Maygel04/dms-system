

<?php $__env->startSection('content'); ?>

<div class="container mt-4">

    <h4>Applicant Documents</h4>

    <table class="table table-bordered">

        <thead>
            <tr>
                <th>Department</th>
                <th>File</th>
                <th>Action</th>
            </tr>
        </thead>

        <tbody>

        <?php $__empty_1 = true; $__currentLoopData = $documents; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $doc): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>

            <tr>
                <td><?php echo e(strtoupper($doc->department ?? 'N/A')); ?></td>
                <td><?php echo e($doc->file_name ?? 'No file'); ?></td>

                <td>
                    
                    <button type="button"
                        class="btn btn-sm btn-primary viewFileBtn"
                        data-file="<?php echo e(asset('storage/'.$doc->file_path)); ?>">
                        👁 View
                    </button>
                </td>
            </tr>

        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>

            <tr>
                <td colspan="3" class="text-center text-muted">
                    No documents found
                </td>
            </tr>

        <?php endif; ?>

        </tbody>

    </table>

    
   
<a href="<?php echo e(route('admin.departments.mpdo')); ?>" class="btn btn-secondary">
    ← Back
</a>

</div>


<!-- FILE PREVIEW MODAL -->
<div class="modal fade" id="filePreviewModal" tabindex="-1">
    <div class="modal-dialog modal-xl modal-dialog-centered">
        <div class="modal-content">

            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title">📄 File Preview</h5>
                <button type="button" class="close text-white" data-dismiss="modal">
                    <span>&times;</span>
                </button>
            </div>

            <div class="modal-body p-0" style="height:80vh;">
                <iframe id="filePreviewFrame"
                        width="100%"
                        height="100%"
                        style="border:none;"></iframe>
            </div>

        </div>
    </div>
</div>

<?php $__env->stopSection(); ?>


<?php $__env->startSection('js'); ?>
<script>
document.addEventListener("DOMContentLoaded", function () {

    const buttons = document.querySelectorAll(".viewFileBtn");
    const frame = document.getElementById("filePreviewFrame");

    buttons.forEach(button => {
        button.addEventListener("click", function () {

            let fileUrl = this.getAttribute("data-file");

            if (!fileUrl) return;

            frame.src = fileUrl;

            $('#filePreviewModal').modal('show');
        });
    });

    $('#filePreviewModal').on('hidden.bs.modal', function () {
        frame.src = '';
    });

});
</script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('adminlte::page', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\laragon\www\bps-laravel\resources\views/admin/applicant_documents.blade.php ENDPATH**/ ?>