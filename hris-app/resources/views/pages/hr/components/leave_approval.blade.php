  <!-- Hidden Leave Application Approval Form -->
  <div class="row my-4" id="approvalForm" style="display: none;" data-leave-id="">
      <div class="col">
          <div class="card p-4">
              <h5><strong>LEAVE APPLICATION FORM: Approval</strong></h5>
              <div class="card p-3">
                  <div class="row">
                      <div class="col-md-6">
                          <p><strong>Name and Department</strong><br>
                              <span id="approvalName"></span><br>
                              <!-- <em id="approvalDepartment"></em> -->
                          </p>
                          <p><strong>Position</strong><br>
                              <!-- <span id="approvalPosition"></span> -->
                          </p>
                          <p><strong>Reason for Leave</strong><br>
                              <span id="approvalReason"></span>
                          </p>
                      </div>
                      <div class="col-md-6">
                          <p><strong>Type of Leave*</strong><br>
                              <span id="approvalLeaveType"></span>
                          </p>
                          <p><strong>Date of Leave</strong><br>
                              <span id="approvalDates"></span>
                          </p>
                          <p><strong>Attachment</strong><br>
                              <span id="approvalAttachments"></span>
                          </p>
                      </div>
                  </div>
              </div>
              <form id="approvalFormElement" class="mt-3" enctype="multipart/form-data" onsubmit="return false;">
                  <div class="row mt-3">
                      <div class="col-md-6">
                          <label for="approvalStatus" class="form-label">Approval Status</label>
                          <select class="form-select" id="approvalStatus">
                              <option value="approved">Approved</option>
                              <option value="declined">Declined</option>
                              <option value="pending">Pending</option>
                          </select>
                      </div>
                      <div class="col-md-6">
                          <label for="payStatus" class="form-label">Pay Status*</label>
                          <select class="form-select" id="payStatus">
                              <option selected>With Pay</option>
                              <option>Without Pay</option>
                          </select>
                      </div>
                      <div class="col-12 mt-3">
                          <label for="remarks" class="form-label">Remarks</label>
                          <textarea class="form-control" id="remarks" rows="3" placeholder="(Optional) Enter remarks regarding the leave application"></textarea>
                      </div>
                      <div class="col-12 mt-3 text-center">
                          <button class="btn btn-secondary" id="cancelApproval">Cancel</button>
                          <button class="btn btn-primary submit" id="submitApplication">Submit Application</button>
                      </div>
                  </div>
              </form>
          </div>
      </div>