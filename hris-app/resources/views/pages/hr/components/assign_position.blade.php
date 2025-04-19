<template id="position-select-template">
    <select class="form-select position-select">
        <option value="" disabled selected>Select a position</option>
        @foreach($positions as $position)
        <option value="{{ $position->positionID }}">{{ $position->positionName }}</option>
        @endforeach
    </select>
</template>


<!-- Assign Position Modal -->
<div class="modal fade" id="{{ $modalId }}" tabindex="-1" aria-labelledby="empAssignmentModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">

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
                                        <form action="{{ route('deleteAssignment', $position->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this Assignment?')" style="display: inline;">
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
                    <input type="hidden" name="empID" id="empIDHidden" value="{{ $employee->empID }}">
                    <div id="positionsContainer-{{ $employee->empID }}">
                        <div class="position-item row d-flex justify-content-between mt-4">
                            <input type="hidden" name="positions[0][empAssID]" value="{{ $assignment->id ?? '' }}">

                            <div class="col mb-3">
                                <label class="form-label">Position</label>
                                <select class="form-select" name="positions[0][positionID]">
                                    <option value="" disabled selected>Select a position</option>
                                    @foreach($positions as $position)
                                    <option value="{{ $position->positionID }}">{{ $position->positionName }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col mb-3">
                                <label for="empAssAppointedDate" class="form-label">Appointed Date</label>
                                <input type="date" class="form-control" id="empAssAppointedDate" name="positions[0][empAssAppointedDate]">
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

                    <button type="button" class="btn btn-secondary mb-3" onclick="addPositionField('{{ $employee->empID }}')">Add Another Position</button>

                    <div class="d-flex">

                        <!-- Department and Office Selection -->
                        <div class="col mb-3 mx-1">
                            <label for="departmentID" class="form-label">Department (Optional)</label>
                            <select class="form-select" name="departmentID" id="departmentID-{{ $employee->empID }}" onchange="updateProgramsDropdown(this, '{{ $employee->empID }}')">
                                <option value="">None</option>
                                @foreach($departments as $department)
                                <option
                                    value="{{ $department->departmentCode }}"
                                    data-programs='@json($department->programs)'
                                    {{ $latest && $latest->departmentCode === $department->departmentCode ? 'selected' : '' }}>
                                    {{ $department->departmentName }}
                                </option>
                                @endforeach
                                DB::table('department_program')->get();
                            </select>
                        </div>

                        <!-- Program Selection -->
                        <div class="col mb-3 mx-1">
                            <label for="programCode" class="form-label">Program (Optional)</label>
                            <select class="form-select" name="programCode" id="programContainer-{{ $employee->empID }}">
                                <option value="">None</option>
                                @if($latest && $latest->department)
                                @foreach($latest->department->programs as $program)
                                <option value="{{ $program->programCode }}"
                                    {{ $latest->programCode == $program->programCode ? 'selected' : '' }}>
                                    {{ $program->programName }}
                                </option>
                                @endforeach
                                @endif
                            </select>
                        </div>


                        <div class="col mb-3 mx-1">
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
            </div>

            <!-- Make Head of the Office Checkbox -->
            <div class="form-check mb-3 mx-3">
                <input class="form-check-input" type="checkbox" name="makeHead" value="1"
                    {{ !empty($latest?->empHead) ? 'checked' : '' }}
                    data-locked="true">

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
    const positionIndexes = {};

    function addPositionField(empID) {
        const container = document.getElementById(`positionsContainer-${empID}`);
        if (!container) return;

        if (!positionIndexes[empID]) {
            positionIndexes[empID] = 1;
        }

        const index = positionIndexes[empID];

        const positionItem = document.createElement('div');
        positionItem.classList.add('position-item', 'row', 'd-flex', 'justify-content-between', 'mt-4');

        const selectTemplate = document.getElementById('position-select-template');
        const clonedSelect = selectTemplate.content.cloneNode(true).querySelector('select');
        clonedSelect.setAttribute('name', `positions[${index}][positionID]`);

        const hiddenInput = document.createElement('input');
        hiddenInput.type = 'hidden';
        hiddenInput.name = `positions[${index}][empAssID]`;
        hiddenInput.value = '';

        const selectWrapper = document.createElement('div');
        selectWrapper.classList.add('col', 'mb-3');
        const label = document.createElement('label');
        label.className = 'form-label';
        label.textContent = 'Position';
        selectWrapper.appendChild(label);
        selectWrapper.appendChild(clonedSelect);

        const appointedWrapper = document.createElement('div');
        appointedWrapper.classList.add('col', 'mb-3');
        appointedWrapper.innerHTML = `
        <label class="form-label">Appointed Date</label>
        <input type="date" class="form-control" name="positions[${index}][empAssAppointedDate]" required>
    `;

        const endDateWrapper = document.createElement('div');
        endDateWrapper.classList.add('col', 'mb-3');
        endDateWrapper.innerHTML = `
        <label class="form-label">End Date</label>
        <input type="date" class="form-control" name="positions[${index}][empAssEndDate]">
    `;

        const removeWrapper = document.createElement('div');
        removeWrapper.classList.add('col-auto', 'mb-3', 'd-flex', 'align-items-end');
        removeWrapper.innerHTML = `
        <button type="button" class="btn btn-danger btn-sm" onclick="removePositionField(this)">Remove</button>
    `;

        positionItem.appendChild(hiddenInput);
        positionItem.appendChild(selectWrapper);
        positionItem.appendChild(appointedWrapper);
        positionItem.appendChild(endDateWrapper);
        positionItem.appendChild(removeWrapper);

        container.appendChild(positionItem);
        positionIndexes[empID]++;
    }





    function removePositionField(button) {
        const positionItem = button.closest('.position-item');
        positionItem.remove();
    }

    function updateProgramsDropdown(departmentSelect, empID) {
        const wrapper = departmentSelect.closest('form');
        const programSelect = document.getElementById(`programContainer-${empID}`); // Find element that starts with "programContainer"

        if (!programSelect) {
            console.warn('Program select not found.');
            return;
        }

        programSelect.innerHTML = '<option value="">None</option>';

        const selectedOption = departmentSelect.selectedOptions[0];
        if (!selectedOption || !selectedOption.dataset.programs) {
            console.log('No programs data found for selected department');
            return;
        }

        try {
            const programs = JSON.parse(selectedOption.dataset.programs);
            console.log('Parsed programs:', programs);

            if (programs && programs.length > 0) {
                programs.forEach(program => {
                    if (program && program.programCode) {
                        const option = new Option(program.programName, program.programCode);
                        programSelect.add(option);
                    }
                });
            } else {
                console.log('No programs available for this department');
            }
        } catch (e) {
            console.error('Error parsing programs:', e);
        }
    }


    function autoCheckHeadStatus() {
        const departmentSelect = document.getElementById('departmentID');
        const officeSelect = document.getElementById('officeID');
        const makeHeadCheckbox = document.querySelector('[name="makeHead"]');

        const departmentSelected = departmentSelect && departmentSelect.value !== '';
        const officeSelected = officeSelect && officeSelect.value !== '';

        // Auto-check if either is selected
        // ✅ Only auto-check if not already checked (preserve backend state)
        if (!makeHeadCheckbox.dataset.locked) {
            makeHeadCheckbox.checked = departmentSelected || officeSelected;
        }
    }

    document.addEventListener('DOMContentLoaded', function() {
        const departmentSelect = document.getElementById('departmentID');

        if (departmentSelect) {
            // Initialize on load
            updateProgramsDropdown(departmentSelect);

            // Update on change
            departmentSelect.addEventListener('change', function() {
                updateProgramsDropdown(departmentSelect);
            });
        }
    });
</script>