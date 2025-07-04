<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Employee;
use Exception;
use App\Models\LeaveStatus;
use App\Models\Leave;
use Illuminate\Support\Facades\Auth;
use App\Traits\LogsActivity;


class EmpLeaveController extends Controller
{
    use LogsActivity;

    public function index()
    {
        try {
            // Get base query with relationships
            $baseQuery = LeaveStatus::with('leave.employee')->latest();

            // Get paginated results for each tab with distinct query names
            $tabs = [
                'all' => [
                    'data' => $baseQuery->paginate(10, ['*'], 'all_page'),
                    'empty' => 'No leave applications yet.',
                    'show_actions' => true
                ],
                'approval' => [
                    'data' => $baseQuery->clone()->where('empLSStatus', 'pending')->paginate(10, ['*'], 'approval_page'),
                    'empty' => 'No leave applications pending approval.',
                    'show_actions' => true
                ],
                'approved' => [
                    'data' => $baseQuery->clone()->where('empLSStatus', 'approved')->paginate(10, ['*'], 'approved_page'),
                    'empty' => 'No approved leave applications.',
                    'show_actions' => false
                ],
                'declined' => [
                    'data' => $baseQuery->clone()->where('empLSStatus', 'declined')->paginate(10, ['*'], 'declined_page'),
                    'empty' => 'No declined leave applications.',
                    'show_actions' => false
                ]
            ];

            return view('pages.hr.leave_management', compact('tabs'));
        } catch (Exception $e) {
            // Log the error message
            $currentUser = Auth::user();
            $employee = $currentUser->employee;
            $fullName = $employee
                ? trim("{$employee->empPrefix} {$employee->empFname} {$employee->empMname} {$employee->empLname} {$employee->empSuffix}")
                : 'Unknown Employee';
            $this->logActivity('Leave Management', "User $fullName encountered an error while fetching leave applications: " . $e->getMessage(), $currentUser->id);

            logger()->error('Failed to fetch leave applications: ' . $e->getMessage());
            return redirect()
                ->back()
                ->with('Error', 'Failed to fetch leave applications. Please try again later.');
        }
    }

    public function showLeave()
    {
        try {
            $employee = Auth::user()->employee;

            $baseQuery = LeaveStatus::with('leave')
                ->whereHas('leave', function ($query) use ($employee) {
                    $query->where('empID', $employee->empID);
                })
                ->latest();

            // Prepare paginated tabs
            $tabs = [
                'all' => [
                    'data' => $baseQuery->paginate(10, ['*'], 'all_page'),
                    'empty' => 'No leave applications yet.',
                    'show_actions' => true
                ],
                'approval' => [
                    'data' => $baseQuery->clone()->where('empLSStatus', 'pending')->paginate(10, ['*'], 'approval_page'),
                    'empty' => 'No leave applications pending approval.',
                    'show_actions' => true
                ],
                'approved' => [
                    'data' => $baseQuery->clone()->where('empLSStatus', 'approved')->paginate(10, ['*'], 'approved_page'),
                    'empty' => 'No approved leave applications.',
                    'show_actions' => false
                ],
                'declined' => [
                    'data' => $baseQuery->clone()->where('empLSStatus', 'declined')->paginate(10, ['*'], 'declined_page'),
                    'empty' => 'No declined leave applications.',
                    'show_actions' => false
                ]
            ];


            return view('pages.employee.leave', compact('tabs'));
        } catch (Exception $e) {
            // Log the error message
            $currentUser = Auth::user();
            $employee = $currentUser->employee;
            $fullName = $employee
                ? trim("{$employee->empPrefix} {$employee->empFname} {$employee->empMname} {$employee->empLname} {$employee->empSuffix}")
                : 'Unknown Employee';
            $this->logActivity('Leave Management', "User $fullName encountered an error while fetching leave applications: " . $e->getMessage(), $currentUser->id);
            logger()->error('Failed to fetch leave details: ' . $e->getMessage());
            return response()->json([
                'error' => 'Failed to fetch leave details',
                'message' => config('app.debug') ? $e->getMessage() : 'Please try again later'
            ], 500);
        }
    }


    //For leave application page
    public function show($id)
    {
        try {
            $leave = LeaveStatus::with([
                'leave.employee.assignments.position',
            ])
                ->where('empLeaveNo', $id)
                ->firstOrFail();


            if (!$leave) {
                return response()->json(['error' => 'Leave not found'], 404);
            }

            $employee = optional($leave->leave->employee);

            // Get all unique position names from assignments
            $positions = $employee->assignments
                ->filter(fn($assignment) => $assignment->position) // only if position exists
                ->pluck('position.positionName')
                ->unique()
                ->values()
                ->all();

            return response()->json([
                'empLeaveNo' => $leave->empLeaveNo,
                'empID' => $leave->empID,
                'name' => optional($leave->leave->employee)->empFname . ' ' . optional($leave->leave->employee)->empLname,
                'positionNames' => $positions,
                'type' => $leave->leave->leaveType,
                'dates' => [
                    'start' => $leave->leave->empLeaveStartDate,
                    'end' => $leave->leave->empLeaveEndDate,
                ],
                'reason' => $leave->leave->empLeaveDescription,

                'attachment' => collect(json_decode($leave->leave->empLeaveAttachment ?? '[]', true))
                    ->map(function ($path) {
                        return [
                            'url' => asset('storage/' . $path),
                            'type' => pathinfo($path, PATHINFO_EXTENSION)
                        ];
                    })->toArray(),

                'status' => $leave->empLSStatus,
            ]);
        } catch (Exception $e) {

            $currentUser = Auth::user();
            $employee = $currentUser->employee;
            $fullName = $employee
                ? trim("{$employee->empPrefix} {$employee->empFname} {$employee->empMname} {$employee->empLname} {$employee->empSuffix}")
                : 'Unknown Employee';
            $this->logActivity('View', "User $fullName encountered an error while fetching leave details: " . $e->getMessage(), $currentUser->id);
            logger()->error('Failed to fetch leave details: ' . $e->getMessage());

            return redirect()
                ->back()
                ->with('Error', 'Failed to fetch leave details. Please try again later.');
        }
    }

    public function showEmployee($id)
    {
        try {
            // Get all leave records for the employee
            $leaveRecords = LeaveStatus::with(['leave'])
                ->where('empID', $id)
                ->orderBy('created_at', 'desc')
                ->get();


            if ($leaveRecords->isEmpty()) {
                return response()->json(['error' => 'No leave records found for this employee'], 404);
            }

            $data = $leaveRecords->map(function ($leave) {
                return [
                    'empLeaveNo' => $leave->empLeaveNo,
                    'type' => optional($leave->leave)->leaveType,
                    'dateApplied' => optional($leave->leave)->empLeaveDateApplied,
                    'dates' => [
                        'start' => optional($leave->leave)->empLeaveStartDate,
                        'end' => optional($leave->leave)->empLeaveEndDate,
                    ],
                    'reason' => optional($leave->leave)->empLeaveDescription,
                    'status' => $leave->empLSStatus,
                    'remarks' => $leave->empLSRemarks,
                ];
            });

            return response()->json($data);
        } catch (Exception $e) {
            $currentUser = Auth::user();
            $employee = $currentUser->employee;
            $fullName = $employee
                ? trim("{$employee->empPrefix} {$employee->empFname} {$employee->empMname} {$employee->empLname} {$employee->empSuffix}")
                : 'Unknown Employee';
            $this->logActivity('View', "User $fullName encountered an error while fetching leave details: " . $e->getMessage(), $currentUser->id);

            logger()->error('Failed to fetch leave details: ' . $e->getMessage());

            // return redirect()
            //     ->back()
            //     ->with('Error', 'Failed to fetch leave details. Please try again later.');
            return response()->json([
                'error' => 'Failed to fetch leave details',
                'message' => config('app.debug') ? $e->getMessage() : 'Please try again later'
            ], 500);
        }
    }

    //For leave application page update request leave status
    // ✅ FIX: Leave Approval Submission Endpoint

    public function approval(Request $request)
    {
        try {
            $request->validate([
                'empLeaveNo' => 'required',
                'status' => 'required|string',
                'remarks' => 'nullable|string',
                'empPayStatus' => 'nullable|string',
            ]);

            $leaveStatus = LeaveStatus::where('empLeaveNo', $request->empLeaveNo)->firstOrFail();
            $user = Auth::user();
            $userAssignments = $user->employee?->assignments;

            $officeStatuses = collect(json_decode($leaveStatus->empLSOffice, true) ?? [])
                ->mapWithKeys(fn($status, $office) => [strtoupper($office) => strtolower($status)])
                ->toArray();

            $officeRemarks = collect(json_decode($leaveStatus->empLSRemarks, true) ?? [])
                ->mapWithKeys(fn($remark, $office) => [strtoupper($office) => $remark])
                ->toArray();

            $positionMap = [
                'VICE PRESIDENT OF ACADEMIC AFFAIRS' => 'VPAA',
                'VP FINANCE' => 'VP FINANCE',
                'PRESIDENT' => 'PRESIDENT',
            ];

            $leaveEmployee = $leaveStatus->leave->employee;
            $leaveAssignments = $leaveEmployee?->assignments;

            $updated = false;

            foreach ($userAssignments as $assignment) {
                $positionName = strtoupper($assignment->position?->positionName ?? '');

                // VP or President
                if (isset($positionMap[$positionName])) {
                    $mappedOffice = $positionMap[$positionName];
                    if (isset($officeStatuses[$mappedOffice]) && strtolower($officeStatuses[$mappedOffice]) === 'pending') {
                        // Always use uppercase for VP and President statuses
                        $officeStatuses[$mappedOffice] = strtoupper($request->status);
                        $officeRemarks[$mappedOffice] = $request->remarks ?? 'N/A';
                        $updated = true;
                        break;
                    }
                }

                // HEAD logic - always use lowercase for head office status
                if ($assignment->empHead == 1) {
                    foreach ($leaveAssignments as $leaveAssignment) {
                        if (
                            $assignment->departmentCode === $leaveAssignment->departmentCode ||
                            $assignment->programCode === $leaveAssignment->programCode ||
                            $assignment->officeCode === $leaveAssignment->officeCode
                        ) {
                            if (isset($officeStatuses['HEAD OFFICE']) && strtolower($officeStatuses['HEAD OFFICE']) === 'pending') {
                                $officeStatuses['HEAD OFFICE'] = strtolower($request->status);
                                $officeRemarks['HEAD OFFICE'] = $request->remarks ?? 'N/A';
                                $updated = true;
                                break 2;
                            }
                        }
                    }
                }
            }

            if (!$updated) {
                return response()->json([
                    'error' => 'Unauthorized or already acted upon.',
                    'message' => 'You have already acted on this request or do not have the permission.'
                ], 403);
            }

            // Convert all statuses to lowercase for final evaluation
            $statuses = array_map('strtolower', $officeStatuses);
            $finalStatus = in_array('declined', $statuses)
                ? 'declined'
                : (count(array_filter($statuses, fn($s) => $s === 'approved')) === count($officeStatuses) ? 'approved' : 'pending');

            $leaveStatus->update([
                'empLSOffice' => json_encode($officeStatuses),
                'empLSRemarks' => json_encode($officeRemarks),
                'empLSStatus' => $finalStatus,
                'empPayStatus' => $request->empPayStatus ?? 'Without Pay',
                'updated_at' => now(),
            ]);
            $currentUser = Auth::user();
            $employee = $currentUser->employee;
            $fullName = $employee
                ? trim("{$employee->empPrefix} {$employee->empFname} {$employee->empMname} {$employee->empLname} {$employee->empSuffix}")
                : 'Unknown Employee';
            $this->logActivity('Leave Management', "User $fullName updated leave status to $finalStatus for leave number {$request->empLeaveNo}", $currentUser->id);
          return redirect()->back()->with('success', 'Leave status updated successfully!');
        } catch (Exception $e) {
            $currentUser = Auth::user();
            $employee = $currentUser->employee;
            $fullName = $employee
                ? trim("{$employee->empPrefix} {$employee->empFname} {$employee->empMname} {$employee->empLname} {$employee->empSuffix}")
                : 'Unknown Employee';
            $this->logActivity('Leave Management', "User $fullName encountered an error while updating leave status: " . $e->getMessage(), $currentUser->id);
            logger()->error('Leave status update failed: ' . $e->getMessage());

            return response()->json([
                'error' => 'Failed to update leave status',
                'message' => config('app.debug') ? $e->getMessage() : 'Please try again later'
            ], 500);
        }
    }
    public function store(Request $request)
    {
        try {
            $request->validate([
                'leave_type' => 'required|string',
                'leave_from' => 'required|date',
                'leave_to' => 'required|date|after_or_equal:leave_from',
                'reason' => 'required|string',
                'attachment.*' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:2048',
                'status' => 'required',
            ]);

            $empID = Auth::user()->empID;

            // Generate empLeaveNo (e.g., EMP001-001)
            $leaveCount = Leave::where('empID', $empID)->count() + 1;
            $empLeaveNo = $empID . '-' . str_pad($leaveCount, 0, '0', STR_PAD_LEFT);

            // Generate empLSNo (e.g., EMP001-001-001)
            $empLSNoCount = LeaveStatus::where('empLeaveNo', $empLeaveNo)->count() + 1;
            $empLSNo = $empLeaveNo . '-' . str_pad($empLSNoCount, 0, '0', STR_PAD_LEFT);

            // Handle multiple file uploads
            $filePaths = [];
            if ($request->hasFile('attachment')) {
                foreach ($request->file('attachment') as $file) {
                    $filePaths[] = $file->store('attachments', 'public');
                }
            }

            Leave::create([
                'empLeaveNo' => $empLeaveNo,
                'empID' => $empID,
                'empLeaveDateApplied' => now(),
                'leaveType' => $request->leave_type,
                'empLeaveStartDate' => $request->leave_from,
                'empLeaveEndDate' => $request->leave_to,
                'empLeaveDescription' => $request->reason,
                'empLeaveAttachment' => json_encode($filePaths),
            ]);

            $offices = [
                'Head Office' => 'pending',
                'VPAA' => 'pending',
                'VP Finance' => 'pending',
                'President' => 'pending',
            ];

            $remarks = [
                'Head Office' => 'N/A',
                'VPAA' => 'N/A',
                'VP Finance' => 'N/A',
                'President' => 'N/A',
            ];

            LeaveStatus::create([
                'empLSNo' => $empLSNo,
                'empLeaveNo' => $empLeaveNo,
                'dateUpdated' => now(),
                'empID' => $empID,
                'empPayStatus' => 'Without Pay',
                'empLSOffice' => json_encode($offices),
                'empLSStatus' => 'pending',
                'empLSRemarks' => json_encode($remarks),
            ]);

            $currentUser = Auth::user();
            $employee = $currentUser->employee;
            $fullName = $employee
                ? trim("{$employee->empPrefix} {$employee->empFname} {$employee->empMname} {$employee->empLname} {$employee->empSuffix}")
                : 'Unknown Employee';
            $this->logActivity('Create', "User $fullName submitted a leave application with ID $empLeaveNo", $currentUser->id);

            return redirect()
                ->route('leave_application')
                ->with('success', 'Leave application submitted successfully!');
        } catch (Exception $e) {
            $currentUser = Auth::user();
            $employee = $currentUser->employee;
            $fullName = $employee
                ? trim("{$employee->empPrefix} {$employee->empFname} {$employee->empMname} {$employee->empLname} {$employee->empSuffix}")
                : 'Unknown Employee';
            $this->logActivity('Create', "User $fullName encountered an error while submitting leave application: " . $e->getMessage(), $currentUser->id);
            logger()->error('Leave application failed: ' . $e->getMessage());
            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'Failed. Please try again. ' . (config('app.debug') ? $e->getMessage() : ''));
        }
    }

    public function editForm($id)
    {
        try {
            $leave = LeaveStatus::with('leave')->where('empLeaveNo', $id)->firstOrFail();

            return view('pages.employee.leave', [
                'editLeave' => $leave->leave,
                'tabs' => null,
            ]);
        } catch (Exception $e) {
            $currentUser = Auth::user();
            $employee = $currentUser->employee;
            $fullName = $employee
                ? trim("{$employee->empPrefix} {$employee->empFname} {$employee->empMname} {$employee->empLname} {$employee->empSuffix}")
                : 'Unknown Employee';
            $this->logActivity('View', "User $fullName encountered an error while fetching leave details: " . $e->getMessage(), $currentUser->id);
            logger()->error('Failed to fetch leave details: ' . $e->getMessage());
            return redirect()
                ->back()
                ->with('Error', 'Failed to fetch leave details. Please try again later.');
        }
    }



    public function update(Request $request, $id)
    {
        try {
            $leave = LeaveStatus::with('leave')
                ->where('empLeaveNo', $id)
                ->firstOrFail();
            if (!$leave) {
                return response()->json(['error' => 'Leave not found'], 404);
            }

            if ($id != $leave->empLeaveNo) {
                return response()->json(['error' => 'Leave ID mismatch'], 400);
            }


            $request->validate([
                'empID' => 'required',
                'leave_type' => 'required|string',
                'leave_from' => 'required|date',
                'leave_to' => 'required|date|after_or_equal:leave_from',
                'reason' => 'required|string',
                'attachment.*' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:2048',
                'status' => 'required',
            ]);

            $filePaths = [];

            if ($request->has('existing_attachments')) {
                $filePaths = array_map(function ($url) {
                    // Remove full URL, leave just relative path (after `/storage/`)
                    return str_replace(asset('storage/') . '/', '', $url);
                }, $request->input('existing_attachments'));
            }

            // Handle replacements
            if ($request->hasFile('replace_attachment')) {
                foreach ($request->file('replace_attachment') as $index => $file) {
                    if ($file) {
                        // Replace existing with new one
                        $filePaths[$index] = $file->store('attachments', 'public');
                    }
                }
            }

            // Handle newly added files
            if ($request->hasFile('attachment')) {
                foreach ($request->file('attachment') as $file) {
                    $filePaths[] = $file->store('attachments', 'public');
                }
            }


            $leave->leave->update([
                'empID' => $request->empID,
                'empLeaveDateApplied' => now(),
                'leaveType' => $request->leave_type,
                'empLeaveStartDate' => $request->leave_from,
                'empLeaveEndDate' => $request->leave_to,
                'empLeaveDescription' => $request->reason,
                'empLeaveAttachment' => json_encode($filePaths),
            ]);
            $leave->update([
                'empLSStatus' => $request->status,
                'empPayStatus' => 'Without Pay',
                'empLSRemarks' => '',
                'updated_at' => now(),
            ]);
            $currentUser = Auth::user();
            $employee = $currentUser->employee;
            $fullName = $employee
                ? trim("{$employee->empPrefix} {$employee->empFname} {$employee->empMname} {$employee->empLname} {$employee->empSuffix}")
                : 'Unknown Employee';
            $this->logActivity('Update', "User $fullName updated leave application with ID $id", $currentUser->id);
            return redirect()
                ->route('leave_application')
                ->with('success', 'Leave application updated successfully!');
        } catch (Exception $e) {
            $currentUser = Auth::user();
            $employee = $currentUser->employee;
            $fullName = $employee
                ? trim("{$employee->empPrefix} {$employee->empFname} {$employee->empMname} {$employee->empLname} {$employee->empSuffix}")
                : 'Unknown Employee';
            $this->logActivity('Update', "User $fullName encountered an error while updating leave application: " . $e->getMessage(), $currentUser->id);
            logger()->error('Leave status update failed: ' . $e->getMessage());
            return response()->json([
                'error' => 'Failed to update leave status',
                'message' => config('app.debug') ? $e->getMessage() : 'Please try again later'
            ], 500);
        }
    }
}
