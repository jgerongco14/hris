     <!-- TIN Contributions -->
     <div class="tab-pane fade <?php echo e($activeType === 'TIN' ? 'show active' : ''); ?>" id="tin" role="tabpanel" aria-labelledby="tin-tab">
         <div class="row mb-3 justify-content-between align-items-center">
             <h3 class="col-8 mt-4">TIN Contributions</h3>
             <div class="col-4">
                 <?php if(Auth::check() && Auth::user()->role !== 'employee'): ?>
                 <form method="GET" action="<?php echo e(route('contribution.management')); ?>" class="d-flex">
                     <!-- Export Button -->
                     <a href="<?php echo e(route('contribution.exportWord', array_filter([
    'contribution_type' => 'TIN',
    'search' => request('contribution_type') === 'TIN' ? request('search') : null
]))); ?>" class="btn btn-info d-flex align-items-center mx-2">
                         <i class="ri-file-word-2-line"></i> Export
                     </a>

                     <input type="text" name="search" class="form-control me-2" placeholder="Search by EmpID or Name" value="<?php echo e(request('search')); ?>">
                     <input type="hidden" name="contribution_type" value="TIN">
                     <!-- Search Button -->
                     <button type="submit" class="btn btn-primary d-flex align-items-center">
                         <i class="ri-search-line"></i>
                     </button>
                     <!-- Reset Button -->
                     <a href="<?php echo e(route('contribution.management')); ?>?contribution_type=TIN" class="btn btn-secondary d-flex align-items-center ms-2">
                         <i class="ri-restart-line"></i>
                     </a>
                 </form>
                 <?php endif; ?>
             </div>
         </div>
         <?php if($tinContributions instanceof \Illuminate\Pagination\LengthAwarePaginator): ?>
         <table class="table table-bordered">
             <thead class="text-center">
                 <tr>
                     <th>No</th>
                     <th>TIN ID</th>
                     <th>Emp ID</th>
                     <th>Employee Name</th>
                     <th>Amount</th>
                     <th>Date</th>
                     <?php if(Auth::check() && Auth::user()->role !== 'employee'): ?>
                     <th>Action</th>
                     <?php endif; ?>
                 </tr>
             </thead>
             <tbody class="align-middle">
                 <?php $__empty_1 = true; $__currentLoopData = $tinContributions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $contribution): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                 <tr>
                     <td class="text-center"><?php echo e($loop->iteration + ($pagibigContributions->currentPage() - 1) * $pagibigContributions->perPage()); ?></td>
                     <td><?php echo e($contribution->employee->empTinNum ?? 'N/A'); ?></td>
                     <td><?php echo e($contribution->employee->empID ?? 'N/A'); ?></td>
                     <td>
                         <?php if($contribution->employee): ?>
                         <div class="d-flex align-items-center gap-2">
                             <?php
                             $employeePhoto = $contribution->employee->photo ?? null;
                             $isExternal = $employeePhoto && Str::startsWith($employeePhoto, ['http://', 'https://']);
                             ?>


                             <?php if($employeePhoto): ?>
                             <img
                                 src="<?php echo e($isExternal ? $employeePhoto : asset('storage/employee_photos/' . $contribution->employee->photo)); ?>"
                                 alt="Employee Photo" width="50" height="50" class="rounded-circle">

                             <?php else: ?>
                             <div class="no-photo bg-light rounded-circle d-flex align-items-center justify-content-center"
                                 style="width:50px; height:50px;">
                                 <i class="ri-user-line"></i>
                             </div>
                             <?php endif; ?>

                             <?php echo e($contribution->employee->empPrefix); ?> <?php echo e($contribution->employee->empFname); ?> <?php echo e($contribution->employee->empMname); ?> <?php echo e($contribution->employee->empLname); ?> <?php echo e($contribution->employee->empSuffix); ?>

                             <?php else: ?>
                             Employee not found
                             <?php endif; ?>
                         </div>

                     </td>
                     <td>
                         <?php echo e(is_numeric($contribution->empConAmount) 
        ? '₱' . number_format($contribution->empConAmount, 2) 
        : $contribution->empConAmount); ?>

                     </td>
                     <td><?php echo e($contribution->empConDate); ?></td>
                     <?php if(Auth::check() && Auth::user()->role !== 'employee'): ?>
                     <td class="text-center">
                         <a href="javascript:void(0);" class="btn btn-warning btn-sm edit-contribution"
                             data-id="<?php echo e($contribution->id); ?>"
                             data-emp-id="<?php echo e($contribution->employee->empID); ?>"
                             data-emp-name=" <?php echo e($contribution->employee->empPrefix); ?> <?php echo e($contribution->employee->empFname); ?> <?php echo e($contribution->employee->empMname); ?> <?php echo e($contribution->employee->empLname); ?> <?php echo e($contribution->employee->empSuffix); ?>"
                             data-amount="<?php echo e($contribution->empConAmount); ?>"
                             data-date="<?php echo e($contribution->empConDate); ?>"
                             data-remarks="<?php echo e($contribution->empConRemarks); ?>"
                             data-type="<?php echo e($contribution->empContype); ?>"
                             data-bs-toggle="modal"
                             data-bs-target="#editContributionModal">
                             <i class="ri-edit-line"></i> <!-- Edit Icon -->
                         </a>

                         <form action="<?php echo e(route('contribution.destroy', $contribution->id)); ?>" method="POST" style="display:inline;">
                             <?php echo csrf_field(); ?>
                             <?php echo method_field('DELETE'); ?>
                             <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to delete this contribution?')">
                                 <i class="ri-delete-bin-5-line"></i> <!-- Delete Icon -->
                             </button>
                         </form>
                     </td>
                     <?php endif; ?>
                 </tr>

                 <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                 <tr>
                     <td colspan="8" class="text-center">No TIN contributions found.</td>
                 </tr>
                 <?php endif; ?>
             </tbody>
         </table>
         <?php if($tinContributions->hasPages()): ?>
         <div class="d-flex flex-column align-items-center mt-4 gap-2">
             
             <div>
                 <?php echo e($tinContributions->links('pagination::bootstrap-5')); ?>

             </div>
             
             <div class="text-muted small">
                 Showing <?php echo e($tinContributions->firstItem()); ?> to <?php echo e($tinContributions->lastItem()); ?> of <?php echo e($tinContributions->total()); ?> results
             </div>
         </div>
         <?php endif; ?>
         <?php endif; ?>
     </div><?php /**PATH C:\Projects\hris\hris-app\resources\views/pages/hr/components/contribution_tin_table.blade.php ENDPATH**/ ?>