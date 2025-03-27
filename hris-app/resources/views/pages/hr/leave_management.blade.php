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
            <div class="col-2">
                <!-- Include the navbar component -->
                <x-navbar />
            </div>

            <!-- Main Content Section -->
            <div class="col-10">
                <!-- Include the titlebar component -->
                <x-titlebar />


                <div id="leaveListTable">
                    <!-- Leave Management Section -->
                    @include('pages.hr.components.list_of_leave_application', ['leaveStatuses' => $leaveStatuses])
                </div>



                <!-- User Profile Section -->
                <div class="row my-4" id="profileCard" style="display: none;">
                    <div class="col">
                        <div class="card p-3">
                            <div class="row">
                                <!-- Left: Photo -->
                                <div class="col-md-2 d-flex align-items-center justify-content-center">
                                    <img id="profilePhoto"
                                        alt="Employee Photo"
                                        width="50"
                                        height="50"
                                        onerror="this.onerror=null; this.src='/images/default-user.png';">
                                </div>

                                <!-- Right: Profile Info -->
                                <div class="col-md-10">
                                    <h5 class="card-subtitle mb-2 text-muted">Employee ID: <span id="profileEmpId"></span></h5>
                                    <h4 class="card-title" id="profileName">Full Name</h4>
                                    <p class="card-text" id="profilePosition">Position</p>
                                    <p class="card-text" id="profileAppointed">Appointed Date</p>
                                    <a href="#" class="btn btn-link">Update Profile</a>
                                </div>
                            </div>
                        </div>
                    </div>
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

                    // Set the current status in the dropdown
                    if (leaveData.status) {
                        const approvalStatusDropdown = document.getElementById('approvalStatus');
                        approvalStatusDropdown.value = leaveData.status;
                    }

                    // Populate attachments
                    const attachmentsContainer = document.getElementById('approvalAttachments');
                    attachmentsContainer.innerHTML = ''; // Clear previous attachments
                    if (leaveData.attachment && Array.isArray(leaveData.attachment)) {
                        leaveData.attachment.forEach((attachment, index) => {
                            const attachmentElement = document.createElement('a');
                            attachmentElement.href = attachment.url;
                            attachmentElement.target = '_blank'; // Open in a new tab
                            attachmentElement.innerText = `Attachment ${index + 1}`;
                            attachmentElement.classList.add('d-block'); // Add spacing between links
                            attachmentsContainer.appendChild(attachmentElement);
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

        document.addEventListener('DOMContentLoaded', function() {
            const submitButton = document.getElementById('submitApplication');
            if (submitButton) {
                submitButton.addEventListener('click', () => {
                    const formContainer = document.getElementById('approvalForm');
                    const leaveId = formContainer.getAttribute('data-leave-id');
                    const status = document.getElementById('approvalStatus').value;
                    const payStatus = document.getElementById('payStatus').value;
                    const remarks = document.getElementById('remarks').value;

                    if (!leaveId) {
                        alert('Leave ID is missing. Cannot submit.');
                        return;
                    }

                    fetch(`/leave_management/${leaveId}/approve`, {
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
                                payStatus: payStatus,
                                remarks: remarks,
                            }),
                        })
                        .then(async res => {
                            const data = await res.json();
                            if (!res.ok) throw new Error(data.message || 'Failed to submit approval');
                            alert(data.message || 'Approval submitted successfully!');

                            // Hide form and show leave table
                            formContainer.style.display = 'none';
                            document.getElementById('leaveListTable').style.display = 'block';
                            formContainer.removeAttribute('data-leave-id');
                        })
                        .catch(err => {
                            console.error('Error:', err);
                            alert('Error: ' + err.message);
                        });
                });
            }
        });
    </script>
</body>

</html>