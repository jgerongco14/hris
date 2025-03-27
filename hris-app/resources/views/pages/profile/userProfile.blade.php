<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profile</title>
    @vite(['resources/sass/app.scss', 'resources/js/app.js'])
    <link href="https://cdn.jsdelivr.net/npm/remixicon@2.5.0/fonts/remixicon.css" rel="stylesheet">
</head>

<body>
    <div class="container-fluid">
        <div class="row">
            <!-- Navigation Section -->
            <div class="col-2">
                <x-navbar />
            </div>

            <!-- Main Content Section -->
            <div class="col-10">
                <x-titlebar />

                <!-- Profile Section -->
                <div class="row my-4">
                    <div class="col">
                        @php
                            $photo = Auth::user()->employee->photo ?? null;
                            $isExternal = $photo && Str::startsWith($photo, ['http://', 'https://']);
                        @endphp

                        <div class="card d-flex flex-row align-items-center p-3">
                            <img src="{{ $isExternal ? $photo : asset('storage/' . $photo) }}"
                                alt="User Avatar"
                                width="96"
                                height="96"
                                class="rounded-circle me-4">

                            <div>
                                <h4 class="card-title mb-1">
                                    {{ Auth::user()->employee->empFname ?? '' }}
                                    {{ Auth::user()->employee->empMname ?? '' }}
                                    {{ Auth::user()->employee->empLname ?? '' }}
                                </h4>
                                <p class="card-text">Employee</p>
                                <p class="card-text">Google Account Linked</p>
                                <a href="#" class="btn btn-link">Update Profile</a>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
</body>

</html>