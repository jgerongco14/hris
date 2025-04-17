<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    @vite(['resources/sass/app.scss', 'resources/js/app.js'])
    <link href="https://cdn.jsdelivr.net/npm/remixicon@2.5.0/fonts/remixicon.css" rel="stylesheet">
</head>

<body>
    <div class="container-fluid mt-5">
        <x-notification />
        <div class="row align-items-center" style="height: 80vh;">
            <div class="col-xl-7 col-lg-5 col-md-4 col-sm-3">
                <div class="login-card mx-5">
                    <h2 class="mb-5">HUMAN RESOURCE INFORMATION SYSTEM</h2>

                    {{-- Login Form --}}
                    <form method="POST" action="{{ route('defaultlogin') }}">
                        @csrf
                        <div class="form-group my-3">
                            <label for="identifier">Email or Employee ID</label>
                            <input type="text" name="identifier" class="form-control" id="identifier" placeholder="example@email.com or EMP12345" required>
                        </div>

                        <div class="form-group my-3">

                            <label for="password">Password</label>
                            <div class=" d-flex align-items-center">
                                <input type="password" name="password" class="form-control" id="password" placeholder="********************" required>
                                <button type="button" class="btn btn-outline-secondary  me-2" id="togglePassword">
                                    <i class="ri-eye-off-line" id="togglePasswordIcon"></i>
                                </button>
                            </div>
                        </div>

                        <div class="text-center my-4">
                            <button type="submit" class="btn btn-dark w-100">Sign in</button>
                        </div>
                    </form>

                    {{-- Sign in with Google --}}
                    <div class="text-center my-3">
                        <p>Or</p>
                        <a href="{{ route('auth.google') }}" class="google-signin text-white btn btn-primary btn-outline-white w-100">
                            <i class="ri-google-fill"></i> Sign in with Google
                        </a>
                    </div>

                    <!-- {{-- Forgot Password Link --}}
                    <div class="text-center mt-3">
                        <a href="{{ route('password.request') }}" class="text-decoration-none">Forgot your password?</a>
                    </div> -->

                </div>
            </div>
            <div class="col-5 d-flex justify-content-center align-items-center">
                <img src="{{ asset('assets/lourdes_logo.png') }}" alt="Login Image" class="img-fluid">
            </div>
        </div>
    </div>

    <script>
        if (window.history && window.history.pushState) {
            window.history.pushState(null, null, window.location.href);
            window.onpopstate = function() {
                window.location.replace("{{ route('login') }}");
            };
        }

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

        document.getElementById('togglePassword').addEventListener('click', function() {
            const passwordInput = document.getElementById('password');
            const toggleIcon = document.getElementById('togglePasswordIcon');

            // Toggle the input type between 'password' and 'text'
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
    </script>
</body>

</html>