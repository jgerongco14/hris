<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Employee;
use App\Models\Departments;
use App\Models\Offices;
use App\Models\Position;
use Exception;
use App\Traits\LogsActivity;
use Illuminate\Support\Facades\Auth;


class FinanceController extends Controller
{
    use LogsActivity;
    public function displayEmployees(Request $request)
    {
        try {
            $query = Employee::with(['assignments.position']) // Eager load assignments with positions
                ->whereHas('user', function ($q) {
                    $q->where('role', '!=', 'admin'); // Exclude employees with the 'admin' role
                });

            // 🔍 Search by name
            if ($request->has('search') && $request->search != '') {
                $search = $request->search;
                $query->whereRaw("CONCAT(empFname, ' ', empLname) LIKE ?", ["%{$search}%"]);
            }

            // 🔽 Filter by Position (✅ insert this block here)
            if ($request->has('position') && $request->position != '') {
                $positionId = $request->position;
                $query->whereHas('assignments', function ($q) use ($positionId) {
                    $q->where('positionID', $positionId);
                });
            }

            $departments = Departments::with('programs')->get();
            $offices = Offices::all();

            // Paginate results
            $employees = $query->paginate(10);
            $positions = Position::all();

            return view('pages.finance.rvm', [
                'employees' => $employees,
                'positions' => $positions,
                'departments' => $departments,
                'offices' => $offices,
            ]);
        } catch (Exception $e) {
            // Log the error
            $currentUser = Auth::user();
            $employeeActor = $currentUser->employee;
            $fullName = $employeeActor
                ? trim("{$employeeActor->empPrefix} {$employeeActor->empFname} {$employeeActor->empMname} {$employeeActor->empLname} {$employeeActor->empSuffix}")
                : 'Unknown Employee';
            $this->logActivity('Error', "User $fullName encountered an error while fetching employees: " . $e->getMessage(), $currentUser->id);
            logger()->error('Failed to fetch employees: ' . $e->getMessage());
            return redirect()
                ->back()
                ->with('error', 'Failed to fetch employees. Please try again later.');
        }
    }

    public function updateRvm(Request $request, $id)
    {
        $request->validate([
            'empRVMRetirementNo' => 'nullable|string|max:255',
            'empBPIATMAccountNo' => 'nullable|string|max:255',
        ]);

        try {
            $employee = Employee::findOrFail($id);
            $employee->empRVMRetirementNo = $request->input('empRVMRetirementNo');
            $employee->empBPIATMAccountNo = $request->input('empBPIATMAccountNo');
            $employee->save();

            // Log the activity
            $currentUser = Auth::user();
            $employeeActor = $currentUser->employee;
            $fullName = $employeeActor
                ? trim("{$employeeActor->empPrefix} {$employeeActor->empFname} {$employeeActor->empMname} {$employeeActor->empLname} {$employeeActor->empSuffix}")
                : 'Unknown Employee';
            $this->logActivity('Update', "User $fullName updated RVM info for employee ID: $employee->empID", $currentUser->id);


            return redirect()->back()->with('success', 'RVM info updated successfully.');
        } catch (Exception $e) {
            // Log the error
            $currentUser = Auth::user();
            $employeeActor = $currentUser->employee;
            $fullName = $employeeActor
                ? trim("{$employee->empPrefix} {$employee->empFname} {$employee->empMname} {$employee->empLname} {$employee->empSuffix}")
                : 'Unknown Employee';
            $this->logActivity('Update', "User $fullName encountered an error while updating RVM info: " . $e->getMessage(), $currentUser->id);
            logger()->error('Failed to update RVM info: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Failed to update RVM info.');
        }
    }
}
