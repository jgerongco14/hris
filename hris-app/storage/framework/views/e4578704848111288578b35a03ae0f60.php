<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>HRIS</title>
    <?php echo app('Illuminate\Foundation\Vite')(['resources/sass/app.scss', 'resources/js/app.js']); ?>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>

    
    <main class="py-4">
        <?php echo $__env->yieldContent('content'); ?>
    </main>

    
    <div class="toast-container position-fixed top-0 end-0 p-3" style="z-index: 1055">
        <div id="liveToast" class="toast border border-2 bg-white" role="alert" aria-live="assertive" aria-atomic="true">
            <div id="toast-header" class="toast-header text-success">
                <strong id="toast-icon" class="me-2">✅</strong>
                <strong class="me-auto" id="toast-title">Success</strong>
                <button type="button" class="btn-close ms-2 mb-1" data-bs-dismiss="toast" aria-label="Close"></button>
            </div>
            <div class="toast-body" id="toast-message">This is a toast message.</div>
        </div>
    </div>

    <?php if(session('status')): ?>
    showToast('Success', '<?php echo e(session('status')); ?>', 'success');
    <?php endif; ?>
    <?php if(session('error')): ?>
    showToast('Error', '<?php echo e(session('error')); ?>', 'danger');
    <?php endif; ?>
    <?php if($errors->any()): ?>
    showToast('Error', '<?php echo e($errors->first()); ?>', 'danger');
    <?php endif; ?>

    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        function showToast(title, message, type = 'success') {
            const toastEl = document.getElementById('liveToast');
            const toastHeader = document.getElementById('toast-header');
            const toastTitle = document.getElementById('toast-title');
            const toastMessage = document.getElementById('toast-message');
            const toastIcon = document.getElementById('toast-icon');

            toastEl.className = 'toast align-items-center border border-2 show bg-white';

            const headerColors = {
                success: 'text-success',
                danger: 'text-danger',
                warning: 'text-warning',
                info: 'text-info'
            };

            const icons = {
                success: '✅',
                danger: '❌',
                warning: '⚠️',
                info: 'ℹ️'
            };

            toastHeader.className = `toast-header ${headerColors[type] || 'text-dark'}`;
            toastIcon.textContent = icons[type] || '';
            toastTitle.textContent = title;
            toastMessage.textContent = message;

            const toast = new bootstrap.Toast(toastEl, {
                delay: 10000
            });
            toast.show();
        }
    </script>
</body>

</html><?php /**PATH C:\Projects\hris\hris-app\resources\views/layouts/app.blade.php ENDPATH**/ ?>