<!-- Assign Position Modal -->
<div class="modal fade" id="{{ $modalId }}" tabindex="-1" aria-labelledby="empAssignmentModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">

            <input type="hidden" name="assignEmpID" id="assignEmpID">
            <input type="hidden" name="empID" value="{{ $employee->empID }}">

            <div class="modal-header">
                <h5 class="modal-title">Assign</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <div class="modal-body">

                <!-- Employee ID -->
                <div class="mb-3">
                    <label class="form-label">Employee ID:</label>
                    <input type="text" class="form-control" value="{{ $employee->empID }}" readonly>
                </div>

                <!-- Employee Name -->
                <div class="mb-3">
                    <label class="form-label">Employee Name:</label>
                    <input type="text" class="form-control" value="{{ $employee->empLname }}, {{ $employee->empFname }} {{ $employee->empMname }}" readonly>
                </div>

                <!-- Assigned Positions Table -->
                <div id="assignedPositions" class="mt-4">
                    <h6>Assigned Positions</h6>
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover align-middle">
                            <thead class="table-light text-center">
                                <tr>
                                    <th>No.</th>
                                    <th>Position Name</th>
                                    <th>Appointed Date</th>
                                    <th>End Date</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody class="text-center">
                                @foreach($assignedPositions as $index => $position)
                                <tr>
                                    <td>{{ $index + 1 }}</td>
                                    <td>{{ $position->position->positionName }}</td>
                                    <td>{{ $position->empAssAppointedDate }}</td>
                                    <td>{{ $position->empAssEndDate ?? 'N/A' }}</td>
                                    <td>
                                        <form action="/employee/assignment/{{ $position->empAssID }}/delete" method="POST">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-danger btn-sm">
                                                <i class="ri-delete-bin-line"></i> <!-- Delete Icon -->
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                                @endforeach
                                @if($assignedPositions->isEmpty())
                                <tr>
                                    <td colspan="5" class="text-center">No assigned positions found.</td>
                                </tr>
                                @endif
                            </tbody>

                        </table>
                    </div>
                </div>
                @php
                $latest = $assignedPositions->last();
                @endphp

                <form method="POST" action="{{ route('empAssignment') }}">
                    @csrf
                    <!-- Add Position Fields -->
                    <input type="hidden" name="empID" id="empIDHidden">
                    <div id="positionsContainer">
                        <div class="position-item row d-flex justify-content-between mt-4">
                            <input type="hidden" name="positions[0][empAssID]" value="{{ $assignment->id ?? '' }}">


                            <div class="col mb-3">
                                <label for="positionID" class="form-label">Position</label>
                                <select class="form-select" id="positionID" name="positions[0][positionID]" required>
                                    <option value="" disabled selected>Select a position</option>
                                    @foreach($positions as $position)
                                    <option value="{{ $position->positionID }}">{{ $position->positionName }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col mb-3">
                                <label for="empAssAppointedDate" class="form-label">Appointed Date</label>
                                <input type="date" class="form-control" id="empAssAppointedDate" name="positions[0][empAssAppointedDate]" required>
                            </div>

                            <div class="col mb-3">
                                <label for="empAssEndDate" class="form-label">End Date</label>
                                <input type="date" class="form-control" id="empAssEndDate" name="positions[0][empAssEndDate]">
                            </div>

                            <div class="col-auto d-flex align-items-end mb-3">
                                <button type="button" class="btn btn-danger btn-sm" onclick="removePositionField(this)">Remove</button>
                            </div>
                        </div>
                    </div>

                    <button type="button" class="btn btn-secondary mb-3" onclick="addPositionField()">Add Another Position</button>


                    <!-- Department and Office Selection -->
                    <div class="row d-flex justify-content-between mt-4">
                        <div class="col mb-3">
                            <label for="departmentID" class="form-label">Department (Optional)</label>
                            <select class="form-select" name="departmentID">
                                <option value="">None</option>
                                @foreach($departments as $department)
                                <option
                                    value="{{ $department->departmentCode }}"
                                    data-programs='@json($department->programs)'
                                    {{ $latest && $latest->departmentCode === $department->departmentCode ? 'selected' : '' }}>
                                    {{ $department->departmentName }}
                                </option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Program Selection -->
                        <div class="col mb-3">
                            <label for="programCode" class="form-label">Program (Optional)</label>
                            <select class="form-select" name="programCode">
                                <option value="">None</option>
                                @if($latest && $latest->department && $latest->department->programs)
                                @foreach($latest->department->programs as $program)
                                <option value="{{ $program->programCode }}" {{ $latest->programCode === $program->programCode ? 'selected' : '' }}>
                                    {{ $program->programName }}
                                </option>
                                @endforeach
                                @endif
                            </select>
                        </div>


                        <div class="col mb-3">
                            <label for="officeID" class="form-label">Office (Optional)</label>
                            <select class="form-select" name="officeID">
                                <option value="">None</option>
                                @foreach($offices as $office)
                                <option
                                    value="{{ $office->officeCode }}"
                                    {{ $latest && $latest->officeCode === $office->officeCode ? 'selected' : '' }}>
                                    {{ $office->officeName }}
                                </option>
                                @endforeach
                            </select>

                        </div>
                    </div>



                    <!-- Make Head of the Office Checkbox -->
                    <div class="form-check mb-3 {{ $latest && ($latest->officeCode || $latest->departmentCode) ? '' : 'd-none' }}">
                        <input class="form-check-input" type="checkbox" name="makeHead" value="1" {{ $latest && $latest->empHead ? 'checked' : '' }}>
                        <label class="form-check-label">Make Head of the Office</label>
                    </div>


                    <div class="div mb-3 text-center">
                        <button type="submit" class="btn btn-primary">Assign</button>
                    </div>
                </form>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                </div>

            </div>
        </div>
    </div>
</div>

<script>
    let positionIndex = 1;

    function addPositionField() {
        const container = document.getElementById('positionsContainer');
        const positionItem = document.createElement('div');
        positionItem.classList.add('position-item', 'row', 'd-flex', 'justify-content-between', 'mt-4');
        positionItem.innerHTML = `
          <input type="hidden" name="positions[${positionIndex}][empAssID]" value="">
        <div class="col mb-3">
            <label for="positionID" class="form-label">Position</label>
            <select class="form-select" name="positions[${positionIndex}][positionID]" required>
                <option value="" disabled selected>Select a position</option>
                @foreach($positions as $position)
                <option value="{{ $position->positionID }}">{{ $position->positionName }}</option>
                @endforeach
            </select>
        </div>
        <div class="col mb-3">
            <label for="empAssAppointedDate" class="form-label">Appointed Date</label>
            <input type="date" class="form-control" name="positions[${positionIndex}][empAssAppointedDate]" required>
        </div>
        <div class="col mb-3">
            <label for="empAssEndDate" class="form-label">End Date</label>
            <input type="date" class="form-control" name="positions[${positionIndex}][empAssEndDate]">
        </div>
        <div class="col-auto mb-3 d-flex align-items-end">
            <button type="button" class="btn btn-danger btn-sm" onclick="removePositionField(this)">Remove</button>
        </div>
    `;
        container.appendChild(positionItem);
        positionIndex++;
    }

    function removePositionField(button) {
        const positionItem = button.closest('.position-item');
        positionItem.remove();
    }

    function handleDepartmentChange(departmentSelect) {
        const selectedOption = departmentSelect.options[departmentSelect.selectedIndex];
        const programs = JSON.parse(selectedOption.getAttribute('data-programs') || '[]'); // Get programs from data attribute
        const programSelect = document.getElementById('programCode');
        const makeHeadContainer = document.getElementById('makeHeadContainer');

        // Clear and reset the program dropdown
        programSelect.innerHTML = '<option value="" selected>None</option>';

        // Populate the program dropdown if programs exist
        if (programs.length > 0) {
            programs.forEach(program => {
                const option = document.createElement('option');
                option.value = program.programCode; // Set the value to programCode
                option.textContent = program.programName; // Display the program name
                programSelect.appendChild(option);
            });
        }

        // Show the checkbox if either department or office is selected
        const officeValue = document.getElementById('officeID').value;
        if (departmentSelect.value || officeValue) {
            makeHeadContainer.style.display = 'block';
        } else {
            makeHeadContainer.style.display = 'none';
        }
    }

    function handleOfficeChange(officeSelect) {
        const makeHeadContainer = document.getElementById('makeHeadContainer');
        const departmentValue = document.getElementById('departmentID').value;

        // Show the checkbox if either department or office is selected
        if (officeSelect.value || departmentValue) {
            makeHeadContainer.style.display = 'block';
        } else {
            makeHeadContainer.style.display = 'none';
        }
    }

    document.addEventListener('DOMContentLoaded', function() {
        // Hide the checkbox on page load
        const makeHeadContainer = document.getElementById('makeHeadContainer');
        makeHeadContainer.style.display = 'none';

        // Attach event listeners to department and office dropdowns
        const departmentSelect = document.getElementById('departmentID');
        const officeSelect = document.getElementById('officeID');

        departmentSelect.addEventListener('change', function() {
            handleDepartmentChange(departmentSelect);
        });

        officeSelect.addEventListener('change', function() {
            handleOfficeChange(officeSelect);
        });
    });


    document.addEventListener('DOMContentLoaded', function() {
        const form = document.querySelector('form[action="{{ route("empAssignment") }}"]');

        form.addEventListener('submit', function(e) {
            const officeID = document.getElementById('officeID').value;
            const departmentID = document.getElementById('departmentID').value;
            const programCode = document.getElementById('programCode').value;

            console.log('Office ID:', officeID);
            console.log('Department ID:', departmentID);
            console.log('Selected Program Code:', programCode);

            // Optional: Prevent form submission for testing
            // e.preventDefault();
        });
    });



    // ✅ Hide programContainer on page load
    document.addEventListener('DOMContentLoaded', () => {
        document.getElementById('programContainer').style.display = 'none';
    });
</script>