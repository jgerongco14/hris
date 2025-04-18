<!-- Leave Application Status Section -->
<div class="empleavelist row my-4 mx-1">
    <div class="col">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title text-center">Leave Applications Status</h3>
            </div>
            <div class=" card-body p-3">
                <ul class="nav nav-pills mb-3" id="leaveTabs" role="tablist">
                    <li class="nav-item" role="presentation">
                        <a class="nav-link active" id="all-tab" data-bs-toggle="pill" href="#all" role="tab" aria-controls="all" aria-selected="true">All</a>
                    </li>
                    <li class="nav-item" role="presentation">
                        <a class="nav-link" id="approval-tab" data-bs-toggle="pill" href="#approval" role="tab" aria-controls="approval" aria-selected="false">For Approval</a>
                    </li>
                    <li class="nav-item" role="presentation">
                        <a class="nav-link" id="approved-tab" data-bs-toggle="pill" href="#approved" role="tab" aria-controls="approved" aria-selected="false">Approved</a>
                    </li>
                    <li class="nav-item" role="presentation">
                        <a class="nav-link" id="declined-tab" data-bs-toggle="pill" href="#declined" role="tab" aria-controls="declined" aria-selected="false">Declined</a>
                    </li>
                </ul>

                <div class="tab-content" id="leaveTabsContent">
                    @foreach($tabs as $tabId => $tabConfig)
                    <div class="tab-pane fade {{ $tabId === 'all' ? 'show active' : '' }}" id="{{ $tabId }}" role="tabpanel" aria-labelledby="{{ $tabId }}-tab">
                        <table class="table table-bordered text">
                            <thead class="text-center">
                                <tr>
                                    <th>Date Applied</th>
                                    <th>Employee</th>
                                    <th>Type of Leave</th>
                                    <th>Date Range</th>
                                    <th>Reason</th>
                                    <th>Offices</th>
                                    <th>Status</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody class="align-middle">
                                @forelse($tabConfig['data'] as $status)
                                @php
                                $offices = json_decode($status->empLSOffice, true);
                                $user = Auth::user();
                                $userAssignments = $user->employee?->assignments;

                                $positionMap = [
                                'VICE PRESIDENT OF ACADEMIC AFFAIRS' => 'VPAA',
                                'VP FINANCE' => 'VP FINANCE',
                                'PRESIDENT' => 'PRESIDENT',
                                ];

                                $userPositions = $userAssignments
                                ->map(fn($a) => strtoupper($a->position?->positionName ?? ''))
                                ->map(fn($p) => $positionMap[$p] ?? $p)
                                ->filter()
                                ->unique()
                                ->values()
                                ->toArray();

                                $canSeeAll = collect($userPositions)->intersect(['VPAA', 'VP FINANCE', 'PRESIDENT'])->isNotEmpty();

                                $employee = $status->leave->employee ?? null;
                                $employeeDepartmentCode = $employee?->assignments?->first()?->departmentCode ?? null;
                                $viewerDepartmentCodes = $userAssignments?->filter(fn($a) => $a->empHead == 1)->pluck('departmentCode')->toArray() ?? [];

                                $canSeeLeave = $canSeeAll || in_array($employeeDepartmentCode, $viewerDepartmentCodes);
                                @endphp

                                @if($canSeeLeave)
                                <tr>
                                    <td>{{ \Carbon\Carbon::parse($status->leave->empLeaveDateApplied)->format('M d, Y') }}</td>
                                    <td class="text-center">
                                        @php
                                        $employeePhoto = $employee->photo ?? null;
                                        $isExternal = $employeePhoto && Str::startsWith($employeePhoto, ['http://', 'https://']);
                                        @endphp
                                        <div class="col d-flex align-items-center gap-2">
                                            @if($employeePhoto)
                                            <img
                                                src="{{ $isExternal ? $employeePhoto : asset('storage/employee_photos/' . $employee->photo) }}"
                                                alt="Employee Photo" width="50" height="50" class="rounded-circle">

                                            @else
                                            <div class="no-photo bg-light rounded-circle d-flex align-items-center justify-content-center"
                                                style="width:50px; height:50px;">
                                                <i class="ri-user-line"></i>
                                            </div>
                                            @endif
                                            <span>{{ $employee->empFname ?? '' }} {{ $employee->empMname ?? '' }} {{ $employee->empLname ?? '' }}</span>
                                        </div>
                                    </td>
                                    <td>{{ $status->leave->leaveType }}</td>
                                    <td>
                                        {{ \Carbon\Carbon::parse($status->leave->empLeaveStartDate)->format('M d, Y') }} - {{ \Carbon\Carbon::parse($status->leave->empLeaveEndDate)->format('M d, Y') }}
                                    </td>
                                    <td>{{ $status->leave->empLeaveDescription }}</td>
                                    <td>
                                        @if($offices && is_array($offices))
                                        <ul class="list-unstyled mb-0">
                                            @foreach($offices as $office => $empLSOffice)
                                            @php
                                            $badgeClass = match(strtolower($empLSOffice)) {
                                            'pending' => 'bg-secondary',
                                            'approved' => 'bg-success',
                                            'declined' => 'bg-danger',
                                            default => 'bg-light text-dark',
                                            };
                                            @endphp
                                            <li>
                                                <strong>{{ ucwords(strtolower($office)) }}:</strong>
                                                <span class="badge {{ $badgeClass }}">{{ strtoupper($empLSOffice) }}</span>
                                            </li>
                                            @endforeach
                                        </ul>
                                        @else
                                        <span class="text-muted">No status available.</span>
                                        @endif
                                    </td>
                                    <!-- Status -->
                                    <td class="text-center">
                                        @php
                                        $user = Auth::user();
                                        $userAssignments = $user->employee?->assignments;

                                        $positionMap = [
                                        'VICE PRESIDENT OF ACADEMIC AFFAIRS' => 'VPAA',
                                        'VP FINANCE' => 'VP FINANCE',
                                        'PRESIDENT' => 'PRESIDENT',
                                        ];

                                        $userOffices = collect($userAssignments)
                                        ->map(fn($a) => strtoupper($a->position?->positionName ?? ''))
                                        ->map(fn($p) => $positionMap[$p] ?? null)
                                        ->filter()
                                        ->unique()
                                        ->values()
                                        ->toArray();

                                        $offices = json_decode($status->empLSOffice, true);
                                        $currentStatus = null;
                                        $showActions = false;

                                        // Check VP/President offices first
                                        foreach ($userOffices as $mappedOffice) {
                                        if (isset($offices[$mappedOffice])) {
                                        $currentStatus = strtoupper($offices[$mappedOffice]);
                                        $showActions = ($currentStatus === 'PENDING');
                                        break;
                                        }
                                        }

                                        // Check Head of Department if no VP/President match
                                        if (is_null($currentStatus)) {
                                        foreach ($userAssignments as $assignment) {
                                        if ($assignment->empHead == 1) {
                                        $leaveEmployee = $status->leave->employee ?? null;
                                        $leaveAssignments = $leaveEmployee?->assignments;

                                        foreach ($leaveAssignments as $leaveAssignment) {
                                        if (
                                        $assignment->departmentCode === $leaveAssignment->departmentCode ||
                                        $assignment->programCode === $leaveAssignment->programCode ||
                                        $assignment->officeCode === $leaveAssignment->officeCode
                                        ) {
                                        if (isset($offices['HEAD OFFICE'])) {
                                        $currentStatus = strtoupper($offices['HEAD OFFICE']);
                                        $showActions = ($currentStatus === 'PENDING');
                                        break 2;
                                        }
                                        }
                                        }
                                        }
                                        }
                                        }

                                        // Default to PENDING if no status found
                                        $currentStatus = $currentStatus ?? 'PENDING';
                                        $badgeClass = match($currentStatus) {
                                        'APPROVED' => 'bg-success',
                                        'PENDING' => 'bg-secondary',
                                        'DECLINED' => 'bg-danger',
                                        default => 'bg-light text-dark',
                                        };
                                        @endphp

                                        <span class="badge {{ $badgeClass }}">{{ $currentStatus }}</span>
                                    </td>

                                    <!-- Actions -->
                                    <td class="text-center">
                                        @php
                                        $actionAvailable = false;

                                        // Normalize office status keys to uppercase
                                        $normalizedOffices = collect($offices ?? [])->mapWithKeys(fn($val, $key) => [strtoupper($key) => strtolower($val)]);

                                        // Check for VPAA / VP FINANCE / PRESIDENT
                                        foreach ($userPositions as $userOffice) {
                                        if (isset($normalizedOffices[$userOffice]) && $normalizedOffices[$userOffice] === 'pending') {
                                        $actionAvailable = true;
                                        break;
                                        }
                                        }

                                        // Fallback: Check for HEAD OFFICE if user is a department head
                                        if (!$actionAvailable && $userAssignments) {
                                        foreach ($userAssignments as $assignment) {
                                        if ($assignment->empHead == 1) {
                                        $leaveAssignments = $employee?->assignments ?? [];
                                        foreach ($leaveAssignments as $leaveAssignment) {
                                        if (
                                        $assignment->departmentCode === $leaveAssignment->departmentCode ||
                                        $assignment->programCode === $leaveAssignment->programCode ||
                                        $assignment->officeCode === $leaveAssignment->officeCode
                                        ) {
                                        if (isset($normalizedOffices['HEAD OFFICE']) && $normalizedOffices['HEAD OFFICE'] === 'pending') {
                                        $actionAvailable = true;
                                        break 2;
                                        }
                                        }
                                        }
                                        }
                                        }
                                        }
                                        @endphp

                                        @if($actionAvailable)
                                        <a href="javascript:void(0);" class="btn btn-sm btn-primary" onclick="fetchLeaveData('{{ $status->empLeaveNo }}')" title="Edit Leave Application" data-bs-toggle="tooltip" data-bs-placement="top">
                                            <i class="ri-pencil-line"></i>
                                        </a>
                                        @else
                                        <span class="text-muted">No Actions</span>
                                        @endif
                                    </td>

                                </tr>
                                @endif
                                @empty
                                <tr>
                                    <td colspan="8" class="text-center text-muted">{{ $tabConfig['empty'] }}</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>

                        @if($tabConfig['data']->hasPages())
                        <div class="d-flex flex-column align-items-center mt-4 gap-2">
                            <div>
                                {{ $tabConfig['data']->links('pagination::bootstrap-5') }}
                            </div>
                            <div class="text-muted small">
                                Showing {{ $tabConfig['data']->firstItem() }} to {{ $tabConfig['data']->lastItem() }} of {{ $tabConfig['data']->total() }} results
                            </div>
                        </div>
                        @endif
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div>