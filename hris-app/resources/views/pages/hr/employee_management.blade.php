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
                <div class="sidebar h-100">
                    @include('components.sidebar')
                </div>
            </div>

            <!-- Main Content Section -->
            <div class="col-10">
                <!-- Include the titlebar component -->
                <x-titlebar />

                <!-- Include the notification component -->
                <x-notification />

                <!-- Import Attendance Button -->
                @include('components.import_file')

                <div class="card my-5">
                    <div class="card-body">
                        @include('pages.hr.components.employee_list')
                    </div>
                </div>
                @include('pages.hr.components.assign_position', [
                'departments' => $departments,
                'offices' => $offices,
                'positions' => $positions
                ])


            </div>
        </div>
    </div>

    <!-- Include jQuery and jQuery UI for datepicker -->
    <link rel="stylesheet" href="https://code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.min.js"></script>

    <script>
        let selectedEmployeeId = null;
        let selectedEmployeeName = null;
        let selectedEmpID = null;

        function showEditOptions(employeeId, empName, empID) {
            selectedEmployeeId = employeeId;
            selectedEmployeeName = empName;
            selectedEmpID = empID;
            $('#editChoiceModal').modal('show');
        }


        document.getElementById('editAssignmentBtn').addEventListener('click', function() {
            $('#editChoiceModal').modal('hide');
            // Show the assign position form/modal instead
            empAssignment(selectedEmployeeId, selectedEmployeeName, selectedEmpID);
        });

        $(document).ready(function() {


            $('#addIndividualBtn').click(function() {
                $('#employeeForm').show(); // Always show the form
                $('#addEmployee').modal('hide'); // Close the import modal if it's open
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

        function empAssignment(id, empName, empID) {
            const modal = new bootstrap.Modal(document.getElementById('empAssignmentModal'));
            modal.show();
            // Set static fields
            document.getElementById('assignEmpID').value = id; // Hidden field for employee ID
            document.getElementById('empIDDisplay').value = empID;
            document.getElementById('empIDHidden').value = empID; // Hidden input for form submission
            document.getElementById('employeeName').value = empName; // Employee Name field
            document.getElementById('hiddenDepartmentID').value = document.getElementById('departmentID').value;
            document.getElementById('hiddenProgramCode').value = document.getElementById('programCode').value;
            document.getElementById('hiddenOfficeID').value = document.getElementById('officeID').value;




            const tbody = document.getElementById('assignedPositionsBody');
            tbody.innerHTML = `
        <tr>
            <td colspan="5" class="text-center text-muted">Loading...</td>
        </tr>
    `;

            // Fetch positions
            fetch(`/employee/${id}/positions`)
                .then(res => res.json())
                .then(data => {
                    if (data.length > 0) {
                        tbody.innerHTML = ''; // Clear loading
                        data.forEach((assignment, index) => {
                            const row = `
                        <tr>
                            <td class="text-center">${index + 1}</td>
                            <td>${assignment.positionName}</td>
                            <td class="text-center">${assignment.empAssAppointedDate}</td>
                            <td class="text-center">${assignment.empAssEndDate}</td>
                            <td class="text-center">
                                <button class="btn btn-danger btn-sm" onclick="removePosition(${assignment.empAssID})">
                                    <i class="ri-delete-bin-5-line"></i>
                                </button>
                            </td>
                        </tr>`;
                            tbody.insertAdjacentHTML('beforeend', row);
                        });
                    } else {
                        tbody.innerHTML = `
                    <tr>
                        <td colspan="5" class="text-center text-muted">No position assignments found.</td>
                    </tr>`;
                    }
                })
                .catch(error => {
                    console.error("Fetch error:", error);
                    tbody.innerHTML = `
                <tr>
                    <td colspan="5" class="text-danger text-center">Failed to load positions.</td>
                </tr>`;
                });
        }

        function removePosition(empAssID) {
            if (!confirm("Are you sure you want to remove this position assignment?")) return;

            fetch(`/employee/assignment/${empAssID}/delete`, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Content-Type': 'application/json'
                    }
                })
                .then(res => {
                    if (!res.ok) throw new Error('Failed to delete');
                    return res.json(); // Make sure to return the response
                })
                .then(data => {
                    if (data.success) {
                        showToast("Success", "Position removed successfully.");
                    } else {
                        showToast("Error", data.message || "Delete failed.", 'danger');
                    }

                    // 🟡 Reload the assignment list only, not the entire modal
                    empAssignment(
                        document.getElementById('assignEmpID').value,
                        document.getElementById('employeeName').value,
                        document.getElementById('empID').value
                    );
                })
                .catch(err => {
                    showToast("Error", "Something went wrong while deleting.", 'danger');
                    console.error(err);
                });
        }




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