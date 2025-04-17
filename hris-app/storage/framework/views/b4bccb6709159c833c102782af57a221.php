<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Attendance</title>
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
                        <div class="row align-items-end">
                            <!-- Date Range Picker -->
                            <div class="col-3 my-3">
                                <form method="GET" id="filterForm" class="d-flex gap-2 mb-3">
                                    <div class="input-group">
                                        <button class="btn btn-outline-secondary" type="button" id="open_datepicker">
                                            <i class="ri-calendar-line"></i>
                                        </button>
                                        <input type="text" name="date_range" class="form-control" id="date_range" placeholder="Filter by date range" value="<?php echo e(request('date_range')); ?>">
                                    </div>
                                </form>
                            </div>
                        </div>

                        <table class="table table-bordered text">
                            <thead class="text-center">
                                <tr>
                                    <th>Attendance ID</th>
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
                                    <td class="text-center"><?php echo e(\Carbon\Carbon::parse($item->empAttDate)->format('Y-m-d')); ?></td>
                                    <td class="text-center"><?php echo e($item->empAttTimeIn); ?></td>
                                    <td class="text-center"><?php echo e($item->empAttBreakOut); ?></td>
                                    <td class="text-center"><?php echo e($item->empAttBreakIn); ?></td>
                                    <td class="text-center"><?php echo e($item->empAttTimeOut); ?></td>
                                    <td class="text-center"><?php echo e($item->empAttRemarks); ?></td>
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

        });
    </script>

</body>

</html><?php /**PATH C:\Projects\hris\hris-app\resources\views/pages/employee/attendance.blade.php ENDPATH**/ ?>