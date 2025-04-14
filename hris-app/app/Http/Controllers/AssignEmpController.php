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
                'positions.*.empAssID' => 'nullable|exists:emp_assignments,id',
                'positions.*.positionID' => 'required|exists:positions,positionID',
                'positions.*.empAssAppointedDate' => 'required|date',
                'positions.*.empAssEndDate' => 'nullable|date|after_or_equal:positions.*.empAssAppointedDate',
                'departmentID' => 'nullable|exists:departments,departmentCode',
                'programCode' => 'nullable|exists:programs,programCode',
                'officeID' => 'nullable|exists:offices,officeCode',
                'makeHead' => 'nullable|boolean',
            ]);

            $empID = $request->input('empID');
            $positions = $request->input('positions');
            $departmentCode = $request->input('departmentID');
            $programCode = $request->input('programCode');
            $officeCode = $request->input('officeID');
            $empHead = $request->input('makeHead') ? true : false;

            if ($departmentCode || $officeCode) {
                $alreadyAssigned = EmpAssignment::where('empID', $empID)
                    ->where(function ($query) use ($departmentCode, $officeCode) {
                        if ($departmentCode) {
                            $query->where('departmentCode', '!=', null);
                        }

                        if ($officeCode) {
                            $query->orWhere('officeCode', '!=', null);
                        }
                    })
                    ->exists();

                if ($alreadyAssigned) {
                    return redirect()->back()->with('error', 'Employee is already assigned to a department or office.');
                }
            }
            // ✅ Optional: Check for duplicate positionIDs in the request
            $positionIDs = array_map(fn($p) => $p['positionID'], $positions);
            if (count($positionIDs) !== count(array_unique($positionIDs))) {
                return redirect()->back()->with('error', 'Duplicate positions detected in the submission.');
            }

            // ✅ Loop through all submitted positions
            foreach ($positions as $position) {
                $positionID = $position['positionID'];
                $appointedDate = $position['empAssAppointedDate'];
                $endDate = $position['empAssEndDate'];
                $empAssID = $position['empAssID'] ?? null;

                if ($empAssID) {
                    $existing = EmpAssignment::find($empAssID);
                    if ($existing) {
                        $existing->update([
                            'positionID' => $positionID,
                            'empAssAppointedDate' => $appointedDate,
                            'empAssEndDate' => $endDate,
                            'officeCode' => $officeCode,
                            'departmentCode' => $departmentCode,
                            'programCode' => $programCode,
                            'empHead' => $empHead,
                        ]);
                        continue;
                    }
                }

                // Generate assignment number
                $empAssNo = $positionID . '-' . $empID . '-' . date('Y', strtotime($appointedDate));

                EmpAssignment::create([
                    'empAssNo' => $empAssNo,
                    'empID' => $empID,
                    'positionID' => $positionID,
                    'empAssAppointedDate' => $appointedDate,
                    'empAssEndDate' => $endDate,
                    'officeCode' => $officeCode,
                    'departmentCode' => $departmentCode,
                    'programCode' => $programCode,
                    'empHead' => $empHead,
                ]);
            }

            return redirect()->back()->with('success', 'Position assignment(s) saved successfully.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Error occurred while assigning position: ' . $e->getMessage());
        }
    }



    public function getPositions($empID)
    {
        try {
            // Fetch the employee with assignments filtered by empID
            $assignedPositions = EmpAssignment::with('position')
                ->where('empID', $empID)
                ->get();

            // Fetch all positions, departments, and offices
            $positions = Position::all();
            $departments = Departments::all();
            $offices = Offices::all();

            // Return the view with the fetched data
            return view('pages.hr.employee_management', compact('assignedPositions', 'positions', 'departments', 'offices'));
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Error occurred while fetching positions: ' . $e->getMessage());
        }
    }



    public function deletePosition($id)
    {
        $assignment = EmpAssignment::findOrFail($id);
        $assignment->update([
            'positionID' => null,
            'empAssAppointedDate' => null,
            'empAssEndDate' => null,
        ]);

        return response()->json(['success' => true]);
    }


    public function deleteAssignment($id)
    {
        $assignment = EmpAssignment::findOrFail($id);
        $assignment->delete();

        return response()->json(['success' => true]);
    }
}
