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
                <!-- Include the titlebar component -->
                <x-titlebar />
                <h1>User Management</h1>
                <p>Manage your users here.</p>
                <!-- Include the notification component -->
                <x-notification />
                <!-- Import Attendance Button -->
                @include('components.import_file')
                <div class="dropdown my-4">
                    <button class="btn btn-primary dropdown-toggle" type="button" id="addUsersBtn" data-bs-toggle="dropdown" aria-expanded="false">
                        Add Users
                    </button>
                    <ul class="dropdown-menu" aria-labelledby="addUsersBtn">
                        <li><a class="dropdown-item" href="#" id="addIndividualBtn">Add Individual User</a></li>
                        <li><a class="dropdown-item" href="#" data-bs-toggle="modal" data-bs-target="#addUsers">Import Users (CSV/Excel)</a></li>
                    </ul>
                </div>

                <!-- Add Individual User Form -->
                <div id="userForm" style="display:none;" name="userForm">
                    <div class="card my-4">
                        <div class="card-body">
                            <h2 id="formTitle">Add Individual User</h2>
                            <form method="POST" id="userFormElement" action="{{ route('user.create') }}" class="d-flex align-items-end flex-wrap gap-3 mb-4">
                                @csrf
                                <input type="hidden" name="_method" id="formMethod" value="POST"> <!-- For PUT method -->
                                <input type="hidden" name="user_id" id="user_id"> <!-- Hidden input for user ID -->

                                <div class="mx-3 mb-0">
                                    <label for="empID" class="form-label">Employee ID</label>
                                    <input type="text" class="form-control" name="empID" id="empID" required>
                                </div>
                                <div class="mx-3 mb-0">
                                    <label for="email" class="form-label">Email</label>
                                    <input type="email" class="form-control" name="email" id="email" required>
                                </div>
                                <div class="mx-3 mb-0">
                                    <label for="role" class="form-label">Role</label>
                                    <select class="form-select" name="role" id="role" required>
                                        <option value="" disabled>Select Role</option>
                                        <option value="admin">Admin</option>
                                        <option value="employee">Employee</option>
                                        <option value="manager">HR</option>
                                    </select>
                                </div>
                                <div class="mx-3 mb-0 d-flex align-items-end">
                                    <button type="submit" class="btn btn-primary" id="submitButton">Add User</button>
                                    <button type="button" class="btn btn-secondary ms-2" id="cancelBtn">Cancel</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

                <div class="card my-4 p-3">

                    <div class="d-flex align-items-end gap-3 flex-wrap mb-4">
                        <!-- Filter by Role -->
                        <form method="GET" action="{{ route('user_management') }}">
                            <div class="mb-0">
                                <label for="role" class="form-label">Filter by Role</label>
                                <select name="role" id="role" class="form-select" onchange="this.form.submit()">
                                    <option value="">All Roles</option>
                                    <option value="employee" {{ request('role') == 'employee' ? 'selected' : '' }}>Employee</option>
                                    <option value="hr" {{ request('role') == 'hr' ? 'selected' : '' }}>HR</option>
                                </select>
                            </div>
                        </form>

                        <!-- Search by Email or EmpID -->
                        <form method="GET" action="{{ route('user_management') }}" class="d-flex align-items-end gap-2">
                            <div class="mb-0 d-flex align-items-center">
                                <label for="search" class="form-label visually-hidden">Search</label>
                                <input type="text" name="search" id="search" class="form-control me-2" placeholder="Search by Email or EmpID" value="{{ request('search') }}">
                                <button type="submit" class="btn btn-primary d-flex align-items-center">
                                    <i class="ri-search-line"></i> <!-- Search Icon -->
                                </button>
                                <a href="{{ route('user_management') }}" class="btn btn-primary d-flex align-items-center ms-2">
                                    <i class="ri-restart-line"></i> <!-- Reset Icon -->
                                </a>
                            </div>
                        </form>
                    </div>

                    <div class="card-body">
                        <table class="table table-bordered">
                            <thead>
                                <tr class="text-center">
                                    <th>EmpID</th>
                                    <th>Email</th>
                                    <th>Role</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @if($users->isEmpty())
                                <tr>
                                    <td colspan="4" class="text-center">No users available.</td>
                                </tr>
                                @else
                                @foreach($users as $user)
                                <tr>
                                    <td class="text-center">{{ $user->empID }}</td>
                                    <td>{{ $user->email }}</td>
                                    <td class="text-center">
                                        <span class="badge rounded bg-secondary text-white">{{ $user->role }}</span>
                                    </td>
                                    <td class="text-center">
                                        <!-- Add action icons here -->
                                        <a href="javascript:void(0);" class="btn btn-warning btn-sm" onclick="editUser('{{ $user->id }}', '{{ $user->empID }}', '{{ $user->email }}', '{{ $user->role }}')">
                                            <i class="ri-edit-line"></i> <!-- Edit Icon -->
                                        </a>
                                        <form action="{{ route('user.delete', $user->id) }}" method="POST" style="display:inline;" onsubmit="return confirm('Are you sure you want to delete this user?');">
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
                        @if($users->hasPages())
                        <div class="d-flex flex-column align-items-center mt-4 gap-2">
                            {{-- Pagination links --}}
                            <div>
                                {{ $users->links('pagination::bootstrap-5') }}
                            </div>

                            {{-- Showing text --}}
                            <div class="text-muted small">
                                Showing {{ $users->firstItem() }} to {{ $users->lastItem() }} of {{ $users->total() }} results
                            </div>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>


    <script>
        function editUser(id, empID, email, role) {
            // Show the form
            const userForm = document.getElementById('userForm');
            userForm.style.display = 'block';

            // Update the form title and button text
            document.getElementById('formTitle').textContent = 'Edit User';
            document.getElementById('submitButton').textContent = 'Update User';

            // Populate the form fields
            document.getElementById('user_id').value = id;
            document.getElementById('empID').value = empID;
            document.getElementById('email').value = email;
            document.getElementById('role').value = role;

            // Update the form action and method
            const form = document.getElementById('userFormElement');
            form.action = `/admin/user_management/${id}`; // Update the route to match your update route
            document.getElementById('formMethod').value = 'PUT'; // Use PUT for updates
        }

        document.addEventListener('DOMContentLoaded', function() {
            const addBtn = document.getElementById('addIndividualBtn');
            const userForm = document.getElementById('userForm');
            const addUsersModalEl = document.getElementById('addUsers');
            const cancelBtn = document.getElementById('cancelBtn');
            let addUsersModal;

            if (cancelBtn) {
                cancelBtn.addEventListener('click', function() {
                    userForm.style.display = 'none';
                });
            }

            if (addUsersModalEl) {
                addUsersModal = bootstrap.Modal.getOrCreateInstance(addUsersModalEl);
                addUsersModalEl.addEventListener('hidden.bs.modal', function() {
                    userForm.style.display = 'none';
                });
            }

            addBtn.addEventListener('click', function() {
                if (userForm.style.display === 'none' || userForm.style.display === '') {
                    userForm.style.display = 'block';
                    if (addUsersModal) addUsersModal.hide();
                } else {
                    userForm.style.display = 'none';
                }
            });
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