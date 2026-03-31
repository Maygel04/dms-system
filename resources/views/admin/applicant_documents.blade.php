@extends('adminlte::page')

@section('content')

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

        @forelse($documents as $doc)

            <tr>
                <td>{{ strtoupper($doc->department ?? 'N/A') }}</td>
                <td>{{ $doc->file_name ?? 'No file' }}</td>

                <td>
                    {{-- VIEW BUTTON --}}
                    <button type="button"
                        class="btn btn-sm btn-primary viewFileBtn"
                        data-file="{{ asset('storage/'.$doc->file_path) }}">
                        👁 View
                    </button>
                </td>
            </tr>

        @empty

            <tr>
                <td colspan="3" class="text-center text-muted">
                    No documents found
                </td>
            </tr>

        @endforelse

        </tbody>

    </table>

    {{-- ✅ FIXED BACK BUTTON --}}
   {{-- ✅ CORRECT --}}
<a href="{{ route('admin.departments.mpdo') }}" class="btn btn-secondary">
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

@endsection


@section('js')
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
@endsection