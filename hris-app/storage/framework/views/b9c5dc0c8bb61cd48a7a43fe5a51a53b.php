<!-- Sidebar Start -->
<div class="d-flex flex-column flex-shrink-0 p-3 bg-light border-end vh-100" style="width: 270px; position: fixed;">
    <div class="d-flex justify-content-center align-items-center mb-4 p-3">
        <img src="<?php echo e(asset('assets/lourdes_logo.png')); ?>" alt="Login Image" class="img-fluid">
    </div>

    <ul class="nav nav-pills flex-column mb-auto">
        <!-- Always visible -->
        <li class="nav-item">
            <a href="<?php echo e(route('myProfile')); ?>" class="nav-link <?php echo e(request()->routeIs('myProfile') ? 'active' : 'link-dark'); ?>">
                <i class="bi bi-person-circle me-2"></i> My Profile
            </a>
        </li>

        <?php if(Auth::check()): ?>
        <?php if(Auth::user()->role === 'admin'): ?>
        <li>
            <a href="<?php echo e(route('user_management')); ?>" class="nav-link <?php echo e(request()->routeIs('user_management') ? 'active' : 'link-dark'); ?>">
                <i class="bi bi-people me-2"></i> User Management
            </a>
        </li>
        <li>
            <a href="<?php echo e(route('assignment_management')); ?>" class="nav-link <?php echo e(request()->routeIs('assignment_management') ? 'active' : 'link-dark'); ?>">
                <i class="bi bi-briefcase me-2"></i> Position Management
            </a>
        </li>
        <li>
            <a href="<?php echo e(route('departments_offices_management')); ?>" class="nav-link <?php echo e(request()->routeIs('departments_offices_management') ? 'active' : 'link-dark'); ?>">
                <i class="bi bi-building me-2"></i> Departments & Offices
            </a>
        </li>

        <?php elseif(Auth::user()->role === 'hr'): ?>
        <li>
            <a href="<?php echo e(route('leave_application')); ?>" class="nav-link <?php echo e(request()->routeIs('leave_application') ? 'active' : 'link-dark'); ?>">
                <i class="bi bi-calendar-check me-2"></i> Leave
            </a>
        </li>
        <li>
            <a href="<?php echo e(route('employee_management')); ?>" class="nav-link <?php echo e(request()->routeIs('employee_management') ? 'active' : 'link-dark'); ?>">
                <i class="bi bi-person-lines-fill me-2"></i> Employee Management
            </a>
        </li>
        <li>
            <a href="<?php echo e(route('leave_management')); ?>" class="nav-link <?php echo e(request()->routeIs('leave_management') ? 'active' : 'link-dark'); ?>">
                <i class="bi bi-folder-check me-2"></i> Leave Management
            </a>
        </li>
        <li>
            <a href="<?php echo e(route('attendance_management')); ?>" class="nav-link <?php echo e(request()->routeIs('attendance_management') ? 'active' : 'link-dark'); ?>">
                <i class="bi bi-clock-history me-2"></i> Attendance Management
            </a>
        </li>
        <li>
            <a href="<?php echo e(route('contribution_management')); ?>" class="nav-link <?php echo e(request()->routeIs('contribution_management') ? 'active' : 'link-dark'); ?>">
                <i class="bi bi-cash-stack me-2"></i> Contribution Management
            </a>
        </li>
        <li>
            <a href="<?php echo e(route('reports')); ?>" class="nav-link <?php echo e(request()->routeIs('reports') ? 'active' : 'link-dark'); ?>">
                <i class="bi bi-cash-stack me-2"></i> Reports
            </a>
        </li>

        <?php elseif(Auth::user()->role === 'employee'): ?>
        <?php
        $isHeadOfOffice = Auth::user()->employee?->assignments()->where('empHead', 1)->exists();
        $hasVPAccess = Auth::user()->employee?->hasPosition(['President', 'Vice President of Academic Affairs', 'VP Finance']);
        ?>
        <li>
            <a href="<?php echo e(route('leave_application')); ?>" class="nav-link <?php echo e(request()->routeIs('leave_application') ? 'active' : 'link-dark'); ?>">
                <i class="bi bi-calendar-check me-2"></i> Leave
            </a>
        </li>
        <li>
            <a href="<?php echo e(route('attendance')); ?>" class="nav-link <?php echo e(request()->routeIs('attendance') ? 'active' : 'link-dark'); ?>">
                <i class="bi bi-clock me-2"></i> Attendance
            </a>
        </li>
        <li>
            <a href="<?php echo e(route('contribution.show')); ?>" class="nav-link <?php echo e(request()->routeIs('contribution.show') ? 'active' : 'link-dark'); ?>">
                <i class="bi bi-wallet2 me-2"></i> Contribution
            </a>
        </li>
        <?php if($hasVPAccess || $isHeadOfOffice): ?>
        <li>
            <a href="<?php echo e(route('leave_management')); ?>" class="nav-link <?php echo e(request()->routeIs('leave_management') ? 'active' : 'link-dark'); ?>">
                <i class="bi bi-folder-check me-2"></i> Leave Management
            </a>
        </li>
        <?php endif; ?>
        <?php endif; ?>
        <?php endif; ?>
    </ul>
</div>
<!-- Sidebar End --><?php /**PATH C:\Projects\hris\hris-app\resources\views/components/navbar.blade.php ENDPATH**/ ?>