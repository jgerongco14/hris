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
                'positions.*.empAssID' => 'nullable|exists:empAssignments,id',
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

            $createdAssignments = 0;
            $updatedAssignments = 0;

            // Process position assignments
            foreach ($positions as $position) {
                $positionID = $position['positionID'] ?? null;
                $appointedDate = $position['empAssAppointedDate'] ?? null;
                $endDate = $position['empAssEndDate'] ?? null;
                $empAssID = $position['empAssID'] ?? null;
                
                // Skip if essential fields are missing
                if (!$positionID || !$appointedDate) {
                    continue;
                }

                if ($empAssID) {
                    // Update existing assignment
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
                        $updatedAssignments++;
                        continue;
                    }
                }

                // Create new assignment
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
                $createdAssignments++;
            }

            // If no positions were processed but department/office info was provided,
            // create a department/office assignment without a position
            if ($createdAssignments == 0 && $updatedAssignments == 0 && ($departmentCode || $officeCode)) {
                // Check if there's an existing department/office assignment without position
                $existingDeptOfficeAssignment = EmpAssignment::where('empID', $empID)
                    ->whereNull('positionID')
                    ->where(function ($query) {
                        $query->whereNotNull('departmentCode')
                            ->orWhereNotNull('officeCode');
                    })
                    ->first();

                if ($existingDeptOfficeAssignment) {
                    // Update existing department/office assignment
                    $existingDeptOfficeAssignment->update([
                        'departmentCode' => $departmentCode,
                        'programCode' => $programCode,
                        'officeCode' => $officeCode,
                        'empHead' => $empHead,
                    ]);
                    $updatedAssignments++;
                } else {
                    // Create new department/office assignment without position
                    $empAssNo = 'DEPT-' . $empID . '-' . date('Y');
                    EmpAssignment::create([
                        'empAssNo' => $empAssNo,
                        'empID' => $empID,
                        'positionID' => null,
                        'empAssAppointedDate' => now()->format('Y-m-d'),
                        'empAssEndDate' => null,
                        'officeCode' => $officeCode,
                        'departmentCode' => $departmentCode,
                        'programCode' => $programCode,
                        'empHead' => $empHead,
                    ]);
                    $createdAssignments++;
                }
            }

            // Log activity
            $currentUser = Auth::user();
            $employee = $currentUser->employee;
            $fullName = $employee
                ? trim("{$employee->empPrefix} {$employee->empFname} {$employee->empMname} {$employee->empLname} {$employee->empSuffix}")
                : 'Unknown Employee';

            $this->logActivity('Assign', "User $fullName processed assignments for employee $empID (Created: $createdAssignments, Updated: $updatedAssignments).", $currentUser->id);

            $message = "Assignment completed successfully. ";
            if ($createdAssignments > 0) $message .= "Created: $createdAssignments. ";
            if ($updatedAssignments > 0) $message .= "Updated: $updatedAssignments.";

            return redirect()->back()->with('success', $message);

        } catch (\Exception $e) {
            $currentUser = Auth::user();
            $employee = $currentUser->employee ?? null;
            $fullName = $employee
                ? trim("{$employee->empPrefix} {$employee->empFname} {$employee->empMname} {$employee->empLname} {$employee->empSuffix}")
                : 'Unknown Employee';
            
            $this->logActivity('Error', "User $fullName encountered an error while assigning position: " . $e->getMessage(), $currentUser->id);
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

            $positionName = 'N/A';
            if ($assignment->positionID) {
                $position = Position::find($assignment->positionID);
                $positionName = $position ? $position->positionName : 'N/A';
            }
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
