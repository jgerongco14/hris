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
            <div class="col-2 p-0">
                <div class="sidebar h-100">
                    @include('components.sidebar')
                </div>
            </div>

            <div class="col-10 p-3 pt-0">
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
                    @include('pages.admin.component.modal')

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
                                    <th>No</th>
                                    <th>Position ID</th>
                                    <th>Position Name</th>
                                    <th>Position Description</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @if($positions->isEmpty())
                                <tr>
                                    <td colspan="6" class="text-center">No positions available.</td>
                                </tr>
                                @else
                                @foreach($positions as $position)
                                <tr>
                                    <td class=" text-center">{{ $loop->iteration }}</td>
                                    <td class="text-center">{{ $position->positionID }}</td>
                                    <td>{{ $position->positionName }}</td>
                                    <td class="col-6">{{ $position->positionDescription }}</td>
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
                        @if($positions->hasPages())
                        <div class="d-flex flex-column align-items-center mt-4 gap-2">
                            <div>
                                {{ $positions->links('pagination::bootstrap-5') }}
                            </div>
                            <div class="text-muted small">
                                Showing {{ $positions->firstItem() }} to {{ $positions->lastItem() }} of {{ $positions->total() }} results
                            </div>
                        </div>
                        @endif

                    </div>
                </div>
            </div>

        </div>
    </div>

    <script>
        function showIndividualForm() {
            // Show the individual position form and hide the import form
            document.getElementById('positionForm').style.display = 'block';
            document.getElementById('importForm').style.display = 'none';
            document.getElementById('addPositionModalLabel').textContent = 'Add Individual Position';
            document.getElementById('submitButton').textContent = 'Add Position';
        }

        function showImportForm() {
            // Show the import position form and hide the individual form
            document.getElementById('positionForm').style.display = 'none';
            document.getElementById('importForm').style.display = 'block';
            document.getElementById('addPositionModalLabel').textContent = 'Import Positions';
            document.getElementById('submitButton').textContent = 'Import Positions';
        }

        document.getElementById('addPositionModal').addEventListener('hidden.bs.modal', function() {
            const form = document.getElementById('positionForm');
            form.reset();
            form.action = "{{ route('assignment.storePosition') }}"; // Reset the action for individual form
        });

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