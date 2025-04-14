<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Employee Leave Management</title>
    @vite(['resources/sass/app.scss', 'resources/js/app.js'])

    <link href="https://cdn.jsdelivr.net/npm/remixicon@2.5.0/fonts/remixicon.css" rel="stylesheet">
    <link rel="stylesheet" href="/css/leave_manangement.css">
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

                <!-- Profile Section -->
                <x-myProfile />

                <!-- Spinner -->
                <div id="loadingSpinner" style="display: none;" class="text-center my-4">
                    <div class="spinner-border text-primary" role="status"></div>
                    <p>Loading...</p>
                </div>

                <!-- Table Wrapper -->
                <div id="leaveListTable">
                    @include('pages.hr.components.list_of_leave_application', ['tabs' => $tabs])
                </div>



                <!-- Hidden Leave Application Approval Form -->
                @include('pages.hr.components.leave_approval')


            </div>
        </div>
    </div>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>

    <script>
        function fetchLeaveData(id) {
            fetch(`/leave_management/${id}`, {
                    headers: {
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                })
                .then(async res => {
                    if (!res.ok) {
                        const errorData = await res.json();
                        throw new Error(errorData.message || 'Failed to fetch leave data');
                    }
                    return res.json();
                })
                .then(data => {
                    const leaveData = Array.isArray(data) ? data[0] : data;

                    // Show the approval form and hide the table
                    document.getElementById('leaveListTable').style.display = 'none';
                    document.getElementById('approvalForm').style.display = 'block';

                    document.getElementById('approvalForm').setAttribute('data-leave-id', leaveData.empLeaveNo);

                    // Populate the form with the data
                    document.getElementById('approvalName').innerText = leaveData.name;
                    document.getElementById('approvalLeaveType').innerText = leaveData.type;

                    const formatDate = (dateStr) => {
                        const date = new Date(dateStr);
                        return date.toLocaleDateString('en-US', {
                            year: 'numeric',
                            month: 'short',
                            day: 'numeric'
                        });
                    };

                    const startDate = formatDate(leaveData.dates.start);
                    const endDate = formatDate(leaveData.dates.end);
                    document.getElementById('approvalDates').innerText = `${startDate} - ${endDate}`;

                    document.getElementById('approvalReason').innerText = leaveData.reason;
                    document.getElementById('approvalPosition').innerText = Array.isArray(leaveData.positionNames) ?
                        leaveData.positionNames.join(', ') :
                        'N/A';


                    // Set the current status in the dropdown
                    if (leaveData.status) {
                        const approvalStatusDropdown = document.getElementById('approvalStatus');
                        approvalStatusDropdown.value = leaveData.status;
                    }

                    // Populate attachments
                    const attachmentsContainer = document.getElementById('approvalAttachments');
                    attachmentsContainer.innerHTML = ''; // Clear previous attachments

                    if (leaveData.attachment && Array.isArray(leaveData.attachment)) {
                        leaveData.attachment.forEach((attachment) => {
                            const fileUrl = attachment.url;
                            const fileName = fileUrl.split('/').pop();
                            const ext = fileName.split('.').pop().toLowerCase();
                            let element;

                            if (['jpg', 'jpeg', 'png', 'gif', 'bmp', 'webp'].includes(ext)) {
                                // Image preview
                                element = document.createElement('a');
                                element.href = fileUrl;
                                element.target = '_blank';
                                element.innerHTML = `<img src="${fileUrl}" class="img-thumbnail me-2 mb-2" style="max-width:100px; max-height:100px;" alt="${fileName}" title="${fileName}">`;
                            } else if (['mp4', 'webm', 'ogg'].includes(ext)) {
                                // Video preview
                                element = document.createElement('video');
                                element.src = fileUrl;
                                element.controls = true;
                                element.title = fileName;
                                element.style.maxWidth = "150px";
                                element.style.maxHeight = "100px";
                                element.classList.add("me-2", "mb-2");
                            } else {
                                // Other files like PDFs or DOCs
                                element = document.createElement('a');
                                element.href = fileUrl;
                                element.target = '_blank';
                                element.textContent = fileName;
                                element.classList.add('btn', 'btn-outline-secondary', 'btn-sm', 'me-2', 'mb-2');
                            }

                            attachmentsContainer.appendChild(element);
                        });
                    } else {
                        attachmentsContainer.innerHTML = '<p>No attachments available.</p>';
                    }

                })
                .catch(err => {
                    console.error('Error:', err);
                    alert('Error: ' + err.message);

                    // Show table again on error
                    document.getElementById('approvalForm').style.display = 'none';
                    document.getElementById('leaveListTable').style.display = 'block';
                });
        }

        // Cancel button logic
        document.addEventListener('DOMContentLoaded', function() {
            const cancelButton = document.getElementById('cancelApproval');
            if (cancelButton) {
                cancelButton.addEventListener('click', () => {
                    document.getElementById('approvalForm').style.display = 'none';
                    document.getElementById('leaveListTable').style.display = 'block';
                });
            }
        });

        document.addEventListener("DOMContentLoaded", function() {
            // Initialize toast system
            const toastElList = [].slice.call(document.querySelectorAll('.toast'));
            const toastList = toastElList.map(function(toastEl) {
                return new bootstrap.Toast(toastEl, {
                    delay: 10000,
                });
            });

            const submitButton = document.getElementById('submitApplication');
            if (submitButton) {
                submitButton.addEventListener('click', () => {
                    const formContainer = document.getElementById('approvalForm');
                    const leaveId = formContainer.getAttribute('data-leave-id');
                    const status = document.getElementById('approvalStatus').value;
                    const empPayStatus = document.getElementById('empPayStatus').value;
                    const remarks = document.getElementById('remarks').value;

                    if (!leaveId) {
                        showToast('Error', 'Leave ID is missing. Cannot submit.', 'danger');
                        return;
                    }

                    fetch(`/leave_management/{id}/approve`, {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'Accept': 'application/json',
                                'X-Requested-With': 'XMLHttpRequest',
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                            },
                            body: JSON.stringify({
                                empLeaveNo: leaveId,
                                status: status,
                                empPayStatus: empPayStatus,
                                remarks: remarks,
                            }),
                        })
                        // Inside your submit button event listener, update the success handler:
                        .then(async res => {
                            const data = await res.json();
                            if (!res.ok) throw new Error(data.message || 'Failed to submit approval');

                            showToast('Success', data.message || 'Leave status updated successfully!', 'success');

                            // Hide form and show leave table
                            formContainer.style.display = 'none';
                            document.getElementById('leaveListTable').style.display = 'block';
                            formContainer.removeAttribute('data-leave-id');

                            setTimeout(() => {
                                window.location.href = '/leave_management';
                            }, 500);
                        })
                        .catch(err => {
                            console.error('Error:', err);
                            showToast('Error', err.message || 'Failed to update leave status. Please try again.', 'danger');
                        });
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
        });

        function renderAttachments(attachmentUrls = []) {
            const container = document.getElementById("approvalAttachments");
            container.innerHTML = ""; // Clear previous

            attachmentUrls.forEach(url => {
                const ext = url.split('.').pop().toLowerCase();
                const fileName = url.split('/').pop(); // ⬅️ Extract just the file name
                let element;

                if (['jpg', 'jpeg', 'png', 'gif', 'bmp', 'webp'].includes(ext)) {
                    element = document.createElement('img');
                    element.src = url;
                    element.alt = fileName;
                    element.title = fileName;
                    element.style.maxWidth = "100px";
                    element.style.maxHeight = "100px";
                    element.classList.add("img-thumbnail", "me-2", "mb-2");
                } else if (['mp4', 'webm', 'ogg'].includes(ext)) {
                    element = document.createElement('video');
                    element.src = url;
                    element.controls = true;
                    element.title = fileName;
                    element.style.maxWidth = "150px";
                    element.style.maxHeight = "100px";
                    element.classList.add("me-2", "mb-2");
                } else {
                    // PDF or other files
                    element = document.createElement('a');
                    element.href = url;
                    element.target = "_blank";
                    element.textContent = fileName; // 👈 Use the actual filename here
                    element.classList.add("d-block", "text-decoration-none", "mb-1");
                }

                container.appendChild(element);
            });
        }
    </script>
</body>

</html>