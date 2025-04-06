<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Employee;
use Exception;
use App\Models\LeaveStatus;
use App\Models\Leave;
use Illuminate\Support\Facades\Auth;


class EmpLeaveController extends Controller
{

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
    public function approval(Request $request)
    {
        try {
            $request->validate([
                'empLeaveNo' => 'required',
                'status' => 'required|string',
                'remarks' => 'nullable|string',
                'payStatus' => 'nullable|string',
            ]);

            $leaveStatus = LeaveStatus::where('empLeaveNo', $request->empLeaveNo)->firstOrFail();

            // Decode current office status and remarks
            $officeStatuses = json_decode($leaveStatus->empLSOffice, true);
            $officeRemarks = json_decode($leaveStatus->empLSRemarks, true);

            // Get the current user's position(s)
            $positions = Auth::user()->employee?->assignments()
                ->with('position')
                ->get()
                ->pluck('position.positionName')
                ->map(fn($name) => strtoupper($name))
                ->toArray();

            // Map: 'HUMAN RESOURCE' => 'HR'
            $positionMap = [
                'HUMAN RESOURCE' => 'HR',
                'OFFICE HEAD' => 'OFFICE HEAD',
                'PRESIDENT' => 'PRESIDENT',
                'VICE PRESIDENT' => 'VICE PRESIDENT',
                'FINANCE' => 'FINANCE',
            ];

            // Update statuses only for matched position(s)
            foreach ($positions as $pos) {
                $mapped = $positionMap[$pos] ?? $pos;

                if (isset($officeStatuses[$mapped])) {
                    $officeStatuses[$mapped] = strtolower($request->status);
                    $officeRemarks[$mapped] = $request->remarks ?? 'N/A';
                }
            }

            // Determine overall empLSStatus
            $finalStatus = 'pending';

            if (in_array('declined', array_map('strtolower', $officeStatuses))) {
                $finalStatus = 'declined';
            } elseif (count(array_filter($officeStatuses, fn($s) => strtolower($s) === 'approved')) === count($officeStatuses)) {
                $finalStatus = 'approved';
            }

            // Save updated status
            $leaveStatus->update([
                'empLSOffice' => json_encode($officeStatuses),
                'empLSRemarks' => json_encode($officeRemarks),
                'empLSStatus' => $finalStatus,
                'empLSPayStatus' => $request->payStatus ?? 'With Pay',
                'updated_at' => now(),
            ]);

            return response()->json([
                'message' => 'Leave status updated successfully!',
            ]);
        } catch (Exception $e) {
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
                'Office Head' => 'pending',
                'HR' => 'pending',
                'President' => 'pending',
                'Vice President' => 'pending',
                'Finance' => 'pending',
            ];

            $remarks = [
                'Office Head' => 'N/A',
                'HR' => 'N/A',
                'President' => 'N/A',
                'Vice President' => 'N/A',
                'Finance' => 'N/A',
            ];

            LeaveStatus::create([
                'empLSNo' => $empLSNo,
                'empLeaveNo' => $empLeaveNo,
                'dateUpdated' => now(),
                'empID' => $empID,
                'empLSOffice' => json_encode($offices),
                'empLSStatus' => 'pending',
                'empLSRemarks' => json_encode($remarks),
            ]);

            return redirect()
                ->route('leave_application')
                ->with('success', 'Leave application submitted successfully!');
        } catch (Exception $e) {
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
            $empID = Auth::user()->empID;
            $leave = LeaveStatus::with('leave')
                ->where('empLeaveNo', $id)
                ->firstOrFail();

            if (!$leave) {
                return response()->json(['error' => 'Leave not found'], 404);
            }

            return response()->json([
                'empLeaveNo' => $leave->empLeaveNo,
                'empID' => $empID,
                'name' => optional($leave->leave->employee)->empFname . ' ' . optional($leave->leave->employee)->empLname,
                // 'department' => optional($leave->leave->employee->department)->name ?? 'N/A',
                // 'position' => optional($leave->leave->employee)->position ?? 'N/A',
                'type' => $leave->leave->leaveType,
                'dates' => [
                    'start' => $leave->leave->empLeaveStartDate,
                    'end' => $leave->leave->empLeaveEndDate,
                ],
                'reason' => $leave->leave->empLeaveDescription,
                'attachment' => collect(json_decode($leave->leave->empLeaveAttachment ?? '[]', true))
                    ->map(function ($path) {
                        return [
                            'url' => asset('storage/' . $path),  // Full URL for viewing
                            'type' => pathinfo($path, PATHINFO_EXTENSION)
                        ];
                    })->toArray(),

                'status' => $leave->empLSStatus,
            ]);
        } catch (Exception $e) {
            logger()->error('Failed to fetch leave details: ' . $e->getMessage());
            return response()->json([
                'error' => 'Failed to fetch leave details',
                'message' => config('app.debug') ? $e->getMessage() : 'Please try again later'
            ], 500);
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
                'empLSPayStatus' => '',
                'empLSRemarks' => '',
                'updated_at' => now(),
            ]);
            return redirect()
                ->route('leave_application')
                ->with('success', 'Leave application updated successfully!');
        } catch (Exception $e) {
            logger()->error('Leave status update failed: ' . $e->getMessage());
            return response()->json([
                'error' => 'Failed to update leave status',
                'message' => config('app.debug') ? $e->getMessage() : 'Please try again later'
            ], 500);
        }
    }
}
