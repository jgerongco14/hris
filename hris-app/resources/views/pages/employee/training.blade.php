<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Trainings</title>
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
            <div class="col-10 p-3 pt-0 main-content">
                <!-- Include the titlebar component -->
                <div class="position-sticky top-0 z-3 w-100">
                    <x-titlebar />
                </div>

                <!-- Include the notification component -->
                <x-notification />

                <div class="card my-4">
                    <div class="card-header">
                        <h2 class="text-center card-title">My Trainings</h2>
                    </div>
                    <div class="card-body p-4">
                        @include('pages.employee.components.training_modal')

                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addTrainingModal">
                                Add Training
                            </button>
                        </div>

                        <table class="table table-bordered text">
                            <thead class="text-center">
                                <tr>
                                    <th>Training Name</th>
                                    <th>Desciption</th>
                                    <th>Duration</th>
                                    <th>Location</th>
                                    <th>Conducted By</th>
                                    <th>Attachments</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody class="align-middle">
                                @forelse($trainings as $training)
                                <tr>
                                    <td>{{ $training->empTrainName }}</td>
                                    <td>{{ $training->empTrainDescription }}</td>
                                    <td>From: {{ $training->empTrainFromDate }}<br>To: {{ $training->empTrainToDate }}</td>
                                    <td>{{ $training->empTrainLocation }}</td>
                                    <td>{{ $training->empTrainConductedBy }}</td>
                                    <td class="text-center">
                                        @php
                                        $attachments = json_decode($training->empTrainCertificate, true) ?? [];
                                        @endphp

                                        @if(count($attachments))
                                        @foreach($attachments as $index => $file)
                                        <div class="mb-1">
                                            <a href="{{ asset('storage/' . $file) }}" target="_blank" class="btn btn-outline-primary btn-sm">
                                                Attachment {{ $index + 1 }}
                                            </a>
                                        </div>
                                        @endforeach
                                        @else
                                        No attachment
                                        @endif

                                    </td>
                                    <td class="text-center">
                                        <button class="btn btn-warning btn-sm"
                                            data-id="{{ $training->id }}"
                                            data-name="{{ $training->empTrainName }}"
                                            data-conducted="{{ $training->empTrainConductedBy }}"
                                            data-fromdate="{{ $training->empTrainFromDate }}"
                                            data-todate="{{ $training->empTrainToDate }}"
                                            data-location="{{ $training->empTrainLocation }}"
                                            data-description="{{ $training->empTrainDescription }}"
                                            data-attachments='@json(json_decode($training->empTrainCertificate))'
                                            data-url="{{ route('training.update', $training->id) }}"
                                            data-bs-toggle="modal"
                                            data-bs-target="#editTrainingModal">
                                            <i class="ri-pencil-fill"></i>
                                        </button>


                                        <form action="{{ route('training.delete', $training->id) }}" method="POST" class="d-inline-block">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure?')" title="Delete">
                                                <i class="ri-delete-bin-5-line"></i>
                                            </button>
                                        </form>

                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="7" class="text-center">No training records found.</td>
                                </tr>
                                @endforelse
                            </tbody>

                        </table>
                        @if($trainings->hasPages())
                        <div class="d-flex flex-column align-items-center mt-4 gap-2">
                            {{ $trainings->links('pagination::bootstrap-5') }}
                            <div class="text-muted small">
                                Showing {{ $trainings->firstItem() }} to {{ $trainings->lastItem() }} of {{ $trainings->total() }} results
                            </div>
                        </div>
                        @endif
                    </div>
                </div>

            </div>
        </div>
    </div>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const editModal = document.getElementById('editTrainingModal');
            editModal.addEventListener('show.bs.modal', function(event) {
                const button = event.relatedTarget;

                // Fetch and populate training info
                const id = button.getAttribute('data-id');
                const name = button.getAttribute('data-name');
                const conducted = button.getAttribute('data-conducted');
                const fromDate = button.getAttribute('data-fromdate');
                const toDate = button.getAttribute('data-todate');
                const location = button.getAttribute('data-location');
                const description = button.getAttribute('data-description');
                const attachments = JSON.parse(button.getAttribute('data-attachments') || '[]');
                const actionUrl = button.getAttribute('data-url');

                editModal.querySelector('#editTrainingId').value = id;
                editModal.querySelector('#editEmpTrainName').value = name;
                editModal.querySelector('#editEmpTrainConductedBy').value = conducted;
                editModal.querySelector('#editEmpTrainFromDate').value = fromDate;
                editModal.querySelector('#editEmpTrainToDate').value = toDate;
                editModal.querySelector('#editEmpTrainLocation').value = location;
                editModal.querySelector('#editEmpTrainDescription').value = description;

                const form = editModal.querySelector('#editTrainingForm');
                form.action = actionUrl;
                form.querySelector('input[name="_method"]').value = "PUT";

                const attachmentsDiv = editModal.querySelector('#existingAttachments');
                const hiddenInputsDiv = editModal.querySelector('#existingCertificatesInputs');
                attachmentsDiv.innerHTML = '';
                hiddenInputsDiv.innerHTML = '';

                attachments.forEach((file, index) => {
                    const filename = file.split('/').pop();
                    const link = `<a href="/storage/${file}" target="_blank" class="me-2">${filename}</a>`;
                    const removeBtn = `<button type="button" class="btn btn-sm btn-danger" onclick="removeAttachment('${file}')">Delete</button>`;
                    const div = document.createElement('div');
                    div.classList.add('d-flex', 'align-items-center', 'mb-1');
                    div.innerHTML = link + removeBtn;
                    div.setAttribute('data-file', file);
                    attachmentsDiv.appendChild(div);

                    const input = document.createElement('input');
                    input.type = 'hidden';
                    input.name = 'existingCertificates[]';
                    input.value = file;
                    input.setAttribute('data-file', file);
                    hiddenInputsDiv.appendChild(input);
                });
            });
        });

        function removeAttachment(filePath) {
            const attachmentDiv = document.querySelector(`#existingAttachments div[data-file="${filePath}"]`);
            const inputHidden = document.querySelector(`#existingCertificatesInputs input[data-file="${filePath}"]`);
            if (attachmentDiv) attachmentDiv.remove();
            if (inputHidden) inputHidden.remove();
        }

        function showToast(title, message, type = 'success') {
            const toastEl = document.getElementById('liveToast');
            const toastHeader = document.getElementById('toast-header');
            const toastTitle = document.getElementById('toast-title');
            const toastMessage = document.getElementById('toast-message');
            const toastIcon = document.getElementById('toast-icon');

            // Reset toast class
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

            toastHeader.className = `toast-header ${headerColors[type] || 'text-dark'}`;
            toastIcon.textContent = icons[type] || '';
            toastTitle.textContent = title;
            toastMessage.textContent = message;

            const toast = new bootstrap.Toast(toastEl, {
                delay: 5000
            });
            toast.show();
        }
    </script>
</body>

</html>