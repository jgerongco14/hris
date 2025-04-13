<!-- Add Employee Button -->
<div class="dropdown my-4">
    <button class="btn btn-primary dropdown-toggle" type="button" id="addEmployeeBtn" data-bs-toggle="dropdown" aria-expanded="false">
        Add Employee
    </button>
    <ul class="dropdown-menu" aria-labelledby="addEmployeeBtn">
        <li><a class="dropdown-item" href="#" id="addIndividualBtn">Add Individual Employee</a></li>
        <li><a class="dropdown-item" href="#" data-bs-toggle="modal" data-bs-target="#addEmployee">Import Employee (CSV/Excel)</a></li>
    </ul>
</div>




<!-- The Form (Initially Hidden) -->
<form method="POST" action="{{ route('addEmployee.store') }}" enctype="multipart/form-data" id="employeeForm" style="display: none;" class="container mt-4">
    @csrf
    <input type="hidden" name="_method" id="formMethod" value="POST">
    <div class="card ">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0">Add Employee</h5>
            <button type="button" class="btn-close" onclick="$('#employeeForm').hide(); $('#employeeForm')[0].reset()"></button>
        </div>
        <div class="card-body">


            <div class="row">

                <div class="col-1 mb-3">
                    <label for="empID" class="form-label">Emp ID</label>
                    <input type="text" class="form-control" id="empID" name="empID" value="{{ old('empID') }}" required>
                </div>
                <div class="col-1 mb-3">
                    <label for="empPrefix" class="form-label">Prefix</label>
                    <input type="text" class="form-control" id="empPrefix" name="empPrefix" value="{{ old('empPrefix') }}">
                </div>


                <div class="col mb-3">
                    <label for="empFname" class="form-label">First Name</label>
                    <input type="text" class="form-control" id="empFname" name="empFname" value="{{ old('empFname') }}" required>
                </div>

                <div class="col mb-3">
                    <label for="empMname" class="form-label">Middle Name</label>
                    <input type="text" class="form-control" id="empMname" name="empMname" value="{{ old('empMname') }}">
                </div>

                <div class="col mb-3">
                    <label for="empLname" class="form-label">Last Name</label>
                    <input type="text" class="form-control" id="empLname" name="empLname" value="{{ old('empLname') }}" required>
                </div>
                <div class="col-1 mb-3">
                    <label for="empSuffix" class="form-label">Suffix</label>
                    <input type="text" class="form-control" id="empSuffix" name="empSuffix" value="{{ old('empSuffix') }}">
                </div>
            </div>

            <div class="row">

                <div class="col-4 mb-3">
                    <label for="photo" class="form-label">Profile Picture</label>

                    @if(!empty($employee->photo))
                    @php
                    $isExternal = Str::startsWith($employee->photo, ['http://', 'https://']);
                    @endphp

                    <div class="mb-2">
                        <img src="{{ $isExternal ? $employee->photo : asset('storage/' . $employee->photo) }}"
                            alt="Employee Photo"
                            width="100"
                            height="100"
                            class="rounded-circle border">
                    </div>
                    @endif

                    <input class="form-control" type="file" id="photo" name="photo">
                </div>


                <div class="col-4 mb-3">
                    <label class="form-label">Gender</label>
                    <div class="d-flex align-items-center">
                        <div class="form-check me-3">
                            <input class="form-check-input" type="radio" id="male" name="empGender" value="male" {{ old('empGender', $employee->empGender ?? '') == 'male' ? 'checked' : '' }}>
                            <label class="form-check-label" for="male">Male</label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" id="female" name="empGender" value="female" {{ old('empGender', $employee->empGender ?? '') == 'female' ? 'checked' : '' }}>
                            <label class="form-check-label" for="female">Female</label>
                        </div>
                    </div>
                </div>



                <div class="col-4 mb-3">
                    <label for="empBirthdate" class="form-label">Birthdate</label>
                    <input type="text" class="form-control datepicker" id="empBirthdate" name="empBirthdate" value="{{ old('empBirthdate') }}" readonly required>
                </div>
            </div>

            <div class="mb-3">
                <label for="address" class="form-label">Address</label>
                <input type="text" class="form-control" id="address" name="address" value="{{ old('address') }}">
            </div>

            <div class="row">
                <div class="col-md-4 mb-3">
                    <label for="province" class="form-label">Province</label>
                    <input type="text" class="form-control" id="province" name="province" value="{{ old('province') }}">
                </div>

                <div class="col-md-4 mb-3">
                    <label for="city" class="form-label">City</label>
                    <input type="text" class="form-control" id="city" name="city" value="{{ old('city') }}">
                </div>

                <div class="col-md-4 mb-3">
                    <label for="barangay" class="form-label">Barangay</label>
                    <input type="text" class="form-control" id="barangay" name="barangay" value="{{ old('barangay') }}">
                </div>
            </div>

            <div class="row">
                <div class="col-md-4 mb-3">
                    <label for="empSSSNum" class="form-label">SSS Number</label>
                    <input type="text" class="form-control" id="empSSSNum" name="empSSSNum" value="{{ old('empSSSNum') }}">
                </div>

                <div class="col-md-4 mb-3">
                    <label for="empTinNum" class="form-label">TIN Number</label>
                    <input type="text" class="form-control" id="empTinNum" name="empTinNum" value="{{ old('empTinNum') }}">
                </div>

                <div class="col-md-4 mb-3">
                    <label for="empPagIbigNum" class="form-label">Pag-Ibig Number</label>
                    <input type="text" class="form-control" id="empPagIbigNum" name="empPagIbigNum" value="{{ old('empPagIbigNum') }}">
                </div>
            </div>

            <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                <button type="submit" class="btn btn-primary me-md-2">Submit</button>
                <button type="button" class="btn btn-secondary" onclick="$('#employeeForm').hide(); $('#employeeForm')[0].reset()">Cancel</button>
            </div>
        </div>
    </div>
</form>

<!-- Search Form -->
<form method="GET" action="{{ route('employee_management') }}" class="d-flex align-items-end gap-3 my-4" id="filterForm">
    <!-- Filter by Position -->
    <div class="mb-0">
        <label for="position" class="form-label visually-hidden">Filter by Position</label>
        <select name="position" id="position" class="form-select" onchange="document.getElementById('filterForm').submit()">
            <option value="">All Positions</option>
            @foreach($positions as $position)
            <option value="{{ $position->positionID }}" {{ request('position') == $position->positionID ? 'selected' : '' }}>
                {{ $position->positionName }}
            </option>
            @endforeach
        </select>

    </div>

    <!-- Search by Name -->
    <div class="mb-0">
        <label for="search" class="form-label visually-hidden">Search by Name</label>
        <input type="text" name="search" id="search" class="form-control" placeholder="Search by Employee Name" value="{{ request('search') }}">
    </div>

    <!-- Search Button -->
    <button type="submit" class="btn btn-primary d-flex align-items-center">
        <i class="ri-search-line"></i> <!-- Search Icon -->
    </button>

    <!-- Reset Button -->
    <a href="{{ route('employee_management') }}" class="btn btn-secondary d-flex align-items-center ms-2">
        <i class="ri-restart-line"></i> <!-- Reset Icon -->
    </a>
</form>


<!-- Employee List Section -->
<div class="row my-4">
    <div class="col">
        <h3>EMPLOYEE LIST</h3>
        <table class="table table-bordered">
            <thead>
                <tr class="text-center">
                    <th class="align-middle">EmpID</th>
                    <th class="align-middle">Full Name</th>
                    <th class="align-middle">Position</th>
                    <th class="align-middle">Department/Office</th>
                    <th class="align-middle">Benefits</th>
                    <th class="align-middle">Actions</th>
                </tr>
            </thead>
            <tbody>
                @if($employees->isEmpty())
                <tr>
                    <td colspan="5" class="text-center">No employees found.</td>
                </tr>
                @else
                @foreach($employees as $employee)
                <tr>
                    <td class="text-center align-middle d-flex flex-column">
                        {{ $employee->empID }}

                        @php
                        $rawStatus = strtolower($employee->status ?? 'active'); // default to 'active' if null
                        $badgeClass = $rawStatus === 'resigned' ? 'bg-danger' : 'bg-success';
                        $statusLabel = ucfirst($rawStatus);
                        @endphp
                        <span class="badge rounded {{ $badgeClass }}">{{ $statusLabel }}</span>
                    </td>
                    <td class="align-middle">
                        <div class="d-flex align-items-center gap-2">
                            @php
                            $employeePhoto = $employee->photo ?? null;
                            $isExternal = $employeePhoto && Str::startsWith($employeePhoto, ['http://', 'https://']);
                            @endphp

                            @if($employeePhoto)
                            <img src="{{ $isExternal ? $employeePhoto : asset('storage/' . $employee->photo) }}"
                                alt="Employee Photo" width="50" height="50" class="rounded-circle">
                            @else
                            <div class="no-photo bg-light rounded-circle d-flex align-items-center justify-content-center"
                                style="width:50px; height:50px;">
                                <i class="ri-user-line"></i>
                            </div>
                            @endif

                            <span>
                                {{ $employee->empPrefix }}
                                {{ $employee->empFname }}
                                {{ $employee->empMname }}
                                {{ $employee->empLname }}
                                {{ $employee->empSuffix }}
                            </span>
                        </div>
                    </td>
                    <td class="align-middle">
                        @forelse ($employee->assignments as $assignment)
                        <div>
                            <strong>{{ $assignment->position->positionName }}</strong><br>
                            <small class="text-muted">
                                {{ $assignment->empAssAppointedDate }}
                                to
                                {{ $assignment->empAssEndDate ?? 'Present' }}
                            </small>
                        </div>
                        @empty
                        <span class="text-muted">Unassigned</span>
                        @endforelse
                    </td>
                    <td class="align-middle">
                        @forelse ($employee->assignments->unique(function ($assignment) {
                        return $assignment->departmentCode . $assignment->officeCode . $assignment->programCode . $assignment->empHead;
                        })->sortByDesc('assignDate') as $assignment)
                        <div>
                            @if ($assignment->departmentCode)
                            <strong>Department:</strong> {{ $assignment->department->departmentName ?? 'N/A' }}<br>
                            @endif
                            @if ($assignment->officeCode)
                            <strong>Office:</strong> {{ $assignment->office->officeName ?? 'N/A' }}<br>
                            @endif
                            @if ($assignment->programCode)
                            <strong>Program:</strong> {{ $assignment->program->programName ?? 'N/A' }}<br>
                            @endif
                            @if ($assignment->empHead)
                            <strong>Head of the Office:</strong> {{ $assignment->empHead == 1 ? 'Yes' : 'No' }}
                            @endif
                        </div>
                        @empty
                        <span class="text-muted">Not Belong to any Department or Office</span>
                        @endforelse
                    </td>
                    <td class="align-middle">
                        <ul class="list-unstyled mb-0">
                            <li>SSS: {{ $employee->empSSSNum ?? 'N/A' }}</li>
                            <li>TIN: {{ $employee->empTinNum ?? 'N/A' }}</li>
                            <li>Pag-Ibig: {{ $employee->empPagIbigNum ?? 'N/A' }}</li>
                        </ul>
                    </td>
                    <td class="align-middle text-center">
                        <!-- Updated edit button -->
                        <button class="btn btn-sm btn-primary mx-1" onclick="editEmployee('{{ $employee->id }}')">
                            <i class="ri-pencil-line"></i>
                        </button>

                        <!-- Assign Position Button -->
                        <button class="btn btn-sm btn-warning mx-1"
                            onclick="empAssignment('{{ $employee->id }}', '{{ $employee->empFname }} {{ $employee->empLname }}', '{{ $employee->empID }}')">
                            <i class="ri-user-add-line"></i>
                        </button>


                        <form action="{{ route('employee.destroy', $employee->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this employee?')" style="display: inline;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-danger">
                                <i class="ri-delete-bin-line"></i>
                            </button>
                        </form>

                    </td>
                </tr>
                @endforeach
                @endif
            </tbody>
        </table>
        @if($employees->hasPages())
        <div class="d-flex flex-column align-items-center mt-4 gap-2">
            {{-- Pagination links --}}
            <div>
                {{ $employees->links('pagination::bootstrap-5') }}
            </div>

            {{-- Showing text --}}
            <div class="text-muted small">
                Showing {{ $employees->firstItem() }} to {{ $employees->lastItem() }} of {{ $employees->total() }} results
            </div>
        </div>
        @endif

    </div>
</div>



<!-- Choose Update Type Modal -->
<div class="modal fade" id="editChoiceModal" tabindex="-1" aria-labelledby="editChoiceLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editChoiceLabel">Edit Employee</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body text-center">
                <p>What would you like to update?</p>
                <button class="btn btn-primary m-2" id="editInfoBtn">Employee Information</button>
                <button class="btn btn-secondary m-2" id="editAssignmentBtn">Assignment</button>
            </div>
        </div>
    </div>
</div>


</div>

</div>