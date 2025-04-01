<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin</title>
    @vite(['resources/sass/app.scss', 'resources/js/app.js'])
    <link href="https://cdn.jsdelivr.net/npm/remixicon@2.5.0/fonts/remixicon.css" rel="stylesheet">
</head>

<body>
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-2">
                <!-- Include the navbar component -->
                <x-navbar />
            </div>

            <div class="col-md-10">
                <!-- Include the notification component -->
                <x-notification />

                <x-titlebar />

                <div class="content">
                    <h1>Assignment Management</h1>
                    <p>Manage assignments here.</p>

                    <!-- Add Position Button -->
                    <button type="button" class="btn btn-primary mb-3" data-bs-toggle="modal" data-bs-target="#addPositionModal">
                        Add Position
                    </button>


                    <!-- Positions Table -->
                    <div class="card">
                        <div class="card-body">
                            <form method="GET" action="{{ route('assignment_management') }}" class="d-flex align-items-end gap-4 my-4">
                                <div class="mb-0 d-flex align-items-center">
                                    <label for="search" class="form-label visually-hidden">Search</label>
                                    <input type="text" name="search" id="search" class="form-control me-2" placeholder="Search by Position ID or Position Name" value="{{ request('search') }}">
                                    <button type="submit" class="btn btn-primary d-flex align-items-center">
                                        <i class="ri-search-line"></i> <!-- Search Icon -->
                                    </button>
                                    <a href="{{ route('assignment_management') }}" class="btn btn-secondary d-flex align-items-center ms-2">
                                        <i class="ri-restart-line"></i> <!-- Reset Icon -->
                                    </a>
                            </form>
                        </div>
                        <table class="table table-bordered">
                            <thead>
                                <tr class="text-center">
                                    <th>#</th>
                                    <th>Position ID</th>
                                    <th>Position Name</th>
                                    <th>Position Description</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @if($positions->isEmpty())
                                <tr>
                                    <td colspan="5" class="text-center">No positions available.</td>
                                </tr>
                                @else
                                @foreach($positions as $position)
                                <tr>
                                    <td class="text-center">{{ $loop->iteration }}</td>
                                    <td class="text-center">{{ $position->positionID }}</td>
                                    <td>{{ $position->positionName }}</td>
                                    <td>{{ $position->positionDescription }}</td>
                                    <td class="text-center">
                                        <!-- Edit and Delete Buttons -->
                                        <button class="btn btn-warning btn-sm"
                                            data-id="{{ $position->id }}"
                                            data-position-id="{{ $position->positionID }}"
                                            data-position-name="{{ $position->positionName }}"
                                            data-position-description="{{ $position->positionDescription }}"
                                            onclick="editPosition(this)">
                                            <i class="ri-edit-line"></i>
                                        </button>


                                        <form action="{{ route('assignment.delete', $position->id) }}" method="POST" style="display:inline;" onsubmit="return confirm('Are you sure you want to delete this position?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-danger btn-sm">
                                                <i class="ri-delete-bin-line"></i> <!-- Delete Icon -->
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                                @endforeach
                                @endif
                            </tbody>
                        </table>

                        <!-- Pagination Links -->
                        {{ $positions->links() }}
                    </div>
                </div>
            </div>

            <!-- Add/Edit Position Modal -->
            <div class="modal fade" id="addPositionModal" tabindex="-1" aria-labelledby="addPositionModalLabel" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="addPositionModalLabel">Add Position</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <form method="POST" id="positionForm" action="{{ route('assignment.storePosition') }}">
                            @csrf
                            <input type="hidden" name="_method" id="formMethod" value="POST"> <!-- For PUT method -->
                            <input type="hidden" name="id" id="id"> <!-- Hidden input for position ID -->
                            <div class="modal-body">
                                <div class="mb-3">
                                    <label for="positionID" class="form-label">Position ID</label>
                                    <input type="text" class="form-control" id="positionID" name="positionID" required>
                                </div>
                                <div class="mb-3">
                                    <label for="positionName" class="form-label">Position Name</label>
                                    <input type="text" class="form-control" id="positionName" name="positionName" required>
                                </div>
                                <div class="mb-3">
                                    <label for="positionDescription" class="form-label">Position Description</label>
                                    <textarea class="form-control" id="positionDescription" name="positionDescription" rows="3" required></textarea>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                <button type="submit" class="btn btn-primary" id="submitButton">Add Position</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        function editPosition(button) {
            const modal = new bootstrap.Modal(document.getElementById('addPositionModal'));
            modal.show();

            document.getElementById('addPositionModalLabel').textContent = 'Edit Position';
            document.getElementById('submitButton').textContent = 'Update Position';

            document.getElementById('id').value = button.dataset.id;
            document.getElementById('positionID').value = button.dataset.positionId;
            document.getElementById('positionName').value = button.dataset.positionName;
            document.getElementById('positionDescription').value = button.dataset.positionDescription;

            const form = document.getElementById('positionForm');
            form.action = `/admin/position_management/${button.dataset.id}`;
            document.getElementById('formMethod').value = 'PUT';
        }


        document.getElementById('addPositionModal').addEventListener('hidden.bs.modal', function() {
            const form = document.getElementById('positionForm');
            form.reset();
            form.action = "{{ route('assignment.storePosition') }}"; // ✅ Correct here
            document.getElementById('formMethod').value = 'POST';
            document.getElementById('addPositionModalLabel').textContent = 'Add Position';
            document.getElementById('submitButton').textContent = 'Add Position';
        });

        function showToast(title, message, type = 'success') {
            const toastEl = document.getElementById('liveToast');
            const toastHeader = document.getElementById('toast-header');
            const toastTitle = document.getElementById('toast-title');
            const toastMessage = document.getElementById('toast-message');
            const toastIcon = document.getElementById('toast-icon');

            // Reset and keep background white
            toastEl.className = 'toast align-items-center border border-2 show bg-white';

            const headerColors = {
                success: 'text-success',
                danger: 'text-danger',
                warning: 'text-warning',
                info: 'text-info'
            };

            const icons = {
                success: '✅',
                danger: '❌',
                warning: '⚠️',
                info: 'ℹ️'
            };

            // Style header and icon
            toastHeader.className = `toast-header ${headerColors[type] || 'text-dark'}`;
            toastIcon.textContent = icons[type] || '';
            toastTitle.textContent = title;
            toastMessage.textContent = message;

            const toast = new bootstrap.Toast(toastEl, {
                delay: 10000
            });
            toast.show();
        }
    </script>
</body>

</html>