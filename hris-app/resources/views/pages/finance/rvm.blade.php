<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>RVM</title>
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
                <div class="position-sticky top-0 z-3 w-100">
                    <x-titlebar />
                </div>

                @include('pages.finance.components.employee_list', [
                'employees' => $employees,
                'departments' => $departments,
                'offices' => $offices,
                'positions' => $positions,
                ])

            </div>
        </div>
    </div>
</body>

</html>