<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Change Password</title>
    @vite(['resources/sass/app.scss', 'resources/js/app.js'])
    <link href="https://cdn.jsdelivr.net/npm/remixicon@2.5.0/fonts/remixicon.css" rel="stylesheet">
</head>

<body>
    <div class="container-fluid">
        <div class="row">
            <div class="col-2 p-0">
                <div class="vh-100 position-sticky top-0">
                    @include('components.sidebar')
                </div>
            </div>
            <div class="col-10">
                <x-notification />

                <div class="position-sticky top-0 z-3 w-100">
                    <x-titlebar />
                </div>

                <div class="card my-4 mx-3">
                    <div class="card-header text-center">
                        <h3 class="card-title">Change Password</h3>
                    </div>
                    <div class="card-body justify-content-center align-items-center d-flex flex-column">

                        <form method="POST" action="{{ route('password.change.update') }}" id="changePasswordForm">
                            @csrf
                            <div class="col mb-3">
                                <label for="current_password" class="form-label">Current Password</label>
                                <div class="input-group">
                                    <input type="password" name="current_password" id="current_password" class="form-control"  required>
                                    <span class="input-group-text">
                                        <i class="ri-eye-off-line" id="toggleCurrentPassword" style="cursor: pointer;"></i>
                                    </span>
                                </div>
                            </div>

                            <div class="mb-3">
                                <label for="new_password" class="form-label">New Password</label>
                                <div class="input-group">
                                    <input type="password" name="new_password" id="new_password" class="form-control" minlength="8" required>
                                    <span class="input-group-text">
                                        <i class="ri-eye-off-line" id="toggleNewPassword" style="cursor: pointer;"></i>
                                    </span>
                                </div>
                                <small class="text-muted">Password must be at least 8 characters long.</small>
                            </div>

                            <div class="mb-3">
                                <label for="new_password_confirmation" class="form-label">Confirm New Password</label>
                                <div class="input-group">
                                    <input type="password" name="new_password_confirmation" id="new_password_confirmation" class="form-control" minlength="8" required>
                                    <span class="input-group-text">
                                        <i class="ri-eye-off-line" id="toggleConfirmPassword" style="cursor: pointer;"></i>
                                    </span>
                                </div>
                            </div>

                            <div class="text-center my-4">
                                <button type="submit" class="btn btn-primary ">Update Password</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
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
        // Toggle password visibility
        function togglePasswordVisibility(toggleId, inputId) {
            const toggleIcon = document.getElementById(toggleId);
            const passwordInput = document.getElementById(inputId);

            toggleIcon.addEventListener('click', function() {
                if (passwordInput.type === 'password') {
                    passwordInput.type = 'text';
                    toggleIcon.classList.remove('ri-eye-off-line');
                    toggleIcon.classList.add('ri-eye-line');
                } else {
                    passwordInput.type = 'password';
                    toggleIcon.classList.remove('ri-eye-line');
                    toggleIcon.classList.add('ri-eye-off-line');
                }
            });
        }

        // Apply toggle functionality to all password fields
        togglePasswordVisibility('toggleCurrentPassword', 'current_password');
        togglePasswordVisibility('toggleNewPassword', 'new_password');
        togglePasswordVisibility('toggleConfirmPassword', 'new_password_confirmation');

        // Confirmation dialog on form submission
        document.getElementById('changePasswordForm').addEventListener('submit', function(e) {
            const newPassword = document.getElementById('new_password').value;
            const confirmPassword = document.getElementById('new_password_confirmation').value;

            // Check if passwords match
            if (newPassword !== confirmPassword) {
                toast('Password Mismatch', 'New Password and Confirm Password do not match.', 'danger');
                e.preventDefault();
                return;
            }

            // Check if password length is valid
            if (newPassword.length < 8) {
                toast('Invalid Password', 'Password must be at least 8 characters long.', 'danger');
                e.preventDefault();
                return;
            }

            const confirmation = confirm('Are you sure you want to update your password?');
            if (!confirmation) {
                e.preventDefault();
            }
        });
    </script>
</body>

</html>