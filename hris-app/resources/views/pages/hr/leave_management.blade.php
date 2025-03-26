<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Employee Leave Management</title>
    @vite(['resources/sass/app.scss', 'resources/js/app.js'])
    <link href="https://cdn.jsdelivr.net/npm/remixicon@2.5.0/fonts/remixicon.css" rel="stylesheet">
    <link rel="stylesheet" href="/css/leave_manangement.css">
</head>

<body>

    <div class="container-fluid">

        <div class="row">
            <!-- Navigation Section -->
            <div class="col-2">
                <!-- Include the navbar component -->
                <x-navbar />
            </div>

            <!-- Main Content Section -->
            <div class="col-10">
                <!-- Include the titlebar component -->
                <x-titlebar />


                <!-- Employee List Section -->
                <div class="row my-4">
                    <div class="col">
                        <h3>EMPLOYEE LIST</h3>
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>Employee ID</th>
                                    <th>Avatar & Text</th>
                                    <th>Full Name</th>
                                    <th>Position</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>2024-0001</td>
                                    <td>XXXX, XXXX XXXX</td>
                                    <td>XXXX, XXXX XXXX</td>
                                    <td>President</td>
                                    <td><button class="btn btn-sm btn-primary">View</button></td>
                                </tr>
                                <tr>
                                    <td>2024-0002</td>
                                    <td>XXXX, XXXX XXXX</td>
                                    <td>XXXX, XXXX XXXX</td>
                                    <td>Vice President for Academic Affairs</td>
                                    <td><button class="btn btn-sm btn-primary">View</button></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    <!-- User Profile Section -->
                    <div class="row my-4">
                        <div class="col">
                            <div class="card">
                                <div class="card-body">
                                    <h4 class="card-title">DR. AAAAA A. AAAAAA</h4>
                                    <p class="card-text">Vice President for Academic Affairs</p>
                                    <p class="card-text">Appointed last XXXXXXX XX, XXXX</p>
                                    <a href="#" class="btn btn-link">Update Profile</a>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Leave Application Status Section -->
                    <div class="row my-4">
                        <div class="col">
                            <h3>LEAVE APPLICATION STATUS</h3>
                            <ul class="nav nav-pills mb-3" id="leaveTabs" role="tablist">
                                <li class="nav-item" role="presentation">
                                    <a class="nav-link active" id="all-tab" data-bs-toggle="pill" href="#all" role="tab" aria-controls="all" aria-selected="true">All</a>
                                </li>
                                <li class="nav-item" role="presentation">
                                    <a class="nav-link" id="approval-tab" data-bs-toggle="pill" href="#approval" role="tab" aria-controls="approval" aria-selected="false">For Approval</a>
                                </li>
                                <li class="nav-item" role="presentation">
                                    <a class="nav-link" id="approved-tab" data-bs-toggle="pill" href="#approved" role="tab" aria-controls="approved" aria-selected="false">Approved</a>
                                </li>
                                <li class="nav-item" role="presentation">
                                    <a class="nav-link" id="declined-tab" data-bs-toggle="pill" href="#declined" role="tab" aria-controls="declined" aria-selected="false">Declined</a>
                                </li>
                            </ul>

                            <div class="tab-content" id="leaveTabsContent">
                                <div class="tab-pane fade show active" id="all" role="tabpanel" aria-labelledby="all-tab">
                                    <table class="table table-bordered">
                                        <thead>
                                            <tr>
                                                <th>Date Applied</th>
                                                <th>Avatar & Text</th>
                                                <th>Type of Leave</th>
                                                <th>Date Range</th>
                                                <th>Reason</th>
                                                <th>Status</th>
                                                <th>Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td>Oct 20, 2024</td>
                                                <td>XXXX, XXXX XXXX</td>
                                                <td>Business Leave</td>
                                                <td>Nov 3, 2024 - Nov 3, 2024</td>
                                                <td>Attend Seminar</td>
                                                <td><span class="badge bg-warning">PENDING</span></td>
                                                <td><button class="btn btn-sm btn-primary">Edit</button></td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

</body>

</html>