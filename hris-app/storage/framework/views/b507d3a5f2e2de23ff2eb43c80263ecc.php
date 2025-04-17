<!-- The Form (Initially Hidden) -->
<form method="POST" action="<?php echo e(route('addEmployee.store')); ?>" enctype="multipart/form-data" id="employeeForm" style="display: none;" class="container mt-4">
    <?php echo csrf_field(); ?>
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="card-title my-3">EMPLOYEE INFORMATION</h5>
            <button type="button" class="btn-close" onclick="$('#employeeForm').hide(); $('#employeeForm')[0].reset()"></button>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-1 mb-3">
                    <label for="empPrefix" class="form-label">Prefix</label>
                    <input type="text" class="form-control" id="empPrefix" name="empPrefix" value="<?php echo e(old('empPrefix')); ?>">
                </div>


                <div class="col mb-3">
                    <label for="empFname" class="form-label">First Name</label>
                    <input type="text" class="form-control" id="empFname" name="empFname" value="<?php echo e(old('empFname')); ?>" required>
                </div>

                <div class="col mb-3">
                    <label for="empMname" class="form-label">Middle Name</label>
                    <input type="text" class="form-control" id="empMname" name="empMname" value="<?php echo e(old('empMname')); ?>">
                </div>

                <div class="col mb-3">
                    <label for="empLname" class="form-label">Last Name</label>
                    <input type="text" class="form-control" id="empLname" name="empLname" value="<?php echo e(old('empLname')); ?>" required>
                </div>
                <div class="col-1 mb-3">
                    <label for="empSuffix" class="form-label">Suffix</label>
                    <input type="text" class="form-control" id="empSuffix" name="empSuffix" value="<?php echo e(old('empSuffix')); ?>">
                </div>
            </div>

            <div class="row">

                <div class="col-4 mb-3">
                    <label for="photo" class="form-label">Profile Picture</label>

                    <?php if(!empty($employee->photo)): ?>
                    <?php
                    $isExternal = Str::startsWith($employee->photo, ['http://', 'https://']);
                    ?>

                    <div class="mb-2">
                        <img src="<?php echo e($isExternal ? $employee->photo : asset('storage/' . $employee->photo)); ?>"
                            alt="Employee Photo"
                            width="100"
                            height="100"
                            class="rounded-circle border">
                    </div>
                    <?php endif; ?>

                    <input class="form-control" type="file" id="photo" name="photo">
                </div>


                <div class="col-4 mb-3">
                    <label class="form-label">Gender</label>
                    <div class="d-flex align-items-center">
                        <div class="form-check me-3">
                            <input class="form-check-input" type="radio" id="male" name="empGender" value="male" <?php echo e(old('empGender', $employee->empGender ?? '') == 'male' ? 'checked' : ''); ?>>
                            <label class="form-check-label" for="male">Male</label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" id="female" name="empGender" value="female" <?php echo e(old('empGender', $employee->empGender ?? '') == 'female' ? 'checked' : ''); ?>>
                            <label class="form-check-label" for="female">Female</label>
                        </div>
                    </div>
                </div>



                <div class="col-4 mb-3">
                    <label for="empBirthdate" class="form-label">Birthdate</label>
                    <input type="text" class="form-control datepicker" id="empBirthdate" name="empBirthdate" value="<?php echo e(old('empBirthdate')); ?>" readonly>
                </div>
            </div>

            <div class="mb-3">
                <label for="address" class="form-label">Address</label>
                <input type="text" class="form-control" id="address" name="address" value="<?php echo e(old('address')); ?>">
            </div>

            <div class="row">
                <div class="col-md-4 mb-3">
                    <label for="province" class="form-label">Province</label>
                    <input type="text" class="form-control" id="province" name="province" value="<?php echo e(old('province')); ?>">
                </div>

                <div class="col-md-4 mb-3">
                    <label for="city" class="form-label">City</label>
                    <input type="text" class="form-control" id="city" name="city" value="<?php echo e(old('city')); ?>">
                </div>

                <div class="col-md-4 mb-3">
                    <label for="barangay" class="form-label">Barangay</label>
                    <input type="text" class="form-control" id="barangay" name="barangay" value="<?php echo e(old('barangay')); ?>">
                </div>
            </div>

            <div class="row">
                <div class="col-md-4 mb-3">
                    <label for="empSSSNum" class="form-label">SSS Number</label>
                    <input type="text" class="form-control" id="empSSSNum" name="empSSSNum" value="<?php echo e(old('empSSSNum')); ?>">
                </div>

                <div class="col-md-4 mb-3">
                    <label for="empTinNum" class="form-label">TIN Number</label>
                    <input type="text" class="form-control" id="empTinNum" name="empTinNum" value="<?php echo e(old('empTinNum')); ?>">
                </div>

                <div class="col-md-4 mb-3">
                    <label for="empPagIbigNum" class="form-label">Pag-Ibig Number</label>
                    <input type="text" class="form-control" id="empPagIbigNum" name="empPagIbigNum" value="<?php echo e(old('empPagIbigNum')); ?>">
                </div>
            </div>

            <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                <button type="submit" class="btn btn-primary me-md-2">Submit</button>
                <button type="button" class="btn btn-secondary" onclick="$('#employeeForm').hide(); $('#employeeForm')[0].reset()">Cancel</button>
            </div>
        </div>
    </div>
</form><?php /**PATH C:\Projects\hris\hris-app\resources\views/pages/hr/components/add_employee_card.blade.php ENDPATH**/ ?>