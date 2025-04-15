<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Employee Contributions</title>
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
                <!-- Include the titlebar component -->
                <div class="position-sticky top-0 z-3 w-100">
                    <x-titlebar />
                </div>

                <!-- Include the notification component -->
                <x-notification />

                @if(isset($contribution))
                @include('pages.hr.components.contribution_form', ['contribution' => $contribution])
                @endif

                <!-- Import Attendance Button -->
                @include('components.import_file')

                @include('pages.hr.components.contribution_form')


                <div class="card my-5">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">Employee Contributions</h5>
                        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addContributionModal">
                            <i class="ri-add-line"></i> Add Contribution
                        </button>
                    </div>
                    <div class="card-body">
                        <!-- Tabs for Contributions -->
                        @php
                        $activeType = request('contribution_type', 'SSS'); // default to SSS
                        @endphp

                        @include('pages.hr.components.contribution_tabs', ['activeType' => $activeType])

                        <div class="tab-content" id="contributionTabsContent">

                            @include('pages.hr.components.contribution_sss_table', ['sssContributions' => $sssContributions, 'activeType' => $activeType])
                            @include('pages.hr.components.contribution_pag-ibig_table', ['pagibigContributions' => $pagibigContributions, 'activeType' => $activeType])
                            @include('pages.hr.components.contribution_tin_table', ['tinContributions' => $tinContributions, 'activeType' => $activeType])

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.1/moment.min.js"></script>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.min.js"></script>

    <script>
        $('.edit-contribution').on('click', function() {
            var contributionId = $(this).data('id');
            var amount = $(this).data('amount');
            var date = $(this).data('date');
            var remarks = $(this).data('remarks');
            var type = $(this).data('type');

            // Set the action URL of the form
            var actionUrl = "{{ route('contribution.update', ':id') }}".replace(':id', contributionId);
            $('#editContributionForm').attr('action', actionUrl);

            // Convert date to 'YYYY-MM-DD' format if necessary
            var formattedDate = moment(date).format('YYYY-MM-DD'); // Using moment.js for date formatting

            // Populate the form fields
            $('#empConAmount').val(amount);
            $('#empConDate').val(formattedDate); // Ensure the date is in 'YYYY-MM-DD' format
            $('#empConRemarks').val(remarks);
            $('#empContype').val(type);
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

        const contributionType = urlParams.get('contribution_type');
        if (contributionType) {
            const tabId = contributionType.toLowerCase();
            const tabButton = document.getElementById(`${tabId}-tab`);
            if (tabButton) {
                const tab = new bootstrap.Tab(tabButton);
                tab.show();
            }
        }
    </script>

</body>

</html>