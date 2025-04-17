<div class="container-fluid mt-5">
    <?php if (isset($component)) { $__componentOriginal0d8d3c14ebd2b92d484be47e6c018839 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal0d8d3c14ebd2b92d484be47e6c018839 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.notification','data' => []] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('notification'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal0d8d3c14ebd2b92d484be47e6c018839)): ?>
<?php $attributes = $__attributesOriginal0d8d3c14ebd2b92d484be47e6c018839; ?>
<?php unset($__attributesOriginal0d8d3c14ebd2b92d484be47e6c018839); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal0d8d3c14ebd2b92d484be47e6c018839)): ?>
<?php $component = $__componentOriginal0d8d3c14ebd2b92d484be47e6c018839; ?>
<?php unset($__componentOriginal0d8d3c14ebd2b92d484be47e6c018839); ?>
<?php endif; ?>
    <div class="row align-items-center">
        <div class="col-xl-8 col-lg-7 col-md-6 col-sm-4">
            <div class="login-card">
                <h2>HUMAN RESOURCE INFORMATION SYSTEM</h2>

                
                <form method="POST" action="<?php echo e(route('defaultlogin')); ?>">
                    <?php echo csrf_field(); ?>
                    <div class="form-group my-3">
                        <label for="identifier">Email or Employee ID</label>
                        <input type="text" name="identifier" class="form-control" id="identifier" placeholder="example@email.com or EMP12345" required>
                    </div>

                    <div class="form-group my-3">
                        <label for="password">Password</label>
                        <input type="password" name="password" class="form-control" id="password" placeholder="Enter at least 8 characters" required>
                    </div>

                    <div class="text-center my-4">
                        <button type="submit" class="btn btn-dark w-100">Sign in</button>
                    </div>
                </form>

                
                <div class="text-center my-3">
                    <p>Or</p>
                    <a href="<?php echo e(route('auth.google')); ?>" class="google-signin text-white btn btn-danger btn-outline-dark w-30">
                        <i class="ri-google-fill"></i> Sign in with Google
                    </a>
                </div>

                <!-- <div class="text-center signup-link">
                        <p>For New Employees</p>
                        <a href="#" class="btn btn-secondary text-decoration-none text-white">
                            <i class="bi-pen"></i> Sign-Up Here
                        </a>
                    </div> -->
            </div>
        </div>
        <div class="col">
            <img src="" alt="Login Image" class="img-fluid">
        </div>
    </div>
</div>
<script>
    function showToast(title, message, type = 'success') {
        const toastEl = document.getElementById('liveToast');
        const toastHeader = document.getElementById('toast-header');
        const toastTitle = document.getElementById('toast-title');
        const toastMessage = document.getElementById('toast-message');
        const toastIcon = document.getElementById('toast-icon');

        // Reset and keep background white
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

        // Style header and icon
        toastHeader.className = `toast-header ${headerColors[type] || 'text-dark'}`;
        toastIcon.textContent = icons[type] || '';
        toastTitle.textContent = title;
        toastMessage.textContent = message;

        const toast = new bootstrap.Toast(toastEl, {
            delay: 10000
        });
        toast.show();
    }
</script><?php /**PATH C:\Projects\hris\hris-app\resources\views/pages/login_page.blade.php ENDPATH**/ ?>