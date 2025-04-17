<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Contributions</title>
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

                <div class="card my-4 mx-3">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">Employee Contributions</h5>
                    </div>
                    <div class="card-body">
                        <!-- Contribution Tabs -->
                        <?php
                        $activeType = request('contribution_type', 'SSS'); // default to SSS
                        ?>

                        <ul class="nav nav-tabs" id="contributionTabs" role="tablist">
                            <li class="nav-item" role="presentation">
                                <button class="nav-link <?php echo e($activeType === 'SSS' ? 'active' : ''); ?>" id="sss-tab"
                                    data-bs-toggle="tab" data-bs-target="#sss" type="button" role="tab"
                                    aria-controls="sss" aria-selected="<?php echo e($activeType === 'SSS' ? 'true' : 'false'); ?>">SSS</button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link <?php echo e($activeType === 'PAG-IBIG' ? 'active' : ''); ?>" id="pagibig-tab"
                                    data-bs-toggle="tab" data-bs-target="#pagibig" type="button" role="tab"
                                    aria-controls="pagibig" aria-selected="<?php echo e($activeType === 'PAG-IBIG' ? 'true' : 'false'); ?>">PAG-IBIG</button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link <?php echo e($activeType === 'TIN' ? 'active' : ''); ?>" id="tin-tab"
                                    data-bs-toggle="tab" data-bs-target="#tin" type="button" role="tab"
                                    aria-controls="tin" aria-selected="<?php echo e($activeType === 'TIN' ? 'true' : 'false'); ?>">TIN</button>
                            </li>
                        </ul>


                        <div class="tab-content" id="contributionTabsContent">
                            <!-- SSS Contributions -->
                            <div class="tab-pane fade <?php echo e($activeType === 'SSS' ? 'show active' : ''); ?>" id="sss" role="tabpanel" aria-labelledby="sss-tab">
                                <?php echo $__env->make('pages.hr.components.contribution_sss_table', ['sssContributions' => $sssContributions], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
                            </div>

                            <!-- PAG-IBIG Contributions -->
                            <div class="tab-pane fade <?php echo e($activeType === 'PAG-IBIG' ? 'show active' : ''); ?>" id="pagibig" role="tabpanel" aria-labelledby="pagibig-tab">
                                <?php echo $__env->make('pages.hr.components.contribution_pag-ibig_table', ['pagibigContributions' => $pagibigContributions], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
                            </div>

                            <!-- TIN Contributions -->
                            <div class="tab-pane fade <?php echo e($activeType === 'TIN' ? 'show active' : ''); ?>" id="tin" role="tabpanel" aria-labelledby="tin-tab">
                                <?php echo $__env->make('pages.hr.components.contribution_tin_table', ['tinContributions' => $tinContributions], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const tabs = document.querySelectorAll('#contributionTabs .nav-link');

            tabs.forEach(tab => {
                tab.addEventListener('click', function(e) {
                    e.preventDefault();
                    const type = this.textContent.trim();
                    const url = new URL(window.location.href);
                    url.searchParams.set('contribution_type', type);
                    window.location.href = url.toString();
                });
            });
        });
    </script>
</body>

</html><?php /**PATH C:\Projects\hris\hris-app\resources\views/pages/employee/my_contribution.blade.php ENDPATH**/ ?>