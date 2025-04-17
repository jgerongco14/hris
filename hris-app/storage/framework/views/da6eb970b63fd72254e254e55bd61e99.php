<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profile</title>
    <?php echo app('Illuminate\Foundation\Vite')(['resources/sass/app.scss', 'resources/js/app.js', 'resources/css/profile.css']); ?>
    <link href="https://cdn.jsdelivr.net/npm/remixicon@2.5.0/fonts/remixicon.css" rel="stylesheet">
</head>

<body>
    <div class="container-fluid">
        <div class="row">
            <!-- Navigation Section -->
            <div class="col-2 p-0">
                <div class="vh-100 position-sticky top-0">
                    <?php echo $__env->make('components.sidebar', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
                </div>
            </div>

            <!-- Main Content Section -->
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

                <!-- Profile Section -->
                <?php if (isset($component)) { $__componentOriginal1e62dc2758594845a8da9c7b37d36d28 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal1e62dc2758594845a8da9c7b37d36d28 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.myProfile','data' => []] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('myProfile'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal1e62dc2758594845a8da9c7b37d36d28)): ?>
<?php $attributes = $__attributesOriginal1e62dc2758594845a8da9c7b37d36d28; ?>
<?php unset($__attributesOriginal1e62dc2758594845a8da9c7b37d36d28); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal1e62dc2758594845a8da9c7b37d36d28)): ?>
<?php $component = $__componentOriginal1e62dc2758594845a8da9c7b37d36d28; ?>
<?php unset($__componentOriginal1e62dc2758594845a8da9c7b37d36d28); ?>
<?php endif; ?>

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

                <!-- Profile Information -->
                <form action="<?php echo e(route('profile.update')); ?>" method="POST" enctype="multipart/form-data">
                    <?php echo csrf_field(); ?>
                    <?php echo method_field('PUT'); ?>
                    <?php if($employee): ?>
                    <div class="card mt-3 mx-3">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <h5>Profile Information</h5>
                            <div class="d-flex justify-content-end">
                                <a href="" class="btn btn-primary">Edit Profile</a>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-2">
                                    <h5>ID Number</h5>
                                    <span class="profile-text" id="idText"><?php echo e($employee->empID ?? ''); ?></span>
                                    <input type="text" class="form-control profile-input d-none" id="idInput" name="empID" value="<?php echo e($employee->empID ?? ''); ?>" readonly>
                                </div>
                            </div>
                            <div class="row my-3">
                                <div class="col">
                                    <h5>Prefix</h5>
                                    <span class="profile-text" id="prefixText"><?php echo e($employee->empPrefix ?? ''); ?></span>
                                    <input type="text" class="form-control profile-input d-none" id="prefixInput" name="empPrefix" value="<?php echo e($employee->empPrefix ?? ''); ?>">
                                </div>
                                <!-- Example for First Name -->
                                <div class="col">
                                    <h5>First Name</h5>
                                    <span class="profile-text" id="firstNameText"><?php echo e($employee->empFname ?? ''); ?></span>
                                    <input type="text" class="form-control profile-input d-none" id="firstNameInput" name="empFirstName" value="<?php echo e($employee->empFname ?? ''); ?>">
                                </div>
                                <div class="col">
                                    <h5>Middle Name</h5>
                                    <span class="profile-text" id="middleNameText"><?php echo e($employee->empMname ?? ''); ?></span>
                                    <input type="text" class="form-control profile-input d-none" id="middleNameInput" name="empMiddleName" value="<?php echo e($employee->empMname ?? ''); ?>">
                                </div>
                                <div class="col">
                                    <h5>Last Name</h5>
                                    <span class="profile-text" id="lastNameText"><?php echo e($employee->empLname ?? ''); ?></span>
                                    <input type="text" class="form-control profile-input d-none" id="lastNameInput" name="empLastName" value="<?php echo e($employee->empLname ?? ''); ?>">
                                </div>
                                <div class="col">
                                    <h5>Suffix</h5>
                                    <span class="profile-text" id="suffixText"><?php echo e($employee->empSuffix ?? ''); ?></span>
                                    <input type="text" class="form-control profile-input d-none" id="suffixInput" name="empSuffix" value="<?php echo e($employee->empSuffix ?? ''); ?>">
                                </div>
                            </div>
                            <div class="row">
                                <div class="col">
                                    <h5>Gender</h5>
                                    <span class="profile-text" id="genderText"><?php echo e($employee->empGender ?? ''); ?></span>
                                    <div class="form-control profile-input d-none">
                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input" type="radio" name="empGender" id="genderMale" value="Male"
                                                <?php echo e((isset($employee->empGender) && $employee->empGender === 'Male') ? 'checked' : ''); ?>>
                                            <label class="form-check-label" for="genderMale">Male</label>
                                        </div>
                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input" type="radio" name="empGender" id="genderFemale" value="Female"
                                                <?php echo e((isset($employee->empGender) && $employee->empGender === 'Female') ? 'checked' : ''); ?>>
                                            <label class="form-check-label" for="genderFemale">Female</label>
                                        </div>
                                    </div>
                                </div>
                                <div class="col">
                                    <h5>Birthday</h5>
                                    <span class="profile-text" id="birthdayText"><?php echo e($employee->empBirthdate ? \Carbon\Carbon::parse($employee->empBirthdate)->format('F d, Y') : ''); ?></span>
                                    <input type="date" class="form-control profile-input d-none" id="birthdayInput" name="empBdate" value="<?php echo e($employee->empBirthdate ? \Carbon\Carbon::parse($employee->empBirthdate)->format('Y-m-d') : ''); ?>">
                                </div>
                            </div>
                            <div class="row my-3">
                                <div class="col">
                                    <h5>Address</h5>
                                    <span class="profile-text" id="addressText"><?php echo e($employee->address ?? ''); ?></span>
                                    <input type="text" class="form-control profile-input d-none" id="addressInput" name="empAddress" value="<?php echo e($employee->address ?? ''); ?>">
                                </div>
                            </div>
                            <div class="row my-3">
                                <div class="col">
                                    <h5>Province</h5>
                                    <span class="profile-text" id="provinceText"><?php echo e($employee->province ?? ''); ?></span>
                                    <input type="text" class="form-control profile-input d-none" id="provinceInput" name="empProvince" value="<?php echo e($employee->province ?? ''); ?>">
                                </div>
                                <div class="col">
                                    <h5>City</h5>
                                    <span class="profile-text" id="cityText"><?php echo e($employee->city ?? ''); ?></span>
                                    <input type="text" class="form-control profile-input d-none" id="cityInput" name="empCity" value="<?php echo e($employee->city ?? ''); ?>">
                                </div>
                                <div class="col">
                                    <h5>Barangay</h5>
                                    <span class="profile-text" id="barangayText"><?php echo e($employee->barangay ?? ''); ?></span>
                                    <input type="text" class="form-control profile-input d-none" id="barangayInput" name="empBarangay" value="<?php echo e($employee->barangay ?? ''); ?>">
                                </div>
                            </div>
                            <div class="row my-3">
                                <div class="col">
                                    <h5>SSS</h5>
                                    <span class="profile-text" id="sssText"><?php echo e($employee->empSSSNum ?? ''); ?></span>
                                    <input type="text" class="form-control profile-input d-none" id="sssInput" name="empSSS" value="<?php echo e($employee->empSSSNum ?? ''); ?>">
                                </div>
                                <div class="col">
                                    <h5>PAG-IBIG</h5>
                                    <span class="profile-text" id="pagibigText"><?php echo e($employee->empPagIbigNum ?? ''); ?></span>
                                    <input type="text" class="form-control profile-input d-none" id="pagibigInput" name="empPagibig" value="<?php echo e($employee->empPagIbigNum ?? ''); ?>">
                                </div>
                                <div class="col">
                                    <h5>TIN</h5>
                                    <span class="profile-text" id="tinText"><?php echo e($employee->empTinNum ?? ''); ?></span>
                                    <input type="text" class="form-control profile-input d-none" id="tinInput" name="empTIN" value="<?php echo e($employee->empTinNum ?? ''); ?>">
                                </div>
                            </div>
                        </div>
                    </div>
                    <?php endif; ?>
                </form>
                <!-- End of Profile Information -->
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const editBtn = document.querySelector('.btn-primary');
            const saveBtn = document.createElement('button');
            saveBtn.type = 'submit';
            saveBtn.className = 'btn btn-success ms-2';
            saveBtn.textContent = 'Save';
            let isEditing = false;

            editBtn.addEventListener('click', (e) => {
                e.preventDefault();

                const textFields = document.querySelectorAll('.profile-text');
                const inputFields = document.querySelectorAll('.profile-input');

                if (!isEditing) {
                    textFields.forEach(el => el.classList.add('d-none'));
                    inputFields.forEach(el => el.classList.remove('d-none'));
                    editBtn.textContent = 'Cancel';
                    editBtn.classList.remove('btn-primary');
                    editBtn.classList.add('btn-secondary');
                    editBtn.insertAdjacentElement('afterend', saveBtn);
                    isEditing = true;
                } else {
                    textFields.forEach(el => el.classList.remove('d-none'));
                    inputFields.forEach(el => el.classList.add('d-none'));
                    editBtn.textContent = 'Edit Profile';
                    editBtn.classList.remove('btn-secondary');
                    editBtn.classList.add('btn-primary');
                    saveBtn.remove();
                    isEditing = false;
                }
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

</html><?php /**PATH C:\Projects\hris\hris-app\resources\views/pages/profile/userProfile.blade.php ENDPATH**/ ?>