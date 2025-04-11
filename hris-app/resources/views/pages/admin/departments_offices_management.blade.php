<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Departments and Offices</title>
    @vite(['resources/sass/app.scss', 'resources/js/app.js'])
    <link href="https://cdn.jsdelivr.net/npm/remixicon@2.5.0/fonts/remixicon.css" rel="stylesheet">
</head>

<body>
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-2">
                <!-- Include the navbar component -->
                <x-navbar />
            </div>
            <div class="col-md-10">
                <x-notification />

                <x-titlebar />

                <h1 class="my-3">Departments and Offices Management</h1>

                <div class="card mb-3">
                    <div class="card-header">
                        <h5>Departments and Offices</h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            @include('pages.admin.component.department_list')

                            @include('pages.admin.component.office_list')
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
    <script>
        function toggleForm(formId, buttonId) {
            const form = document.getElementById(formId);
            const button = document.getElementById(buttonId);
            if (form.style.display === 'none') {
                form.style.display = 'block';
                button.textContent = 'Hide Form';
            } else {
                form.style.display = 'none';
                button.textContent = 'Add New';
            }
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