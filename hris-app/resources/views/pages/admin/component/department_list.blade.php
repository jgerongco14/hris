<div class="col">
    <!-- Add New Department -->
    <div class="card mb-3">
        <div class="card-header">
            <h5>Add New Department</h5>
            <button id="toggleAddDepartmentForm" class="btn btn-secondary" onclick="toggleForm('addDepartmentForm', 'toggleAddDepartmentForm')">Add Individual</button>
            <button id="toggleImportDepartmentForm" class="btn btn-secondary" onclick="toggleForm('importDepartmentForm', 'toggleImportDepartmentForm')">Import File</button>
        </div>
        <div class="card-body" id="addDepartmentForm" style="display: none;">
            <form action="{{ route('departments.store') }}" method="POST">
                @csrf

                <div class="mb-3">
                    <label for="departmentCode" class="form-label">Department Code</label>
                    <input type="text" name="departmentCode" id="departmentCode" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label for="name" class="form-label">Department Name</label>
                    <input type="text" name="name" id="name" class="form-control" required>
                </div>

                <div id="programs-container">
                    <div class="program-item mb-3">
                        <label for="programCode" class="form-label">Program Code</label>
                        <input type="text" name="programs[0][programCode]" class="form-control mb-2" required>
                        <label for="programName" class="form-label">Program Name</label>
                        <input type="text" name="programs[0][programName]" class="form-control mb-2" required>
                        <button type="button" class="btn btn-danger btn-sm" onclick="removeProgramField(this)">Remove</button>
                    </div>
                </div>
                <button type="button" class="btn btn-secondary mb-3" onclick="addProgramField()">Add Another Program</button>
                <button type="submit" class="btn btn-success mb-3">Add Department</button>
            </form>
        </div>
        <div class="card-body" id="importDepartmentForm" style="display: none;">
            <form action="{{ route('departments.import') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="mb-3">
                    <label for="department_file" class="form-label">Upload File</label>
                    <input type="file" name="department_file" id="department_file" class="form-control" required>
                </div>
                <button type="submit" class="btn btn-success">Import Departments</button>
            </form>
        </div>
    </div>
    <table class="table table-bordered table-striped table-hover">
        <thead>
            <tr class="text-center">
                <th>Code</th>
                <th>Department</th>
                <th>Programs</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($departments as $department)
            <tr>
                <td>{{ $department->departmentCode }}</td>
                <td>{{ $department->departmentName }}</td>
                <td>
                    @if ($department->programs->isNotEmpty())
                    @foreach ($department->programs as $program)
                    <div class="card-body mb-2 border rounded bg-light">
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <div>
                                <h6 class="mb-0"><strong>Code:</strong> {{ $program->programCode }}</h6>
                                <p class="mb-0"><strong>Name:</strong> {{ $program->programName }}</p>
                            </div>
                            <form action="{{ route('departments.removeProgram', ['departmentId' => $department->id, 'programId' => $program->id]) }}" method="POST" style="margin-left: 10px;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-sm">
                                    <i class="ri-delete-bin-line"></i> <!-- Delete Icon -->
                                </button>
                            </form>
                        </div>
                    </div>
                    @endforeach
                    @else
                    <span class="text-muted">No programs assigned</span>
                    @endif
                </td>
                <td class="col-3 text-center align-middle">
                    <form action="{{ route('departments.destroy', $department->id) }}" method="POST" style="display:inline;">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger btn-sm">
                            <i class="ri-delete-bin-line"></i> <!-- Delete Icon -->
                        </button>
                    </form>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="4" class="text-center">No departments found.</td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>

<script>
    let programIndex = 1;

    function addProgramField() {
        const container = document.getElementById('programs-container');
        const programItem = document.createElement('div');
        programItem.classList.add('program-item', 'mb-3');
        programItem.innerHTML = `
            <label for="programCode" class="form-label">Program Code</label>
            <input type="text" name="programs[${programIndex}][programCode]" class="form-control mb-2" required>
            <label for="programName" class="form-label">Program Name</label>
            <input type="text" name="programs[${programIndex}][programName]" class="form-control mb-2" required>
            <button type="button" class="btn btn-danger btn-sm" onclick="removeProgramField(this)">Remove</button>
        `;
        container.appendChild(programItem);
        programIndex++;
    }

    function removeProgramField(button) {
        const programItem = button.parentElement;
        programItem.remove();
    }
</script>