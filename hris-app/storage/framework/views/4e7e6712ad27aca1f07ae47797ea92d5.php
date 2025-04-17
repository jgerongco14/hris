<!-- Leave Application Status Section -->
<?php if(isset($tabs)): ?>
<div class="empleavelist mx-3 row my-4">
    <div class="col">
        <div class="card card-body p-3">
            <ul class="nav nav-pills mb-3" id="leaveTabs" role="tablist">
                <li class="nav-item" role="presentation">
                    <a class="nav-link active" id="all-tab" data-bs-toggle="pill" href="#all" role="tab" aria-controls="all" aria-selected="true">All</a>
                </li>
                <li class="nav-item" role="presentation">
                    <a class="nav-link" id="approval-tab" data-bs-toggle="pill" href="#approval" role="tab" aria-controls="approval" aria-selected="false">For Approval</a>
                </li>
                <li class="nav-item" role="presentation">
                    <a class="nav-link" id="approved-tab" data-bs-toggle="pill" href="#approved" role="tab" aria-controls="approved" aria-selected="false">Approved</a>
                </li>
                <li class="nav-item" role="presentation">
                    <a class="nav-link" id="declined-tab" data-bs-toggle="pill" href="#declined" role="tab" aria-controls="declined" aria-selected="false">Declined</a>
                </li>
            </ul>

            <div class="tab-content" id="leaveTabsContent">
                <?php $__currentLoopData = $tabs; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $tabId => $tabConfig): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <div class="tab-pane fade <?php echo e($tabId === 'all' ? 'show active' : ''); ?>" id="<?php echo e($tabId); ?>" role="tabpanel" aria-labelledby="<?php echo e($tabId); ?>-tab">
                    <table class="table table-bordered text">
                        <thead class="text-center">
                            <tr>
                                <th>Date Applied</th>
                                <th>Type of Leave</th>
                                <th>Date Range</th>
                                <th>Reason</th>
                                <th>Offices</th>
                                <th>Status</th>
                                <th>Remarks</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody class="align-middle">
                            <?php $__empty_1 = true; $__currentLoopData = $tabConfig['data']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $status): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                            <tr>
                                <!-- Date Applied -->
                                <td><?php echo e(\Carbon\Carbon::parse($status->leave->empLeaveDateApplied)->format('M d, Y')); ?></td>

                                <!-- Type of Leave -->
                                <td><?php echo e($status->leave->leaveType); ?></td>

                                <!-- Date Range -->
                                <td>
                                    <?php echo e(\Carbon\Carbon::parse($status->leave->empLeaveStartDate)->format('M d, Y')); ?>

                                    -
                                    <?php echo e(\Carbon\Carbon::parse($status->leave->empLeaveEndDate)->format('M d, Y')); ?>

                                </td>

                                <!-- Reason -->
                                <td><?php echo e($status->leave->empLeaveDescription); ?></td>

                                <!-- Offices -->
                                <td>
                                    <?php
                                    $offices = json_decode($status->empLSOffice, true);
                                    ?>

                                    <?php if($offices && is_array($offices)): ?>
                                    <ul class="list-unstyled mb-0">
                                        <?php $__currentLoopData = $offices; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $office => $empLSOffice): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <?php
                                        $badgeClass = match(strtolower($empLSOffice)) {
                                        'pending' => 'bg-secondary',
                                        'approved' => 'bg-success',
                                        'declined' => 'bg-danger',
                                        default => 'bg-light text-dark',
                                        };
                                        ?>
                                        <li>
                                            <strong><?php echo e(ucwords(strtolower($office))); ?>:</strong>
                                            <span class="badge <?php echo e($badgeClass); ?>"><?php echo e(strtoupper($empLSOffice)); ?></span>
                                        </li>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </ul>
                                    <?php else: ?>
                                    <span class="text-muted">No status available.</span>
                                    <?php endif; ?>
                                </td>


                                <!-- Status -->
                                <td class="text-center">
                                    <?php
                                    $badgeClass = match(strtolower($status->empLSStatus)) {
                                    'pending' => 'bg-secondary',
                                    'approved' => 'bg-success',
                                    'declined' => 'bg-danger',
                                    default => 'bg-light text-dark',
                                    };
                                    ?>
                                    <span class="badge <?php echo e($badgeClass); ?>"><?php echo e(strtoupper($status->empLSStatus)); ?></span>
                                </td>


                                <!-- Remarks -->
                                <td>
                                    <?php
                                    $remarks = json_decode($status->empLSRemarks, true);
                                    ?>

                                    <?php if($remarks && is_array($remarks)): ?>
                                    <ul class="list-unstyled mb-0">
                                        <?php $__currentLoopData = $remarks; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $office => $remark): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <li><strong><?php echo e(ucwords(strtolower($office))); ?>:</strong> <?php echo e($remark); ?></li>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </ul>
                                    <?php else: ?>
                                    <span class="text-muted">No remarks available.</span>
                                    <?php endif; ?>
                                </td>

                                <!-- Actions -->
                                <td class="text-center">
                                    <?php if($tabConfig['show_actions'] && strtolower($status->empLSStatus) === 'pending'): ?>
                                    <a href="<?php echo e(route('leave_application.edit', $status->empLeaveNo)); ?>"
                                        class="btn btn-sm"
                                        title="Edit Leave Application"
                                        data-bs-toggle="tooltip" data-bs-placement="top">
                                        <i class="ri-pencil-line"></i>
                                    </a>
                                    <?php endif; ?>
                                </td>

                            </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                            <tr>
                                <td colspan="8" class="text-center text-muted"><?php echo e($tabConfig['empty']); ?></td>
                            </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>

                    <?php if($tabConfig['data']->hasPages()): ?>
                    <div class="d-flex flex-column align-items-center mt-4 gap-2">
                        
                        <div>
                            <?php echo e($tabConfig['data']->links('pagination::bootstrap-5')); ?>

                        </div>

                        
                        <div class="text-muted small">
                            Showing <?php echo e($tabConfig['data']->firstItem()); ?> to <?php echo e($tabConfig['data']->lastItem()); ?> of <?php echo e($tabConfig['data']->total()); ?> results
                        </div>
                    </div>
                    <?php endif; ?>


                </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div>
        </div>
    </div>
</div>
<?php else: ?>
<div class="alert alert-danger">Leave tab data not available.</div>
<?php return; ?>
<?php endif; ?><?php /**PATH C:\Projects\hris\hris-app\resources\views/pages/employee/components/leaveList.blade.php ENDPATH**/ ?>