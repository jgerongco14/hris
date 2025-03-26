<!-- resources/views/components/navbar.blade.php -->

<nav class="navbar navbar-expand-lg navbar-light bg-light">

    <div class="collapse navbar-collapse flex-column" id="navbarNav">
        <a class="navbar-brand my-5 h-100" href="#">HRIS</a>
        <!-- <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button> -->
        <ul class="navbar-nav flex-column">
            <li class="nav-item">
                <a class="nav-link active" aria-current="page" href="{{ route('myProfile') }}">My Profile</a>
            </li>
            <!-- <li class="nav-item">
                <a class="nav-link" href="#">Attendance</a>
            </li> -->
            <li class="nav-item">
                <a class="nav-link" href="{{ route('employee_management') }}">Employee Management</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="{{ route('leave_management') }}">Leave</a>
            </li>
            <!-- <li class="nav-item">
                <a class="nav-link" href="#">Trainings</a>
            </li> -->
            <!-- <li class="nav-item">
                <a class="nav-link" href="#">Payroll</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="#">Directory</a>
            </li> -->
        </ul>
    </div>
</nav>