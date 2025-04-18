<!-- The Form (Initially Hidden) -->
<form method="POST" action="<?php echo e(route('addEmployee.store')); ?>" enctype="multipart/form-data" id="employeeForm" style="display: none;" class="mt-3 mx-3">
    <?php echo csrf_field(); ?>
    <input type="hidden" name="_method" id="formMethod" value="POST">
    <div class="card ">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0">Add Employee</h5>
            <button type="button" class="btn-close" onclick="$('#employeeForm').hide(); $('#employeeForm')[0].reset()"></button>
        </div>
        <div class="card-body">

            <div class="row">
                <div class="col mb-3">
                    <label for="empPersonelStatus" class="form-label">Personnel Status</label>
                    <select name="empPersonelStatus" id="empPersonelStatus" class="form-select" required>
                        <option value="" disabled <?php echo e(old('empPersonelStatus') ? '' : 'selected'); ?>>Select Personnel Status</option>
                        <option value="Full-Time" <?php echo e(old('empPersonelStatus') == 'Full-Time' ? 'selected' : ''); ?>>Full-Time</option>
                        <option value="Part-Time" <?php echo e(old('empPersonelStatus') == 'Part-Time' ? 'selected' : ''); ?>>Part-Time</option>
                        <option value="Contractual" <?php echo e(old('empPersonelStatus') == 'Contractual' ? 'selected' : ''); ?>>Contractual</option>
                        <option value="Probationary" <?php echo e(old('empPersonelStatus') == 'Probationary' ? 'selected' : ''); ?>>Probationary</option>
                    </select>
                </div>

                <div class="col mb-3">
                    <label for="empEmployeerName" class="form-label">Name of the Employer</label>
                    <input type="text" class="form-control" id="empEmployeerName" name="empEmployeerName" value="<?php echo e(old('empEmployeerName')); ?>">
                </div>

                <div class="col mb-3">
                    <label for="empEmployeerAddress" class="form-label">Employer Address</label>
                    <input type="text" class="form-control" id="empEmployeerAddress" name="empEmployeerAddress" value="<?php echo e(old('empEmployeerAddress')); ?>">
                </div>


                <div class="col mb-3">
                    <label for="empDateHired" class="form-label">Date Hired</label>
                    <input type="date" class="form-control" id="empDateHired" name="empDateHired" value="<?php echo e(old('empDateHired', $employee->empDateHired ?? '')); ?>">
                </div>
                <div class="col mb-3">
                    <label for="empDateResigned" class="form-label">Date Resigned</label>
                    <input type="date" class="form-control" id="empDateResigned" name="empDateResigned" value="<?php echo e(old('empDateResigned', $employee->empDateResigned ?? '')); ?>">
                </div>

            </div>


            <div class="row">

                <div class="col-1 mb-3">
                    <label for="empID" class="form-label">Emp ID</label>
                    <input type="text" class="form-control" id="empID" name="empID" value="<?php echo e(old('empID')); ?>" required>
                </div>
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

                <div class="col mb-3">
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


                <div class="col mb-3">
                    <label class="form-label">Gender</label>
                    <div class="d-flex align-items-center">
                        <div class="form-check me-3">
                            <input class="form-check-input" type="radio" id="Male" name="empGender" value="Male"
                                <?php echo e(old('empGender', $employee->empGender ?? '') === 'Male' ? 'checked' : ''); ?>>
                            <label class="form-check-label" for="Male">Male</label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" id="Female" name="empGender" value="Female"
                                <?php echo e(old('empGender', $employee->empGender ?? '') === 'Female' ? 'checked' : ''); ?>>
                            <label class="form-check-label" for="Female">Female</label>
                        </div>
                    </div>
                </div>



                <div class="col mb-3">
                    <label for="empBirthdate" class="form-label">Birthdate</label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="ri-calendar-line"></i></span>
                        <input type="text" class="form-control datepicker" id="empBirthdate" name="empBirthdate" value="<?php echo e(old('empBirthdate')); ?>" readonly required>
                    </div>
                </div>

                <div class="col">
                    <label for="empContactNo" class="form-label">Contact Number</label>
                    <input type="text" class="form-control" id="empContactNo" name="empContactNo" value="<?php echo e(old('empContactNo')); ?>">
                </div>
                <div class="col">
                    <label for="empCivilStatus" class="form-label">Civil Status</label>
                    <select name="empCivilStatus" id="empCivilStatus" class="form-select">
                        <option value="" disabled <?php echo e(old('empCivilStatus') ? '' : 'selected'); ?>>Select Civil Status</option>
                        <option value="Single" <?php echo e(old('empCivilStatus') == 'Single' ? 'selected' : ''); ?>>Single</option>
                        <option value="Married" <?php echo e(old('empCivilStatus') == 'Married' ? 'selected' : ''); ?>>Married</option>
                        <option value="Widowed" <?php echo e(old('empCivilStatus') == 'Widowed' ? 'selected' : ''); ?>>Widowed</option>
                        <option value="Divorced" <?php echo e(old('empCivilStatus') == 'Divorced' ? 'selected' : ''); ?>>Divorced</option>
                        <option value="Separated" <?php echo e(old('empCivilStatus') == 'Separated' ? 'selected' : ''); ?>>Separated</option>
                    </select>
                </div>

                <div class="col">
                    <label for="empBloodType" class="form-label">Blood Type</label>
                    <select name="empBloodType" id="empBloodType" class="form-select">
                        <option value="" disabled <?php echo e(old('empBloodType') ? '' : 'selected'); ?>>Select Blood Type</option>
                        <option value="A+" <?php echo e(old('empBloodType') == 'A+' ? 'selected' : ''); ?>>A+</option>
                        <option value="A-" <?php echo e(old('empBloodType') == 'A-' ? 'selected' : ''); ?>>A-</option>
                        <option value="B+" <?php echo e(old('empBloodType') == 'B+' ? 'selected' : ''); ?>>B+</option>
                        <option value="B-" <?php echo e(old('empBloodType') == 'B-' ? 'selected' : ''); ?>>B-</option>
                        <option value="O+" <?php echo e(old('empBloodType') == 'O+' ? 'selected' : ''); ?>>O+</option>
                        <option value="O-" <?php echo e(old('empBloodType') == 'O-' ? 'selected' : ''); ?>>O-</option>
                        <option value="AB+" <?php echo e(old('empBloodType') == 'AB+' ? 'selected' : ''); ?>>AB+</option>
                        <option value="AB-" <?php echo e(old('empBloodType') == 'AB-' ? 'selected' : ''); ?>>AB-</option>
                    </select>
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

            <div class="row my-4">
                <div class="col">
                    <label for="empEmergencyContactName" class="form-label">Contact Name</label>
                    <input type="text" class="form-control" id="empEmergencyContactName" name="empEmergencyContactName" value="<?php echo e(old('empEmergencyContactName')); ?>">
                </div>
                <div class="col">
                    <label for="empEmergencyContactNo" class="form-label">Contact Number</label>
                    <input type="text" class="form-control" id="empEmergencyContactNo" name="empEmergencyContactNo" value="<?php echo e(old('empEmergencyContactNo')); ?>">
                </div>
                <div class="col">
                    <label for="empEmergencyContactAddress" class="form-label">Address</label>
                    <input type="text" class="form-control" id="empEmergencyContactAddress" name="empEmergencyContactAddress" value="<?php echo e(old('empEmergencyContactAddress')); ?>">
                </div>
            </div>

            <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                <button type="submit" class="btn btn-primary me-md-2">Submit</button>
                <button type="button" class="btn btn-secondary" onclick="$('#employeeForm').hide(); $('#employeeForm')[0].reset()">Cancel</button>
            </div>
        </div>
    </div>
</form>

<!-- Add Employee Button -->
<div class="row d-flex justify-content-between align-items-center">
    <div class="col mx-3">
        <div class="dropdown my-4">
            <button class="btn btn-primary dropdown-toggle" type="button" id="addEmployeeBtn" data-bs-toggle="dropdown" aria-expanded="false">
                Add Employee
            </button>
            <ul class="dropdown-menu" aria-labelledby="addEmployeeBtn">
                <li><a class="dropdown-item" href="#" id="addIndividualBtn">Add Individual Employee</a></li>
                <li><a class="dropdown-item" href="#" data-bs-toggle="modal" data-bs-target="#addEmployee">Import Employee (CSV/Excel)</a></li>
            </ul>
        </div>
    </div>

    <div class="col">
        <!-- Search Form -->
        <form method="GET" action="<?php echo e(route('employee_management')); ?>" class="d-flex align-items-end gap-3 my-4" id="filterForm">
            <!-- Filter by Position -->
            <div class="mb-0">
                <label for="position" class="form-label visually-hidden">Filter by Position</label>
                <select name="position" id="position" class="form-select" onchange="document.getElementById('filterForm').submit()">
                    <option value="">All Positions</option>
                    <?php $__currentLoopData = $positions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $position): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <option value="<?php echo e($position->positionID); ?>" <?php echo e(request('position') == $position->positionID ? 'selected' : ''); ?>>
                        <?php echo e($position->positionName); ?>

                    </option>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </select>

            </div>

            <!-- Search by Name -->
            <div class="mb-0">
                <label for="search" class="form-label visually-hidden">Search by Name</label>
                <input type="text" name="search" id="search" class="form-control" placeholder="Search by Employee Name" value="<?php echo e(request('search')); ?>">
            </div>

            <!-- Search Button -->
            <button type="submit" class="btn btn-primary d-flex align-items-center">
                <i class="ri-search-line"></i> <!-- Search Icon -->
            </button>

            <!-- Reset Button -->
            <a href="<?php echo e(route('employee_management')); ?>" class="btn btn-secondary d-flex align-items-center ms-2">
                <i class="ri-restart-line"></i> <!-- Reset Icon -->
            </a>
        </form>
    </div>

</div>







<!-- Employee List Section -->
<div class="card mx-3">
    <div class="card-header">
        <h3 class="card-title text-center">List of Employees</h3>
    </div>
    <div class="card-body">
        <div class="row my-4">
            <div class="col">
                <table class="table table-bordered">
                    <thead>
                        <tr class="text-center">
                            <th class="align-middle">EmpID</th>
                            <th class="align-middle">Full Name</th>
                            <th class="align-middle">Position</th>
                            <th class="align-middle">Department/Office</th>
                            <th class="align-middle">Benefits</th>
                            <th class="align-middle">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if($employees->isEmpty()): ?>
                        <tr>
                            <td colspan="5" class="text-center">No employees found.</td>
                        </tr>
                        <?php else: ?>
                        <?php $__currentLoopData = $employees; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $employee): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <tr>
                            <td class="text-center align-middle d-flex flex-column">
                                <?php echo e($employee->empID); ?>


                                <?php
                                $rawStatus = strtolower($employee->status ?? 'active'); // default to 'active' if null
                                $badgeClass = $rawStatus === 'resigned' ? 'bg-danger' : 'bg-success';
                                $statusLabel = ucfirst($rawStatus);
                                ?>
                                <span class="badge rounded <?php echo e($badgeClass); ?>"><?php echo e($statusLabel); ?></span>
                            </td>
                            <td class="align-middle">
                                <div class="d-flex align-items-center gap-2">
                                    <?php
                                    $employeePhoto = $employee->photo ?? null;
                                    $isExternal = $employeePhoto && Str::startsWith($employeePhoto, ['http://', 'https://']);
                                    ?>


                                    <?php if($employeePhoto): ?>
                                    <img
                                        src="<?php echo e($isExternal ? $employeePhoto : asset('storage/employee_photos/' . $employee->photo)); ?>"
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
                            <td class="align-middle">
                                <?php $__empty_1 = true; $__currentLoopData = $employee->assignments; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $assignment): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                <div>
                                    <strong><?php echo e($assignment->position->positionName); ?></strong><br>
                                    <small class="text-muted">
                                        <?php echo e($assignment->empAssAppointedDate); ?>

                                        to
                                        <?php echo e($assignment->empAssEndDate ?? 'Present'); ?>

                                    </small>
                                </div>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                <span class="text-muted">Unassigned</span>
                                <?php endif; ?>
                            </td>
                            <td class="align-middle">
                                <?php $__empty_1 = true; $__currentLoopData = $employee->assignments->unique(function ($assignment) {
                                return $assignment->departmentCode . $assignment->officeCode . $assignment->programCode . $assignment->empHead;
                                })->sortByDesc('assignDate'); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $assignment): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                <div>
                                    <?php if($assignment->departmentCode): ?>
                                    <strong>Department:</strong> <?php echo e($assignment->department->departmentName ?? 'N/A'); ?><br>
                                    <?php endif; ?>
                                    <?php if($assignment->officeCode): ?>
                                    <strong>Office:</strong> <?php echo e($assignment->office->officeName ?? 'N/A'); ?><br>
                                    <?php endif; ?>
                                    <?php if($assignment->programCode): ?>
                                    <strong>Program:</strong> <?php echo e($assignment->program->programName ?? 'N/A'); ?><br>
                                    <?php endif; ?>
                                    <?php if($assignment->empHead): ?>
                                    <strong>Head of the Office:</strong> <?php echo e($assignment->empHead == 1 ? 'Yes' : 'No'); ?>

                                    <?php endif; ?>
                                </div>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                <span class="text-muted">Not Belong to any Department or Office</span>
                                <?php endif; ?>
                            </td>
                            <td class="align-middle">
                                <ul class="list-unstyled mb-0">
                                    <li>SSS: <?php echo e($employee->empSSSNum ?? 'N/A'); ?></li>
                                    <li>TIN: <?php echo e($employee->empTinNum ?? 'N/A'); ?></li>
                                    <li>Pag-Ibig: <?php echo e($employee->empPagIbigNum ?? 'N/A'); ?></li>
                                </ul>
                            </td>
                            <td class="align-middle text-center">
                                <!-- Updated edit button -->
                                <button class="btn btn-sm btn-primary mx-1" onclick="editEmployee('<?php echo e($employee->id); ?>')">
                                    <i class="ri-pencil-line"></i>
                                </button>

                                <!-- Assign Position Button -->
                                <button class="btn btn-sm btn-success mx-1" data-bs-toggle="modal" data-bs-target="#assignModal_<?php echo e($employee->empID); ?>">
                                    <i class="ri-user-add-line"></i>
                                </button>

                                <form action="<?php echo e(route('employee.destroy', $employee->id)); ?>" method="POST" onsubmit="return confirm('Are you sure you want to delete this employee?')" style="display: inline;">
                                    <?php echo csrf_field(); ?>
                                    <?php echo method_field('DELETE'); ?>
                                    <button type="submit" class="btn btn-sm btn-danger mx-1">
                                        <i class="ri-delete-bin-line"></i>
                                    </button>
                                </form>

                            </td>
                        </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        <?php endif; ?>
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
        </div>
    </div>
</div>



<!-- Choose Update Type Modal -->
<div class="modal fade" id="editChoiceModal" tabindex="-1" aria-labelledby="editChoiceLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editChoiceLabel">Edit Employee</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body text-center">
                <p>What would you like to update?</p>
                <button class="btn btn-primary m-2" id="editInfoBtn">Employee Information</button>
                <button class="btn btn-secondary m-2" id="editAssignmentBtn">Assignment</button>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const empPersonelStatus = document.getElementById('empPersonelStatus');
        const employerName = document.getElementById('empEmployerName');
        const employerAddress = document.getElementById('empEmployerAddress');

        function toggleEmployerFields() {
            const isPartTime = empPersonelStatus.value === 'Part-Time';

            employerName.required = isPartTime;
            employerAddress.required = isPartTime;

            if (isPartTime) {
                employerName.closest('.col').querySelector('label').innerHTML = 'Name of the Employer <span class="text-danger">*</span>';
                employerAddress.closest('.col').querySelector('label').innerHTML = 'Employer Address <span class="text-danger">*</span>';
            } else {
                employerName.closest('.col').querySelector('label').innerHTML = 'Name of the Employer';
                employerAddress.closest('.col').querySelector('label').innerHTML = 'Employer Address';
            }
        }

        personnelStatus.addEventListener('change', toggleEmployerFields);

        // Trigger once on load
        toggleEmployerFields();
    });
</script><?php /**PATH C:\Projects\hris\hris-app\resources\views/pages/hr/components/employee_list.blade.php ENDPATH**/ ?>