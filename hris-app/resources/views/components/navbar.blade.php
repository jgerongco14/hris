<!-- resources/views/components/navbar.blade.php -->

<nav class="navbar navbar-expand-lg navbar-light bg-light">
    <div class="collapse navbar-collapse flex-column" id="navbarNav">
        <a class="navbar-brand my-5 h-100" href="#">HRIS</a>

        <ul class="navbar-nav flex-column">
            <!-- Always visible -->
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('myProfile') ? 'active' : '' }}" href="{{ route('myProfile') }}">
                    My Profile
                </a>
            </li>

            @if(Auth::check() && Auth::user()->role === 'hr')
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('employee_management') ? 'active' : '' }}" href="{{ route('employee_management') }}">
                    Employee Management
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('leave_management') ? 'active' : '' }}" href="{{ route('leave_management') }}">
                    Leave Management
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('attendance_management') ? 'active' : '' }}" href="{{ route('attendance_management') }}">
                    Attendance Management
                </a>
            </li>

            @elseif(Auth::check() && Auth::user()->role === 'employee')
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('leave_application') ? 'active' : '' }}" href="{{ route('leave_application') }}">
                    Leave
                </a>
            </li>
            @endif
        </ul>
    </div>
</nav>