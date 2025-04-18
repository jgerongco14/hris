<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Attendance Management</title>
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
            <div class="col-10 p-3 pt-0 main-content">
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

                <div class="card my-4 mx-3">
                    <div class="card-header">
                        <h3 class="card-title text-center">Attendance Records</h3>
                    </div>
                    <div class="card-body p-4">
                        <div class="row">

                            <div class="col-2">
                                <?php if($totalAbsents > 0): ?>
                                <div class="alert alert-warning d-flex justify-content-between align-items-center">
                                    <div><strong>Total Absents:</strong> <?php echo e($totalAbsents); ?></div>
                                </div>
                                <?php endif; ?>

                            </div>
                        </div>
                        <div class="row align-items-end">
                            <!-- Date Range Picker -->
                            <div class="col-8 my-3">
                                <form method="GET" id="filterForm" class="d-flex gap-2 mb-3">
                                    <div class="input-group">
                                        <button class="btn btn-outline-secondary" type="button" id="open_datepicker">
                                            <i class="ri-calendar-line"></i>
                                        </button>
                                        <input type="text" name="date_range" class="form-control" id="date_range" placeholder="Filter by date range" value="<?php echo e(request('date_range')); ?>">
                                    </div>
                                    <!-- Employee Input -->
                                    <input type="text"
                                        name="employee_name"
                                        id="employee_name_input"
                                        class="form-control"
                                        placeholder="Search employee name"
                                        value="<?php echo e(request('employee_name')); ?>">
                                    <!-- Search Button -->
                                    <button type="submit" class="btn btn-primary d-flex align-items-center">
                                        <i class="ri-search-line"></i>
                                    </button>
                                    <!-- Reset Button -->
                                    <a href="<?php echo e(route('attendance_management')); ?>" class="btn btn-secondary d-flex align-items-center ms-2">
                                        <i class="ri-restart-line"></i>
                                    </a>


                                </form>

                            </div>

                            <!-- Import Attendance Button -->
                            <?php echo $__env->make('components.import_file', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

                            <!-- Push button to the right -->
                            <div class="col-2 my-3 ms-auto">
                                <div class="mb-3">
                                    <button type="button" id="openAddAttendanceModal" class="btn btn-primary w-100" data-bs-toggle="modal" data-bs-target="#addAttendanceModal">
                                        Import Attendance
                                    </button>
                                </div>
                            </div>
                        </div>
                        <!-- Show active filters if any -->
                        <?php if(request('employee_name') || request('date_range')): ?>
                        <div class="alert alert-info d-flex justify-content-between align-items-center">
                            <div>
                                <?php if(request('employee_name')): ?>
                                <strong>Employee Name:</strong> <?php echo e(request('employee_name')); ?>

                                <?php endif; ?>
                                <?php if(request('date_range')): ?>
                                <strong class="ms-3">Date Range:</strong> <?php echo e(request('date_range')); ?>

                                <?php endif; ?>
                            </div>
                            <a href="<?php echo e(url()->current()); ?>" class="btn btn-sm btn-outline-secondary">Clear Filters</a>
                        </div>
                        <?php endif; ?>

                        <table class="table table-bordered text">
                            <!-- your existing table content -->
                        </table>

                        <table class="table table-bordered text">
                            <thead class="text-center">
                                <tr>
                                    <th>Attendance ID</th>
                                    <th>Employee Name</th>
                                    <th>Date</th>
                                    <th>Time In</th>
                                    <th>Breakout</th>
                                    <th>Break-in</th>
                                    <th>Time Out</th>
                                    <th>Remarks</th>
                                </tr>
                            </thead>
                            <tbody class="align-middle">
                                <?php $__empty_1 = true; $__currentLoopData = $attendance; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                <tr>
                                    <td class="text-center"><?php echo e($item->empAttID); ?></td>
                                    <td>
                                        <div class="d-flex align-items-center gap-2">
                                            <?php
                                            $employeePhoto = $item->employee->photo ?? null;
                                            $isExternal = $employeePhoto && Str::startsWith($employeePhoto, ['http://', 'https://']);
                                            ?>


                                            <?php if($employeePhoto): ?>
                                            <img
                                                src="<?php echo e($isExternal ? $employeePhoto : asset('storage/employee_photos/' . $item->employee->photo)); ?>"
                                                alt="Employee Photo" width="50" height="50" class="rounded-circle">

                                            <?php else: ?>
                                            <div class="no-photo bg-light rounded-circle d-flex align-items-center justify-content-center"
                                                style="width:50px; height:50px;">
                                                <i class="ri-user-line"></i>
                                            </div>
                                            <?php endif; ?>
                                            <?php echo e($item->employee->empPrefix); ?> <?php echo e($item->employee->empFname); ?> <?php echo e($item->employee->empMname); ?> <?php echo e($item->employee->empLname); ?> <?php echo e($item->employee->empSuffix); ?>

                                            <input type="hidden" name="empID[]" value="<?php echo e($item->empID); ?>">
                                        </div>
                                    </td>
                                    <td class="text-center"><?php echo e(\Carbon\Carbon::parse($item->empAttDate)->format('M d, Y')); ?></td>
                                    <td class="text-center"><?php echo e($item->empAttTimeIn); ?></td>
                                    <td class="text-center"><?php echo e($item->empAttBreakOut); ?></td>
                                    <td class="text-center"><?php echo e($item->empAttBreakIn); ?></td>
                                    <td class="text-center"><?php echo e($item->empAttTimeOut); ?></td>
                                    <td class="text-center">
                                        <?php
                                        $remarks = strtolower(trim($item->empAttRemarks));
                                        $matchingLeave = $item->leaves->first(fn($leave) =>
                                        $leave->empLeaveStartDate <= $item->empAttDate &&
                                            $leave->empLeaveEndDate >= $item->empAttDate
                                            );
                                            $leaveStatus = $matchingLeave?->status;
                                            ?>

                                            <?php if($remarks === 'absent' && $matchingLeave): ?>
                                            <div class="badge bg-danger mb-1">Absent</div>
                                            <div class="small text-start mt-1">
                                                <strong>Date Leave:</strong> <?php echo e(\Carbon\Carbon::parse($matchingLeave->empLeaveStartDate)->format('M d, Y')); ?>

                                                to <?php echo e(\Carbon\Carbon::parse($matchingLeave->empLeaveEndDate)->format('M d, Y')); ?><br>
                                                <strong>Status:</strong> <?php echo e($leaveStatus->empLSStatus ?? 'Pending'); ?><br>
                                                <strong>Pay Status:</strong> <?php echo e($leaveStatus->empPayStatus ?? '-'); ?>

                                            </div>
                                            <?php elseif($remarks === 'absent'): ?>
                                            <span class="badge bg-danger">Absent</span><br>
                                            <span class="small text-muted">No Filled for Leave.</span>
                                            <?php elseif($remarks === 'present'): ?>
                                            <span class="badge bg-success">Present</span>
                                            <?php elseif($remarks === 'undertime'): ?>
                                            <span class="badge bg-warning text-dark">Undertime</span>
                                            <?php elseif($remarks): ?>
                                            <span class="badge bg-secondary"><?php echo e(ucfirst($remarks)); ?></span>
                                            <?php else: ?>
                                            <span class="text-muted">—</span>
                                            <?php endif; ?>
                                    </td>

                                </tr>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                <tr>
                                    <td colspan="7" class="text-center">No attendance records found.</td>
                                </tr>
                                <?php endif; ?>
                            </tbody>

                        </table>

                        <?php if($attendance->hasPages()): ?>
                        <div class="d-flex flex-column align-items-center mt-4 gap-2">
                            
                            <div>
                                <?php echo e($attendance->links('pagination::bootstrap-5')); ?>

                            </div>

                            
                            <div class="text-muted small">
                                Showing <?php echo e($attendance->firstItem()); ?> to <?php echo e($attendance->lastItem()); ?> of <?php echo e($attendance->total()); ?> results
                            </div>

                        </div>
                        <?php endif; ?>

                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/litepicker/dist/litepicker.js"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const input = document.getElementById('date_range');

            const picker = new Litepicker({
                element: input,
                singleMode: false,
                format: 'YYYY-MM-DD',
                autoApply: true,
                startDate: "<?php echo e(request('date_range') ? explode(' - ', request('date_range'))[0] : date('Y-m-d')); ?>",
                endDate: "<?php echo e(request('date_range') ? explode(' - ', request('date_range'))[1] ?? explode(' - ', request('date_range'))[0] : date('Y-m-d')); ?>",

            });

            picker.on('selected', (start, end) => {
                const startDate = start.format('YYYY-MM-DD');
                const endDate = end.format('YYYY-MM-DD');

                input.value = (startDate === endDate) ?
                    startDate :
                    `${startDate} - ${endDate}`;

                document.getElementById('filterForm').submit();
            });


            document.getElementById('open_datepicker').addEventListener('click', () => {
                picker.show();
            });
            // Initialize modal
            const addAttendanceModal = new bootstrap.Modal(document.getElementById('addAttendanceModal'));

            // Open modal button
            document.getElementById('openAddAttendanceModal').addEventListener('click', function() {
                addAttendanceModal.show();
            });

        });



        document.addEventListener('DOMContentLoaded', function() {
            const input = document.getElementById('employee_name_input');
            const form = document.getElementById('filterForm');

            let typingTimer;
            const debounceDelay = 500;

            input.addEventListener('input', () => {
                clearTimeout(typingTimer);
                typingTimer = setTimeout(() => {
                    form.submit();
                }, debounceDelay);
            });

            input.addEventListener('keydown', () => {
                clearTimeout(typingTimer);
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

</html><?php /**PATH C:\Projects\hris\hris-app\resources\views/pages/hr/attendance_management.blade.php ENDPATH**/ ?>