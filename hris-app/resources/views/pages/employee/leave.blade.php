<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Leave</title>
    @vite(['resources/sass/app.scss', 'resources/js/app.js'])
    <link href="https://cdn.jsdelivr.net/npm/remixicon@2.5.0/fonts/remixicon.css" rel="stylesheet">
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
                <div class="position-sticky top-0 z-3 w-100">
                    <x-titlebar />
                </div>

                <!-- Include the notification component -->
                <x-notification />

                <!-- Toggle Button -->

                <div class="card my-4 mx-3">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h4 class="card-title">Leave Applications</h4>
                        <div class="d-flex justify-content-end">
                            @if(isset($editLeave))
                            <a href="{{ route('leave_application') }}" class="btn btn-secondary">
                                Cancel
                            </a>
                            @else
                            <button id="toggleFormBtn" class="btn btn-primary">
                                <span id="toggleText">Request Leave</span>
                            </button>
                            @endif
                        </div>
                    </div>
                    <div class="card-body">
                        <div id="leaveFormSection" @if(!isset($editLeave)) style="display: none;" @endif>
                            @include('pages.employee.components.applicationForm', ['editLeave' => $editLeave ?? null])
                        </div>

                        <div id="leaveListSection" @if(isset($editLeave)) style="display: none;" @endif>
                            @include('pages.employee.components.leaveList', ['tabs' => $tabs])
                        </div>

                    </div>

                </div>


            </div>
        </div>
    </div>
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const toggleBtn = document.getElementById("toggleFormBtn");
            const toggleText = document.getElementById("toggleText");
            const toggleIcon = document.getElementById("toggleIcon");
            const formSection = document.getElementById("leaveFormSection");
            const listSection = document.getElementById("leaveListSection");

            let isFormVisible = false;

            toggleBtn.addEventListener("click", function() {
                isFormVisible = !isFormVisible;

                formSection.style.display = isFormVisible ? "block" : "none";
                listSection.style.display = isFormVisible ? "none" : "block";

                // Toggle button text and icon
                toggleText.textContent = isFormVisible ? "Cancel" : "Request Leave";
                toggleBtn.className = isFormVisible ? "btn btn-secondary" : "btn btn-primary";

                // ✅ Reset form when hiding
                if (!isFormVisible) {
                    const form = document.getElementById('leaveForm');
                    form.reset();
                    document.getElementById('formMode').value = 'create';
                    document.getElementById('leaveFormSubmitText').innerText = 'Submit Application';
                    form.action = `{{ route('leave_application.store') }}`;

                    // Remove _method override if exists
                    const methodInput = document.querySelector('input[name="_method"]');
                    if (methodInput) {
                        methodInput.remove();
                    }
                }
            });

        });

        function toggleReplaceInput(index) {
            const input = document.getElementById(`replaceInput${index}`);
            if (input) {
                input.classList.toggle('d-none');
            }
        }

        function removeAttachment(index) {
            if (confirm("Are you sure you want to delete this attachment?")) {
                const container = document.getElementById(`existingAttachment${index}`)?.parentElement;
                if (container) {
                    container.remove();
                    showToast('Attachment Removed', `Attachment ${index + 1} has been removed.`, 'warning');
                }
            }
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

</html>