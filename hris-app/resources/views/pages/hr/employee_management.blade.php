<!DOCTYPE html>

<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Employee Management</title>
    @vite(['resources/sass/app.scss', 'resources/js/app.js'])
    <link href="https://cdn.jsdelivr.net/npm/remixicon@2.5.0/fonts/remixicon.css" rel="stylesheet">
</head>

<body>
    <div class="container-fluid">

        <div class="row">
            <!-- Navigation Section -->
            <div class="col-2">
                <!-- Include the navbar component -->
                <x-navbar />
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

                @include('pages.hr.components.assign_position', ['positions' => $positions])


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
                $('#employeeForm').toggle(); // Toggle the form visibility
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

        function assignPosition(id, empName, empID) {
            const modal = new bootstrap.Modal(document.getElementById('assignPositionModal'));
            modal.show();

            // Set the values properly
            document.getElementById('assignEmpID').value = id; // internal ID if you need it
            document.getElementById('empID').value = empID; // this should match the `empID` in the DB
            document.getElementById('employeeName').value = empName;
        }


        function cancelAssign() {
            $('#assignPositionForm').hide();
            $('#assignEmpID').val('');
            $('#employeeForm').show();
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