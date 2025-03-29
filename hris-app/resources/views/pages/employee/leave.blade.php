<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Leave</title>
    @vite(['resources/sass/app.scss', 'resources/js/app.js'])
    <link href="https://cdn.jsdelivr.net/npm/remixicon@2.5.0/fonts/remixicon.css" rel="stylesheet">
</head>

<body>
    <div class="container-fluid">
        <div class="row">
            <!-- Navigation Section -->
            <div class="col-2">
                <x-navbar />
            </div>

            <!-- Main Content Section -->
            <div class="col-10">
                <x-titlebar />

                <!-- Profile Section -->
                <x-myProfile />

                <!-- Include the notification component -->
                <x-notification />

                <!-- Toggle Button -->
                <div class="d-flex justify-content-end mb-3">
                    <button id="toggleFormBtn" class="btn btn-primary">
                        <i id="toggleIcon"></i>
                        <span id="toggleText">Request Leave</span>
                    </button>
                </div>


                <!-- Leave Application Form (Initially Hidden) -->
                <div id="leaveFormSection" style="display: none;">
                    @include('pages.employee.components.applicationForm')
                </div>

                <!-- Leave Application List (Initially Visible) -->
                <div id="leaveListSection">
                    @include('pages.employee.components.leaveList', ['tabs' => $tabs])
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

        function fetchLeaveData(id) {
            fetch(`/employee/${id}`, {
                    headers: {
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                })
                .then(response => {
                    if (!response.ok) {
                        throw new Error('Network response was not ok');
                    }
                    return response.json();
                })
                .then(data => {
                    // 1. Hide approval form if it exists
                    const approvalForm = document.getElementById('approvalForm');
                    if (approvalForm) approvalForm.style.display = 'none';

                    // 2. Show the leave form section and update toggle button
                    document.getElementById('leaveFormSection').style.display = 'block';
                    const toggleBtn = document.getElementById('toggleFormBtn');
                    toggleBtn.className = 'btn btn-secondary';
                    document.getElementById('toggleText').textContent = 'Cancel';

                    // 3. Get the form and prepare it for update
                    const form = document.getElementById('leaveForm');
                    form.action = `/employee/${data.empLeaveNo}`;

                    // Add PUT method override
                    const methodInput = document.createElement('input');
                    methodInput.type = 'hidden';
                    methodInput.name = '_method';
                    methodInput.value = 'PUT';
                    form.appendChild(methodInput);

                    // 4. Fill in all form fields
                    document.getElementById('formMode').value = 'edit';
                    document.getElementById('empLeaveNo').value = data.empLeaveNo;
                    document.getElementById('leave_type').value = data.type;
                    document.querySelector('input[name="leave_from"]').value = data.dates.start;
                    document.querySelector('input[name="leave_to"]').value = data.dates.end;
                    document.getElementById('reason').value = data.reason;

                    // Make sure employee ID is set (critical fix)
                    const empIdInput = document.querySelector('input[name="empId"]');
                    if (empIdInput) {
                        empIdInput.value = data.empID || empIdInput.value;
                        console.log('empId set to:', empIdInput.value); // Debugging
                    } else {
                        console.error('empId input not found!');
                    }

                    // 5. Handle attachments display
                    const attachmentContainer = document.getElementById('existingAttachments');
                    attachmentContainer.innerHTML = ''; // Clear existing content

                    if (data.attachment && Array.isArray(data.attachment)) {
                        data.attachment.forEach((file, index) => {
                            const fileDisplay = document.createElement('div');
                            fileDisplay.className = 'mb-3';

                            // File info and preview
                            fileDisplay.innerHTML = `
                    <label><strong>Attachment ${index + 1}:</strong></label>
                    ${file.type.toLowerCase() === 'pdf' 
                        ? `<div><a href="${file.url}" target="_blank">View PDF</a></div>` 
                        : `<div><img src="${file.url}" alt="Attachment ${index + 1}" style="max-width: 200px;" class="img-thumbnail"></div>`}
                    <input type="hidden" name="existing_attachments[]" value="${file.url}">
                    <div class="mt-1">
                        <label class="form-label small">Replace this attachment (optional):</label>
                        <input type="file" name="replace_attachment[${index}]" class="form-control" accept="image/*,application/pdf">
                    </div>
                `;

                            attachmentContainer.appendChild(fileDisplay);
                        });
                    } else {
                        attachmentContainer.innerHTML = '<p class="text-muted">No attachments available.</p>';
                    }

                    // 6. Update submit button text
                    document.getElementById('leaveFormSubmitText').innerText = 'Resubmit Application';
                })
                .catch(error => {
                    console.error('Error fetching leave data:', error);
                    showToast('Error', 'Failed to load leave details', 'danger');
                });
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