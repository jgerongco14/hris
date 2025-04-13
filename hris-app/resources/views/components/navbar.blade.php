<!-- Sidebar Start -->
<div class="d-flex flex-column flex-shrink-0 p-3 bg-light border-end vh-100" style="width: 270px; position: fixed;">
    <div class="d-flex justify-content-center align-items-center mb-4 p-3">
        <img src="{{ asset('assets/lourdes_logo.png') }}" alt="Login Image" class="img-fluid">
    </div>

    <ul class="nav nav-pills flex-column mb-auto">
        <!-- Always visible -->
        <li class="nav-item">
            <a href="{{ route('myProfile') }}" class="nav-link {{ request()->routeIs('myProfile') ? 'active' : 'link-dark' }}">
                <i class="bi bi-person-circle me-2"></i> My Profile
            </a>
        </li>

        @if(Auth::check())
        @if(Auth::user()->role === 'admin')
        <li>
            <a href="{{ route('user_management') }}" class="nav-link {{ request()->routeIs('user_management') ? 'active' : 'link-dark' }}">
                <i class="bi bi-people me-2"></i> User Management
            </a>
        </li>
        <li>
            <a href="{{ route('assignment_management') }}" class="nav-link {{ request()->routeIs('assignment_management') ? 'active' : 'link-dark' }}">
                <i class="bi bi-briefcase me-2"></i> Position Management
            </a>
        </li>
        <li>
            <a href="{{ route('departments_offices_management') }}" class="nav-link {{ request()->routeIs('departments_offices_management') ? 'active' : 'link-dark' }}">
                <i class="bi bi-building me-2"></i> Departments & Offices
            </a>
        </li>

        @elseif(Auth::user()->role === 'hr')
        <li>
            <a href="{{ route('leave_application') }}" class="nav-link {{ request()->routeIs('leave_application') ? 'active' : 'link-dark' }}">
                <i class="bi bi-calendar-check me-2"></i> Leave
            </a>
        </li>
        <li>
            <a href="{{ route('employee_management') }}" class="nav-link {{ request()->routeIs('employee_management') ? 'active' : 'link-dark' }}">
                <i class="bi bi-person-lines-fill me-2"></i> Employee Management
            </a>
        </li>
        <li>
            <a href="{{ route('leave_management') }}" class="nav-link {{ request()->routeIs('leave_management') ? 'active' : 'link-dark' }}">
                <i class="bi bi-folder-check me-2"></i> Leave Management
            </a>
        </li>
        <li>
            <a href="{{ route('attendance_management') }}" class="nav-link {{ request()->routeIs('attendance_management') ? 'active' : 'link-dark' }}">
                <i class="bi bi-clock-history me-2"></i> Attendance Management
            </a>
        </li>
        <li>
            <a href="{{ route('contribution_management') }}" class="nav-link {{ request()->routeIs('contribution_management') ? 'active' : 'link-dark' }}">
                <i class="bi bi-cash-stack me-2"></i> Contribution Management
            </a>
        </li>
        <li>
            <a href="{{ route('reports') }}" class="nav-link {{ request()->routeIs('reports') ? 'active' : 'link-dark' }}">
                <i class="bi bi-cash-stack me-2"></i> Reports
            </a>
        </li>

        @elseif(Auth::user()->role === 'employee')
        @php
        $isHeadOfOffice = Auth::user()->employee?->assignments()->where('empHead', 1)->exists();
        $hasVPAccess = Auth::user()->employee?->hasPosition(['President', 'Vice President of Academic Affairs', 'VP Finance']);
        @endphp
        <li>
            <a href="{{ route('leave_application') }}" class="nav-link {{ request()->routeIs('leave_application') ? 'active' : 'link-dark' }}">
                <i class="bi bi-calendar-check me-2"></i> Leave
            </a>
        </li>
        <li>
            <a href="{{ route('attendance') }}" class="nav-link {{ request()->routeIs('attendance') ? 'active' : 'link-dark' }}">
                <i class="bi bi-clock me-2"></i> Attendance
            </a>
        </li>
        <li>
            <a href="{{ route('contribution.show') }}" class="nav-link {{ request()->routeIs('contribution.show') ? 'active' : 'link-dark' }}">
                <i class="bi bi-wallet2 me-2"></i> Contribution
            </a>
        </li>
        @if($hasVPAccess || $isHeadOfOffice)
        <li>
            <a href="{{ route('leave_management') }}" class="nav-link {{ request()->routeIs('leave_management') ? 'active' : 'link-dark' }}">
                <i class="bi bi-folder-check me-2"></i> Leave Management
            </a>
        </li>
        @endif
        @endif
        @endif
    </ul>
</div>
<!-- Sidebar End -->