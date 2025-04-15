@php
$employee = Auth::user()->employee;
$photo = $employee ? ($employee->photo ?? null) : null;
$isExternal = $photo && Str::startsWith($photo, ['http://', 'https://']);
$defaultPhoto = asset('images/default-avatar.png');
$assignments = $employee ? $employee->assignments()->with('position')->get()->sortByDesc('assignDate') : collect();
$latestAssignment = $assignments->first() ?? null;
@endphp

<div class="card d-flex flex-row align-items-center p-4 bg-light mt-4 mx-3">
    <img src="{{ $photo ? ($isExternal ? $photo : asset('storage/attachments/employee_photos/' . $photo)) : $defaultPhoto }}"
        alt="User Avatar"
        width="150"
        height="150"
        class="rounded me-4"
        style="object-fit: cover; background-color: #e0e0e0;">

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

        <a href="#" class="btn btn-link p-0 text-decoration-none">
            <i class="bi bi-pencil-square me-1"></i> Update Profile
        </a>
    </div>
</div>