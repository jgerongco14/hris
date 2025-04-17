<div class="card">
    <div class="card-header">
        <h5 class="card-title">Employee List</h5>
    </div>
    <div class="card-body">
        <div class="col-6 my-3">
            <form action="<?php echo e(route('reports')); ?>" method="GET" class="d-flex">
                <input type="text" name="employee_search" class="form-control form-control-sm me-2" placeholder="Search by Emp ID or Name" value="<?php echo e(request('employee_search')); ?>">
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
                    <th class="col-3">Emp ID</th>
                    <th>Name</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                <?php $__currentLoopData = $employees; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $employee): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <tr>
                    <td class="align-middle text-center"><?php echo e($employee->empID); ?></td>
                    <td class="align-middle">
                        <div class="d-flex align-items-center gap-2">
                            <?php
                            $employeePhoto = $employee->photo ?? null;
                            $isExternal = $employeePhoto && Str::startsWith($employeePhoto, ['http://', 'https://']);
                            ?>

                            <?php if($employeePhoto): ?>
                            <img src="<?php echo e($isExternal ? $employeePhoto : asset('storage/' . $employeePhoto)); ?>"
                                alt="Employee Photo" width="50" height="50" class="rounded-circle">
                            <?php else: ?>
                            <div class="no-photo bg-light rounded-circle d-flex align-items-center justify-content-center"
                                style="width:50px; height:50px;">
                                <i class="ri-user-line"></i>
                            </div>
                            <?php endif; ?>

                            <span>
                                <?php echo e($employee->empPrefix); ?>

                                <?php echo e($employee->empFname); ?>

                                <?php echo e($employee->empMname); ?>

                                <?php echo e($employee->empLname); ?>

                                <?php echo e($employee->empSuffix); ?>

                            </span>
                        </div>
                    </td>
                    <td class="align-middle text-center">

                        <?php
                        $rawStatus = strtolower($employee->status ?? 'active'); // default to 'active' if null
                        $isClickable = $rawStatus === 'active';
                        $badgeClass = $rawStatus === 'resigned' ? 'bg-danger' : 'bg-success';
                        $statusLabel = ucfirst($rawStatus);
                        ?>

                        <?php if($isClickable): ?>
                        <button class="btn btn-link text-decoration-none p-0" type="button">
                            <span class="badge <?php echo e($badgeClass); ?> cursor-pointer"
                                onclick="confirmStatusChange('<?php echo e($employee->empID); ?>', '<?php echo e($statusLabel); ?>', '<?php echo e($employee->empPrefix); ?> <?php echo e($employee->empFname); ?> <?php echo e($employee->empMname); ?> <?php echo e($employee->empLname); ?> <?php echo e($employee->empSuffix); ?>')">
                                <?php echo e($statusLabel); ?>

                            </span>
                        </button>
                        <?php else: ?>
                        <span class="badge <?php echo e($badgeClass); ?>">
                            <?php echo e($statusLabel); ?>

                        </span>
                        <?php endif; ?>

                    </td>
                </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </tbody>
        </table>
        <?php if($employees->hasPages()): ?>
        <div class="d-flex flex-column align-items-center mt-4 gap-2">
            
            <div>
                <?php echo e($employees->links('pagination::bootstrap-5')); ?>

            </div>

            
            <div class="text-muted small">
                Showing <?php echo e($employees->firstItem()); ?> to <?php echo e($employees->lastItem()); ?> of <?php echo e($employees->total()); ?> results
            </div>
        </div>
        <?php endif; ?>
    </div>
</div><?php /**PATH C:\Projects\hris\hris-app\resources\views/pages/hr/components/report_employee_list.blade.php ENDPATH**/ ?>