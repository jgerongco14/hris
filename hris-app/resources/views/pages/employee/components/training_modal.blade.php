<!-- Add Training Modal -->
<div class="modal fade" id="addTrainingModal" tabindex="-1" aria-labelledby="addTrainingModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">

            <form action="{{ route('training.store') }}" method="POST" enctype="multipart/form-data">
                @csrf

                <div class="modal-header">
                    <h5 class="modal-title" id="addTrainingModalLabel">Add New Training</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <div class="modal-body row g-3">
                    <div class="col-md-6">
                        <label for="empTrainName" class="form-label">Training Name <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" name="empTrainName" required>
                    </div>

                    <div class="col-md-6">
                        <label for="empTrainConductedBy" class="form-label">Conducted By</label>
                        <input type="text" class="form-control" name="empTrainConductedBy">
                    </div>

                    <div class="col-md-6">
                        <label for="empTrainFromDate" class="form-label">From Date</label>
                        <input type="date" class="form-control" name="empTrainFromDate">
                    </div>

                    <div class="col-md-6">
                        <label for="empTrainToDate" class="form-label">To Date</label>
                        <input type="date" class="form-control" name="empTrainToDate">
                    </div>

                    <div class="col-md-12">
                        <label for="empTrainLocation" class="form-label">Location</label>
                        <input type="text" class="form-control" name="empTrainLocation">
                    </div>

                    <div class="col-md-12">
                        <label for="empTrainDescription" class="form-label">Description</label>
                        <textarea class="form-control" name="empTrainDescription" rows="3"></textarea>
                    </div>

                    <div class="col-md-12">
                        <label for="empTrainCertificate" class="form-label">Certificate (PDF only)</label>
                        <input type="file" name="empTrainCertificate[]" class="form-control" multiple accept="application/pdf">
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary">Save Training</button>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                </div>

            </form>

        </div>
    </div>
</div>


<!-- Edit Training Modal -->
<div class="modal fade" id="editTrainingModal" tabindex="-1" aria-labelledby="editTrainingModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">

            <form id="editTrainingForm" method="POST" enctype="multipart/form-data">
                @csrf
                @method('POST') <!-- Will be dynamically replaced -->

                <div class="modal-header">
                    <h5 class="modal-title">Edit Training</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <div class="modal-body row g-3">
                    <input type="hidden" id="editTrainingId">

                    <div class="col-md-6">
                        <label class="form-label">Training Name</label>
                        <input type="text" class="form-control" name="empTrainName" id="editEmpTrainName" required>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">Conducted By</label>
                        <input type="text" class="form-control" name="empTrainConductedBy" id="editEmpTrainConductedBy">
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">From Date</label>
                        <input type="date" class="form-control" name="empTrainFromDate" id="editEmpTrainFromDate">
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">To Date</label>
                        <input type="date" class="form-control" name="empTrainToDate" id="editEmpTrainToDate">
                    </div>

                    <div class="col-md-12">
                        <label class="form-label">Location</label>
                        <input type="text" class="form-control" name="empTrainLocation" id="editEmpTrainLocation">
                    </div>

                    <div class="col-md-12">
                        <label class="form-label">Description</label>
                        <textarea class="form-control" name="empTrainDescription" id="editEmpTrainDescription" rows="3"></textarea>
                    </div>

                    <div class="col-md-12">
                        <label class="form-label">Existing Certificates</label>

                        <div id="existingAttachments" class="mb-2">
                            <!-- links + delete buttons -->
                        </div>

                        <!-- 👇 move this out of the box that gets cleared -->
                        <div id="existingCertificatesInputs"></div>
                    </div>


                    <div class="col-md-12">
                        <label class="form-label">Add New Certificates (PDF only)</label>
                        <input type="file" name="empTrainCertificate[]" class="form-control" multiple accept="application/pdf">
                    </div>

                </div>

                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary">Update Training</button>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                </div>

            </form>

        </div>
    </div>
</div>