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
                'leave.employee',
                // 'leave.employee.department'
            ])
                ->where('empLeaveNo', $id)
                ->firstOrFail();


            if (!$leave) {
                return response()->json(['error' => 'Leave not found'], 404);
            }

            return response()->json([
                'empLeaveNo' => $leave->empLeaveNo,
                'empID' => $leave->empID,
                'name' => optional($leave->leave->employee)->empFname . ' ' . optional($leave->leave->employee)->empLname,
                // 'department' => optional($leave->leave->employee->department)->name ?? 'N/A',
                // 'position' => optional($leave->leave->employee)->position ?? 'N/A',
                'type' => $leave->leave->leaveType,
                'dates' => [
                    'start' => $leave->leave->empLeaveStartDate,
                    'end' => $leave->leave->empLeaveEndDate,
                ],
                'reason' => $leave->leave->empLeaveDescription,
                'attachment' => collect($leave->leave->empLeaveAttachment)
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

            // return redirect()
            //     ->back()
            //     ->with('Error', 'Failed to fetch leave details. Please try again later.');
            return response()->json([
                'error' => 'Failed to fetch leave details',
                'message' => config('app.debug') ? $e->getMessage() : 'Please try again later'
            ], 500);
        }
    }

    public function showEmployee($id)
    {
        try {
            $leave = LeaveStatus::with([
                'leave.employee',
                // 'leave.employee.department'
            ])
                ->where('empLeaveNo', $id)
                ->firstOrFail();


            if (!$leave) {
                return response()->json(['error' => 'Leave not found'], 404);
            }

            return response()->json([
                'empLeaveNo' => $leave->empLeaveNo,
                'empID' => $leave->empID,
                'dateApplied' => $leave->leave->empLeaveDateApplied,
                'type' => $leave->leave->leaveType,
                'dates' => [
                    'start' => $leave->leave->empLeaveStartDate,
                    'end' => $leave->leave->empLeaveEndDate,
                ],
                'reason' => $leave->leave->empLeaveDescription,
                'status' => $leave->empLSStatus,
                'remarks' => $leave->empLSRemarks,
            ]);
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

            $leaveStatus->update([
                'empLSStatus' => $request->status,
                'empLSPayStatus' => $request->payStatus ?? 'With Pay',
                'empLSRemarks' => $request->remarks,
                'updated_at' => now(),
            ]);

            return response()->json([
                'message' => 'Leave status updated successfully!'
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
                'emp_id' => 'required',
                'leave_type' => 'required|string',
                'leave_from' => 'required|date',
                'leave_to' => 'required|date|after_or_equal:leave_from',
                'reason' => 'required|string',
                'attachment.*' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:2048',
                'status' => 'required',
            ]);

            // Handle multiple file uploads
            $filePaths = [];
            if ($request->hasFile('attachment')) {
                foreach ($request->file('attachment') as $file) {
                    $filePaths[] = $file->store('attachments', 'public');
                }
            }

            $leave = Leave::create([
                'empID' => $request->emp_id,
                'empLeaveDateApplied' => now(),
                'leaveType' => $request->leave_type,
                'empLeaveStartDate' => $request->leave_from,
                'empLeaveEndDate' => $request->leave_to,
                'empLeaveDescription' => $request->reason,
                'empLeaveAttachment' => json_encode($filePaths),
            ]);

            LeaveStatus::create([
                'empLeaveNo' => $leave->empLeaveNo,
                'status' => $request->status,
                'dateUpdated' => now(),
                'empID' => $request->emp_id,
                'empLSOffice' => '',
                'empLSStatus' => 'pending',
                'empLSRemarks' => '',
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
            $leave = LeaveStatus::with('leave')
                ->where('empLeaveNo', $id)
                ->firstOrFail();

            if (!$leave) {
                return response()->json(['error' => 'Leave not found'], 404);
            }

            return response()->json([
                'empLeaveNo' => $leave->empLeaveNo,
                'empID' => $leave->empID,
                'name' => optional($leave->leave->employee)->empFname . ' ' . optional($leave->leave->employee)->empLname,
                // 'department' => optional($leave->leave->employee->department)->name ?? 'N/A',
                // 'position' => optional($leave->leave->employee)->position ?? 'N/A',
                'type' => $leave->leave->leaveType,
                'dates' => [
                    'start' => $leave->leave->empLeaveStartDate,
                    'end' => $leave->leave->empLeaveEndDate,
                ],
                'reason' => $leave->leave->empLeaveDescription,
                'attachment' => collect($leave->leave->empLeaveAttachment)
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

            // Handle multiple file uploads
            $filePaths = [];

            if ($request->hasFile('attachment')) {
                foreach ($request->file('attachment') as $file) {
                    $filePaths[] = $file->store('attachments', 'public');
                }
            } else {
                // No new attachments? Keep the old ones
                $filePaths = json_decode($leave->leave->empLeaveAttachment ?? '[]', true);
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
