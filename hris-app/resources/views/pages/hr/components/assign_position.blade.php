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
                    <div id="positionsContainer">
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
                    <div class="form-check mb-3">
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
    let positionIndex = 1;

    function addPositionField() {
        const container = document.getElementById('positionsContainer');
        const positionItem = document.createElement('div');
        positionItem.classList.add('position-item', 'row', 'd-flex', 'justify-content-between', 'mt-4');

        // ✅ Get and clone <select> from the <template>
        const selectTemplate = document.getElementById('position-select-template');
        const clonedSelect = selectTemplate.content.cloneNode(true).querySelector('select');

        // ✅ Assign the correct name
        clonedSelect.setAttribute('name', `positions[${positionIndex}][positionID]`);

        // ✅ Hidden ID input
        const hiddenInput = document.createElement('input');
        hiddenInput.type = 'hidden';
        hiddenInput.name = `positions[${positionIndex}][empAssID]`;
        hiddenInput.value = '';

        // ✅ Select wrapper
        const selectWrapper = document.createElement('div');
        selectWrapper.classList.add('col', 'mb-3');
        const label = document.createElement('label');
        label.className = 'form-label';
        label.textContent = 'Position';
        selectWrapper.appendChild(label);
        selectWrapper.appendChild(clonedSelect);

        // ✅ Appointed Date
        const appointedWrapper = document.createElement('div');
        appointedWrapper.classList.add('col', 'mb-3');
        appointedWrapper.innerHTML = `
        <label class="form-label">Appointed Date</label>
        <input type="date" class="form-control" name="positions[${positionIndex}][empAssAppointedDate]" required>
    `;

        // ✅ End Date
        const endDateWrapper = document.createElement('div');
        endDateWrapper.classList.add('col', 'mb-3');
        endDateWrapper.innerHTML = `
        <label class="form-label">End Date</label>
        <input type="date" class="form-control" name="positions[${positionIndex}][empAssEndDate]">
    `;

        // ✅ Remove button
        const removeWrapper = document.createElement('div');
        removeWrapper.classList.add('col-auto', 'mb-3', 'd-flex', 'align-items-end');
        removeWrapper.innerHTML = `
        <button type="button" class="btn btn-danger btn-sm" onclick="removePositionField(this)">Remove</button>
    `;

        // ✅ Add all to positionItem row
        positionItem.appendChild(hiddenInput);
        positionItem.appendChild(selectWrapper);
        positionItem.appendChild(appointedWrapper);
        positionItem.appendChild(endDateWrapper);
        positionItem.appendChild(removeWrapper);

        container.appendChild(positionItem);
        positionIndex++;
    }




    function removePositionField(button) {
        const positionItem = button.closest('.position-item');
        positionItem.remove();
    }

    function updateProgramsDropdown(departmentSelect) {
        const selectedOption = departmentSelect.options[departmentSelect.selectedIndex];
        const programs = JSON.parse(selectedOption.getAttribute('data-programs') || '[]');
        const programSelect = document.querySelector('[name="programCode"]');

        // Reset programs
        programSelect.innerHTML = '<option value="">None</option>';

        if (programs.length > 0) {
            programs.forEach(program => {
                const option = document.createElement('option');
                option.value = program.programCode;
                option.textContent = program.programName;
                programSelect.appendChild(option);
            });
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
        const officeSelect = document.getElementById('officeID');

        if (departmentSelect) {
            departmentSelect.addEventListener('change', function() {
                updateProgramsDropdown(departmentSelect);
                autoCheckHeadStatus();
            });

            // Update programs on initial load
            updateProgramsDropdown(departmentSelect);
        }

        if (officeSelect) {
            officeSelect.addEventListener('change', autoCheckHeadStatus);
        }

        // Initial auto-check
        autoCheckHeadStatus();
    });


    // ✅ Hide programContainer on page load
    document.addEventListener('DOMContentLoaded', () => {
        document.getElementById('programContainer').style.display = 'none';
    });
</script>