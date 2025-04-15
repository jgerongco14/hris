     <!-- TIN Contributions -->
     <div class="tab-pane fade {{ $activeType === 'TIN' ? 'show active' : '' }}" id="tin" role="tabpanel" aria-labelledby="tin-tab">
         <div class="row mb-3 justify-content-between align-items-center">
             <h3 class="col-8 mt-4">TIN Contributions</h3>
             <div class="col-4">
                 @if(Auth::check() && Auth::user()->role !== 'employee')
                 <form method="GET" action="{{ route('contribution.management') }}" class="d-flex">
                     <!-- Export Button -->
                     <a href="{{ route('contribution.exportWord', array_filter([
    'contribution_type' => 'TIN',
    'search' => request('contribution_type') === 'TIN' ? request('search') : null
])) }}" class="btn btn-info d-flex align-items-center mx-2">
                         <i class="ri-file-word-2-line"></i> Export
                     </a>

                     <input type="text" name="search" class="form-control me-2" placeholder="Search by EmpID or Name" value="{{ request('search') }}">
                     <input type="hidden" name="contribution_type" value="TIN">
                     <!-- Search Button -->
                     <button type="submit" class="btn btn-primary d-flex align-items-center">
                         <i class="ri-search-line"></i>
                     </button>
                     <!-- Reset Button -->
                     <a href="{{ route('contribution.management') }}?contribution_type=TIN" class="btn btn-secondary d-flex align-items-center ms-2">
                         <i class="ri-restart-line"></i>
                     </a>
                 </form>
                 @endif
             </div>
         </div>
         @if($tinContributions instanceof \Illuminate\Pagination\LengthAwarePaginator)
         <table class="table table-bordered">
             <thead class="text-center">
                 <tr>
                     <th>No</th>
                     <th>TIN ID</th>
                     <th>Emp ID</th>
                     <th>Employee Name</th>
                     <th>Amount</th>
                     <th>Date</th>
                     @if(Auth::check() && Auth::user()->role !== 'employee')
                     <th>Action</th>
                     @endif
                 </tr>
             </thead>
             <tbody class="align-middle">
                 @forelse($tinContributions as $contribution)
                 <tr>
                     <td class="text-center">{{ $loop->iteration + ($pagibigContributions->currentPage() - 1) * $pagibigContributions->perPage() }}</td>
                     <td>{{ $contribution->employee->empTinNum ?? 'N/A' }}</td>
                     <td>{{ $contribution->employee->empID ?? 'N/A' }}</td>
                     <td>
                         @if($contribution->employee)
                         {{ $contribution->employee->empFname }} {{ $contribution->employee->empLname }}
                         @else
                         Employee not found
                         @endif
                     </td>
                     <td>
                         {{ is_numeric($contribution->empConAmount) 
        ? '₱' . number_format($contribution->empConAmount, 2) 
        : $contribution->empConAmount }}
                     </td>
                     <td>{{ $contribution->empConDate }}</td>
                     @if(Auth::check() && Auth::user()->role !== 'employee')
                     <td class="text-center">
                         <a href="javascript:void(0);" class="btn btn-warning btn-sm edit-contribution"
                             data-id="{{ $contribution->id }}"
                             data-amount="{{ $contribution->empConAmount }}"
                             data-date="{{ $contribution->empConDate }}"
                             data-remarks="{{ $contribution->empConRemarks }}"
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
                     <td colspan="8" class="text-center">No TIN contributions found.</td>
                 </tr>
                 @endforelse
             </tbody>
         </table>
         @if($tinContributions->hasPages())
         <div class="d-flex flex-column align-items-center mt-4 gap-2">
             {{-- Pagination links --}}
             <div>
                 {{ $tinContributions->links('pagination::bootstrap-5') }}
             </div>
             {{-- Showing text --}}
             <div class="text-muted small">
                 Showing {{ $tinContributions->firstItem() }} to {{ $tinContributions->lastItem() }} of {{ $tinContributions->total() }} results
             </div>
         </div>
         @endif
         @endif
     </div>