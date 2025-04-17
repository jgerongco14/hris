<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Departments and Offices</title>
    <?php echo app('Illuminate\Foundation\Vite')(['resources/sass/app.scss', 'resources/js/app.js']); ?>
    <link href="https://cdn.jsdelivr.net/npm/remixicon@2.5.0/fonts/remixicon.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
</head>

<body>
    <div class="container-fluid">
        <div class="row">
            <div class="col-2 p-0">
                <div class="vh-100 position-sticky top-0">
                    <?php echo $__env->make('components.sidebar', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
                </div>
            </div>
            <div class="col-10 p-3 pt-0">
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

                <div class="position-sticky top-0 z-3 w-100">
                    <?php if (isset($component)) { $__componentOriginal26cfa232fa8c76246a26da566a934cd3 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal26cfa232fa8c76246a26da566a934cd3 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.titlebar','data' => []] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('titlebar'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal26cfa232fa8c76246a26da566a934cd3)): ?>
<?php $attributes = $__attributesOriginal26cfa232fa8c76246a26da566a934cd3; ?>
<?php unset($__attributesOriginal26cfa232fa8c76246a26da566a934cd3); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal26cfa232fa8c76246a26da566a934cd3)): ?>
<?php $component = $__componentOriginal26cfa232fa8c76246a26da566a934cd3; ?>
<?php unset($__componentOriginal26cfa232fa8c76246a26da566a934cd3); ?>
<?php endif; ?>
                </div>

                <div class="card my-4 mx-3">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5>Departments and Offices</h5>
                        <div>
                            <!-- Department/Program Dropdown Button -->
                            <div class="btn-group me-2">
                                <button type="button" class="btn btn-primary dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                                    <i class="bi bi-building-add me-1"></i> Add Department/Program
                                </button>
                                <ul class="dropdown-menu">
                                    <li><a class="dropdown-item" href="#" data-bs-toggle="modal" data-bs-target="#addDepartmentModal">Add Individual</a></li>
                                    <li><a class="dropdown-item" href="#" data-bs-toggle="modal" data-bs-target="#importDepartmentModal">Import from File</a></li>
                                </ul>
                            </div>

                            <!-- Office Dropdown Button -->
                            <div class="btn-group">
                                <button type="button" class="btn btn-success dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                                    <i class="bi bi-house-add me-1"></i> Add Office
                                </button>
                                <ul class="dropdown-menu">
                                    <li><a class="dropdown-item" href="#" data-bs-toggle="modal" data-bs-target="#addOfficeModal">Add Individual</a></li>
                                    <li><a class="dropdown-item" href="#" data-bs-toggle="modal" data-bs-target="#importOfficeModal">Import from File</a></li>
                                </ul>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <!-- Department List -->
                            <div class="col">
                                <?php echo $__env->make('pages.admin.component.department_list', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
                            </div>

                            <!-- Office List -->
                            <div class="col">
                                <?php echo $__env->make('pages.admin.component.office_list', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>



    <script>
        let programIndex = 1;

        function addProgramField() {
            const container = document.getElementById('programs-container');
            const programItem = document.createElement('div');
            programItem.classList.add('program-item', 'mb-3', 'border', 'p-3', 'rounded');
            programItem.innerHTML = `
                <h6>Program #${programIndex + 1}</h6>
                <div class="mb-3">
                    <label for="programCode" class="form-label">Program Code</label>
                    <input type="text" name="programs[${programIndex}][programCode]" class="form-control mb-2" required>
                </div>
                <div class="mb-3">
                    <label for="programName" class="form-label">Program Name</label>
                    <input type="text" name="programs[${programIndex}][programName]" class="form-control mb-2" required>
                </div>
                <button type="button" class="btn btn-danger btn-sm" onclick="removeProgramField(this)">Remove Program</button>
            `;
            container.appendChild(programItem);
            programIndex++;
        }

        function removeProgramField(button) {
            const programItem = button.closest('.program-item');
            programItem.remove();
            // Reindex remaining programs if needed
        }

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

        // Reset forms when modals are closed
        document.querySelectorAll('.modal').forEach(modal => {
            modal.addEventListener('hidden.bs.modal', function() {
                const form = this.querySelector('form');
                if (form) {
                    form.reset();
                    // For department form, reset programs container
                    if (form.id === 'addDepartmentForm') {
                        const container = document.getElementById('programs-container');
                        container.innerHTML = `
                            <div class="program-item mb-3 border p-3 rounded">
                                <h6>Program #1</h6>
                                <div class="mb-3">
                                    <label for="programCode" class="form-label">Program Code</label>
                                    <input type="text" name="programs[0][programCode]" class="form-control mb-2" required>
                                </div>
                                <div class="mb-3">
                                    <label for="programName" class="form-label">Program Name</label>
                                    <input type="text" name="programs[0][programName]" class="form-control mb-2" required>
                                </div>
                                <button type="button" class="btn btn-danger btn-sm" onclick="removeProgramField(this)">Remove Program</button>
                            </div>
                        `;
                        programIndex = 1;
                    }
                }
            });
        });
    </script>
</body>

</html><?php /**PATH C:\Projects\hris\hris-app\resources\views/pages/admin/departments_offices_management.blade.php ENDPATH**/ ?>