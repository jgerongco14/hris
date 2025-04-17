<!-- Modal -->
<div class="modal fade" id="addAttendanceModal" tabindex="-1" aria-labelledby="addAttendanceModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <form method="POST" action="<?php echo e(route('attendance.import')); ?>" enctype="multipart/form-data" class="modal-content">
            <?php echo csrf_field(); ?>
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
</div><?php /**PATH C:\Projects\hris\hris-app\resources\views/pages/hr/components/import_attendance.blade.php ENDPATH**/ ?>