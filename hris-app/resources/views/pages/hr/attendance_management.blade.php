<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Attendance Management</title>
    @vite(['resources/sass/app.scss', 'resources/js/app.js'])
    <link href="https://cdn.jsdelivr.net/npm/remixicon@2.5.0/fonts/remixicon.css" rel="stylesheet">
</head>

<body>
    <div class="container-fluid">
        <div class="row">
            <div class="col-2 p-0">
                <div class="vh-100 position-sticky top-0">
                    @include('components.sidebar')
                </div>
            </div>
            <div class="col-10 p-3 pt-0 main-content">
                <!-- Include the titlebar component -->
                <div class="position-sticky top-0 z-3 w-100">
                    <x-titlebar />
                </div>

                <!-- Include the notification component -->
                <x-notification />

                <!-- Include the modal for adding attendance -->
                <h1 class="my-3">Attendance Management</h1>
                <div class="card mb-4">
                    <div class="card-body p-4">
                        <h3 class="text-center">Attendance Records</h3>
                        <div class="row">

                            <div class="col-2">
                                @if($totalAbsents > 0)
                                <div class="alert alert-warning d-flex justify-content-between align-items-center">
                                    <div><strong>Total Absents:</strong> {{ $totalAbsents }}</div>
                                </div>
                                @endif

                            </div>
                        </div>
                        <div class="row align-items-end">
                            <!-- Date Range Picker -->
                            <div class="col-8 my-3">
                                <form method="GET" id="filterForm" class="d-flex gap-2 mb-3">
                                    <div class="input-group">
                                        <button class="btn btn-outline-secondary" type="button" id="open_datepicker">
                                            <i class="ri-calendar-line"></i>
                                        </button>
                                        <input type="text" name="date_range" class="form-control" id="date_range" placeholder="Filter by date range" value="{{ request('date_range') }}">
                                    </div>
                                    <!-- Employee Input -->
                                    <input type="text"
                                        name="employee_name"
                                        id="employee_name_input"
                                        class="form-control"
                                        placeholder="Search employee name"
                                        value="{{ request('employee_name') }}">
                                    <!-- Search Button -->
                                    <button type="submit" class="btn btn-primary d-flex align-items-center">
                                        <i class="ri-search-line"></i>
                                    </button>
                                    <!-- Reset Button -->
                                    <a href="{{ route('attendance_management') }}" class="btn btn-secondary d-flex align-items-center ms-2">
                                        <i class="ri-restart-line"></i>
                                    </a>


                                </form>

                            </div>

                            <!-- Import Attendance Button -->
                            @include('components.import_file')

                            <!-- Push button to the right -->
                            <div class="col-2 my-3 ms-auto">
                                <div class="mb-3">
                                    <button type="button" id="openAddAttendanceModal" class="btn btn-primary w-100" data-bs-toggle="modal" data-bs-target="#addAttendanceModal">
                                        <i class="ri-add-line"></i> Add Attendance
                                    </button>
                                </div>
                            </div>
                        </div>
                        <!-- Show active filters if any -->
                        @if(request('employee_name') || request('date_range'))
                        <div class="alert alert-info d-flex justify-content-between align-items-center">
                            <div>
                                @if(request('employee_name'))
                                <strong>Employee Name:</strong> {{ request('employee_name') }}
                                @endif
                                @if(request('date_range'))
                                <strong class="ms-3">Date Range:</strong> {{ request('date_range') }}
                                @endif
                            </div>
                            <a href="{{ url()->current() }}" class="btn btn-sm btn-outline-secondary">Clear Filters</a>
                        </div>
                        @endif

                        <table class="table table-bordered text">
                            <!-- your existing table content -->
                        </table>

                        <table class="table table-bordered text">
                            <thead class="text-center">
                                <tr>
                                    <th>Attendance ID</th>
                                    <th>Employee Name</th>
                                    <th>Date</th>
                                    <th>Time In</th>
                                    <th>Breakout</th>
                                    <th>Break-in</th>
                                    <th>Time Out</th>
                                    <th>Remarks</th>
                                </tr>
                            </thead>
                            <tbody class="align-middle">
                                @forelse($attendance as $item)
                                <tr>
                                    <td class="text-center">{{ $item->empAttID }}</td>
                                    <td>
                                        {{ $item->employee->empLname ?? 'Unknown' }}, {{ $item->employee->empFname ?? 'Unknown' }}
                                        <input type="hidden" name="empID[]" value="{{ $item->empID }}">
                                    </td>
                                    <td class="text-center">{{ \Carbon\Carbon::parse($item->empAttDate)->format('M d, Y') }}</td>
                                    <td class="text-center">{{ $item->empAttTimeIn }}</td>
                                    <td class="text-center">{{ $item->empAttBreakOut }}</td>
                                    <td class="text-center">{{ $item->empAttBreakIn }}</td>
                                    <td class="text-center">{{ $item->empAttTimeOut }}</td>
                                    <td class="text-center">
                                        @php
                                        $remarks = strtolower(trim($item->empAttRemarks));
                                        $matchingLeave = $item->leaves->first(fn($leave) =>
                                        $leave->empLeaveStartDate <= $item->empAttDate &&
                                            $leave->empLeaveEndDate >= $item->empAttDate
                                            );
                                            $leaveStatus = $matchingLeave?->status;
                                            @endphp

                                            @if($remarks === 'absent' && $matchingLeave)
                                            <div class="badge bg-danger mb-1">Absent</div>
                                            <div class="small text-start mt-1">
                                                <strong>Date Leave:</strong> {{ \Carbon\Carbon::parse($matchingLeave->empLeaveStartDate)->format('M d, Y') }}
                                                to {{ \Carbon\Carbon::parse($matchingLeave->empLeaveEndDate)->format('M d, Y') }}<br>
                                                <strong>Status:</strong> {{ $leaveStatus->empLSStatus ?? 'Pending' }}<br>
                                                <strong>Pay Status:</strong> {{ $leaveStatus->empPayStatus ?? '-' }}
                                            </div>
                                            @elseif($remarks === 'absent')
                                            <span class="badge bg-danger">Absent</span><br>
                                            <span class="small text-muted">No Filled for Leave.</span>
                                            @elseif($remarks === 'present')
                                            <span class="badge bg-success">Present</span>
                                            @elseif($remarks === 'undertime')
                                            <span class="badge bg-warning text-dark">Undertime</span>
                                            @elseif($remarks)
                                            <span class="badge bg-secondary">{{ ucfirst($remarks) }}</span>
                                            @else
                                            <span class="text-muted">—</span>
                                            @endif
                                    </td>

                                </tr>
                                @empty
                                <tr>
                                    <td colspan="7" class="text-center">No attendance records found.</td>
                                </tr>
                                @endforelse
                            </tbody>

                        </table>

                        @if($attendance->hasPages())
                        <div class="d-flex flex-column align-items-center mt-4 gap-2">
                            {{-- Pagination links --}}
                            <div>
                                {{ $attendance->links('pagination::bootstrap-5') }}
                            </div>

                            {{-- Showing text --}}
                            <div class="text-muted small">
                                Showing {{ $attendance->firstItem() }} to {{ $attendance->lastItem() }} of {{ $attendance->total() }} results
                            </div>

                        </div>
                        @endif

                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/litepicker/dist/litepicker.js"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const input = document.getElementById('date_range');

            const picker = new Litepicker({
                element: input,
                singleMode: false,
                format: 'YYYY-MM-DD',
                autoApply: true,
                startDate: "{{ request('date_range') ? explode(' - ', request('date_range'))[0] : date('Y-m-d') }}",
                endDate: "{{ request('date_range') ? explode(' - ', request('date_range'))[1] ?? explode(' - ', request('date_range'))[0] : date('Y-m-d') }}",

            });

            picker.on('selected', (start, end) => {
                const startDate = start.format('YYYY-MM-DD');
                const endDate = end.format('YYYY-MM-DD');

                input.value = (startDate === endDate) ?
                    startDate :
                    `${startDate} - ${endDate}`;

                document.getElementById('filterForm').submit();
            });


            document.getElementById('open_datepicker').addEventListener('click', () => {
                picker.show();
            });
            // Initialize modal
            const addAttendanceModal = new bootstrap.Modal(document.getElementById('addAttendanceModal'));

            // Open modal button
            document.getElementById('openAddAttendanceModal').addEventListener('click', function() {
                addAttendanceModal.show();
            });

        });



        document.addEventListener('DOMContentLoaded', function() {
            const input = document.getElementById('employee_name_input');
            const form = document.getElementById('filterForm');

            let typingTimer;
            const debounceDelay = 500;

            input.addEventListener('input', () => {
                clearTimeout(typingTimer);
                typingTimer = setTimeout(() => {
                    form.submit();
                }, debounceDelay);
            });

            input.addEventListener('keydown', () => {
                clearTimeout(typingTimer);
            });
        });



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
    </script>



</body>

</html>