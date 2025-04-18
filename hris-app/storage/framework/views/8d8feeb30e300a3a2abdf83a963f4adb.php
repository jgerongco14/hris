<div class="col">

    <!-- Department/Program Modals -->
    <!-- Add Individual Department Modal -->
    <div class="modal fade" id="addDepartmentModal" tabindex="-1" aria-labelledby="addDepartmentModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addDepartmentModalLabel">Add New Department/Program</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="<?php echo e(route('departments.store')); ?>" method="POST">
                    <?php echo csrf_field(); ?>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="departmentCode" class="form-label">Department Code</label>
                            <input type="text" name="departmentCode" id="departmentCode" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label for="name" class="form-label">Department Name</label>
                            <input type="text" name="name" id="name" class="form-control" required>
                        </div>

                        <div id="programs-container">
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
                        </div>
                        <button type="button" class="btn btn-secondary mb-3" onclick="addProgramField()">Add Another Program</button>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary">Save Department</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Import Department Modal -->
    <div class="modal fade" id="importDepartmentModal" tabindex="-1" aria-labelledby="importDepartmentModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="importDepartmentModalLabel">Import Departments/Programs</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="<?php echo e(route('departments.import')); ?>" method="POST" enctype="multipart/form-data">
                    <?php echo csrf_field(); ?>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="department_file" class="form-label">Upload File</label>
                            <input type="file" name="department_file" id="department_file" class="form-control" required>
                        </div>
                        <div class="alert alert-info">
                            <small>File should include columns: Department Code, Department Name, Program Code, Program Name</small>
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
    <!-- Add Program to Existing Department Modal -->
    <div class="modal fade" id="addProgramToDepartmentModal" tabindex="-1" aria-labelledby="addProgramToDepartmentModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <form action="<?php echo e(route('departments.addProgram')); ?>" method="POST">
                    <?php echo csrf_field(); ?>
                    <div class="modal-header">
                        <h5 class="modal-title" id="addProgramToDepartmentModalLabel">Add Program to Existing Department</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>

                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="department_id" class="form-label">Select Department</label>
                            <select name="department_id" id="department_id" class="form-select" required>
                                <option value="" disabled selected>Choose a department</option>
                                <?php $__currentLoopData = $departments; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $department): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($department->id); ?>"><?php echo e($department->departmentName); ?> (<?php echo e($department->departmentCode); ?>)</option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </select>
                        </div>

                        <div id="add-programs-container">
                            <div class="program-item mb-3 border p-3 rounded">
                                <h6>Program #1</h6>
                                <div class="mb-3">
                                    <label for="programCode" class="form-label">Program Code</label>
                                    <input type="text" name="programs[0][programCode]" class="form-control" required>
                                </div>
                                <div class="mb-3">
                                    <label for="programName" class="form-label">Program Name</label>
                                    <input type="text" name="programs[0][programName]" class="form-control" required>
                                </div>
                                <button type="button" class="btn btn-danger btn-sm" onclick="removeProgramField(this)">Remove Program</button>
                            </div>
                        </div>

                        <button type="button" class="btn btn-secondary mb-3" onclick="addProgramField()">Add Another Program</button>
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary">Add Program(s)</button>
                    </div>
                </form>
            </div>
        </div>
    </div>



    <table class="table table-bordered table-striped table-hover">
        <thead>
            <tr class="text-center">
                <th>Code</th>
                <th>Department</th>
                <th>Programs</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php $__empty_1 = true; $__currentLoopData = $departments; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $department): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
            <tr>
                <td><?php echo e($department->departmentCode); ?></td>
                <td><?php echo e($department->departmentName); ?></td>
                <td>
                    <?php
                    // Filter only programs that have at least code or name
                    $validPrograms = $department->programs->filter(function ($program) {
                    return !empty($program->programCode) || !empty($program->programName);
                    });
                    ?>

                    <?php if($validPrograms->isNotEmpty()): ?>
                    <?php $__currentLoopData = $validPrograms; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $program): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <div class="card-body mb-2 border rounded bg-light">
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <div>
                                <h6 class="mb-0"><strong>Code:</strong> <?php echo e($program->programCode); ?></h6>
                                <p class="mb-0"><strong>Name:</strong> <?php echo e($program->programName); ?></p>
                            </div>
                            <form action="<?php echo e(route('departments.removeProgram', ['departmentId' => $department->id, 'programId' => $program->id])); ?>" method="POST">
                                <?php echo csrf_field(); ?>
                                <?php echo method_field('DELETE'); ?>
                                <button type="submit" class="btn btn-danger btn-sm">
                                    <i class="ri-delete-bin-line"></i>
                                </button>
                            </form>
                        </div>
                    </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    <?php else: ?>
                    <span class="text-muted">No programs assigned</span>
                    <?php endif; ?>
                </td>
                <td class="col-3 text-center align-middle">
                    <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#editDepartmentModal-<?php echo e($department->id); ?>">
                        <i class="ri-pencil-line"></i>
                    </button>
                    <form action="<?php echo e(route('departments.destroy', $department->id)); ?>" method="POST" style="display:inline;">
                        <?php echo csrf_field(); ?>
                        <?php echo method_field('DELETE'); ?>
                        <button type="submit" class="btn btn-danger btn-sm">
                            <i class="ri-delete-bin-line"></i>
                        </button>
                    </form>
                </td>
            </tr>
            <!-- Edit Department Modal -->
            <div class="modal fade" id="editDepartmentModal-<?php echo e($department->id); ?>" tabindex="-1" aria-labelledby="editDepartmentModalLabel-<?php echo e($department->id); ?>" aria-hidden="true">
                <div class="modal-dialog modal-lg">
                    <div class="modal-content">
                        <form method="POST" action="<?php echo e(route('departments.update', $department->id)); ?>">
                            <?php echo csrf_field(); ?>
                            <?php echo method_field('PUT'); ?>

                            <div class="modal-header">
                                <h5 class="modal-title" id="editDepartmentModalLabel-<?php echo e($department->id); ?>">Edit Department</h5>
                                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                            </div>

                            <div class="modal-body">
                                <div class="mb-3">
                                    <label class="form-label">Department Code</label>
                                    <input type="text" name="departmentCode" class="form-control" value="<?php echo e($department->departmentCode); ?>" required>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label">Department Name</label>
                                    <input type="text" name="name" class="form-control" value="<?php echo e($department->departmentName); ?>" required>
                                </div>
                            </div>

                            <div class="modal-footer">
                                <button class="btn btn-secondary" type="button" data-bs-dismiss="modal">Cancel</button>
                                <button class="btn btn-primary" type="submit">Update Department</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
            <tr>
                <td colspan="4" class="text-center">No departments found.</td>
            </tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>
<script>
let programIndex = 1;

function addProgramField() {
    const container = document.getElementById('add-programs-container');
    const newItem = document.createElement('div');
    newItem.className = 'program-item mb-3 border p-3 rounded';
    newItem.innerHTML = `
        <h6>Program #${programIndex + 1}</h6>
        <div class="mb-3">
            <label class="form-label">Program Code</label>
            <input type="text" name="programs[${programIndex}][programCode]" class="form-control" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Program Name</label>
            <input type="text" name="programs[${programIndex}][programName]" class="form-control" required>
        </div>
        <button type="button" class="btn btn-danger btn-sm" onclick="removeProgramField(this)">Remove Program</button>
    `;
    container.appendChild(newItem);
    programIndex++;
}

function removeProgramField(button) {
    button.closest('.program-item').remove();
}
</script>
<?php /**PATH C:\Projects\hris\hris-app\resources\views/pages/admin/component/department_list.blade.php ENDPATH**/ ?>