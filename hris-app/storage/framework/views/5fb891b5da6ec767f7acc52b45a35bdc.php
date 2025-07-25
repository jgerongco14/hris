<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Activity Logs</title>
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
                <div class="card mt-4 mx-3">
                    <div class="card-header">
                        <h3 class="card-title text-center">Activity Logs</h3>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped table-hover">
                                <thead class="text-center align-middle">
                                    <tr>
                                        <th class="col-1">No</th>
                                        <th class="col-1">Action</th>
                                        <th class="col-5">Description</th>
                                        <th class="col-1">IP Address</th>
                                        <th class="col-3">Device</th>
                                        <th class="col-1">Date & Time</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php $__currentLoopData = $logs; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $log): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <tr class="align-middle">
                                    <td class="col-1 text-center"><?php echo e($loop->iteration + ($logs->currentPage() - 1) * $logs->perPage()); ?></td>
                                        <td class="col-1 text-center"><?php echo e($log->action); ?></td>
                                        <td class="col-5"><?php echo e($log->description); ?></td>
                                        <td class="col-1 text-center"><?php echo e($log->ip_address); ?></td>
                                        <td class="col-3"><?php echo e($log->user_agent); ?></td>
                                        <td class="col-1 text-center"><?php echo e($log->created_at->format('Y-m-d h:i A')); ?></td>
                                    </tr>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    <?php if($logs->isEmpty()): ?>
                                    <tr class="text-center">
                                        <td colspan="6">No activity logs found.</td>
                                    </tr>
                                    <?php endif; ?>
                                </tbody>
                            </table>

                            <!-- Pagination -->
                            <?php if($logs->hasPages()): ?>
                            <div class="d-flex flex-column align-items-center mt-4 gap-2">
                                <div>
                                    <?php echo e($logs->links('pagination::bootstrap-5')); ?>

                                </div>
                                <div class="text-muted small">
                                    Showing <?php echo e($logs->firstItem()); ?> to <?php echo e($logs->lastItem()); ?> of <?php echo e($logs->total()); ?> results
                                </div>
                            </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>

</html><?php /**PATH C:\Projects\hris\hris-app\resources\views/pages/admin/monitoring/logs.blade.php ENDPATH**/ ?>