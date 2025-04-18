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
                            <h3 class="my-4">Personal Information</h3>
                            <div class="row">
                                <div class="col">
                                    <h5>ID Number</h5>
                                    <span class="profile-text" id="idText">{{ $employee->empID ?? '' }}</span>
                                    <input type="text" class="form-control profile-input d-none" id="idInput" name="empID" value="{{ $employee->empID ?? '' }}" readonly>
                                </div>
                                <div class="col">
                                    <h5>Date Hired</h5>
                                    <span class="profile-text" id="empDateHiredText">{{ $employee->empDateHired ?? '' }}</span>
                                    <input type="text" class="form-control profile-input d-none" id="empDateHiredInput" name="empDateHired" value="{{ $employee->empDateHired ?? 'Unknown' }}" readonly>
                                </div>
                                <div class="col">
                                    <h5>Date Resigned</h5>
                                    <span class="profile-text" id="empDateResignedText">{{ $employee->empDateResigned ?? '' }}</span>
                                    <input type="text" class="form-control profile-input d-none" id="empDateResignedInput" name="empDateResigned" value="{{ $employee->empDateResigned ?? 'Unknown' }}" readonly>
                                </div>
                            </div>
                            <div class="row my-3">
                                <div class="col">
                                    <h5>Personel Status</h5>
                                    <span class="profile-text" id="empPersonelStatusText">{{ $employee->empPersonelStatus ?? '' }}</span>
                                    <input type="text" class="form-control profile-input d-none" id="empPersonelStatusInput" name="empPersonelStatus" value="{{ $employee->empPersonelStatus ?? 'Unknown' }}" readonly>
                                </div>
                                <div class="col">
                                    <h5>Employer Name</h5>
                                    <span class="profile-text" id="empEmployerNameText">{{ $employee->empEmployerName ?? '' }}</span>
                                    <input type="text" class="form-control profile-input d-none" id="empEmployerNameInput" name="empEmployerName" value="{{ $employee->empEmployerName ?? 'Unknown' }}" readonly>
                                </div>
                                <div class="col">
                                    <h5>Employer Address</h5>
                                    <span class="profile-text" id="empEmployerAddressText">{{ $employee->empEmployerAddress ?? '' }}</span>
                                    <input type="text" class="form-control profile-input d-none" id="empEmployerAddressInput" name="empEmployerAddress" value="{{ $employee->empEmployerAddress ?? 'Unknown' }}" readonly>
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
                                    <span class="profile-text" id="birthdayText">{{ $employee->empBirthdate ? \Carbon\Carbon::createFromFormat('d/m/Y', $employee->empBirthdate)->format('F d, Y') : '' }}</span>
                                    <input type="date" class="form-control profile-input d-none" id="birthdayInput" name="empBdate" value="{{ $employee->empBirthdate ? \Carbon\Carbon::createFromFormat('d/m/Y', $employee->empBirthdate)->format('Y-m-d') : '' }}">
                                </div>
                                <div class="col">
                                    <h5>Civil Status</h5>
                                    <span class="profile-text" id="empCivilStatusText">{{ $employee->empCivilStatus ?? '' }}</span>
                                    <select class="form-control profile-input d-none" id="empCivilStatusInput" name="empCivilStatus">
                                        <option value="" disabled {{ empty($employee->empCivilStatus) ? 'selected' : '' }}>Select Civil Status</option>
                                        <option value="Single" {{ (isset($employee->empCivilStatus) && $employee->empCivilStatus === 'Single') ? 'selected' : '' }}>Single</option>
                                        <option value="Married" {{ (isset($employee->empCivilStatus) && $employee->empCivilStatus === 'Married') ? 'selected' : '' }}>Married</option>
                                        <option value="Widowed" {{ (isset($employee->empCivilStatus) && $employee->empCivilStatus === 'Widowed') ? 'selected' : '' }}>Widowed</option>
                                        <option value="Separated" {{ (isset($employee->empCivilStatus) && $employee->empCivilStatus === 'Separated') ? 'selected' : '' }}>Separated</option>
                                        <option value="Divorced" {{ (isset($employee->empCivilStatus) && $employee->empCivilStatus === 'Divorced') ? 'selected' : '' }}>Divorced</option>
                                    </select>
                                </div>
                                <div class="col">
                                    <h5>Blood Type</h5>
                                    <span class="profile-text" id="bloodTypeText">{{ $employee->empBloodType ?? '' }}</span>
                                    <select class="form-control profile-input d-none" id="bloodTypeInput" name="empBloodType">
                                        <option value="" disabled {{ empty($employee->empBloodType) ? 'selected' : '' }}>Select Blood Type</option>
                                        <option value="A+" {{ (isset($employee->empBloodType) && $employee->empBloodType === 'A+') ? 'selected' : '' }}>A+</option>
                                        <option value="A-" {{ (isset($employee->empBloodType) && $employee->empBloodType === 'A-') ? 'selected' : '' }}>A-</option>
                                        <option value="B+" {{ (isset($employee->empBloodType) && $employee->empBloodType === 'B+') ? 'selected' : '' }}>B+</option>
                                        <option value="B-" {{ (isset($employee->empBloodType) && $employee->empBloodType === 'B-') ? 'selected' : '' }}>B-</option>
                                        <option value="AB+" {{ (isset($employee->empBloodType) && $employee->empBloodType === 'AB+') ? 'selected' : '' }}>AB+</option>
                                        <option value="AB-" {{ (isset($employee->empBloodType) && $employee->empBloodType === 'AB-') ? 'selected' : '' }}>AB-</option>
                                        <option value="O+" {{ (isset($employee->empBloodType) && $employee->empBloodType === 'O+') ? 'selected' : '' }}>O+</option>
                                        <option value="O-" {{ (isset($employee->empBloodType) && $employee->empBloodType === 'O-') ? 'selected' : '' }}>O-</option>
                                    </select>
                                </div>
                                <div class="col">
                                    <h5>Contact Number</h5>
                                    <span class="profile-text" id="contactText">{{ $employee->empContactNo ?? '' }}</span>
                                    <input type="text" class="form-control profile-input d-none" id="contactInput" name="empContactNo" value="{{ $employee->empContactNo ?? '' }}">
                                </div>
                                <div class="col">
                                    <h5>Email</h5>
                                    <span class="profile-text" id="emailText">{{ $user->email ?? '' }}</span>
                                    <input type="email" class="form-control profile-input d-none" id="emailInput" name="email" value="{{ $user->email ?? '' }}">
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
                            <h3 class="my-4">Family Information</h3>
                            <div class="row">
                                <div class="col">
                                    <h5>Father's Name</h5>
                                    <span class="profile-text" id="fatherNameText">{{ $employee->empFatherName ?? '' }}</span>
                                    <input type="text" class="form-control profile-input d-none" id="fatherNameInput" name="empFatherName" value="{{ $employee->empFatherName ?? '' }}">
                                </div>
                                <div class="col">
                                    <h5>Mother's Name</h5>
                                    <span class="profile-text" id="motherNameText">{{ $employee->empMotherName ?? '' }}</span>
                                    <input type="text" class="form-control profile-input d-none" id="motherNameInput" name="empMotherName" value="{{ $employee->empMotherName ?? '' }}">
                                </div>
                            </div>
                            <div class="row my-3">
                                <div class="col">
                                    <h5>Spouse Name</h5>
                                    <span class="profile-text" id="spouseNameText">{{ $employee->empSpouseName ?? '' }}</span>
                                    <input type="text" class="form-control profile-input d-none" id="spouseNameInput" name="empSpouseName" value="{{ $employee->empSpouseName ?? '' }}">
                                </div>
                                <div class="col">
                                    <h5>Birh Date of Spouse</h5>
                                    <span class="profile-text" id="spouseBdateText">{{ $employee->empSpouseBdate ? \Carbon\Carbon::createFromFormat('d/m/Y', $employee->empSpouseBdate)->format('F d, Y') : '' }}</span>
                                    <input type="date" class="form-control profile-input d-none" id="spouseBdateInput" name="empSpouseBdate" value="{{ $employee->empSpouseBdate ? \Carbon\Carbon::createFromFormat('d/m/Y', $employee->empSpouseBdate)->format('Y-m-d') : '' }}">
                                </div>
                            </div>
                            <div class="row">
                                <div class="col">
                                    <h5>Children</h5>

                                    <!-- Display Mode -->
                                    <div class="profile-text">
                                        @forelse($employee->children as $child)
                                        <p>
                                            <strong>Name:</strong> {{ $child['name'] ?? 'N/A' }}<br>
                                            <strong>Birthdate:</strong>
                                            {{ $child['birthdate'] ? \Carbon\Carbon::parse($child['birthdate'])->format('F d, Y') : 'N/A' }}
                                        </p>
                                        @empty
                                        <p>No children information provided.</p>
                                        @endforelse
                                    </div>

                                    <!-- Edit Mode -->
                                    <div class="profile-input d-none" id="childrenContainer">
                                        @forelse($employee->children as $index => $child)
                                        <div class="child-entry mb-3">
                                            <div class="row">
                                                <div class="col">
                                                    <input type="text" class="form-control profile-input" name="children[{{ $index }}][name]" placeholder="Child's Name" value="{{ $child['name'] ?? '' }}">
                                                </div>
                                                <div class="col">
                                                    <input type="date" class="form-control profile-input" name="children[{{ $index }}][birthdate]" value="{{ $child['birthdate'] ?? '' }}">
                                                </div>
                                                <div class="col-auto">
                                                    <button type="button" class="btn btn-danger remove-child">Remove</button>
                                                </div>
                                            </div>
                                        </div>
                                        @empty
                                        <div class="child-entry mb-3">
                                            <div class="row">
                                                <div class="col">
                                                    <input type="text" class="form-control profile-input" name="children[0][name]" placeholder="Child's Name">
                                                </div>
                                                <div class="col">
                                                    <input type="date" class="form-control profile-input" name="children[0][birthdate]">
                                                </div>
                                                <div class="col-auto">
                                                    <button type="button" class="btn btn-danger remove-child">Remove</button>
                                                </div>
                                            </div>
                                        </div>
                                        @endforelse

                                        <button type="button" id="addChild" class="btn btn-secondary my-3">Add Child</button>
                                    </div>
                                </div>
                            </div>

                            <h3 class="my-4">In Case of Emergency, please notify</h3>
                            <div class="row my-3">
                                <div class="col">
                                    <h5>Name</h5>
                                    <span class="profile-text" id="emergencyNameText">{{ $employee->empEmergencyContactName ?? '' }}</span>
                                    <input type="text" class="form-control profile-input d-none" id="emergencyNameInput" name="empEmergencyContactName" value="{{ $employee->empEmergencyContactName ?? '' }}">
                                </div>
                                <div class="col">
                                    <h5>Contact Number</h5>
                                    <span class="profile-text" id="emergencyContactText">{{ $employee->empEmergencyContactNo ?? '' }}</span>
                                    <input type="text" class="form-control profile-input d-none" id="emergencyContactInput" name="empEmergencyContactNo" value="{{ $employee->empEmergencyContactNo ?? '' }}">
                                </div>
                            </div>
                            <div class="row">
                                <div class="col">
                                    <h5>Address</h5>
                                    <span class="profile-text" id="emergencyAddressText">{{ $employee->empEmergencyContactAddress ?? '' }}</span>
                                    <input type="text" class="form-control profile-input d-none" id="emergencyAddressInput" name="empEmergencyContactAddress" value="{{ $employee->empEmergencyContactAddress ?? '' }}">
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

        document.addEventListener('DOMContentLoaded', function() {
            const childrenContainer = document.getElementById('childrenContainer');
            const addChildButton = document.getElementById('addChild');

            addChildButton.addEventListener('click', function() {
                const index = childrenContainer.children.length;

                const childEntry = document.createElement('div');
                childEntry.classList.add('child-entry', 'mb-3');
                childEntry.innerHTML = `
            <div class="row">
                <div class="col">
                    <input type="text" class="form-control profile-input" name="children[${index}][name]" placeholder="Child's Name">
                </div>
                <div class="col">
                    <input type="date" class="form-control profile-input" name="children[${index}][birthdate]">
                </div>
                <div class="col-auto">
                    <button type="button" class="btn btn-danger remove-child">Remove</button>
                </div>
            </div>
        `;
                childrenContainer.appendChild(childEntry);
            });

            childrenContainer.addEventListener('click', function(e) {
                if (e.target.classList.contains('remove-child')) {
                    e.target.closest('.child-entry').remove();
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