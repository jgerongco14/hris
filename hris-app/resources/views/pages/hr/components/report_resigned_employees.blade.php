<div class="card">
    <div class="card-header ">
        <h5 class="card-title">Resigned Employees</h5>
    </div>
    <div class="card-body">
        <div class="col-6 my-3">
            <form action="{{ route('reports') }}" method="GET" class="d-flex">
                <input type="text" name="report_search" class="form-control form-control-sm me-2" placeholder="Search by Emp ID or Name" value="{{ request('report_search') }}">
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
                    <th>Name</th>
                    <th>Semester</th>
                    <th>Year</th>
                    <th>Reason</th>
                    <th>Attachments</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($reports as $report)
                <tr>
                    <td>
                        <div class="d-flex align-items-center gap-2">
                            @php
                            $employeePhoto = $report->employee->photo ?? null;
                            $isExternal = $employeePhoto && Str::startsWith($employeePhoto, ['http://', 'https://']);
                            @endphp

                            @if($employeePhoto)
                            <img src="{{ $isExternal ? $employeePhoto : asset('storage/employee_photos/' . $report->employee->photo) }}"
                                alt="Employee Photo" width="50" height="50" class="rounded-circle">
                            @else
                            <div class="no-photo bg-light rounded-circle d-flex align-items-center justify-content-center"
                                style="width:50px; height:50px;">
                                <i class="ri-user-line"></i>
                            </div>
                            @endif
                            <span>
                                {{ $report->employee->empPrefix }}
                                {{ $report->employee->empFname }}
                                {{ $report->employee->empMname }}
                                {{ $report->employee->empLname }}
                                {{ $report->employee->empSuffix }}
                            </span>
                        </div>
                    </td>
                    <td class="text-center align-middle">{{ $report->semester }}</td>
                    <td class="text-center align-middle">{{ $report->year }}</td>
                    <td>{{ $report->reason }}</td>
                    <td class="text-center align-middle">
                        @if ($report->attachments)
                        @foreach (json_decode($report->attachments) as $file)
                        <a href="{{ asset('storage/' . $file) }}" target="_blank" class="badge bg-primary text-decoration-none">
                            Attachment {{ $loop->iteration }}
                        </a><br>
                        @endforeach
                        @else
                        <span class="badge bg-secondary">No Attachments</span>
                        @endif
                    </td>
                    <td class="text-center align-middle">
                        <form action="{{ route('reports.delete', $report->id) }}" method="POST" onsubmit="return confirmDelete()">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger btn-sm">
                                <i class="ri-delete-bin-5-line"></i>
                            </button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="text-center">No Reports found.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<script>
    function confirmDelete() {
        return confirm('Are you sure you want to delete this report? This action cannot be undone.');
    }
</script>