<!DOCTYPE html>

<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Employee Management</title>
    <?php echo app('Illuminate\Foundation\Vite')(['resources/sass/app.scss', 'resources/js/app.js']); ?>
    <link href="https://cdn.jsdelivr.net/npm/remixicon@2.5.0/fonts/remixicon.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">

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

                <!-- Import Attendance Button -->
                <?php echo $__env->make('components.import_file', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>


                <?php echo $__env->make('pages.hr.components.employee_list', [
                'employees' => $employees,], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>


                <?php $__currentLoopData = $employees; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $employee): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <?php
                $assignedPositions = \App\Models\EmpAssignment::with('position')
                ->where('empID', $employee->empID)
                ->get();
                ?>

                <?php echo $__env->make('pages.hr.components.assign_position', [
                'employee' => $employee,
                'assignedPositions' => $assignedPositions,
                'departments' => $departments,
                'offices' => $offices,
                'positions' => $positions,
                'modalId' => 'assignModal_' . $employee->empID
                ], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>


            </div>
        </div>
    </div>

    <!-- Include jQuery and jQuery UI for datepicker -->
    <link rel="stylesheet" href="https://code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.min.js"></script>

    <script>
        $(document).ready(function() {


            $('#addIndividualBtn').click(function() {
                $('#employeeForm')[0].reset();
                $('#employeeForm').attr('action', '<?php echo e(route("addEmployee.store")); ?>');
                $('#formMethod').val('POST');
                $('#empID').prop('readonly', false);
                $('#employeeForm').show();
            });

            // Optional: when clicking the icon, focus the input
            $('.input-group-text').click(function() {
                $(this).siblings('input').focus();
            });


            // Initialize the datepicker
            $('.datepicker').datepicker({
                changeMonth: true,
                changeYear: true,
                yearRange: '-100:+0',
                maxDate: new Date(),
                dateFormat: 'yy-mm-dd'
            });

            // Prevent manual input for birthdate
            $('#empBirthdate').on('keydown paste', function(e) {
                e.preventDefault();
                return false;
            });

            // Handle SSS Number formatting
            $('#empSSSNum').on('input', function() {
                let value = $(this).val().replace(/\D/g, ''); // Remove non-numeric characters
                if (value.length > 2) value = value.slice(0, 2) + '-' + value.slice(2);
                if (value.length > 10) value = value.slice(0, 9) + '-' + value.slice(9);
                $(this).val(value);
            });

            // Handle TIN Number formatting
            $('#empTinNum').on('input', function() {
                let value = $(this).val().replace(/\D/g, ''); // Remove non-numeric characters
                if (value.length > 3) value = value.slice(0, 3) + '-' + value.slice(3);
                if (value.length > 6) value = value.slice(0, 6) + '-' + value.slice(6);
                $(this).val(value);
            });

            // Handle Pag-Ibig Number formatting
            $('#empPagIbigNum').on('input', function() {
                let value = $(this).val().replace(/\D/g, ''); // Remove non-numeric characters
                if (value.length > 4) value = value.slice(0, 4) + '-' + value.slice(4);
                if (value.length > 8) value = value.slice(0, 8) + '-' + value.slice(8);
                $(this).val(value);
            });
        });


        function cancelAssign() {
            $('#empAssignmentForm').hide();
            $('#assignEmpID').val('');
            $('#employeeForm').show();
        }

        document.addEventListener("DOMContentLoaded", function() {
            const form = document.getElementById("employeeForm");

            form.addEventListener("submit", function(e) {
                let isValid = true;
                let requiredFields = [
                    "empID",
                    "empFname",
                    "empLname",
                ];

                // Check if required fields are empty
                requiredFields.forEach(field => {
                    const input = document.getElementById(field);
                    if (!input.value.trim()) {
                        isValid = false;
                        // Show a toast message
                        showToast("Error", `${input.placeholder} is required.`, "danger");
                    }
                });

                // If form is invalid, prevent submission
                if (!isValid) {
                    e.preventDefault();
                }
            });


        });

        document.addEventListener('DOMContentLoaded', function() {
            const form = document.getElementById('employeeForm');
            const genderInputs = document.getElementsByName('empGender');
            const birthdateInput = document.getElementById('empBirthdate');

            form.addEventListener('submit', function(event) {
                let isValid = true;

                // Check if gender is selected
                const genderSelected = Array.from(genderInputs).some(input => input.checked);
                if (!genderSelected) {
                    isValid = false;
                    showToast('Error', 'Please select a gender.', 'danger');
                }

                // Check if birthdate has a value
                if (!birthdateInput.value) {
                    isValid = false;
                    showToast('Error', 'Please select a birthdate.', 'danger');
                }

                // Prevent form submission if validation fails
                if (!isValid) {
                    event.preventDefault();
                }
            });
        });

        function editEmployee(id) {
            fetch(`/employee/${id}/edit`)
                .then(response => response.json())
                .then(employee => {
                    // Show and configure form
                    $('#employeeForm').show();
                    $('#employeeForm')[0].reset();

                    // Set form to update mode
                    $('#employeeForm').attr('action', `/employee/${id}`);
                    $('#formMethod').val('PUT');
                    $('#empID').prop('readonly', true); // prevent changing empID

                    // Populate fields
                    $('#empID').val(employee.empID);
                    $('#empPrefix').val(employee.empPrefix);
                    $('#empFname').val(employee.empFname);
                    $('#empMname').val(employee.empMname);
                    $('#empLname').val(employee.empLname);
                    $('#empSuffix').val(employee.empSuffix);
                    $('#address').val(employee.address);
                    $('#province').val(employee.province);
                    $('#city').val(employee.city);
                    $('#barangay').val(employee.barangay);
                    $('#empSSSNum').val(employee.empSSSNum);
                    $('#empTinNum').val(employee.empTinNum);
                    $('#empPagIbigNum').val(employee.empPagIbigNum);
                    $('#empBirthdate').val(employee.empBirthdate);

                    if (employee.empGender === 'Male') {
                        $('#Male').prop('checked', true);
                    } else if (employee.empGender === 'Female') {
                        $('#Female').prop('checked', true);
                    }

                    $('#empDateHired').val(employee.empDateHired);
                    $('#empDateResigned').val(employee.empDateResigned);
                    $('#empPersonelStatus').val(employee.empPersonelStatus);
                    $('#empEmployeerName').val(employee.empEmployeerName);
                    $('#empEmployeerAddress').val(employee.empEmployeerAddress);
                    $('#empContactNo').val(employee.empContactNo);
                    $('#empCivilStatus').val(employee.empCivilStatus);
                    $('#empBloodType').val(employee.empBloodType);
                    $('#empEmployerName').val(employee.empEmployeerName); // note: correct spelling
                    $('#empEmployerAddress').val(employee.empEmployeerAddress);

                    $('#empEmergencyContactName').val(employee.empEmergencyContactName);
                    $('#empEmergencyContactNo').val(employee.empEmergencyContactNo);
                    $('#empEmergencyContactAddress').val(employee.empEmergencyContactAddress);

                })
                .catch(error => {
                    showToast('Error', 'Failed to load employee data', 'danger');
                    console.error(error);
                });
        }


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

</html><?php /**PATH C:\Projects\hris\hris-app\resources\views/pages/hr/employee_management.blade.php ENDPATH**/ ?>