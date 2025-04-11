<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\EmpAssignment;
use App\Models\Employee;
use App\Models\Position;
use App\Models\Departments;
use App\Models\Offices;



class AssignEmpController extends Controller
{
    public function empAssignment(Request $request)
    {
        try {
            $request->validate([
                'empID' => 'required|exists:employees,empID',
                'positions' => 'required|array',
                'positions.*.positionID' => 'required|exists:positions,positionID',
                'positions.*.empAssAppointedDate' => 'required|date',
                'positions.*.empAssEndDate' => 'nullable|date|after_or_equal:positions.*.empAssAppointedDate',
                'departmentID' => 'nullable|exists:departments,departmentCode',
                'officeID' => 'nullable|exists:offices,officeCode',
                'makeHead' => 'nullable|boolean',
            ]);

            $empID = $request->input('empID');
            $positions = $request->input('positions'); // Array of positions
            $appointedDate = $request->input('empAssAppointedDate');
            $departmentCode = $request->input('departmentID'); // Department Code
            $officeCode = $request->input('officeID'); // Office Code
            $empHead = $request->input('makeHead'); // Checkbox for Head of Office

            foreach ($positions as $position) {
                $positionID = $position['positionID'];
                $appointedDate = $position['empAssAppointedDate'];
                $endDate = $position['empAssEndDate'];

                $empAssNo = $positionID . '-' . $empID . '-' . date('Y', strtotime($appointedDate));

                // Check if the assignment already exists
                $existingAssignment = EmpAssignment::where('empAssNo', $empAssNo)->first();
                if ($existingAssignment) {
                    return redirect()->back()->with('error', 'Position already assigned to this employee: ' . $positionID);
                }

                // Create the new assignment
                EmpAssignment::create([
                    'empAssNo' => $empAssNo,
                    'empID' => $empID,
                    'positionID' => $positionID,
                    'empAssAppointedDate' => $appointedDate,
                    'empAssEndDate' => $endDate,
                    'officeCode' => $officeCode,
                    'departmentCode' => $departmentCode,
                    'empHead' => $empHead ? true : false, 
                ]);
            }

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
