<!-- Sidebar Start -->
@vite(['resources/sass/app.scss', 'resources/js/app.js'])
<div class="sidebar-custom d-flex flex-column flex-shrink-0 p-3 border-end vh-100">
    <div class="d-flex justify-content-center align-items-center mb-4 p-3">
        <img src="{{ asset('assets/lourdes_logo.png') }}" alt="Login Image" class="img-fluid">
    </div>
    @php
    $role = Auth::user()->role;
    $showAll = $role === 'superadmin';
    $isHeadOfOffice = Auth::user()->employee?->assignments()->where('empHead', 1)->exists();
    $hasVPAccess = Auth::user()->employee?->hasPosition(['President', 'Vice President of Academic Affairs', 'VP Finance']);
    @endphp

    <ul class="nav nav-pills flex-column mb-auto">
        <!-- Always visible -->
        <a href="{{ route('myProfile') }}" class="nav-link d-flex align-items-center gap-2 {{ request()->routeIs('myProfile') ? 'active' : 'link-dark' }}">
            <i class="ri-account-box-fill" style="font-size: 25px;"></i>
            <span>My Profile</span>
        </a>


        {{-- Admin --}}
        @if($role === 'admin' || $showAll)
        <li>
            <a href="{{ route('user_management') }}" class="nav-link d-flex align-items-center gap-2 {{ request()->routeIs('user_management') ? 'active' : 'link-dark' }}">
                <i class="ri-team-fill" style="font-size: 25px;"></i>
                <span>User Management</span>
            </a>
        </li>
        <li>
            <a href="{{ route('assignment_management') }}" class="nav-link d-flex align-items-center gap-2 {{ request()->routeIs('assignment_management') ? 'active' : 'link-dark' }}">
                <i class="ri-user-2-fill" style="font-size: 25px;"></i>
                <span>Position Management</span>
            </a>
        </li>
        <li>
            <a href="{{ route('departments_offices_management') }}" class="nav-link d-flex align-items-center gap-2 {{ request()->routeIs('departments_offices_management') ? 'active' : 'link-dark' }}">
                <i class="ri-building-fill" style="font-size: 25px;"></i>
                <span>Departments & Offices</span>
            </a>
        </li>
        <li>
            <a href="{{ route('show.activity-logs') }}" class="nav-link d-flex align-items-center gap-2 {{ request()->routeIs('show.activity-logs') ? 'active' : 'link-dark' }}">
                <i class="ri-file-2-fill" style="font-size: 25px;"></i>
                <span>Activity Logs</span>
            </a>
        </li>
        @endif

        {{-- HR --}}
        @if($role === 'hr' || $showAll)
        <li>
            <a href="{{ route('leave_application') }}" class="nav-link d-flex align-items-center gap-2 {{ request()->routeIs('leave_application') ? 'active' : 'link-dark' }}">
                <i class="ri-calendar-2-fill" style="font-size: 25px"></i>
                <span>Leave</span>
            </a>
        </li>
        <li>
            <a href="{{ route('employee_management') }}" class="nav-link d-flex align-items-center gap-2 {{ request()->routeIs('employee_management') ? 'active' : 'link-dark' }}">
                <i class="ri-team-fill" style="font-size: 25px"></i>
                <span>Employee Management</span>
            </a>
        </li>
        <li>
            <a href="{{ route('leave_management') }}" class="nav-link d-flex align-items-center gap-2 {{ request()->routeIs('leave_management') ? 'active' : 'link-dark' }}">
                <i class="ri-calendar-2-fill" style="font-size: 25px"></i>
                <span>Leave Management</span>
            </a>
        </li>
        <li>
            <a href="{{ route('attendance_management') }}" class="nav-link d-flex align-items-center gap-2 {{ request()->routeIs('attendance_management') ? 'active' : 'link-dark' }}">
                <i class="ri-time-fill" style="font-size: 25px"></i>
                <span>Attendance Management</span>
            </a>
        </li>
        <li>
            <a href="{{ route('contribution_management') }}" class="nav-link d-flex align-items-center gap-2 {{ request()->routeIs('contribution_management') ? 'active' : 'link-dark' }}">
                <i class="ri-hand-coin-fill" style="font-size: 25px"></i>
                <span>Contribution Management</span>
            </a>
        </li>
        <li>
            <a href="{{ route('reports') }}" class="nav-link d-flex align-items-center gap-2 {{ request()->routeIs('reports') ? 'active' : 'link-dark' }}">
                <i class="ri-folder-user-fill" style="font-size: 25px"></i>
                <span>Reports</span>
            </a>
        </li>
        <li>
            <a href="{{ route('training') }}" class="nav-link d-flex align-items-center gap-2 {{ request()->routeIs('training') ? 'active' : 'link-dark' }}">
                <i class="ri-book-fill" style="font-size: 25px"></i>
                <span>Trainings</span>
            </a>
        </li>
        @endif

        {{-- Employee --}}
        @if($role === 'employee' || $showAll)
        <li>
            <a href="{{ route('leave_application') }}" class="nav-link d-flex align-items-center gap-2 {{ request()->routeIs('leave_application') ? 'active' : 'link-dark' }}">
                <i class="ri-calendar-2-fill" style="font-size: 25px"></i>
                <span>Leave</span>
            </a>
        </li>
        <li>
            <a href="{{ route('attendance') }}" class="nav-link d-flex align-items-center gap-2 {{ request()->routeIs('attendance') ? 'active' : 'link-dark' }}">
                <i class="ri-time-fill" style="font-size: 25px"></i>
                <span>Attendance</span>
            </a>
        </li>
        <li>
            <a href="{{ route('contribution.show') }}" class="nav-link d-flex align-items-center gap-2 {{ request()->routeIs('contribution.show') ? 'active' : 'link-dark' }}">
                <i class="ri-hand-coin-fill" style="font-size: 25px"></i>
                <span>Contribution</span>
            </a>
        </li>
        <li>
            <a href="{{ route('training') }}" class="nav-link d-flex align-items-center gap-2 {{ request()->routeIs('training') ? 'active' : 'link-dark' }}">
                <i class="ri-book-fill" style="font-size: 25px"></i>
                <span>Trainings</span>
            </a>
        </li>
        @if($hasVPAccess || $isHeadOfOffice)
        <li>
            <a href="{{ route('leave_management') }}" class="nav-link d-flex align-items-center gap-2 {{ request()->routeIs('leave_management') ? 'active' : 'link-dark' }}">
                <i class="ri-calendar-2-fill" style="font-size: 25px"></i>
                <span>Leave Management</span>
            </a>
        </li>
        @endif
        @endif
    </ul>
    <ul class="nav nav-pills flex-column mt-auto">
        <li>
            <a href="{{ route('password.change.form') }}" class="nav-link d-flex align-items-center gap-2 {{ request()->routeIs('password.change') ? 'active' : 'link-dark' }}">
                <i class="ri-lock-password-fill" style="font-size: 25px"></i>
                <span>Change Password</span>
            </a>
        </li>
    </ul>
</div>
<!-- Sidebar End -->