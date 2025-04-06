<!-- Assign Position Modal -->
<div class="modal fade" id="assignPositionModal" tabindex="-1" aria-labelledby="assignPositionModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">

            <div class="modal-header">
                <h5 class="modal-title">Assign Position</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <div class="modal-body">
                <input type="hidden" name="assignEmpID" id="assignEmpID">

                <div class="mb-3">
                    <label for="empID" class="form-label">Employee ID:</label>
                    <input type="text" class="form-control" id="empID" name="empID" readonly>
                </div>

                <div class="mb-3">
                    <label for="employeeName" class="form-label">Employee Name:</label>
                    <input type="text" class="form-control" id="employeeName" readonly>
                </div>

                <!-- Assigned Positions Table -->
                <div id="assignedPositions" class="mt-4">
                    <h6>Assigned Positions</h6>
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover align-middle">
                            <thead class="table-light text-center">
                                <tr>
                                    <th>#</th>
                                    <th>Position Name</th>
                                    <th>Appointed Date</th>
                                    <th>End Date</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody id="assignedPositionsBody">
                                <!-- Loaded dynamically via JS -->
                            </tbody>
                        </table>
                    </div>
                </div>
                <form method="POST" action="{{ route('assignPosition') }}" >
                    @csrf
                    <!-- Add Position Fields -->
                    <input type="hidden" name="empID" id="empIDHidden">
                    <div class="row d-flex justify-content-between mt-4">
                        <div class="col mb-3">
                            <label for="positionID" class="form-label">Position</label>
                            <select class="form-select" id="positionID" name="positionID" required>
                                <option value="" disabled selected>Select a position</option>
                                @foreach($positions as $position)
                                <option value="{{ $position->positionID }}">{{ $position->positionName }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col mb-3">
                            <label for="empAssAppointedDate" class="form-label">Appointed Date</label>
                            <input type="date" class="form-control" id="empAssAppointedDate" name="empAssAppointedDate" required>
                        </div>

                        <div class="col mb-3">
                            <label for="empAssEndDate" class="form-label">End Date</label>
                            <input type="date" class="form-control" id="empAssEndDate" name="empAssEndDate">
                        </div>
                    </div>
            </div>

            <div class="div mb-3 text-center">
                <button type="submit" class="btn btn-primary">Assign Position</button>
            </div>
            </form>

            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
            </div>


        </div>
    </div>
</div>



