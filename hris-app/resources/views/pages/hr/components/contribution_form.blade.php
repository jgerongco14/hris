<!-- Edit Contribution Modal -->
<div class="modal fade" id="editContributionModal" tabindex="-1" aria-labelledby="editContributionModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editContributionModalLabel">Edit Contribution</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <!-- Contribution Form -->
                <form id="editContributionForm" action="{{ route('contribution.update', ['id' => ':id']) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <!-- EmpConType (hidden field) -->
                    <input type="hidden" id="empConType" name="empConType">

                    <div class="mb-3">
                        <label for="employeeName" class="form-label">Employee Name</label>
                        <input type="text" class="form-control" id="employeeName" name="employeeName" readonly>
                    </div>

                    <div class="mb-3">
                        <label for="empConAmount" class="form-label">Amount</label>
                        <input type="number" step="0.01" class="form-control" id="empConAmount" name="empConAmount" required>
                    </div>

                    <div class="mb-3">
                        <label for="employeerContribution" class="form-label">Employer Contribution</label>
                        <input type="text" step="0.01" class="form-control" id="employeerContribution" name="employeerContribution" required>
                    </div>

                    <div class="mb-3">
                        <label for="empPRNo" class="form-label">Payment Reference Number</label>
                        <input type="text" class="form-control" id="empPRNo" name="empPRNo" required>
                    </div>

                    <div class="mb-3">
                        <label for="empConDate" class="form-label">Date</label>
                        <input type="date" class="form-control" id="empConDate" name="empConDate" required>
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary">Update</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>