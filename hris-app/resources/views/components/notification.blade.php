<div class="position-fixed top-0 end-0 p-3" style="z-index: 1055">
    <div id="liveToast" class="toast align-items-center border-0 bg-white" role="alert" aria-live="assertive" aria-atomic="true">
        <div class="toast-header" id="toast-header">
            <strong class="me-auto" id="toast-title"></strong>
            <button type="button" class="btn-close" data-bs-dismiss="toast" aria-label="Close"></button>
        </div>
        <div class="toast-body d-flex flex-column align-items-center justify-content-center" id="toast-body">
            <span id="toast-icon" class="me-2 fs-4 ">✅</span>
            <div id="toast-message"></div>
        </div>
    </div>
</div>


@if (session('success'))
<script>
    document.addEventListener('DOMContentLoaded', function() {
        showToast('Success', "{{ session('success') }}", 'success');
    });
</script>
@elseif (session('warning'))
<script>
    document.addEventListener('DOMContentLoaded', function() {
        showToast('Warning', "{{ session('warning') }}", 'warning');
    });
</script>
@elseif (session('error'))
<script>
    document.addEventListener('DOMContentLoaded', function() {
        showToast('Error', "{{ session('error') }}", 'danger');
    });
</script>
@endif