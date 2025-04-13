<!-- Confirmation Modal -->
<div class="modal fade" id="confirmStatusModal" tabindex="-1" aria-labelledby="confirmStatusModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Confirm Status Change</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                Are you sure you want to change the status of this employee?
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button id="confirmStatusBtn" type="button" class="btn btn-primary">Yes, continue</button>
            </div>
        </div>
    </div>
</div>


<!-- Create Report Modal -->
<div class="modal fade" id="createReportModal" tabindex="-1" aria-labelledby="createReportModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <form action="{{ route('reports.create') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="createReportModalLabel">Create Report</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <div class="modal-body">
                    <!-- Employee ID -->
                    <div class="mb-3">
                        <label for="empID" class="form-label">Employee ID</label>
                        <input type="text" class="form-control" id="empID" name="empID" required>
                    </div>

                    <!-- Employee Name -->
                    <div class="mb-3">
                        <label for="empName" class="form-label">Employee Name</label>
                        <input type="text" class="form-control" id="empName" name="empName" readonly required>
                    </div>

                    <!-- Status -->
                    <div class="mb-3">
                        <label for="status" class="form-label">Status</label>
                        <input type="text" class="form-control" id="status" name="status" required>
                    </div>

                    <!-- Semester -->
                    <div class="mb-3">
                        <label for="semester" class="form-label">Semester</label>
                        <select class="form-select" id="semester" name="semester" required>
                            <option value="">Select Semester</option>
                            <option value="1st">1st Semester</option>
                            <option value="2nd">2nd Semester</option>
                            <option value="3rd">3rd Semester</option>
                            <option value="Summer">Summer</option>
                        </select>
                    </div>

                    <!-- Year -->
                    <div class="mb-3">
                        <label for="year" class="form-label">School Year</label>
                        <input class="form-control" id="year" name="year" required>
                    </div>

                    <!-- Reason -->
                    <div class="mb-3">
                        <label for="reason" class="form-label">Reason</label>
                        <textarea class="form-control" id="reason" name="reason" rows="3" required></textarea>
                    </div>

                    <!-- Attachments -->
                    <div class="mb-3">
                        <label for="attachment" class="form-label">Attachments (PDF only)</label>
                        <input type="file" class="form-control" id="attachment" name="attachment[]" multiple accept=".pdf">
                        <small class="text-muted">Max file size: 2MB each</small>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary">Submit Report</button>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                </div>
            </div>
        </form>
    </div>
</div>