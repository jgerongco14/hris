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
                <!-- Include the titlebar component -->
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
                <!-- Import Attendance Button -->
                <?php echo $__env->make('components.import_file', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

                <!-- Add Individual User Form -->
                <div id="userForm" style="display:none;" name="userForm">
                    <div class="card my-4 mx-3">
                        <div class="card-header">
                            <h3 class="card-title text-center" id="formTitle">Add Individual User</h3>
                        </div>
                        <div class="card-body">
                            <form method="POST" id="userFormElement" action="<?php echo e(route('user.create')); ?>" class="d-flex align-items-end flex-wrap gap-3 mb-4">
                                <?php echo csrf_field(); ?>
                                <input type="hidden" name="_method" id="formMethod" value="POST"> <!-- For PUT method -->
                                <input type="hidden" name="user_id" id="user_id"> <!-- Hidden input for user ID -->

                                <div class="mx-3 mb-0">
                                    <label for="empID" class="form-label">Employee ID</label>
                                    <input type="text" class="form-control" name="empID" id="empID" required>
                                </div>
                                <div class="mx-3 mb-0">
                                    <label for="email" class="form-label">Email</label>
                                    <input type="email" class="form-control" name="email" id="email" required>
                                </div>
                                <div class="mx-3 mb-0">
                                    <label for="role" class="form-label">Role</label>
                                    <select class="form-select" name="role" id="role" required>
                                        <option value="" disabled>Select Role</option>
                                        <option value="admin">Admin</option>
                                        <option value="employee">Employee</option>
                                        <option value="hr">HR</option>
                                    </select>
                                </div>

                                <div class="mx-3 mb-0">
                                    <label for="status" class="form-label">Status</label>
                                    <select class="form-select" name="status" id="status" required>
                                        <option value="" disabled>Select Status</option>
                                        <option value="active">Active</option>
                                        <option value="resigned">Resigned</option>
                                    </select>
                                </div>

                                <div class="mx-3 mb-0 d-flex align-items-end">
                                    <button type="submit" class="btn btn-primary" id="submitButton">Add User</button>
                                    <button type="button" class="btn btn-secondary ms-2" id="cancelBtn">Cancel</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

                <div class="card my-4 mx-3">
                    <div class="card-header">
                        <h3 class="card-title text-center">User List</h3>
                    </div>
                    <div class="d-flex align-items-end gap-3 flex-wrap mx-3 my-3">
                        <!-- Filter by Role -->
                        <form method="GET" action="<?php echo e(route('user_management')); ?>">
                            <div class="mb-0">
                                <label for="role" class="form-label">Filter by Role</label>
                                <select name="role" id="role" class="form-select" onchange="this.form.submit()">
                                    <option value="">All Roles</option>
                                    <option value="employee" <?php echo e(request('role') == 'employee' ? 'selected' : ''); ?>>Employee</option>
                                    <option value="hr" <?php echo e(request('role') == 'hr' ? 'selected' : ''); ?>>HR</option>
                                </select>
                            </div>
                        </form>

                        <!-- Search by Email or EmpID -->
                        <form method="GET" action="<?php echo e(route('user_management')); ?>" class="d-flex align-items-end gap-2">
                            <div class="mb-0 d-flex align-items-center">
                                <label for="search" class="form-label visually-hidden">Search</label>
                                <input type="text" name="search" id="search" class="form-control me-2" placeholder="Search by Email or EmpID" value="<?php echo e(request('search')); ?>">
                                <button type="submit" class="btn btn-primary d-flex align-items-center">
                                    <i class="ri-search-line"></i> <!-- Search Icon -->
                                </button>
                                <a href="<?php echo e(route('user_management')); ?>" class="btn btn-primary d-flex align-items-center ms-2">
                                    <i class="ri-restart-line"></i> <!-- Reset Icon -->
                                </a>
                            </div>
                        </form>

                        <div class="dropdown ms-auto">
                            <button class="btn btn-primary dropdown-toggle" type="button" id="addUsersBtn" data-bs-toggle="dropdown" aria-expanded="false">
                                Add Users
                            </button>
                            <ul class="dropdown-menu" aria-labelledby="addUsersBtn">
                                <li><a class="dropdown-item" href="#" id="addIndividualBtn">Add Individual User</a></li>
                                <li><a class="dropdown-item" href="#" data-bs-toggle="modal" data-bs-target="#addUsers">Import Users (CSV/Excel)</a></li>
                            </ul>
                        </div>
                    </div>

                    <div class="card-body">
                        <table class="table table-bordered">
                            <thead>
                                <tr class="text-center">
                                    <th>EmpID</th>
                                    <th>Email</th>
                                    <th>Role</th>
                                    <th>Status</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if($users->isEmpty()): ?>
                                <tr>
                                    <td colspan="4" class="text-center">No users available.</td>
                                </tr>
                                <?php else: ?>
                                <?php $__currentLoopData = $users; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $user): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <tr>
                                    <td class="text-center"><?php echo e($user->empID); ?></td>
                                    <td><?php echo e($user->email); ?></td>
                                    <td class="text-center">
                                        <span class="badge rounded bg-secondary text-white"><?php echo e($user->role); ?></span>
                                    </td>
                                    <td class="text-center">
                                        <?php
                                        $rawStatus = strtolower($user->employee->status ?? 'active'); // default to 'active' if null
                                        $badgeClass = $rawStatus === 'resigned' ? 'bg-danger' : 'bg-success';
                                        $statusLabel = ucfirst($rawStatus);
                                        ?>
                                        <span class="badge rounded <?php echo e($badgeClass); ?>"><?php echo e($statusLabel); ?></span>
                                    </td>
                                    <td class="text-center d-flex justify-content-center gap-3">
                                        <!-- Add action icons here -->
                                        <a href="javascript:void(0);" class="btn btn-warning btn-sm" onclick="editUser('<?php echo e($user->id); ?>', '<?php echo e($user->empID); ?>', '<?php echo e($user->email); ?>', '<?php echo e($user->role); ?>' , '<?php echo e($user->employee->status); ?>')">
                                            <i class="ri-edit-line"></i> <!-- Edit Icon -->
                                        </a>
                                        <form action="<?php echo e(route('user.delete', $user->id)); ?>" method="POST" style="display:inline;" onsubmit="return confirm('Are you sure you want to delete this user?');">
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
                        <?php if($users->hasPages()): ?>
                        <div class="d-flex flex-column align-items-center mt-4 gap-2">
                            
                            <div>
                                <?php echo e($users->links('pagination::bootstrap-5')); ?>

                            </div>

                            
                            <div class="text-muted small">
                                Showing <?php echo e($users->firstItem()); ?> to <?php echo e($users->lastItem()); ?> of <?php echo e($users->total()); ?> results
                            </div>
                        </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>


    <script>
        function editUser(id, empID, email, role, status) {
            // Show the form
            const userForm = document.getElementById('userForm');
            userForm.style.display = 'block';

            // Update the form title and button text
            document.getElementById('formTitle').textContent = 'Edit User';
            document.getElementById('submitButton').textContent = 'Update User';

            // Populate the form fields
            document.getElementById('user_id').value = id;
            document.getElementById('empID').value = empID;
            document.getElementById('email').value = email;
            document.getElementById('role').value = role;
            document.getElementById('status').value = status;

            // Update the form action and method
            const form = document.getElementById('userFormElement');
            form.action = `/admin/user_management/${id}`; // Update the route to match your update route
            document.getElementById('formMethod').value = 'PUT'; // Use PUT for updates
        }

        document.addEventListener('DOMContentLoaded', function() {
            const addBtn = document.getElementById('addIndividualBtn');
            const userForm = document.getElementById('userForm');
            const addUsersModalEl = document.getElementById('addUsers');
            const cancelBtn = document.getElementById('cancelBtn');
            let addUsersModal;

            if (cancelBtn) {
                cancelBtn.addEventListener('click', function() {
                    userForm.style.display = 'none';
                });
            }

            if (addUsersModalEl) {
                addUsersModal = bootstrap.Modal.getOrCreateInstance(addUsersModalEl);
                addUsersModalEl.addEventListener('hidden.bs.modal', function() {
                    userForm.style.display = 'none';
                });
            }

            addBtn.addEventListener('click', function() {
                if (userForm.style.display === 'none' || userForm.style.display === '') {
                    userForm.style.display = 'block';
                    if (addUsersModal) addUsersModal.hide();
                } else {
                    userForm.style.display = 'none';
                }
            });
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

</html><?php /**PATH C:\Projects\hris\hris-app\resources\views/pages/admin/user_management.blade.php ENDPATH**/ ?>