<!-- Modal -->
<div class="modal fade" id="addAttendanceModal" tabindex="-1" aria-labelledby="addAttendanceModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <form method="POST" action="{{ route('attendance.import') }}" enctype="multipart/form-data" class="modal-content">
            @csrf
            <div class="modal-header">
                <h5 class="modal-title" id="addAttendanceModalLabel">Import Attendance File</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="mb-3">
                    <label for="attendance_file" class="form-label">Upload CSV or Excel</label>
                    <input class="form-control" type="file" name="attendance_file" id="attendance_file" accept=".csv, .xlsx, .xls" required>
                </div>
            </div>
            <div class="modal-footer">
                <button type="submit" class="btn btn-primary">Import</button>
            </div>
        </form>
    </div>
</div>

<div class="modal fade" id="addEmployee" tabindex="-1" aria-labelledby="addemployeeModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <form method="POST" action="{{ route('employee.import') }}" enctype="multipart/form-data" class="modal-content">
            @csrf
            <div class="modal-header">
                <h5 class="modal-title" id="addemployeeModalLabel">Import employee File</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="mb-3">
                    <label for="employee_file" class="form-label">Upload CSV or Excel</label>
                    <input class="form-control" type="file" name="employee_file" id="employee_file" accept=".csv, .xlsx, .xls" required>
                </div>
            </div>
            <div class="modal-footer">
                <button type="submit" class="btn btn-primary">Import</button>
            </div>
        </form>
    </div>
</div>

<div class="modal fade" id="addUsers" tabindex="-1" aria-labelledby="adduserModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <form method="POST" action="{{ route('user.store') }}" enctype="multipart/form-data" class="modal-content">
            @csrf
            <div class="modal-header">
                <h5 class="modal-title" id="adduserModalLabel">Import File</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="mb-3">
                    <label for="user_file" class="form-label">Upload CSV or Excel</label>
                    <input class="form-control" type="file" name="user_file" id="user_file" accept=".csv, .xlsx, .xls" required>
                </div>
            </div>
            <div class="modal-footer">
                <button type="submit" class="btn btn-primary">Import</button>
            </div>
        </form>
    </div>
</div>