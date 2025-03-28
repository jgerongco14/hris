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
                <x-navbar />
            </div>

            <!-- Main Content Section -->
            <div class="col-10">
                <x-titlebar />

                <!-- Profile Section -->
                <x-myProfile />
            </div>
        </div>
    </div>
</body>

</html>