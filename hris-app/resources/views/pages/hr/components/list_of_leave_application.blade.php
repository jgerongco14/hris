<!-- Leave Application Status Section -->
<div class="empleavelist row my-4">
    <div class="col">
        <h3>LEAVE APPLICATION STATUS</h3>
        <div class="card card-body p-3">
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
                @foreach($tabs as $tabId => $tabConfig)
                <div class="tab-pane fade {{ $tabId === 'all' ? 'show active' : '' }}" id="{{ $tabId }}" role="tabpanel" aria-labelledby="{{ $tabId }}-tab">
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
                            @forelse($tabConfig['data'] as $status)
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

                                    <img src="{{ $photo ? ($isExternal ? $photo : asset('storage/' . $photo)) : '/images/default-user.png' }}"
                                        alt="Avatar"
                                        width="40"
                                        height="40"
                                        class="{{ $photo ? 'rounded-circle' : 'rounded' }}"
                                        onerror="this.onerror=null; this.src='/images/default-user.png';">

                                    <span>
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
                                    @if($tabConfig['show_actions'] && strtolower($status->empLSStatus) === 'pending')
                                    <a href="javascript:void(0);"
                                        class="btn btn-sm"
                                        onclick="fetchLeaveData('{{ $status->empLeaveNo }}')"
                                        title="Edit Leave Application"
                                        data-bs-toggle="tooltip" data-bs-placement="top">
                                        <i class="ri-pencil-line"></i>
                                    </a>
                                    @endif
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="7" class="text-center text-muted">{{ $tabConfig['empty'] }}</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>

                  @if($tabConfig['data']->hasPages())
                     <div class="d-flex flex-column align-items-center mt-4 gap-2">
                        {{-- Pagination links --}}
                        <div>
                            {{ $tabConfig['data']->links('pagination::bootstrap-5') }}
                        </div>

                        {{-- Showing text --}}
                        <div class="text-muted small">
                            Showing {{ $tabConfig['data']->firstItem() }} to {{ $tabConfig['data']->lastItem() }} of {{ $tabConfig['data']->total() }} results
                        </div>
                    </div> 
                    @endif 


                </div>
                @endforeach
            </div>
        </div>
    </div>
</div>