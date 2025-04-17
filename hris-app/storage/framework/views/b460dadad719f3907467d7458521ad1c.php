<div class="col">
    <!-- Add New Office -->
    <div class="card mb-3">
        <div class="card-header">
            <h5>Add New Office</h5>
            <button id="toggleAddOfficeForm" class="btn btn-secondary" onclick="toggleForm('addOfficeForm', 'toggleAddOfficeForm')">Add Individual</button>
            <button id="toggleImportOfficeForm" class="btn btn-secondary" onclick="toggleForm('importOfficeForm', 'toggleImportOfficeForm')">Import File</button>
        </div>
        <div class="card-body" id="addOfficeForm" style="display: none;">
            <form action="<?php echo e(route('offices.store')); ?>" method="POST">
                <?php echo csrf_field(); ?>
                <div class="mb-3">
                    <label for="officeCode" class="form-label">Office Code</label>
                    <input type="text" name="officeCode" id="officeCode" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label for="officeName" class="form-label">Office Name</label>
                    <input type="text" name="officeName" id="officeName" class="form-control" required>
                </div>
                <button type="submit" class="btn btn-success">Add Office</button>
            </form>
        </div>
        <div class="card-body" id="importOfficeForm" style="display: none;">
            <form action="<?php echo e(route('offices.import')); ?>" method="POST" enctype="multipart/form-data">
                <?php echo csrf_field(); ?>
                <div class="mb-3">
                    <label for="office_file" class="form-label">Upload File</label>
                    <input type="file" name="office_file" id="office_file" class="form-control" required>
                </div>
                <button type="submit" class="btn btn-success">Import Offices</button>
            </form>
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
                <td class="col-3 text-center align-middle">
                    <form action="<?php echo e(route('offices.destroy', $office->id)); ?>" method="POST" style="display:inline;">
                        <?php echo csrf_field(); ?>
                        <?php echo method_field('DELETE'); ?>
                        <button type="submit" class="btn btn-danger btn-sm">
                            <i class="ri-delete-bin-line"></i> <!-- Delete Icon -->
                        </button>
                    </form>
                </td>
            </tr>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
            <tr>
                <td colspan="2" class="text-center">No offices found.</td>
            </tr>
            <?php endif; ?>
        </tbody>
    </table>
</div><?php /**PATH C:\Projects\hris\hris-app\resources\views/pages/admin/component/office_list.blade.php ENDPATH**/ ?>