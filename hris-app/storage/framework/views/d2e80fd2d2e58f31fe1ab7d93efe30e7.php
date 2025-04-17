<?php
use Illuminate\Support\Str;
$employee = Auth::user()->employee;
$photo = $employee->photo ?? null;
$isExternal = $photo && Str::startsWith($photo, ['http://', 'https://']);
$defaultPhoto = asset('images/default-avatar.png');
$assignments = $employee ? $employee->assignments()->with('position')->get()->sortByDesc('assignDate') : collect();
$latestAssignment = $assignments->first();
?>

<!-- Profile Display Card -->
<div class="card d-flex flex-row align-items-center p-4 mx-3 bg-light my-3">
    <img src="<?php echo e($photo ? ($isExternal ? $photo : asset('storage/employee_photos/' . $photo)) : $defaultPhoto); ?>"
        alt="User Avatar"
        width="150"
        height="150"
        class="rounded me-4"
        style="object-fit: cover; background-color: #e0e0e0;">

    <div>
        <?php if($employee): ?>
        <h3 class="fw-bold mb-1 text-uppercase">
            <?php echo e($employee->empPrefix ? $employee->empPrefix . ' ' : ''); ?>

            <?php echo e($employee->empFname ?? ''); ?>

            <?php echo e($employee->empMname ? substr($employee->empMname, 0, 1) . '.' : ''); ?>

            <?php echo e($employee->empLname ?? ''); ?>

        </h3>

        <?php if($assignments->isNotEmpty()): ?>
        <?php
        $positions = $assignments->pluck('position.positionName')->filter()->implode(', ');
        $appointmentDates = $assignments->pluck('assignDate')
        ->map(fn($date) => \Carbon\Carbon::parse($date)->format('F d, Y'))
        ->implode(', ');
        ?>

        <p class="mb-2">
            <?php echo e($positions); ?><br>
            <small class="text-muted">Appointed last <?php echo e($appointmentDates); ?></small>
        </p>
        <?php else: ?>
        <p class="text-muted"><em>No position assigned.</em></p>
        <?php endif; ?>
        <?php else: ?>
        <h3 class="fw-bold mb-1 text-uppercase">User Profile</h3>
        <p class="text-muted"><em>No employee information available.</em></p>
        <?php endif; ?>

        <!-- Open modal button -->
        <button class="btn btn-outline-secondary btn-sm" data-bs-toggle="modal" data-bs-target="#photoModal">
            Update Profile Picture
        </button>
    </div>
</div>

<!-- Update Profile Photo Modal -->
<div class="modal fade" id="photoModal" tabindex="-1" aria-labelledby="photoModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <form action="<?php echo e(route('employee.update-photo')); ?>" method="POST" enctype="multipart/form-data" class="modal-content">
            <?php echo csrf_field(); ?>
            <div class="modal-header">
                <h5 class="modal-title" id="photoModalLabel">Update Profile Photo</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <div class="modal-body">
                <input type="file" class="form-control d-none" id="photo" name="photo" accept="image/*" onchange="handlePhotoChange(event)">
                <button type="button" class="btn btn-outline-primary" onclick="document.getElementById('photo').click()">Choose File</button>
                <span id="filename" class="ms-2 text-muted">No file chosen</span>
                <div id="preview" class="mt-3"></div>
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="submit" class="btn btn-success">Save</button>
            </div>
        </form>
    </div>
</div>

<!-- JavaScript -->
<script>
    function handlePhotoChange(event) {
        const fileInput = event.target;
        const file = fileInput.files[0];
        const filenameText = document.getElementById('filename');
        const preview = document.getElementById('preview');

        filenameText.textContent = file ? file.name : 'No file chosen';
        preview.innerHTML = '';

        if (file) {
            const img = document.createElement('img');
            img.src = URL.createObjectURL(file);
            img.className = 'img-thumbnail mt-2';
            img.style.maxWidth = '150px';
            img.onload = () => URL.revokeObjectURL(img.src);
            preview.appendChild(img);
        }
    }
</script><?php /**PATH C:\Projects\hris\hris-app\resources\views/components/myProfile.blade.php ENDPATH**/ ?>