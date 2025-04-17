<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin</title>
    <?php echo app('Illuminate\Foundation\Vite')(['resources/sass/app.scss', 'resources/js/app.js']); ?>
    <link href="https://cdn.jsdelivr.net/npm/remixicon@2.5.0/fonts/remixicon.css" rel="stylesheet">
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
                <!-- Include the notification component -->
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

                <div class="content">

                    <!-- Add Position Button -->
                    <?php echo $__env->make('pages.admin.component.modal', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

                    <!-- Positions Table -->
                    <div class="card mx-3 my-4">
                        <div class="card-header">
                            <h3 class="card-title text-center">Position List</h3>
                        </div>
                        <div class="card-body">
                            <div class="row align-items-end justify-content-between mb-3">
                                <!-- Search Form -->
                                <div class="col-md-4">
                                    <form method="GET" action="<?php echo e(route('assignment_management')); ?>" class="d-flex gap-2">
                                        <input type="text" name="search" id="search" class="form-control" placeholder="Search by Position ID or Position Name" value="<?php echo e(request('search')); ?>">
                                        <button type="submit" class="btn btn-primary d-flex align-items-center">
                                            <i class="ri-search-line"></i>
                                        </button>
                                        <a href="<?php echo e(route('assignment_management')); ?>" class="btn btn-secondary d-flex align-items-center">
                                            <i class="ri-restart-line"></i>
                                        </a>
                                    </form>
                                </div>

                                <!-- Add Button -->
                                <div class="col-md-3 text-end">
                                    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addPositionModal">
                                        Add Position
                                    </button>
                                </div>
                            </div>
                        </div>

                        <table class="table table-bordered">
                            <thead>
                                <tr class="text-center">
                                    <th>No</th>
                                    <th>Position ID</th>
                                    <th>Position Name</th>
                                    <th>Position Description</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if($positions->isEmpty()): ?>
                                <tr>
                                    <td colspan="6" class="text-center">No positions available.</td>
                                </tr>
                                <?php else: ?>
                                <?php $__currentLoopData = $positions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $position): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <tr>
                                    <td class=" text-center"><?php echo e($loop->iteration); ?></td>
                                    <td class="text-center"><?php echo e($position->positionID); ?></td>
                                    <td><?php echo e($position->positionName); ?></td>
                                    <td class="col-6"><?php echo e($position->positionDescription); ?></td>
                                    <td class="text-center">
                                        <!-- Edit and Delete Buttons -->
                                        <button class="btn btn-warning btn-sm"
                                            data-id="<?php echo e($position->id); ?>"
                                            data-position-id="<?php echo e($position->positionID); ?>"
                                            data-position-name="<?php echo e($position->positionName); ?>"
                                            data-position-description="<?php echo e($position->positionDescription); ?>"
                                            onclick="editPosition(this)">
                                            <i class="ri-edit-line"></i>
                                        </button>


                                        <form action="<?php echo e(route('assignment.delete', $position->id)); ?>" method="POST" style="display:inline;" onsubmit="return confirm('Are you sure you want to delete this position?');">
                                            <?php echo csrf_field(); ?>
                                            <?php echo method_field('DELETE'); ?>
                                            <button type="submit" class="btn btn-danger btn-sm">
                                                <i class="ri-delete-bin-line"></i> <!-- Delete Icon -->
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                <?php endif; ?>
                            </tbody>
                        </table>

                        <!-- Pagination Links -->
                        <?php if($positions->hasPages()): ?>
                        <div class="d-flex flex-column align-items-center mt-4 gap-2">
                            <div>
                                <?php echo e($positions->links('pagination::bootstrap-5')); ?>

                            </div>
                            <div class="text-muted small">
                                Showing <?php echo e($positions->firstItem()); ?> to <?php echo e($positions->lastItem()); ?> of <?php echo e($positions->total()); ?> results
                            </div>
                        </div>
                        <?php endif; ?>

                    </div>
                </div>
            </div>

        </div>
    </div>

    <script>
        function showIndividualForm() {
            // Show the individual position form and hide the import form
            document.getElementById('positionForm').style.display = 'block';
            document.getElementById('importForm').style.display = 'none';
            document.getElementById('addPositionModalLabel').textContent = 'Add Individual Position';
            document.getElementById('submitButton').textContent = 'Add Position';
        }

        function showImportForm() {
            // Show the import position form and hide the individual form
            document.getElementById('positionForm').style.display = 'none';
            document.getElementById('importForm').style.display = 'block';
            document.getElementById('addPositionModalLabel').textContent = 'Import Positions';
            document.getElementById('submitButton').textContent = 'Import Positions';
        }

        document.getElementById('addPositionModal').addEventListener('hidden.bs.modal', function() {
            const form = document.getElementById('positionForm');
            form.reset();
            form.action = "<?php echo e(route('assignment.storePosition')); ?>"; // Reset the action for individual form
        });

        function editPosition(button) {
            const modal = new bootstrap.Modal(document.getElementById('addPositionModal'));
            modal.show();

            document.getElementById('addPositionModalLabel').textContent = 'Edit Position';
            document.getElementById('submitButton').textContent = 'Update Position';

            document.getElementById('id').value = button.dataset.id;
            document.getElementById('positionID').value = button.dataset.positionId;
            document.getElementById('positionName').value = button.dataset.positionName;
            document.getElementById('positionDescription').value = button.dataset.positionDescription;

            const form = document.getElementById('positionForm');
            form.action = `/admin/position_management/${button.dataset.id}`;
            document.getElementById('formMethod').value = 'PUT';
        }


        document.getElementById('addPositionModal').addEventListener('hidden.bs.modal', function() {
            const form = document.getElementById('positionForm');
            form.reset();
            form.action = "<?php echo e(route('assignment.storePosition')); ?>"; // ✅ Correct here
            document.getElementById('formMethod').value = 'POST';
            document.getElementById('addPositionModalLabel').textContent = 'Add Position';
            document.getElementById('submitButton').textContent = 'Add Position';
        });

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
    </script>
</body>

</html><?php /**PATH C:\Projects\hris\hris-app\resources\views/pages/admin/assignment_management.blade.php ENDPATH**/ ?>