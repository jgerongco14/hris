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
            <div class="col-md-2 sidebar">
                <x-navbar />
            </div>
            <div class="col-md-10 main-content">
                <!-- Include the titlebar component -->
                <x-titlebar />

                <!-- Include the notification component -->
                <x-notification />

                <!-- Include the modal for adding attendance -->
                <h1>Attendance Management</h1>
                <div class="card">
                    <div class="card-body">
                        <h3>Attendance Records</h3>
                        <div class="row align-items-end">
                            <!-- Date Range Picker -->
                            <div class="col-6">
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
                                        autocomplete="off"
                                        value="{{ request('employee_name') }}">

                                </form>

                            </div>

                            <!-- Import Attendance Button -->
                            @include('pages.hr.components.import_attendance')

                            <!-- Push button to the right -->
                            <div class="col-md-2 ms-auto">
                                <div class="mb-3">
                                    <button type="button" id="openAddAttendanceModal" class="btn btn-primary w-100" data-bs-toggle="modal" data-bs-target="#addAttendanceModal">
                                        <i class="ri-add-line"></i> Add Attendance
                                    </button>
                                </div>
                            </div>
                        </div>

                        <table class="table table-bordered text">
                            <thead class="text-center">
                                <tr>
                                    <th>Attendance ID</th>
                                    <th>Employee Name</th>
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
                                    <td class="text-center">{{ $item->empAttTimeIn }}</td>
                                    <td class="text-center">{{ $item->empAttBreakOut }}</td>
                                    <td class="text-center">{{ $item->empAttBreakIn }}</td>
                                    <td class="text-center">{{ $item->empAttTimeOut }}</td>
                                    <td class="text-center">{{ $item->empAttRemarks }}</td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="7" class="text-center">No attendance records found.</td>
                                </tr>
                                @endforelse
                            </tbody>

                        </table>
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