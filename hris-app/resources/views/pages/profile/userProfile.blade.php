<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profile</title>
    @vite(['resources/sass/app.scss', 'resources/js/app.js', 'resources/css/profile.css'])
    <link href="https://cdn.jsdelivr.net/npm/remixicon@2.5.0/fonts/remixicon.css" rel="stylesheet">
</head>

<body>
    <div class="container-fluid">
        <div class="row">
            <!-- Navigation Section -->
            <div class="col-2 p-0">
                <div class="vh-100 position-sticky top-0">
                    @include('components.sidebar')
                </div>
            </div>

            <!-- Main Content Section -->
            <div class="col-10 p-3 pt-0">
                <div class="position-sticky top-0 z-3 w-100">
                    <x-titlebar />
                </div>

                <!-- Profile Section -->
                <x-myProfile />

                <!-- Include the notification component -->
                <x-notification />

                <!-- Profile Information -->
                <form action="{{ route('profile.update') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    @if($employee)
                    <div class="card mt-3 mx-3">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <h5>Profile Information</h5>
                            <div class="d-flex justify-content-end">
                                <a href="" class="btn btn-primary">Edit Profile</a>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-2">
                                    <h5>ID Number</h5>
                                    <span class="profile-text" id="idText">{{ $employee->empID ?? '' }}</span>
                                    <input type="text" class="form-control profile-input d-none" id="idInput" name="empID" value="{{ $employee->empID ?? '' }}" readonly>
                                </div>
                            </div>
                            <div class="row my-3">
                                <div class="col">
                                    <h5>Prefix</h5>
                                    <span class="profile-text" id="prefixText">{{ $employee->empPrefix ?? '' }}</span>
                                    <input type="text" class="form-control profile-input d-none" id="prefixInput" name="empPrefix" value="{{ $employee->empPrefix ?? '' }}">
                                </div>
                                <!-- Example for First Name -->
                                <div class="col">
                                    <h5>First Name</h5>
                                    <span class="profile-text" id="firstNameText">{{ $employee->empFname ?? '' }}</span>
                                    <input type="text" class="form-control profile-input d-none" id="firstNameInput" name="empFirstName" value="{{ $employee->empFname ?? '' }}">
                                </div>
                                <div class="col">
                                    <h5>Middle Name</h5>
                                    <span class="profile-text" id="middleNameText">{{ $employee->empMname ?? '' }}</span>
                                    <input type="text" class="form-control profile-input d-none" id="middleNameInput" name="empMiddleName" value="{{ $employee->empMname ?? '' }}">
                                </div>
                                <div class="col">
                                    <h5>Last Name</h5>
                                    <span class="profile-text" id="lastNameText">{{ $employee->empLname ?? '' }}</span>
                                    <input type="text" class="form-control profile-input d-none" id="lastNameInput" name="empLastName" value="{{ $employee->empLname ?? '' }}">
                                </div>
                                <div class="col">
                                    <h5>Suffix</h5>
                                    <span class="profile-text" id="suffixText">{{ $employee->empSuffix ?? '' }}</span>
                                    <input type="text" class="form-control profile-input d-none" id="suffixInput" name="empSuffix" value="{{ $employee->empSuffix ?? '' }}">
                                </div>
                            </div>
                            <div class="row">
                                <div class="col">
                                    <h5>Gender</h5>
                                    <span class="profile-text" id="genderText">{{ $employee->empGender ?? '' }}</span>
                                    <div class="form-control profile-input d-none">
                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input" type="radio" name="empGender" id="genderMale" value="Male"
                                                {{ (isset($employee->empGender) && $employee->empGender === 'Male') ? 'checked' : '' }}>
                                            <label class="form-check-label" for="genderMale">Male</label>
                                        </div>
                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input" type="radio" name="empGender" id="genderFemale" value="Female"
                                                {{ (isset($employee->empGender) && $employee->empGender === 'Female') ? 'checked' : '' }}>
                                            <label class="form-check-label" for="genderFemale">Female</label>
                                        </div>
                                    </div>
                                </div>
                                <div class="col">
                                    <h5>Birthday</h5>
                                    <span class="profile-text" id="birthdayText">{{ $employee->empBirthdate ? \Carbon\Carbon::parse($employee->empBirthdate)->format('F d, Y') : '' }}</span>
                                    <input type="date" class="form-control profile-input d-none" id="birthdayInput" name="empBdate" value="{{ $employee->empBirthdate ? \Carbon\Carbon::parse($employee->empBirthdate)->format('Y-m-d') : '' }}">
                                </div>
                            </div>
                            <div class="row my-3">
                                <div class="col">
                                    <h5>Address</h5>
                                    <span class="profile-text" id="addressText">{{ $employee->address ?? '' }}</span>
                                    <input type="text" class="form-control profile-input d-none" id="addressInput" name="empAddress" value="{{ $employee->address ?? '' }}">
                                </div>
                            </div>
                            <div class="row my-3">
                                <div class="col">
                                    <h5>Province</h5>
                                    <span class="profile-text" id="provinceText">{{ $employee->province ?? '' }}</span>
                                    <input type="text" class="form-control profile-input d-none" id="provinceInput" name="empProvince" value="{{ $employee->province ?? '' }}">
                                </div>
                                <div class="col">
                                    <h5>City</h5>
                                    <span class="profile-text" id="cityText">{{ $employee->city ?? '' }}</span>
                                    <input type="text" class="form-control profile-input d-none" id="cityInput" name="empCity" value="{{ $employee->city ?? '' }}">
                                </div>
                                <div class="col">
                                    <h5>Barangay</h5>
                                    <span class="profile-text" id="barangayText">{{ $employee->barangay ?? '' }}</span>
                                    <input type="text" class="form-control profile-input d-none" id="barangayInput" name="empBarangay" value="{{ $employee->barangay ?? '' }}">
                                </div>
                            </div>
                            <div class="row my-3">
                                <div class="col">
                                    <h5>SSS</h5>
                                    <span class="profile-text" id="sssText">{{ $employee->empSSSNum ?? '' }}</span>
                                    <input type="text" class="form-control profile-input d-none" id="sssInput" name="empSSS" value="{{ $employee->empSSSNum ?? '' }}">
                                </div>
                                <div class="col">
                                    <h5>PAG-IBIG</h5>
                                    <span class="profile-text" id="pagibigText">{{ $employee->empPagIbigNum ?? '' }}</span>
                                    <input type="text" class="form-control profile-input d-none" id="pagibigInput" name="empPagibig" value="{{ $employee->empPagIbigNum ?? '' }}">
                                </div>
                                <div class="col">
                                    <h5>TIN</h5>
                                    <span class="profile-text" id="tinText">{{ $employee->empTinNum ?? '' }}</span>
                                    <input type="text" class="form-control profile-input d-none" id="tinInput" name="empTIN" value="{{ $employee->empTinNum ?? '' }}">
                                </div>
                            </div>
                        </div>
                    </div>
                    @endif
                </form>
                <!-- End of Profile Information -->
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const editBtn = document.querySelector('.btn-primary');
            const saveBtn = document.createElement('button');
            saveBtn.type = 'submit';
            saveBtn.className = 'btn btn-success ms-2';
            saveBtn.textContent = 'Save';
            let isEditing = false;

            editBtn.addEventListener('click', (e) => {
                e.preventDefault();

                const textFields = document.querySelectorAll('.profile-text');
                const inputFields = document.querySelectorAll('.profile-input');

                if (!isEditing) {
                    textFields.forEach(el => el.classList.add('d-none'));
                    inputFields.forEach(el => el.classList.remove('d-none'));
                    editBtn.textContent = 'Cancel';
                    editBtn.classList.remove('btn-primary');
                    editBtn.classList.add('btn-secondary');
                    editBtn.insertAdjacentElement('afterend', saveBtn);
                    isEditing = true;
                } else {
                    textFields.forEach(el => el.classList.remove('d-none'));
                    inputFields.forEach(el => el.classList.add('d-none'));
                    editBtn.textContent = 'Edit Profile';
                    editBtn.classList.remove('btn-secondary');
                    editBtn.classList.add('btn-primary');
                    saveBtn.remove();
                    isEditing = false;
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