<div class="leave col my-3">

    <div class="card mx-3 p-4">
        <div class="card-header">
            <h5 class="card-title">Leave Application Form</h5>
            <p class="card-text">Please fill out the form below to apply for leave.</p>
        </div>
        <div class="card-body">
            <form id="leaveForm"
                action="{{ isset($editLeave) ? route('leave_application.update', $editLeave->empLeaveNo) : route('leave_application.store') }}"
                method="POST"
                enctype="multipart/form-data">
                @csrf
                @if(isset($editLeave))
                @method('PUT')
                @endif

                <input type="hidden" name="formMode" value="{{ isset($editLeave) ? 'edit' : 'create' }}">
                <input type="hidden" name="emp_leave_no" value="{{ $editLeave->empLeaveNo ?? '' }}">
                <input type="hidden" name="empID" value="{{ Auth::user()->employee->empID ?? '' }}">

                <!-- Leave Type -->
                <div class="mb-3">
                    <label for="leave_type" class="form-label fw-semibold">Type of Leave*</label>
                    <select class="form-select" id="leave_type" name="leave_type" required>
                        <option value="" disabled {{ !isset($editLeave) ? 'selected' : '' }}>Leave Type</option>
                        <option value="Sick Leave" {{ ($editLeave->leaveType ?? '') === 'Sick Leave' ? 'selected' : '' }}>Sick Leave</option>
                        <option value="Vacation Leave" {{ ($editLeave->leaveType ?? '') === 'Vacation Leave' ? 'selected' : '' }}>Vacation Leave</option>
                        <option value="Emergency Leave" {{ ($editLeave->leaveType ?? '') === 'Emergency Leave' ? 'selected' : '' }}>Emergency Leave</option>
                    </select>
                </div>

                <!-- Dates -->
                <div class="mb-3 row">
                    <div class="col">
                        <label for="leave_from" class="form-label small">From</label>
                        <input type="date" class="form-control" name="leave_from"
                            value="{{ isset($editLeave) ? $editLeave->empLeaveStartDate : '' }}" required>
                    </div>
                    <div class="col">
                        <label for="leave_to" class="form-label small">To</label>
                        <input type="date" class="form-control" name="leave_to"
                            value="{{ isset($editLeave) ? $editLeave->empLeaveEndDate : '' }}" required>
                    </div>
                </div>

                <!-- Reason -->
                <div class="mb-3">
                    <label for="reason" class="form-label fw-semibold">Reason / Purpose*</label>
                    <textarea class="form-control" id="reason" name="reason" rows="3" required>{{ $editLeave->empLeaveDescription ?? '' }}</textarea>
                </div>

                <!-- Attachments -->
                <div class="mb-4">
                    <label for="attachment" class="form-label fw-semibold">New Attachment/s</label>
                    <input class="form-control" type="file" name="attachment[]" id="attachment" accept="image/*,application/pdf" multiple>
                    <div class="form-text">Accepted: IMAGE, PDF</div>

                    @if(isset($editLeave->empLeaveAttachment))
                    <label class="form-label fw-semibold mt-3">Current Attachments:</label>
                    @php
                    $attachments = json_decode($editLeave->empLeaveAttachment, true) ?? [];
                    @endphp
                    @forelse($attachments as $index => $file)
                    <div class="mb-2 d-flex justify-content-between align-items-center attachment-item">
                        <a href="{{ asset('storage/' . $file) }}" target="_blank">{{ basename($file) }}</a>

                        <div class="d-flex align-items-center gap-2">
                            <!-- Hidden field to track file -->
                            <input type="hidden" name="existing_attachments[]" value="{{ $file }}">
                            <!-- Delete icon -->
                            <button type="button"
                                class="btn btn-sm btn-outline-danger"
                                onclick="markAttachmentForDeletion('{{ $file }}', this)">
                                <i class="ri-delete-bin-line"></i>
                            </button>
                        </div>
                    </div>
                    @empty
                    <p class="text-muted">No attachments.</p>
                    @endforelse

                    @endif

                </div>

                <!-- Status (Hidden) -->
                <input type="hidden" name="status" value="Pending">

                <!-- Submit Button -->
                <button type="submit" class="btn btn-primary">
                    <i class="bi bi-send me-1"></i>
                    <span>{{ isset($editLeave) ? 'Resubmit Application' : 'Submit Application' }}</span>
                </button>

                @if(isset($editLeave))
                <a href="{{ route('leave_application') }}" class="btn btn-secondary ms-2">Cancel</a>
                @endif
            </form>

        </div>
    </div>
</div>

<script>
    function markAttachmentForDeletion(file, btnElement) {
        // Remove the visual row
        const item = btnElement.closest('.attachment-item');
        if (item) item.remove();

        // Add a hidden input for deletion
        const container = document.getElementById('leaveForm');
        const input = document.createElement('input');
        input.type = 'hidden';
        input.name = 'delete_attachments[]';
        input.value = file;
        container.appendChild(input);
    }
</script>