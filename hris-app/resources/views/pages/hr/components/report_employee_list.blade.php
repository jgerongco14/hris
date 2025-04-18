<div class="card">
    <div class="card-header">
        <h5 class="card-title">Employee List</h5>
    </div>
    <div class="card-body">
        <div class="col-6 my-3">
            <form action="{{ route('reports') }}" method="GET" class="d-flex">
                <input type="text" name="employee_search" class="form-control form-control-sm me-2" placeholder="Search by Emp ID or Name" value="{{ request('employee_search') }}">
                <button type="submit" class="btn btn-primary btn-sm me-2">
                    <i class="ri-search-line"></i>
                </button>
                <a href="{{ route('reports') }}" class="btn btn-secondary btn-sm">
                    <i class="ri-refresh-line"></i>
                </a>
            </form>

        </div>
        <table class="table table-bordered table-striped">
            <thead class="align-middle text-center">
                <tr>
                    <th class="col-3">Emp ID</th>
                    <th>Name</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($employees as $employee)
                <tr>
                    <td class="align-middle text-center">{{ $employee->empID }}</td>
                    <td class="align-middle">
                        <div class="d-flex align-items-center gap-2">
                            @php
                            $employeePhoto = $employee->photo ?? null;
                            $isExternal = $employeePhoto && Str::startsWith($employeePhoto, ['http://', 'https://']);
                            @endphp


                            @if($employeePhoto)
                            <img
                                src="{{ $isExternal ? $employeePhoto : asset('storage/employee_photos/' . $employee->photo) }}"
                                alt="Employee Photo" width="50" height="50" class="rounded-circle">

                            @else
                            <div class="no-photo bg-light rounded-circle d-flex align-items-center justify-content-center"
                                style="width:50px; height:50px;">
                                <i class="ri-user-line"></i>
                            </div>
                            @endif
                            <span>
                                {{ $employee->empPrefix }}
                                {{ $employee->empFname }}
                                {{ $employee->empMname }}
                                {{ $employee->empLname }}
                                {{ $employee->empSuffix }}
                            </span>
                        </div>
                    </td>
                    <td class="align-middle text-center">

                        @php
                        $rawStatus = strtolower($employee->status ?? 'active'); // default to 'active' if null
                        $isClickable = $rawStatus === 'active';
                        $badgeClass = $rawStatus === 'resigned' ? 'bg-danger' : 'bg-success';
                        $statusLabel = ucfirst($rawStatus);
                        @endphp

                        @if ($isClickable)
                        <button class="btn btn-link text-decoration-none p-0" type="button">
                            <span class="badge {{ $badgeClass }} cursor-pointer"
                                onclick="confirmStatusChange('{{ $employee->empID }}', '{{ $statusLabel }}', '{{ $employee->empPrefix }} {{ $employee->empFname }} {{ $employee->empMname }} {{ $employee->empLname }} {{ $employee->empSuffix }}')">
                                {{ $statusLabel }}
                            </span>
                        </button>
                        @else
                        <span class="badge {{ $badgeClass }}">
                            {{ $statusLabel }}
                        </span>
                        @endif

                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
        @if($employees->hasPages())
        <div class="d-flex flex-column align-items-center mt-4 gap-2">
            {{-- Pagination links --}}
            <div>
                {{ $employees->links('pagination::bootstrap-5') }}
            </div>

            {{-- Showing text --}}
            <div class="text-muted small">
                Showing {{ $employees->firstItem() }} to {{ $employees->lastItem() }} of {{ $employees->total() }} results
            </div>
        </div>
        @endif
    </div>
</div>