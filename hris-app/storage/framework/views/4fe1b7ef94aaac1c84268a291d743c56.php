<div class="card">
    <div class="card-header ">
        <h5 class="card-title">Resigned Employees</h5>
    </div>
    <div class="card-body">
        <div class="col-6 my-3">
            <form action="<?php echo e(route('reports')); ?>" method="GET" class="d-flex">
                <input type="text" name="report_search" class="form-control form-control-sm me-2" placeholder="Search by Emp ID or Name" value="<?php echo e(request('report_search')); ?>">
                <button type="submit" class="btn btn-primary btn-sm me-2">
                    <i class="ri-search-line"></i>
                </button>
                <a href="<?php echo e(route('reports')); ?>" class="btn btn-secondary btn-sm">
                    <i class="ri-refresh-line"></i>
                </a>
            </form>

        </div>
        <table class="table table-bordered table-striped">
            <thead class="align-middle text-center">
                <tr>
                    <th>Name</th>
                    <th>Semester</th>
                    <th>Year</th>
                    <th>Reason</th>
                    <th>Attachments</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php $__empty_1 = true; $__currentLoopData = $reports; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $report): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <tr>
                    <td>
                        <div class="d-flex align-items-center gap-2">
                            <?php
                            $employeePhoto = $report->employee->photo ?? null;
                            $isExternal = $employeePhoto && Str::startsWith($employeePhoto, ['http://', 'https://']);
                            ?>

                            <?php if($employeePhoto): ?>
                            <img src="<?php echo e($isExternal ? $employeePhoto : asset('storage/' . $employee->photo)); ?>"
                                alt="Employee Photo" width="50" height="50" class="rounded-circle">
                            <?php else: ?>
                            <div class="no-photo bg-light rounded-circle d-flex align-items-center justify-content-center"
                                style="width:50px; height:50px;">
                                <i class="ri-user-line"></i>
                            </div>
                            <?php endif; ?>
                            <span>
                                <?php echo e($report->employee->empPrefix); ?>

                                <?php echo e($report->employee->empFname); ?>

                                <?php echo e($report->employee->empMname); ?>

                                <?php echo e($report->employee->empLname); ?>

                                <?php echo e($report->employee->empSuffix); ?>

                            </span>
                        </div>
                    </td>
                    <td class="text-center align-middle"><?php echo e($report->semester); ?></td>
                    <td class="text-center align-middle"><?php echo e($report->year); ?></td>
                    <td><?php echo e($report->reason); ?></td>
                    <td class="text-center align-middle">
                        <?php if($report->attachments): ?>
                        <?php $__currentLoopData = json_decode($report->attachments); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $file): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <a href="<?php echo e(asset('storage/' . $file)); ?>" target="_blank" class="badge bg-primary text-decoration-none">
                            Attachment <?php echo e($loop->iteration); ?>

                        </a><br>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        <?php else: ?>
                        <span class="badge bg-secondary">No Attachments</span>
                        <?php endif; ?>
                    </td>
                    <td class="text-center align-middle">
                        <form action="<?php echo e(route('reports.delete', $report->id)); ?>" method="POST" onsubmit="return confirmDelete()">
                            <?php echo csrf_field(); ?>
                            <?php echo method_field('DELETE'); ?>
                            <button type="submit" class="btn btn-danger btn-sm">
                                <i class="ri-delete-bin-5-line"></i>
                            </button>
                        </form>
                    </td>
                </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <tr>
                    <td colspan="6" class="text-center">No Reports found.</td>
                </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<script>
    function confirmDelete() {
        return confirm('Are you sure you want to delete this report? This action cannot be undone.');
    }
</script><?php /**PATH C:\Projects\hris\hris-app\resources\views/pages/hr/components/report_resigned_employees.blade.php ENDPATH**/ ?>