<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reports</title>
    @vite(['resources/sass/app.scss', 'resources/js/app.js'])
    <link href="https://cdn.jsdelivr.net/npm/remixicon@2.5.0/fonts/remixicon.css" rel="stylesheet">
</head>

<body>
    <div class="container-fluid">
        <div class="row">
            <div class="col-2 p-0">
                <div class="sidebar h-100">
                    @include('components.sidebar')
                </div>
            </div>
            <div class="col-10">
                <!-- Include the titlebar component -->
                <x-titlebar />

                <!-- Include the notification component -->
                <x-notification />
                <!-- Summary Row at the Top -->
                <div class="row mb-3">
                    <div class="col-12">
                        <div class="alert alert-info d-flex justify-content-between align-items-center p-3 rounded shadow-sm" style="background-color: #e3f7ff;">
                            <div class="fw-bold">📊 Current School Year Status Summary</div>
                            <div class="d-flex gap-3">
                                <span class="badge bg-success px-3 py-2">Active: {{ $activeCount }}</span>
                                <span class="badge bg-danger px-3 py-2">Resigned: {{ $resignedCount }}</span>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row my-3">
                    <div class="col-5">
                        @include('pages.hr.components.report_employee_list')
                    </div>
                    <div class="col-7">

                        @include('pages.hr.components.report_resigned_employees')
                        @include('pages.hr.components.report_modal')
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script>
        let selectedEmpID = null;
        let selectedStatus = null;
        let selectedEmpName = null;

        function confirmStatusChange(empID, currentStatus, empName) {
            selectedEmpID = empID;
            selectedStatus = currentStatus;
            selectedEmpName = empName;
            const confirmModal = new bootstrap.Modal(document.getElementById('confirmStatusModal'));
            confirmModal.show();
        }

        document.getElementById('confirmStatusBtn').addEventListener('click', function() {
            // Close confirmation modal
            bootstrap.Modal.getInstance(document.getElementById('confirmStatusModal')).hide();

            // Prefill values
            document.getElementById('empID').value = selectedEmpID;
            document.getElementById('status').value = selectedStatus === 'Active' ? 'Resigned' : 'Active';
            document.getElementById('empName').value = selectedEmpName;

            // Show createReport modal
            const reportModal = new bootstrap.Modal(document.getElementById('createReportModal'));
            reportModal.show();
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