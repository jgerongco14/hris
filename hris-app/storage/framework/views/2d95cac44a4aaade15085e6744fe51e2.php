<form method="POST" action="<?php echo e(route('contribution.update', $contribution->id)); ?>">
    <?php echo csrf_field(); ?>
    <?php echo method_field('PUT'); ?>

    <div class="card">
        <div class="card-body">
            <div class="form-group">
                <label for="empID">Employee ID</label>
                <input type="text" name="empID" class="form-control" value="<?php echo e(old('empID', $contribution->empID)); ?>" required>
            </div>

            <div class="form-group">
                <label for="empContype">Contribution Type</label>
                <input type="text" name="empContype" class="form-control" value="<?php echo e(old('empContype', $contribution->empContype)); ?>" required>
            </div>

            <div class="form-group">
                <label for="empConAmount">Amount</label>
                <input type="number" name="empConAmount" class="form-control" value="<?php echo e(old('empConAmount', $contribution->empConAmount)); ?>" required>
            </div>

            <div class="form-group">
                <label for="empConDate">Contribution Date</label>
                <input type="date" name="empConDate" class="form-control" value="<?php echo e(old('empConDate', $contribution->empConDate)); ?>" required>
            </div>

            <div class="form-group">
                <label for="empConRemarks">Remarks</label>
                <textarea name="empConRemarks" class="form-control"><?php echo e(old('empConRemarks', $contribution->empConRemarks)); ?></textarea>
            </div>

            <button type="submit" class="btn btn-primary mt-3">Update Contribution</button>
        </div>
    </div>
</form>
<?php /**PATH C:\Projects\hris\hris-app\resources\views/pages/hr/components/update_contribution_form.blade.php ENDPATH**/ ?>