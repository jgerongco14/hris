<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\EmpAssignment;
use App\Models\Employee;
use App\Models\Position;
use App\Models\Departments;
use App\Models\Offices;
use Illuminate\Support\Facades\Auth;
use App\Traits\LogsActivity;
use App\Models\Programs;
use Illuminate\Support\Facades\Log;



class AssignEmpController extends Controller
{
    use LogsActivity;

    public function empAssignment(Request $request)
    {
        try {
            $request->validate([
                'empID' => 'required|exists:employees,empID',
                'positions' => 'nullable|array',
                'positions.*.empAssID' => 'nullable|exists:emp_assignments,id',
                'positions.*.positionID' => 'nullable|exists:positions,positionID',
                'positions.*.empAssAppointedDate' => 'nullable|date',
                'positions.*.empAssEndDate' => 'nullable|date|after_or_equal:positions.*.empAssAppointedDate',
                'departmentID' => 'nullable|exists:departments,departmentCode',
                'programCode' => 'nullable|exists:programs,programCode',
                'officeID' => 'nullable|exists:offices,officeCode',
                'makeHead' => 'nullable|boolean',
            ]);

            $empID = $request->input('empID');
            $positions = $request->input('positions', []);
            $departmentCode = $request->input('departmentID');
            $programCode = $request->input('programCode');
            $officeCode = $request->input('officeID');
            $empHead = $request->input('makeHead') ? true : false;

            $existingAssignment = EmpAssignment::where('empID', $empID)
                ->where(function ($query) {
                    $query->whereNotNull('departmentCode')
                        ->orWhereNotNull('officeCode');
                })
                ->latest('created_at')
                ->first();

            // If an existing department/office assignment is found, update it
            if ($existingAssignment) {
                $existingAssignment->update([
                    'departmentCode' => $departmentCode,
                    'programCode'    => $programCode,
                    'officeCode'     => $officeCode,
                    'empHead'        => $empHead,
                ]);
                // Update all related positions if needed
                foreach ($positions as $position) {
                    $empAssID = $position['empAssID'] ?? null;
                    if ($empAssID) {
                        EmpAssignment::where('id', $empAssID)->update([
                            'officeCode'     => $officeCode,
                            'departmentCode' => $departmentCode,
                            'programCode'    => $programCode,
                            'empHead'        => $empHead,
                        ]);
                    }
                }
            }

            // ✅ Optional: Check for duplicate positionIDs in the request
            $positionIDs = collect($positions)
                ->pluck('positionID')
                ->filter()
                ->toArray();

            if (count($positionIDs) !== count(array_unique($positionIDs))) {
                return redirect()->back()->with('error', 'Duplicate positions detected in the submission.');
            }


            // ✅ Loop through all submitted positions
            foreach ($positions as $position) {
                $positionID = $position['positionID'] ?? null;
                $appointedDate = $position['empAssAppointedDate'] ?? null;
                $endDate = $position['empAssEndDate'] ?? null;
                if (!$positionID || !$appointedDate || !$endDate) {
                    continue; // Skip if positionID is not provided
                }
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

            $currentUser = Auth::user();
            $employee = $currentUser->employee;

            $positionName = Position::find($positions[0]['positionID'] ?? null)->positionName ?? 'N/A';
            $departmentName = Departments::find($departmentCode)->departmentName ?? 'N/A';
            $programName = Programs::find($programCode)->programName ?? 'N/A';
            $officeName = Offices::find($officeCode)->officeName ?? 'N/A';


            $fullName = $employee
                ? trim("{$employee->empPrefix} {$employee->empFname} {$employee->empMname} {$employee->empLname} {$employee->empSuffix}")
                : 'Unknown Employee';

            $this->logActivity('Assign', "User $fullName assigned $empID to $positionName $departmentName $programName $officeName successfully.", $currentUser->id);


            return redirect()->back()->with('success', 'Position assignment(s) saved successfully.');
        } catch (\Exception $e) {
            $fullName = $employee
                ? trim("{$employee->empPrefix} {$employee->empFname} {$employee->empMname} {$employee->empLname} {$employee->empSuffix}")
                : 'Unknown Employee';
            $this->logActivity('Error', "User $fullName an error occurred while assigning position: " . $e->getMessage(), Auth::id());
            return redirect()->back()->with('error', 'Error occurred while assigning position: ' . $e->getMessage());
        }
    }



    public function getAssignments($empID)
    {
        try {
            $employee = Employee::with('assignments.position', 'assignments.department', 'assignments.office', 'assignments.program')
                ->where('empID', $empID)
                ->firstOrFail();

            $departments = Departments::with(['programs' => function ($query) {
                $query->select('programs.id', 'programs.programCode', 'programs.programName');
            }])->get();

            // Debug: Check if departments have programs
            foreach ($departments as $department) {
                Log::debug("Department: {$department->departmentName}", [
                    'programs_count' => $department->programs->count(),
                    'programs' => $department->programs->toArray()
                ]);
            }

            $offices = Offices::all();
            $positions = Position::all();

            return view('pages.hr.employee_management', compact('employee', 'departments', 'offices', 'positions'));
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Error occurred while fetching assignments: ' . $e->getMessage());
        }
    }



    public function deletePosition($id)
    {
        try {
            $assignment = EmpAssignment::findOrFail($id);
            $assignment->update([
                'positionID' => null,
                'empAssAppointedDate' => null,
                'empAssEndDate' => null,
            ]);

            $positionName = Position::find($assignment->positionID)->positionName ?? 'N/A';
            $currentUser = Auth::user();
            $employee = $currentUser->employee;


            $fullName = $employee
                ? trim("{$employee->empPrefix} {$employee->empFname} {$employee->empMname} {$employee->empLname} {$employee->empSuffix}")
                : 'Unknown Employee';

            $this->logActivity('Delete', "User $fullName deleted $positionName successfully.", $currentUser->id);

            return redirect()->back()->with('success', 'Assignment deleted successfully.');
        } catch (\Exception $e) {
            $fullName = $employee
                ? trim("{$employee->empPrefix} {$employee->empFname} {$employee->empMname} {$employee->empLname} {$employee->empSuffix}")
                : 'Unknown Employee';
            $this->logActivity('Error', "User $fullName an error occurred while deleting a position: " . $e->getMessage(), Auth::id());
            return redirect()->back()->with('error', 'Error occurred while deleting position assignment: ');
        }
    }


    public function deleteAssignment($id)
    {
        try {
            $currentUser = Auth::user();
            $employee = $currentUser->employee;


            $fullName = $employee
                ? trim("{$employee->empPrefix} {$employee->empFname} {$employee->empMname} {$employee->empLname} {$employee->empSuffix}")
                : 'Unknown Employee';

            $this->logActivity('Delete', "User $fullName deleted assignment successfully.", $currentUser->id);

            $assignment = EmpAssignment::findOrFail($id);
            $assignment->delete();

            return redirect()->back()->with('success', 'Assignment deleted successfully.');
        } catch (\Exception $e) {
            $fullName = $employee
                ? trim("{$employee->empPrefix} {$employee->empFname} {$employee->empMname} {$employee->empLname} {$employee->empSuffix}")
                : 'Unknown Employee';
            $this->logActivity('Error', "User $fullName an error occurred while deleting assignment: " . $e->getMessage(), Auth::id());
            return redirect()->back()->with('error', 'Error occurred while deleting assignment: ' . $e->getMessage());
        }
    }
}
