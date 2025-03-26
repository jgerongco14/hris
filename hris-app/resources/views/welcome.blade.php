<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>HRIS</title>
    @vite(['resources/sass/app.scss', 'resources/js/app.js'])
    <link href="https://cdn.jsdelivr.net/npm/remixicon@2.5.0/fonts/remixicon.css" rel="stylesheet">
</head>

<body>

    <div class="container-fluid mt-5">
        <h1 class="text-center">HUMAN RESOURCE INFORMATION SYSTEM</h1>
        <!-- Button to go to Login Page -->
        <div class="text-center my-4">
            <a href="{{ route('login') }}" class="btn btn-primary">Go to Login</a>
        </div>
    </div>

</body>

</html>