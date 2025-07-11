<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Contributions</title>
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

                <x-notification />

                <div class="card my-4 mx-3">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">Employee Contributions</h5>
                    </div>
                    <div class="card-body">
                        <!-- Contribution Tabs -->
                        @php
                        $activeType = request('contribution_type', 'SSS'); // default to SSS
                        @endphp

                        <ul class="nav nav-tabs" id="contributionTabs" role="tablist">
                            <li class="nav-item" role="presentation">
                                <button class="nav-link {{ $activeType === 'SSS' ? 'active' : '' }}" id="sss-tab"
                                    data-bs-toggle="tab" data-bs-target="#sss" type="button" role="tab"
                                    aria-controls="sss" aria-selected="{{ $activeType === 'SSS' ? 'true' : 'false' }}">SSS</button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link {{ $activeType === 'PAG-IBIG' ? 'active' : '' }}" id="pagibig-tab"
                                    data-bs-toggle="tab" data-bs-target="#pagibig" type="button" role="tab"
                                    aria-controls="pagibig" aria-selected="{{ $activeType === 'PAG-IBIG' ? 'true' : 'false' }}">PAG-IBIG</button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link {{ $activeType === 'TIN' ? 'active' : '' }}" id="tin-tab"
                                    data-bs-toggle="tab" data-bs-target="#tin" type="button" role="tab"
                                    aria-controls="tin" aria-selected="{{ $activeType === 'TIN' ? 'true' : 'false' }}">TIN</button>
                            </li>
                        </ul>


                        <div class="tab-content" id="contributionTabsContent">
                            <!-- SSS Contributions -->
                            <div class="tab-pane fade {{ $activeType === 'SSS' ? 'show active' : '' }}" id="sss" role="tabpanel" aria-labelledby="sss-tab">
                                @include('pages.hr.components.contribution_sss_table', ['sssContributions' => $sssContributions])
                            </div>

                            <!-- PAG-IBIG Contributions -->
                            <div class="tab-pane fade {{ $activeType === 'PAG-IBIG' ? 'show active' : '' }}" id="pagibig" role="tabpanel" aria-labelledby="pagibig-tab">
                                @include('pages.hr.components.contribution_pag-ibig_table', ['pagibigContributions' => $pagibigContributions])
                            </div>

                            <!-- TIN Contributions -->
                            <div class="tab-pane fade {{ $activeType === 'TIN' ? 'show active' : '' }}" id="tin" role="tabpanel" aria-labelledby="tin-tab">
                                @include('pages.hr.components.contribution_tin_table', ['tinContributions' => $tinContributions])
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const tabs = document.querySelectorAll('#contributionTabs .nav-link');

            tabs.forEach(tab => {
                tab.addEventListener('click', function(e) {
                    e.preventDefault();
                    const type = this.textContent.trim();
                    const url = new URL(window.location.href);
                    url.searchParams.set('contribution_type', type);
                    window.location.href = url.toString();
                });
            });
        });
    </script>
</body>

</html>