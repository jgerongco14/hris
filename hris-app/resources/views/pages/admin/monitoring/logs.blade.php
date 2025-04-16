<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Activity Logs</title>
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
            <div class="col-10 p-3 pt-0">
                <x-notification />
                <div class="position-sticky top-0 z-3 w-100">
                    <x-titlebar />
                </div>
                <div class="card mt-4 mx-3">
                    <div class="card-header">
                        <h3 class="card-title text-center">Activity Logs</h3>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped table-hover">
                                <thead class="text-center align-middle">
                                    <tr>
                                        <th class="col-1">No</th>
                                        <th class="col-1">Action</th>
                                        <th class="col-5">Description</th>
                                        <th class="col-1">IP Address</th>
                                        <th class="col-3">Device</th>
                                        <th class="col-1">Date & Time</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($logs as $log)
                                    <tr class="align-middle">
                                    <td class="col-1 text-center">{{ $loop->iteration + ($logs->currentPage() - 1) * $logs->perPage() }}</td>
                                        <td class="col-1 text-center">{{ $log->action }}</td>
                                        <td class="col-5">{{ $log->description }}</td>
                                        <td class="col-1 text-center">{{ $log->ip_address }}</td>
                                        <td class="col-3">{{ $log->user_agent }}</td>
                                        <td class="col-1 text-center">{{ $log->created_at->format('Y-m-d h:i A') }}</td>
                                    </tr>
                                    @endforeach
                                    @if($logs->isEmpty())
                                    <tr class="text-center">
                                        <td colspan="6">No activity logs found.</td>
                                    </tr>
                                    @endif
                                </tbody>
                            </table>

                            <!-- Pagination -->
                            @if($logs->hasPages())
                            <div class="d-flex flex-column align-items-center mt-4 gap-2">
                                <div>
                                    {{ $logs->links('pagination::bootstrap-5') }}
                                </div>
                                <div class="text-muted small">
                                    Showing {{ $logs->firstItem() }} to {{ $logs->lastItem() }} of {{ $logs->total() }} results
                                </div>
                            </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>

</html>