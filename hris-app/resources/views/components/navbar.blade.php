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
            
            @if(Auth::check())
                @if(Auth::user()->role === 'admin')
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('user_management') ? 'active' : '' }}" href="{{ route('user_management') }}">
                            User Management
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('assignment_management') ? 'active' : '' }}" href="{{ route('assignment_management') }}">
                            Position Management
                        </a>
                    </li>
                @elseif (Auth::user()->role === 'hr')
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
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('contribution_management') ? 'active' : '' }}" href="{{ route('contribution_management') }}">
                            Contribution Management
                        </a>
                    </li>
                @elseif(Auth::user()->role === 'employee')
                    @php
                        $hasVPAccess = Auth::user()->employee?->hasPosition(['President', 'Vice President of Academic Affairs', 'VP Finance', 'Head Office']);
                    @endphp
                    @if(!$hasVPAccess)
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('leave_application') ? 'active' : '' }}" href="{{ route('leave_application') }}">
                                Leave
                            </a>
                        </li>
                    @endif
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('attendance') ? 'active' : '' }}" href="{{ route('attendance') }}">
                            Attendance
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('contribution.show') ? 'active' : '' }}" href="{{ route('contribution.show') }}">
                            Contribution
                        </a>
                    </li>

                    @if($hasVPAccess)
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('leave_management') ? 'active' : '' }}" href="{{ route('leave_management') }}">
                                Leave Management
                            </a>
                        </li>
                    @endif
                @endif
            @endif
        </ul>
    </div>
</nav>
