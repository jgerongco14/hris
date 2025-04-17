<div class="col">
    <!-- Add New Office -->
    <!-- Office Modals -->
    <!-- Add Individual Office Modal -->
    <div class="modal fade" id="addOfficeModal" tabindex="-1" aria-labelledby="addOfficeModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addOfficeModalLabel">Add New Office</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="<?php echo e(route('offices.store')); ?>" method="POST">
                    <?php echo csrf_field(); ?>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="officeCode" class="form-label">Office Code</label>
                            <input type="text" name="officeCode" id="officeCode" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label for="officeName" class="form-label">Office Name</label>
                            <input type="text" name="officeName" id="officeName" class="form-control" required>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary">Save Office</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <!-- Import Office Modal -->
    <div class="modal fade" id="importOfficeModal" tabindex="-1" aria-labelledby="importOfficeModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="importOfficeModalLabel">Import Offices</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="<?php echo e(route('offices.import')); ?>" method="POST" enctype="multipart/form-data">
                    <?php echo csrf_field(); ?>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="office_file" class="form-label">Upload File</label>
                            <input type="file" name="office_file" id="office_file" class="form-control" required>
                        </div>
                        <div class="alert alert-info">
                            <small>File should include columns: Office Code, Office Name</small>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary">Import</button>
                    </div>
                </form>
            </div>
        </div>
    </div>




    <table class="table table-bordered table-striped table-hover">
        <thead class="text-center">
            <tr>
                <th>Code</th>
                <th>Offices</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody class="align-middle">
            <?php $__empty_1 = true; $__currentLoopData = $offices; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $office): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
            <tr>
                <td><?php echo e($office->officeCode); ?></td>
                <td><?php echo e($office->officeName); ?></td>
                <!-- Update your table actions column to include edit button -->
                <td class="col-3 text-center align-middle">
                    <button class="btn btn-primary btn-sm me-1" data-bs-toggle="modal" data-bs-target="#editOfficeModal-<?php echo e($office->id); ?>">
                        <i class="ri-pencil-line"></i>
                    </button>
                    <form action="<?php echo e(route('offices.destroy', $office->id)); ?>" method="POST" style="display:inline;">
                        <?php echo csrf_field(); ?>
                        <?php echo method_field('DELETE'); ?>
                        <button type="submit" class="btn btn-danger btn-sm">
                            <i class="ri-delete-bin-line"></i>
                        </button>
                    </form>
                </td>

            </tr>
            <!-- Edit Office Modal -->
            <div class="modal fade" id="editOfficeModal-<?php echo e($office->id); ?>" tabindex="-1" aria-labelledby="editOfficeModalLabel-<?php echo e($office->id); ?>" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <form method="POST" action="<?php echo e(route('offices.update', $office->id)); ?>">
                            <?php echo csrf_field(); ?>
                            <?php echo method_field('PUT'); ?>

                            <div class="modal-header">
                                <h5 class="modal-title" id="editOfficeModalLabel-<?php echo e($office->id); ?>">Edit Office</h5>
                                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>

                            <div class="modal-body">
                                <div class="mb-3">
                                    <label for="officeCode-<?php echo e($office->id); ?>" class="form-label">Office Code</label>
                                    <input type="text" name="officeCode" id="officeCode-<?php echo e($office->id); ?>" class="form-control" value="<?php echo e($office->officeCode); ?>" required>
                                </div>

                                <div class="mb-3">
                                    <label for="officeName-<?php echo e($office->id); ?>" class="form-label">Office Name</label>
                                    <input type="text" name="officeName" id="officeName-<?php echo e($office->id); ?>" class="form-control" value="<?php echo e($office->officeName); ?>" required>
                                </div>
                            </div>

                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                <button type="submit" class="btn btn-primary">Update Office</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
            <tr>
                <td colspan="3" class="text-center">No offices found.</td>
            </tr>
            <?php endif; ?>
        </tbody>
    </table>
</div><?php /**PATH C:\Projects\hris\hris-app\resources\views/pages/admin/component/office_list.blade.php ENDPATH**/ ?>