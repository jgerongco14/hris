@php
use Illuminate\Support\Str;
$employee = Auth::user()->employee;
$photo = $employee->photo ?? null;
$isExternal = $photo && Str::startsWith($photo, ['http://', 'https://']);

// Build image URL with fallback handling
$imageUrl = null;
if ($photo) {
    if ($isExternal) {
        $imageUrl = $photo;
    } else {
        // For local files, use storage link
        $imageUrl = asset('storage/employee_photos/' . $photo);
    }
} else {
    // Use a placeholder service for default avatar
    $imageUrl = 'https://ui-avatars.com/api/?name=' . urlencode(($employee ? trim($employee->empFname . ' ' . $employee->empLname) : 'User')) . '&size=150&background=6c757d&color=ffffff';
}

$assignments = $employee ? $employee->assignments()->with('position')->get()->sortByDesc('assignDate') : collect();
$latestAssignment = $assignments->first();
@endphp

<!-- Profile Display Card -->
<div class="card d-flex flex-row align-items-center p-4 mx-3 bg-light my-3">
    <img src="{{ $imageUrl }}"
        alt="User Avatar"
        width="150"
        height="150"
        class="rounded me-4"
        style="object-fit: cover; background-color: #e0e0e0;"
        onerror="this.src='https://ui-avatars.com/api/?name=User&size=150&background=6c757d&color=ffffff'">

    <div>
        @if($employee)
        <h3 class="fw-bold mb-1 text-uppercase">
            {{ $employee->empPrefix ? $employee->empPrefix . ' ' : '' }}
            {{ $employee->empFname ?? '' }}
            {{ $employee->empMname ? substr($employee->empMname, 0, 1) . '.' : '' }}
            {{ $employee->empLname ?? '' }}
        </h3>

        @if($assignments->isNotEmpty())
        @php
        $positions = $assignments->pluck('position.positionName')->filter()->implode(', ');
        $appointmentDates = $assignments->pluck('assignDate')
        ->map(fn($date) => \Carbon\Carbon::parse($date)->format('F d, Y'))
        ->implode(', ');
        @endphp

        <p class="mb-2">
            {{ $positions }}<br>
            <small class="text-muted">Appointed last {{ $appointmentDates }}</small>
        </p>
        @else
        <p class="text-muted"><em>No position assigned.</em></p>
        @endif
        @else
        <h3 class="fw-bold mb-1 text-uppercase">User Profile</h3>
        <p class="text-muted"><em>No employee information available.</em></p>
        @endif

        <!-- Open modal button -->
        <button class="btn btn-outline-secondary btn-sm" data-bs-toggle="modal" data-bs-target="#photoModal">
            Update Profile Picture
        </button>
    </div>
</div>

<!-- Update Profile Photo Modal -->
<div class="modal fade" id="photoModal" tabindex="-1" aria-labelledby="photoModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <form action="{{ route('employee.update-photo') }}" method="POST" enctype="multipart/form-data" class="modal-content">
            @csrf
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
</script>