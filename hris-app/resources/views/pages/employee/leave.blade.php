<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Leave</title>
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

                <div class="col">
                    @php
                    $photo = Auth::user()->employee->photo ?? null;
                    $isExternal = $photo && Str::startsWith($photo, ['http://', 'https://']);
                    @endphp

                    <div class="card d-flex flex-row align-items-center p-3 my-3">
                        <img src="{{ $isExternal ? $photo : asset('storage/' . $photo) }}"
                            alt="User Avatar"
                            width="150"
                            height="150"
                            class=" me-4">

                        <div>
                            <h6> {{ Auth::user()->employee->empID ?? '' }}</h6>
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

                <div class="col my-5">

                    @if (session('success'))
                    <div class="alert alert-success" role="alert">
                        {{ session('success') }}
                    </div>
                    @elseif (session('error'))
                    <div class="alert alert-danger" role="alert">
                        {{ session('error') }}
                    </div>
                    @endif


                    <h4 class="mb-4 fw-bold">LEAVE APPLICATION FORM</h4>

                    <form action="{{ route('leave_application.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf

                        <!-- Include emp_id as hidden input -->
                        <input type="hidden" name="emp_id" value="{{ Auth::user()->employee->empID ?? '' }}">

                        <!-- Leave type -->
                        <div class="mb-3">
                            <label for="leave_type" class="form-label fw-semibold">Type of Leave*</label>
                            <select class="form-select" id="leave_type" name="leave_type" required>
                                <option value="" disabled selected>Leave Type</option>
                                <option value="Sick Leave">Sick Leave</option>
                                <option value="Vacation Leave">Vacation Leave</option>
                                <option value="Emergency Leave">Emergency Leave</option>
                            </select>
                        </div>

                        <!-- Dates -->
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Date of Leave*</label>
                            <div class="d-flex gap-3">
                                <div>
                                    <label class="form-label small">From</label>
                                    <input type="date" class="form-control" name="leave_from" required>
                                </div>
                                <div>
                                    <label class="form-label small">To</label>
                                    <input type="date" class="form-control" name="leave_to" required>
                                </div>
                            </div>
                        </div>

                        <!-- Reason -->
                        <div class="mb-3">
                            <label for="reason" class="form-label fw-semibold">Reason / Purpose*</label>
                            <textarea class="form-control" id="reason" name="reason" rows="3" placeholder="Place the reason for leave here..." required></textarea>
                        </div>

                        <!-- Attachments -->
                        <div class="mb-4">
                            <label for="attachment" class="form-label fw-semibold">Attachment/s</label>
                            <input class="form-control" type="file" name="attachment" id="attachment" accept="image/*,application/pdf">
                            <div class="form-text">Accepted: IMAGE, PDF</div>
                        </div>

                        <!-- Hidden status (default to pending or filed) -->
                        <input type="hidden" name="status" value="Pending">

                        <!-- Submit -->
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-send me-1"></i> Submit Application
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</body>

</html>