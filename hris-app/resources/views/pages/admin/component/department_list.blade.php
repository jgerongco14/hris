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
                    <label for="name" class="form-label">Department Name</label>
                    <input type="text" name="name" id="name" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label for="programName" class="form-label">Add New Program</label>
                    <input type="text" name="programName" id="programName" class="form-control" placeholder="Enter program name (optional)">
                </div>
                <button type="submit" class="btn btn-success">Add Department</button>
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
                <th>Department</th>
                <th>Programs</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($departments as $department)
            <tr>
                <td>{{ $department->departmentName }}</td>
                <td>
                    @if ($department->programs->isNotEmpty())
                    @foreach ($department->programs as $program)
                    <span class="text-dark">{{ $program->programName }}</span><br>
                    @endforeach
                    @else
                    <span class="text-muted">No programs assigned</span>
                    @endif
                </td>
                <td class="col-3 text-center">
                    <form action="{{ route('departments.destroy', $department->id) }}" method="POST" style="display:inline;">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger">Delete</button>
                    </form>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="3" class="text-center">No departments found.</td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>