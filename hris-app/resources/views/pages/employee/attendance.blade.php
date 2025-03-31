<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Attendance</title>
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

                <div class="card my-4">
                    <div class="card-body p-4">
                        <h3 class="text-center">Attendance Records</h3>
                        <div class="row align-items-end">
                            <!-- Date Range Picker -->
                            <div class="col-3 my-3">
                                <form method="GET" id="filterForm" class="d-flex gap-2 mb-3">
                                    <div class="input-group">
                                        <button class="btn btn-outline-secondary" type="button" id="open_datepicker">
                                            <i class="ri-calendar-line"></i>
                                        </button>
                                        <input type="text" name="date_range" class="form-control" id="date_range" placeholder="Filter by date range" value="{{ request('date_range') }}">
                                    </div>
                                </form>
                            </div>
                        </div>

                        <table class="table table-bordered text">
                            <thead class="text-center">
                                <tr>
                                    <th>Attendance ID</th>
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
                                    <td class="text-center">{{ \Carbon\Carbon::parse($item->empAttDate)->format('Y-m-d') }}</td>
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

        });
    </script>

</body>

</html>