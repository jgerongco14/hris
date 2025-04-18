 <!-- PAG-IBIG Contributions -->
 <div class="tab-pane fade {{ $activeType === 'PAG-IBIG' ? 'show active' : '' }}" id="pagibig" role="tabpanel" aria-labelledby="pagibig-tab">
     <div class="row mb-3 justify-content-between align-items-center">
         <h3 class="col-8 mt-4">PAG-IBIG Contributions</h3>
         <div class="col-4">
             @if(Auth::check() && Auth::user()->role !== 'employee')
             <form method="GET" action="{{ route('contribution.management') }}" class="d-flex">
                 <!-- Export Button -->
                 <a href="{{ route('contribution.exportWord', array_filter([
    'contribution_type' => 'PAG-IBIG',
    'search' => request('contribution_type') === 'PAG-IBIG' ? request('search') : null
])) }}" class="btn btn-info d-flex align-items-center mx-2">
                     <i class="ri-file-word-2-line"></i> Export
                 </a>

                 <input type="text" name="search" class="form-control me-2" placeholder="Search by EmpID or Name" value="{{ request('search') }}">
                 <input type="hidden" name="contribution_type" value="PAG-IBIG">
                 <!-- Search Button -->
                 <button type="submit" class="btn btn-primary d-flex align-items-center">
                     <i class="ri-search-line"></i>
                 </button>
                 <!-- Reset Button -->
                 <a href="{{ route('contribution.management') }}?contribution_type=PAG-IBIG" class="btn btn-secondary d-flex align-items-center ms-2">
                     <i class="ri-restart-line"></i>
                 </a>
             </form>
             @endif
         </div>
     </div>
     @if($pagibigContributions instanceof \Illuminate\Pagination\LengthAwarePaginator)
     <table class="table table-bordered">
         <thead class="text-center">
             <tr>
                 <th>No</th>
                 <th>PAG-IBIG ID</th>
                 <th>Emp ID</th>
                 <th>Employee Name</th>
                 <th>Amount</th>
                 <th>EC</th>
                 <th>PR Number</th>
                 <th>Date</th>
                 @if(Auth::check() && Auth::user()->role !== 'employee')
                 <th>Action</th>
                 @endif
             </tr>
         </thead>
         <tbody class="align-middle">
             @forelse($pagibigContributions as $contribution)
             <tr>
                 <td class="text-center">{{ $loop->iteration + ($pagibigContributions->currentPage() - 1) * $pagibigContributions->perPage() }}</td>
                 <td>{{ $contribution->employee->empPagIbigNum ?? 'N/A' }}</td>
                 <td>{{ $contribution->employee->empID ?? 'N/A' }}</td>
                 <td>
                     @if($contribution->employee)
                     <div class="d-flex align-items-center gap-2">
                         @php
                         $employeePhoto = $contribution->employee->photo ?? null;
                         $isExternal = $employeePhoto && Str::startsWith($employeePhoto, ['http://', 'https://']);
                         @endphp


                         @if($employeePhoto)
                         <img
                             src="{{ $isExternal ? $employeePhoto : asset('storage/employee_photos/' . $contribution->employee->photo) }}"
                             alt="Employee Photo" width="50" height="50" class="rounded-circle">

                         @else
                         <div class="no-photo bg-light rounded-circle d-flex align-items-center justify-content-center"
                             style="width:50px; height:50px;">
                             <i class="ri-user-line"></i>
                         </div>
                         @endif

                         {{ $contribution->employee->empPrefix }} {{ $contribution->employee->empFname }} {{ $contribution->employee->empMname }} {{ $contribution->employee->empLname }} {{ $contribution->employee->empSuffix }}
                         @else
                         Employee not found
                         @endif
                     </div>

                 </td>
                 <td>
                     {{ is_numeric($contribution->empConAmount) 
                        ? '₱' . number_format($contribution->empConAmount, 2) 
                        : 'No Earnings' }}
                 </td>
                 <td>
                     {{ is_numeric($contribution->employeerContribution) 
                        ? '₱' . number_format($contribution->employeerContribution, 2) 
                        : 'No Earnings' }}
                 </td>

                 <td>{{ $contribution->empPRNo }}</td>
                 <td>{{ $contribution->empConDate }}</td>
                 @if(Auth::check() && Auth::user()->role !== 'employee')
                 <td class="text-center">
                     <a href="javascript:void(0);" class="btn btn-warning btn-sm edit-contribution"
                         data-id="{{ $contribution->id }}"
                         data-emp-id="{{ $contribution->empID }}"
                         data-emp-name=" {{ $contribution->employee->empPrefix }} {{ $contribution->employee->empFname }} {{ $contribution->employee->empMname }} {{ $contribution->employee->empLname }} {{ $contribution->employee->empSuffix }}"
                         data-amount="{{ $contribution->empConAmount }}"
                         data-date="{{ $contribution->empConDate }}"
                         data-employeer-contribution="{{ $contribution->employeerContribution }}"
                         data-emp-pr-no="{{ $contribution->empPRNo }}"
                         data-type="{{ $contribution->empContype }}"
                         data-bs-toggle="modal"
                         data-bs-target="#editContributionModal">
                         <i class="ri-edit-line"></i> <!-- Edit Icon -->
                     </a>


                     <form action="{{ route('contribution.destroy', $contribution->id) }}" method="POST" style="display:inline;">
                         @csrf
                         @method('DELETE')
                         <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to delete this contribution?')">
                             <i class="ri-delete-bin-5-line"></i> <!-- Delete Icon -->
                         </button>
                     </form>

                 </td>
                 @endif
             </tr>
             @empty
             <tr>
                 <td colspan="9" class="text-center">No PAG-IBIG contributions found.</td>
             </tr>
             @endforelse
         </tbody>
     </table>
     @if($pagibigContributions->hasPages())
     <div class="d-flex flex-column align-items-center mt-4 gap-2">
         {{-- Pagination links --}}
         <div>
             {{ $pagibigContributions->links('pagination::bootstrap-5') }}
         </div>
         <div class="text-muted small">
             Showing {{ $pagibigContributions->firstItem() }} to {{ $pagibigContributions->lastItem() }} of {{ $pagibigContributions->total() }} results
         </div>

     </div>
     @endif
     @endif
 </div>