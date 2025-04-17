<div class="col">

    <!-- Department/Program Modals -->
    <!-- Add Individual Department Modal -->
    <div class="modal fade" id="addDepartmentModal" tabindex="-1" aria-labelledby="addDepartmentModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addDepartmentModalLabel">Add New Department/Program</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="{{ route('departments.store') }}" method="POST">
                    @csrf
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="departmentCode" class="form-label">Department Code</label>
                            <input type="text" name="departmentCode" id="departmentCode" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label for="name" class="form-label">Department Name</label>
                            <input type="text" name="name" id="name" class="form-control" required>
                        </div>

                        <div id="programs-container">
                            <div class="program-item mb-3 border p-3 rounded">
                                <h6>Program #1</h6>
                                <div class="mb-3">
                                    <label for="programCode" class="form-label">Program Code</label>
                                    <input type="text" name="programs[0][programCode]" class="form-control mb-2" required>
                                </div>
                                <div class="mb-3">
                                    <label for="programName" class="form-label">Program Name</label>
                                    <input type="text" name="programs[0][programName]" class="form-control mb-2" required>
                                </div>
                                <button type="button" class="btn btn-danger btn-sm" onclick="removeProgramField(this)">Remove Program</button>
                            </div>
                        </div>
                        <button type="button" class="btn btn-secondary mb-3" onclick="addProgramField()">Add Another Program</button>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary">Save Department</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Import Department Modal -->
    <div class="modal fade" id="importDepartmentModal" tabindex="-1" aria-labelledby="importDepartmentModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="importDepartmentModalLabel">Import Departments/Programs</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="{{ route('departments.import') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="department_file" class="form-label">Upload File</label>
                            <input type="file" name="department_file" id="department_file" class="form-control" required>
                        </div>
                        <div class="alert alert-info">
                            <small>File should include columns: Department Code, Department Name, Program Code, Program Name</small>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary">Import</button>
                    </div>
                </form>
            </div>
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
                    @php
                    // Filter only programs that have at least code or name
                    $validPrograms = $department->programs->filter(function ($program) {
                    return !empty($program->programCode) || !empty($program->programName);
                    });
                    @endphp

                    @if($validPrograms->isNotEmpty())
                    @foreach($validPrograms as $program)
                    <div class="card-body mb-2 border rounded bg-light">
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <div>
                                <h6 class="mb-0"><strong>Code:</strong> {{ $program->programCode }}</h6>
                                <p class="mb-0"><strong>Name:</strong> {{ $program->programName }}</p>
                            </div>
                            <form action="{{ route('departments.removeProgram', ['departmentId' => $department->id, 'programId' => $program->id]) }}" method="POST">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-sm">
                                    <i class="ri-delete-bin-line"></i>
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
                    <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#editDepartmentModal-{{ $department->id }}">
                        <i class="ri-pencil-line"></i>
                    </button>
                    <form action="{{ route('departments.destroy', $department->id) }}" method="POST" style="display:inline;">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger btn-sm">
                            <i class="ri-delete-bin-line"></i>
                        </button>
                    </form>
                </td>
            </tr>
            <!-- Edit Department Modal -->
            <div class="modal fade" id="editDepartmentModal-{{ $department->id }}" tabindex="-1" aria-labelledby="editDepartmentModalLabel-{{ $department->id }}" aria-hidden="true">
                <div class="modal-dialog modal-lg">
                    <div class="modal-content">
                        <form method="POST" action="{{ route('departments.update', $department->id) }}">
                            @csrf
                            @method('PUT')

                            <div class="modal-header">
                                <h5 class="modal-title" id="editDepartmentModalLabel-{{ $department->id }}">Edit Department</h5>
                                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                            </div>

                            <div class="modal-body">
                                <div class="mb-3">
                                    <label class="form-label">Department Code</label>
                                    <input type="text" name="departmentCode" class="form-control" value="{{ $department->departmentCode }}" required>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label">Department Name</label>
                                    <input type="text" name="name" class="form-control" value="{{ $department->departmentName }}" required>
                                </div>

                                <hr>
                                <h6 class="fw-bold">Programs</h6>

                                <div id="edit-programs-container-{{ $department->id }}">
                                    @foreach ($department->programs as $index => $program)
                                    <div class="program-item border p-3 rounded mb-3 bg-light">
                                        <input type="hidden" name="programs[{{ $index }}][id]" value="{{ $program->id }}">
                                        <div class="mb-2">
                                            <label class="form-label">Program Code</label>
                                            <input type="text" name="programs[{{ $index }}][programCode]" value="{{ $program->programCode }}" class="form-control" required>
                                        </div>
                                        <div class="mb-2">
                                            <label class="form-label">Program Name</label>
                                            <input type="text" name="programs[{{ $index }}][programName]" value="{{ $program->programName }}" class="form-control" required>
                                        </div>
                                        <button type="button" class="btn btn-danger btn-sm" onclick="removeProgramField(this)">Remove</button>
                                    </div>
                                    @endforeach

                                </div>

                                <!-- Add Program Button -->
                                <button type="button" class="btn btn-secondary btn-sm mb-3" onclick="addProgramFieldToCreateModal('edit-programs-container-{{ $department->id }}')">
                                    + Add Another Program
                                </button>
                            </div>

                            <div class="modal-footer">
                                <button class="btn btn-secondary" type="button" data-bs-dismiss="modal">Cancel</button>
                                <button class="btn btn-primary" type="submit">Update Department</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            @empty
            <tr>
                <td colspan="4" class="text-center">No departments found.</td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>
<script>
    // Global counter for new programs
    let programCounter = 0;

    function addProgramFieldToCreateModal(containerId) {
        const container = document.getElementById(containerId);
        if (!container) {
            console.error('Container not found:', containerId);
            return;
        }

        // Get the current number of program items to determine the next index
        const currentItems = container.querySelectorAll('.program-item').length;
        const newIndex = currentItems;

        const programDiv = document.createElement('div');
        programDiv.classList.add('program-item', 'border', 'p-3', 'rounded', 'mb-3', 'bg-light');
        programDiv.innerHTML = `
            <div class="mb-2">
                <label class="form-label">Program Code</label>
                <input type="text" name="programs[${newIndex}][programCode]" class="form-control" required>
            </div>
            <div class="mb-2">
                <label class="form-label">Program Name</label>
                <input type="text" name="programs[${newIndex}][programName]" class="form-control" required>
            </div>
            <button type="button" class="btn btn-danger btn-sm" onclick="removeProgramField(this)">Remove</button>
        `;
        container.appendChild(programDiv);
        
        programCounter++;
    }

    function removeProgramField(button) {
        const programItem = button.closest('.program-item');
        if (programItem) {
            programItem.remove();

        }
    }
</script>