<div class="col">
    <!-- Add New Office -->
    <div class="card mb-3">
        <div class="card-header">
            <h5>Add New Office</h5>
            <button id="toggleAddOfficeForm" class="btn btn-secondary" onclick="toggleForm('addOfficeForm', 'toggleAddOfficeForm')">Add Individual</button>
            <button id="toggleImportOfficeForm" class="btn btn-secondary" onclick="toggleForm('importOfficeForm', 'toggleImportOfficeForm')">Import File</button>
        </div>
        <div class="card-body" id="addOfficeForm" style="display: none;">
            <form action="{{ route('offices.store') }}" method="POST">
                @csrf
                <div class="mb-3">
                    <label for="name" class="form-label">Office Name</label>
                    <input type="text" name="name" id="name" class="form-control" required>
                </div>
                <button type="submit" class="btn btn-success">Add Office</button>
            </form>
        </div>
        <div class="card-body" id="importOfficeForm" style="display: none;">
            <form action="{{ route('offices.import') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="mb-3">
                    <label for="office_file" class="form-label">Upload File</label>
                    <input type="file" name="office_file" id="office_file" class="form-control" required>
                </div>
                <button type="submit" class="btn btn-success">Import Offices</button>
            </form>
        </div>
    </div>
    <table class="table table-bordered table-striped table-hover">
        <thead class="text-center">
            <tr>
                <th>Offices</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody class="align-middle">
            
            @forelse ($offices as $office)
            <tr>
                <td>{{ $office->officeName }}</td>
                <td class="col-3 text-center">
                    <form action="{{ route('offices.destroy', $office->id) }}" method="POST" style="display:inline;">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger">Delete</button>
                    </form>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="2" class="text-center">No offices found.</td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>