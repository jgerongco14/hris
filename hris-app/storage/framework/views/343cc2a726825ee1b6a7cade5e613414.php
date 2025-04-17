<!-- Add/Edit Position Modal -->
<div class="modal fade" id="addPositionModal" tabindex="-1" aria-labelledby="addPositionModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addPositionModalLabel">Add Position</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">

                <!-- Individual Position Form -->
                <form method="POST" id="positionForm" action="<?php echo e(route('assignment.storePosition')); ?>" style="display:none;">
                    <?php echo csrf_field(); ?>
                    <input type="hidden" name="id" id="id">
                    <input type="hidden" name="_method" id="formMethod" value="POST">
                    <div class="mb-3">
                        <label for="positionID" class="form-label">Position ID</label>
                        <input type="text" class="form-control" id="positionID" name="positionID" required>
                    </div>
                    <div class="mb-3">
                        <label for="positionName" class="form-label">Position Name</label>
                        <input type="text" class="form-control" id="positionName" name="positionName" required>
                    </div>
                    <div class="mb-3">
                        <label for="positionDescription" class="form-label">Position Description</label>
                        <textarea class="form-control" id="positionDescription" name="positionDescription" rows="3" required></textarea>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" id="submitButton" class="btn btn-primary">Add Position</button>
                    </div>
                </form>

                <!-- Import Position Form -->
                <form method="POST" id="importForm" action="<?php echo e(route('assignment.importPosition')); ?>" enctype="multipart/form-data" style="display:none;">
                    <?php echo csrf_field(); ?>
                    <div class="mb-3">
                        <label for="position_file" class="form-label">Position File (CSV, XLSX, XLS)</label>
                        <input type="file" class="form-control" id="position_file" name="position_file" required>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary">Import Positions</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div><?php /**PATH C:\Projects\hris\hris-app\resources\views/pages/admin/component/modal.blade.php ENDPATH**/ ?>