<!-- Sidebar Start -->
<?php echo app('Illuminate\Foundation\Vite')(['resources/sass/app.scss', 'resources/js/app.js']); ?>
<div class="sidebar-custom d-flex flex-column flex-shrink-0 p-3 border-end vh-100">
    <div class="d-flex justify-content-center align-items-center mb-4 p-3">
        <img src="<?php echo e(asset('assets/lourdes_logo.png')); ?>" alt="Login Image" class="img-fluid">
    </div>
    <?php
    $role = Auth::user()->role;
    $showAll = $role === 'superadmin';
    $isHeadOfOffice = Auth::user()->employee?->assignments()->where('empHead', 1)->exists();
    $hasVPAccess = Auth::user()->employee?->hasPosition(['President', 'Vice President of Academic Affairs', 'VP Finance']);
    $isFinanceEmployee = Auth::user()->employee?->isAssignedToOfficeByName('Finance');
    ?>

    <ul class="nav nav-pills flex-column mb-auto">
        <!-- Always visible -->
        <a href="<?php echo e(route('myProfile')); ?>" class="nav-link d-flex align-items-center gap-2 <?php echo e(request()->routeIs('myProfile') ? 'active' : 'link-dark'); ?>">
            <i class="ri-account-box-fill" style="font-size: 25px;"></i>
            <span>My Profile</span>
        </a>


        
        <?php if($role === 'admin' || $showAll): ?>
        <li>
            <a href="<?php echo e(route('user_management')); ?>" class="nav-link d-flex align-items-center gap-2 <?php echo e(request()->routeIs('user_management') ? 'active' : 'link-dark'); ?>">
                <i class="ri-team-fill" style="font-size: 25px;"></i>
                <span>User Management</span>
            </a>
        </li>
        <li>
            <a href="<?php echo e(route('assignment_management')); ?>" class="nav-link d-flex align-items-center gap-2 <?php echo e(request()->routeIs('assignment_management') ? 'active' : 'link-dark'); ?>">
                <i class="ri-user-2-fill" style="font-size: 25px;"></i>
                <span>Position Management</span>
            </a>
        </li>
        <li>
            <a href="<?php echo e(route('departments_offices_management')); ?>" class="nav-link d-flex align-items-center gap-2 <?php echo e(request()->routeIs('departments_offices_management') ? 'active' : 'link-dark'); ?>">
                <i class="ri-building-fill" style="font-size: 25px;"></i>
                <span>Departments & Offices</span>
            </a>
        </li>
        <li>
            <a href="<?php echo e(route('show.activity-logs')); ?>" class="nav-link d-flex align-items-center gap-2 <?php echo e(request()->routeIs('show.activity-logs') ? 'active' : 'link-dark'); ?>">
                <i class="ri-file-2-fill" style="font-size: 25px;"></i>
                <span>Activity Logs</span>
            </a>
        </li>
        <?php endif; ?>

        
        <?php if($role === 'hr' || $showAll): ?>
        <li>
            <a href="<?php echo e(route('leave_application')); ?>" class="nav-link d-flex align-items-center gap-2 <?php echo e(request()->routeIs('leave_application') ? 'active' : 'link-dark'); ?>">
                <i class="ri-calendar-2-fill" style="font-size: 25px"></i>
                <span>Leave</span>
            </a>
        </li>
        <li>
            <a href="<?php echo e(route('employee_management')); ?>" class="nav-link d-flex align-items-center gap-2 <?php echo e(request()->routeIs('employee_management') ? 'active' : 'link-dark'); ?>">
                <i class="ri-team-fill" style="font-size: 25px"></i>
                <span>Employee Management</span>
            </a>
        </li>
        <li>
            <a href="<?php echo e(route('leave_management')); ?>" class="nav-link d-flex align-items-center gap-2 <?php echo e(request()->routeIs('leave_management') ? 'active' : 'link-dark'); ?>">
                <i class="ri-calendar-2-fill" style="font-size: 25px"></i>
                <span>Leave Management</span>
            </a>
        </li>
        <li>
            <a href="<?php echo e(route('attendance_management')); ?>" class="nav-link d-flex align-items-center gap-2 <?php echo e(request()->routeIs('attendance_management') ? 'active' : 'link-dark'); ?>">
                <i class="ri-time-fill" style="font-size: 25px"></i>
                <span>Attendance Management</span>
            </a>
        </li>
        <li>
            <a href="<?php echo e(route('contribution_management')); ?>" class="nav-link d-flex align-items-center gap-2 <?php echo e(request()->routeIs('contribution_management') ? 'active' : 'link-dark'); ?>">
                <i class="ri-hand-coin-fill" style="font-size: 25px"></i>
                <span>Contribution Management</span>
            </a>
        </li>
        <li>
            <a href="<?php echo e(route('reports')); ?>" class="nav-link d-flex align-items-center gap-2 <?php echo e(request()->routeIs('reports') ? 'active' : 'link-dark'); ?>">
                <i class="ri-folder-user-fill" style="font-size: 25px"></i>
                <span>Reports</span>
            </a>
        </li>
        <li>
            <a href="<?php echo e(route('training')); ?>" class="nav-link d-flex align-items-center gap-2 <?php echo e(request()->routeIs('training') ? 'active' : 'link-dark'); ?>">
                <i class="ri-book-fill" style="font-size: 25px"></i>
                <span>Trainings</span>
            </a>
        </li>
        <?php endif; ?>

        
        <?php if($role === 'employee' || $showAll): ?>
        <li>
            <a href="<?php echo e(route('leave_application')); ?>" class="nav-link d-flex align-items-center gap-2 <?php echo e(request()->routeIs('leave_application') ? 'active' : 'link-dark'); ?>">
                <i class="ri-calendar-2-fill" style="font-size: 25px"></i>
                <span>Leave</span>
            </a>
        </li>
        <li>
            <a href="<?php echo e(route('attendance')); ?>" class="nav-link d-flex align-items-center gap-2 <?php echo e(request()->routeIs('attendance') ? 'active' : 'link-dark'); ?>">
                <i class="ri-time-fill" style="font-size: 25px"></i>
                <span>Attendance</span>
            </a>
        </li>
        <li>
            <a href="<?php echo e(route('contribution.show')); ?>" class="nav-link d-flex align-items-center gap-2 <?php echo e(request()->routeIs('contribution.show') ? 'active' : 'link-dark'); ?>">
                <i class="ri-hand-coin-fill" style="font-size: 25px"></i>
                <span>Contribution</span>
            </a>
        </li>
        <li>
            <a href="<?php echo e(route('training')); ?>" class="nav-link d-flex align-items-center gap-2 <?php echo e(request()->routeIs('training') ? 'active' : 'link-dark'); ?>">
                <i class="ri-book-fill" style="font-size: 25px"></i>
                <span>Trainings</span>
            </a>
        </li>
        <?php if($hasVPAccess || $isHeadOfOffice): ?>
        <li>
            <a href="<?php echo e(route('leave_management')); ?>" class="nav-link d-flex align-items-center gap-2 <?php echo e(request()->routeIs('leave_management') ? 'active' : 'link-dark'); ?>">
                <i class="ri-calendar-2-fill" style="font-size: 25px"></i>
                <span>Leave Management</span>
            </a>
        </li>
        <?php endif; ?>
        <?php endif; ?>

        
        <?php if($isFinanceEmployee): ?>
        <li>
            <a href="<?php echo e(route('finance')); ?>" class="nav-link d-flex align-items-center gap-2 <?php echo e(request()->routeIs('finance') ? 'active' : 'link-dark'); ?>">
                <i class="ri-building-line" style="font-size: 25px;"></i>
                <span>RVM</span>
            </a>
        </li>
        <?php endif; ?>

    </ul>
    <ul class="nav nav-pills flex-column mt-auto">
        <li>
            <a href="<?php echo e(route('password.change.form')); ?>" class="nav-link d-flex align-items-center gap-2 <?php echo e(request()->routeIs('password.change') ? 'active' : 'link-dark'); ?>">
                <i class="ri-lock-password-fill" style="font-size: 25px"></i>
                <span>Change Password</span>
            </a>
        </li>
    </ul>
</div>
<!-- Sidebar End --><?php /**PATH C:\Projects\hris\hris-app\resources\views/components/sidebar.blade.php ENDPATH**/ ?>