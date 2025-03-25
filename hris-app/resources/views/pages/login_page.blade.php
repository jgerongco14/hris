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
        <div class="row align-items-center">
            <div class="col-xl-8 col-lg-7 col-md-6 col-sm-4">
                <div class="login-card">
                    <h2>HUMAN RESOURCE INFORMATION SYSTEM</h2>
                    <form>
                        <div class="form-group my-3">
                            <label for="email">Email</label>
                            <input type="email" class="form-control" id="email" placeholder="example.email@gmail.com" required>
                        </div>
                        <div class="form-group my-3">
                            <label for="password">Password</label>
                            <input type="password" class="form-control" id="password" placeholder="Enter at least 8 characters" required>
                        </div>
                        <div class="text-center my-4">
                            <button type="submit" class="btn btn-dark w-100">Sign in</button>
                        </div>

                    </form>

                    {{-- Sign in with Google --}}

                    <div class="text-center my-3">
                        <p>Or</p>
                        <a href="{{ route('auth.google') }}" class="google-signin text-white btn btn-danger btn-outline-dark w-30">
                            <i class="ri-google-fill"></i> Sign in with Google
                        </a>
                    </div>



                    <div class="text-center signup-link">
                        <p>For New Employees</p>
                        <a href="#" class="btn btn-secondary text-decoration-none text-white">
                            <i class="bi-pen"></i> Sign-Up Here
                        </a>
                    </div>

                </div>
            </div>
            <div class="col">
                <img src="{{ asset('images/login.svg') }}" alt="Login Image" class="img-fluid">
            </div>
        </div>
    </div>
</body>

</html>