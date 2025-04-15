<!DOCTYPE html>

<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Employee Management</title>
    @vite(['resources/sass/app.scss', 'resources/js/app.js'])
    <link href="https://cdn.jsdelivr.net/npm/remixicon@2.5.0/fonts/remixicon.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">

</head>

<body>
    <div class="container-fluid">

        <div class="row">
            <!-- Navigation Section -->
            <div class="col-2 p-0">
                <div class="vh-100 position-sticky top-0">
                    @include('components.sidebar')
                </div>
            </div>

            <!-- Main Content Section -->
            <div class="col-10 p-3 pt-0">
                <!-- Include the titlebar component -->
                <div class="position-sticky top-0 z-3 w-100">
                    <x-titlebar />
                </div>

                <!-- Include the notification component -->
                <x-notification />

                <!-- Import Attendance Button -->
                @include('components.import_file')

                <div class="card my-5">
                    <div class="card-body">
                        @include('pages.hr.components.employee_list', [
                        'employees' => $employees,])
                    </div>
                </div>
                @foreach($employees as $employee)
                @php
                $assignedPositions = \App\Models\EmpAssignment::with('position')
                ->where('empID', $employee->empID)
                ->get();
                @endphp

                @include('pages.hr.components.assign_position', [
                'employee' => $employee,
                'assignedPositions' => $assignedPositions,
                'departments' => $departments,
                'offices' => $offices,
                'positions' => $positions,
                'modalId' => 'assignModal_' . $employee->empID
                ])
                @endforeach


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
                $('#employeeForm').attr('action', '{{ route("addEmployee.store") }}');
                $('#formMethod').val('POST');
                $('#empID').prop('readonly', false);
                $('#employeeForm').show();
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

                    if (employee.empGender === 'male') {
                        $('#male').prop('checked', true);
                    } else if (employee.empGender === 'female') {
                        $('#female').prop('checked', true);
                    }
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

</html>