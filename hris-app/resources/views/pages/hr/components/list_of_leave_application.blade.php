  <!-- Leave Application Status Section -->
  <div class="empleavelist row my-4">
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
                  <table class="table table-bordered text">
                      <thead class="text-center">
                          <tr>
                              <th>Date Applied</th>
                              <th>Employee</th>
                              <th>Type of Leave</th>
                              <th>Date Range</th>
                              <th>Reason</th>
                              <th>Status</th>
                              <th>Actions</th>
                          </tr>
                      </thead>
                      <tbody class="align-middle">
                          @forelse($leaveStatuses as $status)
                          <tr>
                              <!-- Date Applied -->
                              <td>{{ \Carbon\Carbon::parse($status->leave->empLeaveDateApplied)->format('M d, Y') }}</td>

                              <!-- Employee -->
                              <td class="d-flex align-items-center gap-2">
                                  @php
                                  $employee = $status->leave->employee ?? null;
                                  $photo = $employee->photo ?? null;
                                  $isExternal = $photo && Str::startsWith($photo, ['http://', 'https://']);
                                  @endphp

                                  @if($photo)
                                  <img src="{{ $isExternal ? $photo : asset('storage/' . $photo) }}"
                                      alt="Avatar"
                                      width="40"
                                      height="40"
                                      class="rounded-circle"
                                      onerror="this.onerror=null; this.src='/images/default-user.png';">
                                  @else
                                  <img src="/images/default-user.png" alt="Avatar" width="40" height="40" class="rounded">
                                  @endif

                                  <span>
                                      <!-- {{ $employee->empID ?? 'N/A' }}, -->
                                      {{ $employee->empFname ?? '' }} {{ $employee->empMname ?? '' }} {{ $employee->empLname ?? '' }}
                                  </span>
                              </td>

                              <!-- Type of Leave -->
                              <td>{{ $status->leave->leaveType }}</td>

                              <!-- Date Range -->
                              <td>
                                  {{ \Carbon\Carbon::parse($status->leave->empLeaveStartDate)->format('M d, Y') }}
                                  -
                                  {{ \Carbon\Carbon::parse($status->leave->empLeaveEndDate)->format('M d, Y') }}
                              </td>

                              <!-- Reason -->
                              <td>{{ $status->leave->empLeaveDescription }}</td>

                              <!-- Status -->
                              <td class="text-center">
                                  @php
                                  $badgeClass = match(strtolower($status->empLSStatus)) {
                                  'pending' => 'bg-secondary',
                                  'approved' => 'bg-success',
                                  'declined' => 'bg-danger',
                                  default => 'bg-light text-dark',
                                  };
                                  @endphp
                                  <span class="badge {{ $badgeClass }}">{{ strtoupper($status->empLSStatus) }}</span>
                              </td>

                              <!-- Actions -->
                              <td class="text-center">
                                  @if(strtolower($status->empLSStatus) === 'pending')
                                  <a href="javascript:void(0);"
                                      class="btn btn-sm"
                                      onclick="fetchLeaveData('{{ $status->empLeaveNo }}')"
                                      title="Edit Leave Application"
                                      data-bs-toggle="tooltip" data-bs-placement="top">
                                      <i class="ri-pencil-line"></i> <!-- Pencil Icon -->
                                  </a>
                                  @endif
                              </td>
                          </tr>
                          @empty
                          <tr>
                              <td colspan="7" class="text-center text-muted">No leave applications yet.</td>
                          </tr>
                          @endforelse
                      </tbody>

                  </table>
              </div>
          </div>
      </div>
  </div>