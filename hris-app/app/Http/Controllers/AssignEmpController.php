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
            $empID = $request->input('empID');
            $positionID = $request->input('positionID');
            $appointedDate = $request->input('empAssAppointedDate');

            $empAssNo = $positionID . '-' . $empID . '-' . date('Y', strtotime($appointedDate));

            $existingAssignment = EmpAssignment::where('empAssNo', $empAssNo)->first();
            if ($existingAssignment) {
                return redirect()->back()->with('error', 'Position already assigned to this employee.');
            }

            EmpAssignment::create([
                'empAssNo' => $empAssNo,
                'empID' => $empID,
                'positionID' => $positionID,
                'empAssAppointedDate' => $appointedDate,
                'empAssEndDate' => $request->input('empAssEndDate'),
            ]);

            return redirect()->back()->with('success', 'Position successfully assigned.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Error occurred while assigning position: ' . $e->getMessage());
        }
    }



    public function getPositions($id)
    {
        // Query employee using empID instead of primary key
        $employee = Employee::with('assignments.position')
            ->where('id', $id)
            ->firstOrFail();

        return $employee->assignments->map(function ($assignment) {
            return [
                'empAssID' => $assignment->id,
                'positionName' => $assignment->position->positionName ?? 'N/A',
                'empAssAppointedDate' => $assignment->empAssAppointedDate,
                'empAssEndDate' => $assignment->empAssEndDate ?? 'Present',
            ];
        });
    }

    public function deleteAssignment($id)
    {
        $assignment = EmpAssignment::findOrFail($id);
        $assignment->delete();

        return response()->json(['success' => true]);
    }
}
