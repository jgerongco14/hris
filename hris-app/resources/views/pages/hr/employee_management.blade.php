<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Employee Management</title>
    @vite(['resources/sass/app.scss', 'resources/js/app.js'])
    <link href="https://cdn.jsdelivr.net/npm/remixicon@2.5.0/fonts/remixicon.css" rel="stylesheet">
</head>

<body>
    <div class="container-fluid">

        <div class="row">
            <!-- Navigation Section -->
            <div class="col-2">
                <!-- Include the navbar component -->
                <x-navbar />
            </div>

            <!-- Main Content Section -->
            <div class="col-10">
                <!-- Include the titlebar component -->
                <x-titlebar />

                @if (session('success'))
                <div class="alert alert-success" role="alert">
                    {{ session('success') }}
                </div>
                @elseif (session('error'))
                <div class="alert alert-danger" role="alert">
                    {{ session('error') }}
                </div>
                @endif

                <!-- Add Employee Button -->
                <button class="btn btn-primary my-4" id="addEmployeeBtn">Add Employee</button>

                <!-- The Form (Initially Hidden) -->
                <form method="POST" action="{{ route('addEmployee.store') }}" enctype="multipart/form-data" id="employeeForm" style="display: none;" class="container mt-4">
                    @csrf
                    <div class="row">
                        <div class="col-1 mb-3">
                            <label for="empPrefix" class="form-label">Prefix</label>
                            <input type="text" class="form-control" id="empPrefix" name="empPrefix" value="{{ old('empPrefix') }}">
                        </div>


                        <div class="col mb-3">
                            <label for="empFname" class="form-label">First Name</label>
                            <input type="text" class="form-control" id="empFname" name="empFname" value="{{ old('empFname') }}" required>
                        </div>

                        <div class="col mb-3">
                            <label for="empMname" class="form-label">Middle Name</label>
                            <input type="text" class="form-control" id="empMname" name="empMname" value="{{ old('empMname') }}">
                        </div>

                        <div class="col mb-3">
                            <label for="empLname" class="form-label">Last Name</label>
                            <input type="text" class="form-control" id="empLname" name="empLname" value="{{ old('empLname') }}" required>
                        </div>
                        <div class="col-1 mb-3">
                            <label for="empSuffix" class="form-label">Suffix</label>
                            <input type="text" class="form-control" id="empSuffix" name="empSuffix" value="{{ old('empSuffix') }}">
                        </div>
                    </div>

                    <div class="row">

                        <div class="col-4 mb-3">

                            <label for="photo" class="form-label">Profile Picture</label>
                            <input class="form-control" type="file" id="photo" name="photo">

                        </div>

                        <div class="col-4 mb-3">
                            <label class="form-label">Gender</label>
                            <div class="d-flex align-items-center">
                                <div class="form-check me-3">
                                    <input class="form-check-input" type="radio" id="male" name="empGender" value="male" {{ old('empGender', $employee->empGender ?? '') == 'male' ? 'checked' : '' }}>
                                    <label class="form-check-label" for="male">Male</label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" id="female" name="empGender" value="female" {{ old('empGender', $employee->empGender ?? '') == 'female' ? 'checked' : '' }}>
                                    <label class="form-check-label" for="female">Female</label>
                                </div>
                            </div>
                        </div>



                        <div class="col-4 mb-3">
                            <label for="empBirthdate" class="form-label">Birthdate</label>
                            <input type="text" class="form-control datepicker" id="empBirthdate" name="empBirthdate" value="{{ old('empBirthdate') }}" readonly>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="address" class="form-label">Address</label>
                        <input type="text" class="form-control" id="address" name="address" value="{{ old('address') }}">
                    </div>

                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <label for="province" class="form-label">Province</label>
                            <input type="text" class="form-control" id="province" name="province" value="{{ old('province') }}">
                        </div>

                        <div class="col-md-4 mb-3">
                            <label for="city" class="form-label">City</label>
                            <input type="text" class="form-control" id="city" name="city" value="{{ old('city') }}">
                        </div>

                        <div class="col-md-4 mb-3">
                            <label for="barangay" class="form-label">Barangay</label>
                            <input type="text" class="form-control" id="barangay" name="barangay" value="{{ old('barangay') }}">
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <label for="empSSSNum" class="form-label">SSS Number</label>
                            <input type="text" class="form-control" id="empSSSNum" name="empSSSNum" value="{{ old('empSSSNum') }}">
                        </div>

                        <div class="col-md-4 mb-3">
                            <label for="empTinNum" class="form-label">TIN Number</label>
                            <input type="text" class="form-control" id="empTinNum" name="empTinNum" value="{{ old('empTinNum') }}">
                        </div>

                        <div class="col-md-4 mb-3">
                            <label for="empPagIbigNum" class="form-label">Pag-Ibig Number</label>
                            <input type="text" class="form-control" id="empPagIbigNum" name="empPagIbigNum" value="{{ old('empPagIbigNum') }}">
                        </div>
                    </div>

                    <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                        <button type="submit" class="btn btn-primary me-md-2">Submit</button>
                        <button type="button" class="btn btn-secondary" onclick="$('#employeeForm').hide(); $('#employeeForm')[0].reset()">Cancel</button>
                    </div>
                </form>


                <!-- Employee List Section -->
                <div class="row my-4">
                    <div class="col">
                        <h3>EMPLOYEE LIST</h3>
                        <table class="table table-bordered">
                            <thead>
                                <tr class="text-center">
                                    <th class="align-middle">EmpID</th>
                                    <th class="align-middle">Full Name</th>
                                    <th class="align-middle">Position</th>
                                    <th class="align-middle">Email</th>
                                    <th class="align-middle">Address</th>
                                    <th class="align-middle">Benefits</th>
                                    <th class="align-middle">Actions</th>
                                </tr>
                            </thead>
                            <tbody>


                                @foreach($employees as $employee)
                                <tr>
                                    <td class="text-center align-middle">{{ $employee->empID }}</td>
                                    <td class="align-middle">
                                        <div class="d-flex align-items-center gap-2">
                                            @php
                                            $employeePhoto = $employee->photo ?? null;
                                            $isExternal = $employeePhoto && Str::startsWith($employeePhoto, ['http://', 'https://']);
                                            @endphp

                                            @if($employeePhoto)
                                            <img src="{{ $isExternal ? $employeePhoto : asset('storage/' . $employeePhoto) }}"
                                                alt="Employee Photo"
                                                width="50"
                                                height="50"
                                                class="rounded-circle">
                                            @else
                                            <div class="no-photo bg-light rounded-circle d-flex align-items-center justify-content-center"
                                                style="width:50px; height:50px;">
                                                <i class="ri-user-line"></i>
                                            </div>
                                            @endif

                                            <span>
                                                {{ $employee->empPrefix }}
                                                {{ $employee->empFname }}
                                                {{ $employee->empMname }}
                                                {{ $employee->empLname }}
                                                {{ $employee->empSuffix }}
                                            </span>
                                        </div>
                                    </td>
                                    <td class="align-middle">{{ $employee->position->positionName ?? 'N/A' }}</td>
                                    <td class="align-middle">{{ $employee->email }}</td>
                                    <td class="align-middle">
                                        <ul class="list-unstyled mb-0">
                                            <li>{{ $employee->address }}</li>
                                            <li>{{ $employee->city }}, {{ $employee->province }}</li>
                                            <li>{{ $employee->barangay }}</li>
                                        </ul>
                                    <td class="align-middle">
                                        <ul class="list-unstyled mb-0">
                                            <li>SSS: {{ $employee->empSSSNum ?? 'N/A' }}</li>
                                            <li>TIN: {{ $employee->empTinNum ?? 'N/A' }}</li>
                                            <li>Pag-Ibig: {{ $employee->empPagIbigNum ?? 'N/A' }}</li>
                                        </ul>
                                    </td>
                                    <td class="align-middle">
                                        <button class="btn btn-sm btn-primary me-1">
                                            <i class="ri-pencil-line"></i>
                                        </button>
                                        <button class="btn btn-sm btn-danger">
                                            <i class="ri-delete-bin-line"></i>
                                        </button>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                </div>
            </div>
        </div>
    </div>


    <!-- Include Bootstrap CSS and JS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Include jQuery and jQuery UI for datepicker -->
    <link rel="stylesheet" href="https://code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        $(document).ready(function() {
            // Toggle form visibility when the button is clicked
            $('#addEmployeeBtn').click(function() {
                $('#employeeForm').toggle(); // Toggle visibility
            });

            // Initialize datepicker
            $('.datepicker').datepicker({
                changeMonth: true,
                changeYear: true,
                yearRange: '-100:+0',
                maxDate: new Date(),
                dateFormat: 'yy-mm-dd'
            });

            // Prevent manual input in date field
            $('#empBirthdate').on('keydown paste', function(e) {
                e.preventDefault();
                return false;
            });
        });

        $(document).ready(function() {
            // Format SSS number as XX-XXXXXXX-X
            $('#empSSSNum').on('input', function() {
                let value = $(this).val().replace(/\D/g, ''); // Remove non-numeric characters
                if (value.length > 2) value = value.slice(0, 2) + '-' + value.slice(2);
                if (value.length > 10) value = value.slice(0, 9) + '-' + value.slice(9);
                $(this).val(value);
            });

            // Format TIN number as XXX-XXX-XXX
            $('#empTinNum').on('input', function() {
                let value = $(this).val().replace(/\D/g, ''); // Remove non-numeric characters
                if (value.length > 3) value = value.slice(0, 3) + '-' + value.slice(3);
                if (value.length > 6) value = value.slice(0, 6) + '-' + value.slice(6);
                $(this).val(value);
            });

            // Format Pag-Ibig number as XXXX-XXXX-XXXX
            $('#empPagIbigNum').on('input', function() {
                let value = $(this).val().replace(/\D/g, ''); // Remove non-numeric characters
                if (value.length > 4) value = value.slice(0, 4) + '-' + value.slice(4);
                if (value.length > 8) value = value.slice(0, 8) + '-' + value.slice(8);
                $(this).val(value);
            });
        });
    </script>
</body>

</html>