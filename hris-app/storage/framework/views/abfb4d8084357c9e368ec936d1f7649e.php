<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Trainings</title>
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
                        <h3 class="text-center card-title">My Trainings</h3>
                    </div>
                    <div class="card-body p-4">
                        <?php echo $__env->make('pages.employee.components.training_modal', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addTrainingModal">
                                Add Training
                            </button>
                        </div>

                        <table class="table table-bordered text">
                            <thead class="text-center">
                                <tr>
                                    <th>Training Name</th>
                                    <th>Desciption</th>
                                    <th>Duration</th>
                                    <th>Location</th>
                                    <th>Conducted By</th>
                                    <th>Attachments</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody class="align-middle">
                                <?php $__empty_1 = true; $__currentLoopData = $trainings; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $training): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                <tr>
                                    <td><?php echo e($training->empTrainName); ?></td>
                                    <td><?php echo e($training->empTrainDescription); ?></td>
                                    <td>From: <?php echo e($training->empTrainFromDate); ?><br>To: <?php echo e($training->empTrainToDate); ?></td>
                                    <td><?php echo e($training->empTrainLocation); ?></td>
                                    <td><?php echo e($training->empTrainConductedBy); ?></td>
                                    <td class="text-center">
                                        <?php
                                        $attachments = json_decode($training->empTrainCertificate, true) ?? [];
                                        ?>

                                        <?php if(count($attachments)): ?>
                                        <?php $__currentLoopData = $attachments; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $file): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <div class="mb-1">
                                            <a href="<?php echo e(asset('storage/' . $file)); ?>" target="_blank" class="btn btn-outline-primary btn-sm">
                                                Attachment <?php echo e($index + 1); ?>

                                            </a>
                                        </div>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                        <?php else: ?>
                                        No attachment
                                        <?php endif; ?>

                                    </td>
                                    <td class="text-center">
                                        <button class="btn btn-warning btn-sm"
                                            data-id="<?php echo e($training->id); ?>"
                                            data-name="<?php echo e($training->empTrainName); ?>"
                                            data-conducted="<?php echo e($training->empTrainConductedBy); ?>"
                                            data-fromdate="<?php echo e($training->empTrainFromDate); ?>"
                                            data-todate="<?php echo e($training->empTrainToDate); ?>"
                                            data-location="<?php echo e($training->empTrainLocation); ?>"
                                            data-description="<?php echo e($training->empTrainDescription); ?>"
                                            data-attachments='<?php echo json_encode(json_decode($training->empTrainCertificate), 15, 512) ?>'
                                            data-url="<?php echo e(route('training.update', $training->id)); ?>"
                                            data-bs-toggle="modal"
                                            data-bs-target="#editTrainingModal">
                                            <i class="ri-pencil-fill"></i>
                                        </button>


                                        <form action="<?php echo e(route('training.delete', $training->id)); ?>" method="POST" class="d-inline-block">
                                            <?php echo csrf_field(); ?>
                                            <?php echo method_field('DELETE'); ?>
                                            <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure?')" title="Delete">
                                                <i class="ri-delete-bin-5-line"></i>
                                            </button>
                                        </form>

                                    </td>
                                </tr>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                <tr>
                                    <td colspan="7" class="text-center">No training records found.</td>
                                </tr>
                                <?php endif; ?>
                            </tbody>

                        </table>
                        <?php if($trainings->hasPages()): ?>
                        <div class="d-flex flex-column align-items-center mt-4 gap-2">
                            <?php echo e($trainings->links('pagination::bootstrap-5')); ?>

                            <div class="text-muted small">
                                Showing <?php echo e($trainings->firstItem()); ?> to <?php echo e($trainings->lastItem()); ?> of <?php echo e($trainings->total()); ?> results
                            </div>
                        </div>
                        <?php endif; ?>
                    </div>
                </div>

            </div>
        </div>
    </div>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const editModal = document.getElementById('editTrainingModal');
            editModal.addEventListener('show.bs.modal', function(event) {
                const button = event.relatedTarget;

                // Fetch and populate training info
                const id = button.getAttribute('data-id');
                const name = button.getAttribute('data-name');
                const conducted = button.getAttribute('data-conducted');
                const fromDate = button.getAttribute('data-fromdate');
                const toDate = button.getAttribute('data-todate');
                const location = button.getAttribute('data-location');
                const description = button.getAttribute('data-description');
                const attachments = JSON.parse(button.getAttribute('data-attachments') || '[]');
                const actionUrl = button.getAttribute('data-url');

                editModal.querySelector('#editTrainingId').value = id;
                editModal.querySelector('#editEmpTrainName').value = name;
                editModal.querySelector('#editEmpTrainConductedBy').value = conducted;
                editModal.querySelector('#editEmpTrainFromDate').value = fromDate;
                editModal.querySelector('#editEmpTrainToDate').value = toDate;
                editModal.querySelector('#editEmpTrainLocation').value = location;
                editModal.querySelector('#editEmpTrainDescription').value = description;

                const form = editModal.querySelector('#editTrainingForm');
                form.action = actionUrl;
                form.querySelector('input[name="_method"]').value = "PUT";

                const attachmentsDiv = editModal.querySelector('#existingAttachments');
                const hiddenInputsDiv = editModal.querySelector('#existingCertificatesInputs');
                attachmentsDiv.innerHTML = '';
                hiddenInputsDiv.innerHTML = '';

                attachments.forEach((file, index) => {
                    const filename = file.split('/').pop();
                    const link = `<a href="/storage/${file}" target="_blank" class="me-2">${filename}</a>`;
                    const removeBtn = `<button type="button" class="btn btn-sm btn-danger" onclick="removeAttachment('${file}')">Delete</button>`;
                    const div = document.createElement('div');
                    div.classList.add('d-flex', 'align-items-center', 'mb-1');
                    div.innerHTML = link + removeBtn;
                    div.setAttribute('data-file', file);
                    attachmentsDiv.appendChild(div);

                    const input = document.createElement('input');
                    input.type = 'hidden';
                    input.name = 'existingCertificates[]';
                    input.value = file;
                    input.setAttribute('data-file', file);
                    hiddenInputsDiv.appendChild(input);
                });
            });
        });

        function removeAttachment(filePath) {
            const attachmentDiv = document.querySelector(`#existingAttachments div[data-file="${filePath}"]`);
            const inputHidden = document.querySelector(`#existingCertificatesInputs input[data-file="${filePath}"]`);
            if (attachmentDiv) attachmentDiv.remove();
            if (inputHidden) inputHidden.remove();
        }

        function showToast(title, message, type = 'success') {
            const toastEl = document.getElementById('liveToast');
            const toastHeader = document.getElementById('toast-header');
            const toastTitle = document.getElementById('toast-title');
            const toastMessage = document.getElementById('toast-message');
            const toastIcon = document.getElementById('toast-icon');

            // Reset toast class
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

            toastHeader.className = `toast-header ${headerColors[type] || 'text-dark'}`;
            toastIcon.textContent = icons[type] || '';
            toastTitle.textContent = title;
            toastMessage.textContent = message;

            const toast = new bootstrap.Toast(toastEl, {
                delay: 5000
            });
            toast.show();
        }
    </script>
</body>

</html><?php /**PATH C:\Projects\hris\hris-app\resources\views/pages/employee/training.blade.php ENDPATH**/ ?>