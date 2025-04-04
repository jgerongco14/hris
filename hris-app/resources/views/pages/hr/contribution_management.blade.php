<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Employee Contributions</title>
    @vite(['resources/sass/app.scss', 'resources/js/app.js'])
    <link href="https://cdn.jsdelivr.net/npm/remixicon@2.5.0/fonts/remixicon.css" rel="stylesheet">
</head>

<body>
    <div class="container-fluid">
        <div class="row">
            <div class="col-2">
                <!-- Include the navbar component -->
                <x-navbar />
            </div>
            <div class="col-10">
                <!-- Include the titlebar component -->
                <x-titlebar />

                <!-- Include the notification component -->
                <x-notification />

                @if(isset($contribution))
                @include('pages.hr.components.update_contribution_form', ['contribution' => $contribution])
                @endif

                <!-- Import Attendance Button -->
                @include('components.import_file')

                <!-- Add Contribution Modal -->
                <div class="modal fade" id="addContributionModal" tabindex="-1" aria-labelledby="addContributionModalLabel" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="addContributionModalLabel">Add New Contribution</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <form action="{{ route('contribution.store') }}" method="POST">
                                @csrf
                                <div class="modal-body">
                                    <div class="mb-3">
                                        <label for="empID" class="form-label">Employee</label>
                                        <select class="form-select" id="empID" name="empID" required>
                                            <option value="">Select Employee</option>
                                            @foreach($employees as $employee)
                                            <option value="{{ $employee->empID }}">{{ $employee->empFname }} {{ $employee->empLname }} ({{ $employee->empID }})</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="mb-3">
                                        <label for="empContype" class="form-label">Contribution Type</label>
                                        <select class="form-select" id="empContype" name="empContype" required>
                                            <option value="">Select Type</option>
                                            <option value="SSS">SSS</option>
                                            <option value="PAG-IBIG">PAG-IBIG</option>
                                            <option value="TIN">TIN</option>
                                        </select>
                                    </div>
                                    <div class="mb-3">
                                        <label for="empConNo" class="form-label">Contribution Number</label>
                                        <input type="text" class="form-control" id="empConNo" name="empConNo" required>
                                    </div>
                                    <div class="mb-3">
                                        <label for="empConAmount" class="form-label">Amount</label>
                                        <input type="number" step="0.01" class="form-control" id="empConAmount" name="empConAmount" required>
                                    </div>
                                    <div class="mb-3">
                                        <label for="empConDate" class="form-label">Date</label>
                                        <input type="date" class="form-control" id="empConDate" name="empConDate" required>
                                    </div>
                                    <div class="mb-3">
                                        <label for="empConRemarks" class="form-label">Remarks</label>
                                        <textarea class="form-control" id="empConRemarks" name="empConRemarks"></textarea>
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                    <button type="submit" class="btn btn-primary">Save Contribution</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

                <div class="card my-5">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">Employee Contributions</h5>
                        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addContributionModal">
                            <i class="ri-add-line"></i> Add Contribution
                        </button>
                    </div>
                    <div class="card-body">
                        <!-- Tabs for Contributions -->
                        @php
                        $activeType = request('contribution_type', 'SSS'); // default to SSS
                        @endphp

                        <ul class="nav nav-tabs" id="contributionTabs" role="tablist">
                            <li class="nav-item" role="presentation">
                                <button class="nav-link {{ $activeType === 'SSS' ? 'active' : '' }}" id="sss-tab"
                                    data-bs-toggle="tab" data-bs-target="#sss" type="button" role="tab"
                                    aria-controls="sss" aria-selected="{{ $activeType === 'SSS' ? 'true' : 'false' }}">SSS</button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link {{ $activeType === 'PAG-IBIG' ? 'active' : '' }}" id="pagibig-tab"
                                    data-bs-toggle="tab" data-bs-target="#pagibig" type="button" role="tab"
                                    aria-controls="pagibig" aria-selected="{{ $activeType === 'PAG-IBIG' ? 'true' : 'false' }}">PAG-IBIG</button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link {{ $activeType === 'TIN' ? 'active' : '' }}" id="tin-tab"
                                    data-bs-toggle="tab" data-bs-target="#tin" type="button" role="tab"
                                    aria-controls="tin" aria-selected="{{ $activeType === 'TIN' ? 'true' : 'false' }}">TIN</button>
                            </li>
                        </ul>


                        <div class="tab-content" id="contributionTabsContent">

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

                            <!-- PAG-IBIG Contributions -->
                            <div class="tab-pane fade {{ $activeType === 'PAG-IBIG' ? 'show active' : '' }}" id="pagibig" role="tabpanel" aria-labelledby="pagibig-tab">
                                <div class="row mb-3 justify-content-between align-items-center">
                                    <h3 class="col-8 mt-4">PAG-IBIG Contributions</h3>
                                    <div class="col-4">
                                        <form method="GET" action="{{ route('contribution.management') }}" class="d-flex">
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
                                    </div>
                                </div>
                                @if($pagibigContributions instanceof \Illuminate\Pagination\LengthAwarePaginator)
                                <table class="table table-bordered">
                                    <thead>
                                        <tr>
                                            <th>Contribution No</th>
                                            <th>PAG-IBIG ID</th>
                                            <th>Emp ID</th>
                                            <th>Employee Name</th>
                                            <th>Amount</th>
                                            <th>Date</th>
                                            <th>Remarks</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($pagibigContributions as $contribution)
                                        <tr>
                                            <td>{{ $contribution->empConNo }}</td>
                                            <td>{{ $contribution->employee->empPagIbigNum ?? 'N/A' }}</td>
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
                                            <td colspan="8" class="text-center">No PAG-IBIG contributions found.</td>
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

                            <!-- TIN Contributions -->
                            <div class="tab-pane fade {{ $activeType === 'TIN' ? 'show active' : '' }}" id="tin" role="tabpanel" aria-labelledby="tin-tab">
                                <div class="row mb-3 justify-content-between align-items-center">
                                    <h3 class="col-8 mt-4">TIN Contributions</h3>
                                    <div class="col-4">
                                        <form method="GET" action="{{ route('contribution.management') }}" class="d-flex">
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
                                    </div>
                                </div>
                                @if($tinContributions instanceof \Illuminate\Pagination\LengthAwarePaginator)
                                <table class="table table-bordered">
                                    <thead>
                                        <tr>
                                            <th>Contribution No</th>
                                            <th>TIN ID</th>
                                            <th>Emp ID</th>
                                            <th>Employee Name</th>
                                            <th>Amount</th>
                                            <th>Date</th>
                                            <th>Remarks</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($tinContributions as $contribution)
                                        <tr>
                                            <td>{{ $contribution->empConNo }}</td>
                                            <td>{{ $contribution->employee->empTinNum ?? 'N/A' }}</td>
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
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <link rel="stylesheet" href="https://code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.min.js"></script>

    <script>
        function showToast(title, message, type = 'success') {
            const toastEl = document.getElementById('liveToast');
            const toastHeader = document.getElementById('toast-header');
            const toastTitle = document.getElementById('toast-title');
            const toastMessage = document.getElementById('toast-message');
            const toastIcon = document.getElementById('toast-icon');

            // Reset and keep background white
            toastEl.className = 'toast align-items-center border border-2 show bg-white';

            const headerColors = {
                success: 'text-success',
                danger: 'text-danger',
                warning: 'text-warning',
                info: 'text-info'
            };

            const icons = {
                success: '✅',
                danger: '❌',
                warning: '⚠️',
                info: 'ℹ️'
            };

            // Style header and icon
            toastHeader.className = `toast-header ${headerColors[type] || 'text-dark'}`;
            toastIcon.textContent = icons[type] || '';
            toastTitle.textContent = title;
            toastMessage.textContent = message;

            const toast = new bootstrap.Toast(toastEl, {
                delay: 10000
            });
            toast.show();
        }


        const contributionType = urlParams.get('contribution_type');
        if (contributionType) {
            const tabId = contributionType.toLowerCase();
            const tabButton = document.getElementById(`${tabId}-tab`);
            if (tabButton) {
                const tab = new bootstrap.Tab(tabButton);
                tab.show();
            }
        }
    </script>

</body>

</html>