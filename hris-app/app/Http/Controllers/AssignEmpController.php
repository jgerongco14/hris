<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\EmpAssignment;
use App\Models\Employee;
use App\Models\Position;

class AssignEmpController extends Controller
{
    public function assignPosition(Request $request)
    {
        try {

            // Generate empAssNo
            $empAssNo = $request->positionID . '-' . $request->empID . '-' . date('Y', strtotime($request->empAssAppointedDate));
            $existingAssignment = EmpAssignment::where('empAssNo', $empAssNo)->first();
            if ($existingAssignment) {
                return redirect()->back()->with('error', 'Position already assigned to this employee.');
            }

            EmpAssignment::create([
                'empAssNo' => $empAssNo,
                'empID' => $request->empID,
                'positionID' => $request->positionID,
                'empAssAppointedDate' => $request->empAssAppointedDate,
                'empAssEndDate' => $request->empAssEndDate,
            ]);

            return redirect()->back()->with('success', 'Position successfully assigned.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Error occurred while assigning position: ' . $e->getMessage());
        }
    }
}
