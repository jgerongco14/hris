<div class="leave col my-5">

    <h4 class="mb-4 fw-bold">LEAVE APPLICATION FORM</h4>

    <div class="card p-4">
        <form id="leaveForm" action="{{ route('leave_application.store') }}" method="POST" enctype="multipart/form-data">

            @csrf

            <input type="hidden" id="formMode" value="create"> {{-- default mode --}}
            <input type="hidden" name="emp_leave_no" id="empLeaveNo" value="">


            <!-- Include emp_id as hidden input -->
            <input type="hidden" name="empID" id="empID" value="{{ Auth::user()->employee->empID ?? '' }}">

            <!-- Leave type -->
            <div class="mb-3">
                <label for="leave_type" class="form-label fw-semibold">Type of Leave*</label>
                <select class="form-select" id="leave_type" name="leave_type" required>
                    <option value="" disabled selected>Leave Type</option>
                    <option value="Sick Leave">Sick Leave</option>
                    <option value="Vacation Leave">Vacation Leave</option>
                    <option value="Emergency Leave">Emergency Leave</option>
                </select>
            </div>

            <!-- Dates -->
            <div class="mb-3">
                <label class="form-label fw-semibold">Date of Leave*</label>
                <div class="d-flex gap-3">
                    <div>
                        <label class="form-label small">From</label>
                        <input type="date" class="form-control" name="leave_from" required>
                    </div>
                    <div>
                        <label class="form-label small">To</label>
                        <input type="date" class="form-control" name="leave_to" required>
                    </div>
                </div>
            </div>

            <!-- Reason -->
            <div class="mb-3">
                <label for="reason" class="form-label fw-semibold">Reason / Purpose*</label>
                <textarea class="form-control" id="reason" name="reason" rows="3" placeholder="Place the reason for leave here..." required></textarea>
            </div>

            <!-- Attachments -->
            <div class="mb-4">
                <label for="attachment" class="form-label fw-semibold">New Attachment/s</label>
                <input class="form-control" type="file" name="attachment[]" id="attachment" accept="image/*,application/pdf" multiple>
                <div class="form-text">Accepted: IMAGE, PDF</div>

                <!-- Existing attachments will be rendered via JS -->
                <label class="form-label fw-semibold mt-3">Current Attachments:</label>
                <div id="existingAttachments"></div>
            </div>




            <!-- Hidden status (default to pending or filed) -->
            <input type="hidden" name="status" value="Pending">

            <!-- Submit -->
            <button type="submit" id="leaveFormSubmitBtn" class="btn btn-primary">
                <i class="bi bi-send me-1"></i> <span id="leaveFormSubmitText">Submit Application</span>
            </button>

        </form>
    </div>
</div>