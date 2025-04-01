<!-- Assign Position Modal -->
<div class="modal fade" id="assignPositionModal" tabindex="-1" aria-labelledby="assignPositionModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="assignPositionModalLabel">Assign Position</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form method="POST" action="{{ route('assignPosition') }}">
                @csrf
                <input type="hidden" name="assignEmpID" id="assignEmpID">
                <input type="hidden" name="empID" id="empID">
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="employeeName" class="form-label">Employee Name</label>
                        <input type="text" class="form-control" id="employeeName" readonly>
                    </div>
                    <div class="mb-3">
                        <label for="positionID" class="form-label">Position</label>
                        <select class="form-select" id="positionID" name="positionID" required>
                            <option value="" disabled selected>Select a position</option>
                            @foreach($positions as $position)
                                <option value="{{ $position->positionID }}">{{ $position->positionName }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="empAssAppointedDate" class="form-label">Appointed Date</label>
                        <input type="date" class="form-control" id="empAssAppointedDate" name="empAssAppointedDate" required>
                    </div>
                    <div class="mb-3">
                        <label for="empAssEndDate" class="form-label">End Date</label>
                        <input type="date" class="form-control" id="empAssEndDate" name="empAssEndDate">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Assign Position</button>
                </div>
            </form>
        </div>
    </div>
</div>