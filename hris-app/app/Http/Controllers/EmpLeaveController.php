<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Employee;
use Exception;
use App\Models\LeaveStatus;
use App\Models\Leave;


class EmpLeaveController extends Controller
{

    public function index()
    {
        $leaveStatuses = LeaveStatus::with('leave.employee')->get();
        return view('pages.hr.leave_management', compact('leaveStatuses'));
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
                'attachment' => $leave->leave->empLeaveAttachment ? [
                    ['url' => asset('storage/' . $leave->leave->empLeaveAttachment)]
                ] : [],
                'status' => $leave->empLSStatus,
            ]);
        } catch (Exception $e) {
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
        } catch (\Exception $e) {
            return response()->json([
                'message' => config('app.debug') ? $e->getMessage() : 'Something went wrong while updating leave.'
            ], 500);
        }
    }





    //For indiviual leave of employee
    public function store(Request $request)
    {
        try {
            $request->validate([
                'emp_id' => 'required',
                'leave_type' => 'required|string',
                'leave_from' => 'required|date',
                'leave_to' => 'required|date|after_or_equal:leave_from',
                'reason' => 'required|string',
                'attachment' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:2048',
                'status' => 'required',
            ]);

            // Handle file upload if present
            $filePath = null;
            if ($request->hasFile('attachment')) {
                $filePath = $request->file('attachment')->store('attachments', 'public');
            }

            $leave = Leave::create([
                'empID' => $request->emp_id,
                'empLeaveDateApplied' => now(),
                'leaveType' => $request->leave_type,
                'empLeaveStartDate' => $request->leave_from,
                'empLeaveEndDate' => $request->leave_to,
                'empLeaveDescription' => $request->reason,
                'empLeaveAttachment' => $filePath,
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
            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'Failed. Please try again. ' . (config('app.debug') ? $e->getMessage() : ''));
        }
    }
}
