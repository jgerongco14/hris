  <!-- SSS Contributions -->
  <div class="tab-pane fade {{ $activeType === 'SSS' ? 'show active' : '' }}" id="sss" role="tabpanel" aria-labelledby="sss-tab">
      <div class="row mb-3 justify-content-between align-items-center">
          <h3 class="col-8 mt-4">SSS Contributions</h3>
          <div class="col-4">
              <form method="GET" action="{{ route('contribution.management') }}" class="d-flex">
                  <input type="text" name="search" class="form-control me-2" placeholder="Search by EmpID or Name" value="{{ request('search') }}">
                  <input type="hidden" name="contribution_type" value="SSS">
                  <!-- Search Button -->
                  <button type="submit" class="btn btn-primary d-flex align-items-center">
                      <i class="ri-search-line"></i>
                  </button>
                  <!-- Reset Button -->
                  <a href="{{ route('contribution.management') }}?contribution_type=SSS" class="btn btn-secondary d-flex align-items-center ms-2">
                      <i class="ri-restart-line"></i>
                  </a>
              </form>
          </div>
      </div>
      @if($sssContributions instanceof \Illuminate\Pagination\LengthAwarePaginator)
      <table class="table table-bordered">
          <thead>
              <tr>
                  <th>Contribution No</th>
                  <th>SSS ID</th>
                  <th>Emp ID</th>
                  <th>Employee Name</th>
                  <th>Amount</th>
                  <th>Date</th>
                  <th>Remarks</th>
                  <th>Action</th>
              </tr>
          </thead>
          <tbody>
              @forelse($sssContributions as $contribution)
              <tr>
                  <td>{{ $contribution->empConNo }}</td>
                  <td>{{ $contribution->employee->empSSSNum ?? 'N/A' }}</td>
                  <td>{{ $contribution->employee->empID ?? 'N/A' }}</td>
                  <td>
                      @if($contribution->employee)
                      {{ $contribution->employee->empFname }} {{ $contribution->employee->empMname }} {{ $contribution->employee->empLname }}
                      @else
                      Employee not found
                      @endif
                  </td>
                  <td>{{ number_format($contribution->empConAmount, 2) }}</td>
                  <td>{{ $contribution->empConDate }}</td>
                  <td>{{ $contribution->empConRemarks }}</td>
                  <td>
                      <form action="{{ route('contribution.destroy', $contribution->id) }}" method="POST" style="display:inline;">
                          @csrf
                          @method('DELETE')
                          <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to delete this contribution?')">Delete</button>
                      </form>
                  </td>
              </tr>
              @empty
              <tr>
                  <td colspan="8" class="text-center">No SSS contributions found.</td>
              </tr>
              @endforelse
          </tbody>
      </table>
      @if($sssContributions->hasPages())
      <div class="d-flex flex-column align-items-center mt-4 gap-2">
          {{-- Pagination links --}}
          <div>
              {{ $sssContributions->links('pagination::bootstrap-5') }}
          </div>
          {{-- Showing text --}}
          <div class="text-muted small">
              Showing {{ $sssContributions->firstItem() }} to {{ $sssContributions->lastItem() }} of {{ $sssContributions->total() }} results
          </div>

      </div>
      @endif
      @endif
  </div>