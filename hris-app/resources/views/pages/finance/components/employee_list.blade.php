<div class="card mx-3 my-4">
    <div class="card-header">
        <h3 class="card-title text-center">RVM</h3>
    </div>
    <div class="card-body">
        <table class="table table-bordered text">
            <thead class="text-center align-middle">
                <th>Name</th>
                <th>Position</th>
                <th>Department/Office</th>
                <th>Retirement No</th>
                <th>BPI ATM Account Number</th>
                <th>Action</th>
            </thead>
            <tbody>
                @if($employees->isEmpty())
                <tr>
                    <td colspan="6" class="text-center">No employees found.</td>
                </tr>
                @else
                @foreach($employees as $employee)
                <tr>
                    <td class="align-middle">
                        <div class="d-flex align-items-center gap-2">
                            @php
                            $employeePhoto = $employee->photo ?? null;
                            $isExternal = $employeePhoto && Str::startsWith($employeePhoto, ['http://', 'https://']);
                            @endphp


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

                            <div class="d-flex flex-column">
                                <span class="fw-bold" style="font-size: 16px;">
                                    {{ $employee->empPrefix }}
                                    {{ $employee->empFname }}
                                    {{ $employee->empMname }}
                                    {{ $employee->empLname }}
                                    {{ $employee->empSuffix }}
                                </span>
                                <span>
                                    @if(!empty($employee->empPersonelStatus))
                                    ({{ $employee->empPersonelStatus ?? '' }})
                                    @endif
                                </span>
                                <span>
                                    @if(!empty($employee->empDateHired))
                                    (Date Hired: {{ \Carbon\Carbon::parse($employee->empDateHired)->format('F d, Y') }})
                                    @endif
                                    @if(!empty($employee->empDateResigned))
                                    (Date Resigned: {{ \Carbon\Carbon::parse($employee->empDateResigned)->format('F d, Y') }})
                                    @endif

                                </span>

                            </div>
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
                    <td class="align-middle text-center">{{ $employee->empRVMRetirementNo }}</td>
                    <td class="align-middle text-center">{{ $employee->empBPIATMAccountNo }}</td>
                    <td class="align-middle text-center">
                        <button class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#editRvmModal{{ $employee->id }}">
                            <i class="ri-pencil-fill"></i>
                        </button>
                    </td>
                </tr>
                <!-- Edit RVM Modal -->
                <div class="modal fade" id="editRvmModal{{ $employee->id }}" tabindex="-1" aria-labelledby="editRvmModalLabel{{ $employee->id }}" aria-hidden="true">
                    <div class="modal-dialog">
                        <form method="POST" action="{{ route('finance.updateRvm', $employee->id) }}">
                            @csrf
                            @method('PUT')
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="editRvmModalLabel{{ $employee->id }}">Edit RVM Info</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>

                                <div class="modal-body">
                                    <div class="mb-3">
                                        <label for="employeeName{{ $employee->id }}" class="form-label">Employee Name</label>
                                        <input type="text" class="form-control" id="employeeName{{ $employee->id }}" value="{{ $employee->empPrefix }} {{ $employee->empFname }} {{ $employee->empMname }} {{ $employee->empLname }} {{ $employee->empSuffix }}" readonly>
                                    </div>

                                    <div class="mb-3">
                                        <label for="retirementNo{{ $employee->id }}" class="form-label">Retirement No</label>
                                        <input type="text" class="form-control" name="empRVMRetirementNo" id="retirementNo{{ $employee->id }}" value="{{ $employee->empRVMRetirementNo }}" required>
                                    </div>
                                    <div class="mb-3">
                                        <label for="bpiAtm{{ $employee->id }}" class="form-label">BPI ATM Account Number</label>
                                        <input type="text" class="form-control" name="empBPIATMAccountNo" id="bpiAtm{{ $employee->id }}" value="{{ $employee->empBPIATMAccountNo }}" required>
                                    </div>
                                </div>

                                <div class="modal-footer">
                                    <button type="submit" class="btn btn-success">Update</button>
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
                @endforeach
                @endif
            </tbody>

        </table>
    </div>
</div>