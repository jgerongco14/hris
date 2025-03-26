<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profile</title>
    @vite(['resources/sass/app.scss', 'resources/js/app.js'])
    <link href="https://cdn.jsdelivr.net/npm/remixicon@2.5.0/fonts/remixicon.css" rel="stylesheet">
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
                <!-- Profile Section -->
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
            </div>
        </div>
    </div>
</body>

</html>