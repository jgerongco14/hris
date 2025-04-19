<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Employee; // Import the Employee model
use Exception;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Reader\Csv;
use Illuminate\Support\Facades\Storage;
use App\Models\User;
use App\Models\Position;
use Illuminate\Support\Facades\Hash;
use App\Models\Departments;
use App\Models\Offices;
use Illuminate\Support\Facades\Auth;
use App\Traits\LogsActivity;

class EmployeeController extends Controller
{
    use LogsActivity;

    private function parseDate($date)
    {
        try {
            // Try parsing as d/m/Y or d-m-Y
            return \Carbon\Carbon::createFromFormat('d/m/Y', $date)->format('Y-m-d');
        } catch (\Exception $e1) {
            try {
                return \Carbon\Carbon::createFromFormat('d-m-Y', $date)->format('Y-m-d');
            } catch (\Exception $e2) {
                return null; // or throw exception if you want to stop the import
            }
        }
    }


    public function store(Request $request)
    {
        try {


            // Validate the request data
            $validatedData = $request->validate([
                'empID' => 'required|unique:employees,empID|max:255',
                'empFname' => 'required|string|max:255',
                'empLname' => 'required|string|max:255',
                'empGender' => 'nullable|in:Male,Female',
                'empBirthdate' => 'date',
                'photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:5120', // 5MB max
                'empDateHired' => 'nullable|date',
                'empDateResigned' => 'nullable|date',
                'empPersonelStatus' => 'nullable|string|max:50',
                'empEmployeerName' => 'nullable|string|max:100',
                'empEmployeerAddress' => 'nullable|string|max:255',
                'empEmergencyContactNo' => 'nullable|string|max:15',
                'empEmergencyContactName' => 'nullable|string|max:100',
                'empEmergencyContactAddress' => 'nullable|string|max:255',
            ]);

            if ($request->has('empBirthdate')) {
                $parsed = $this->parseDate($request->input('empBirthdate'));
                if ($parsed) {
                    $request->merge(['empBirthdate' => $parsed]);
                } else {
                    return redirect()->back()->withInput()->with('error', 'Invalid birthdate format. Please use MM/DD/YYYY or DD/MM/YYYY.');
                }
            }


            // Handle file upload
            $photoPath = null;
            if ($request->hasFile('photo')) {
                // Generate unique filename
                $filename = 'employee_' . time() . '.' . $request->file('photo')->extension();

                // Store file with unique name
                $photoPath = $request->file('photo')->storeAs(
                    'employee_photos',
                    $filename,
                    'public'
                );
            }

            $user = User::create([
                'empID' => $request->input('empID', null),
                'role' => 'employee',
                'password' => Hash::make('temppass'),
            ]);

            // Create employee record with explicit field mapping
            Employee::create([
                'user_id' => $user->id,
                'empID' => $request->input('empID', null),
                'empPrefix' => $request->input('empPrefix', null),
                'empSuffix' => $request->input('empSuffix', null),
                'empFname' => $validatedData['empFname'],
                'empMname' => $request->input('empMname', null),
                'empLname' => $validatedData['empLname'],
                'empGender' => $validatedData['empGender'],
                'empBirthdate' => $validatedData['empBirthdate'],
                'address' => $request->input('address', null),
                'province' => $request->input('province', null),
                'city' => $request->input('city', null),
                'barangay' => $request->input('barangay', null),
                'empSSSNum' => $request->input('empSSSNum', null),
                'empTinNum' => $request->input('empTinNum', null),
                'empPagIbigNum' => $request->input('empPagIbigNum', null),
                'photo' => $photoPath,
                'empDateHired' => $request->input('empDateHired', null),
                'empDateResigned' => $request->input('empDateResigned', null),
                'empContactNo' => $request->input('empContactNo', null),
                'empPersonelStatus' => $request->input('empPersonelStatus', null),
                'empEmployeerName' => $request->input('empEmployeerName', null),
                'empEmployeerAddress' => $request->input('empEmployeerAddress', null),
                'empCivilStatus' => $request->input('empCivilStatus', null),
                'empBloodType' => $request->input('empBloodType', null),
                'empEmergencyContactAddress' => $request->input('empEmergencyContactAddress', null),
                'empEmergencyContactNo' => $request->input('empEmergencyContactNo', null),
                'empEmergencyContactName' => $request->input('empEmergencyContactName', null),
            ]);



            // Return JSON if it's an AJAX request
            if ($request->expectsJson()) {
                return response()->json(['message' => 'Leave status updated successfully!']);
            }

            //logs
            $currentUser = Auth::user();
            $employee = $currentUser->employee;
            $fullName = $employee
                ? trim("{$employee->empPrefix} {$employee->empFname} {$employee->empMname} {$employee->empLname} {$employee->empSuffix}")
                : 'Unknown Employee';
            $this->logActivity('Create', "User $fullName created a new employee with ID {$request->input('empID', null)}.", $currentUser->id);

            return redirect()
                ->route('employee_management')
                ->with('success', 'Employee data saved successfully!');
        } catch (Exception $e) {
            // Log the error
            $currentUser = Auth::user();
            $employee = $currentUser->employee;
            $fullName = $employee
                ? trim("{$employee->empPrefix} {$employee->empFname} {$employee->empMname} {$employee->empLname} {$employee->empSuffix}")
                : 'Unknown Employee';
            $this->logActivity('Create', "User $fullName encountered an error while creating employee: " . $e->getMessage(), $currentUser->id);
            logger()->error('Employee data save failed: ' . $e->getMessage());

            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'Employee data save failed. Please try again. ' .
                    (config('app.debug') ? $e->getMessage() : ''));
        }
    }



    public function importEmp(Request $request)
    {
        try {
            $request->validate([
                'employee_file' => 'required|file|mimes:csv,xlsx,xls|max:2048',
            ]);

            $file = $request->file('employee_file');
            $reader = IOFactory::createReaderForFile($file->getRealPath());

            // Handle CSV-specific settings
            if ($reader instanceof Csv) {
                $reader->setDelimiter(','); // or ';' based on your file
                $reader->setEnclosure('"');
            }

            $spreadsheet = $reader->load($file->getRealPath());
            $rows = $spreadsheet->getActiveSheet()->toArray();

            foreach ($rows as $index => $row) {
                if ($index === 0) continue;

                $empID = isset($row[0]) ? trim($row[0]) : null;
                $empPrefix = isset($row[1]) ? trim($row[1]) : null;
                $empFname = isset($row[2]) ? trim($row[2]) : null;
                $empMname = isset($row[3]) ? trim($row[3]) : null;
                $empLname = isset($row[4]) ? trim($row[4]) : null;
                $empSuffix = isset($row[5]) ? trim($row[5]) : null;
                $empGender = isset($row[6]) ? trim($row[6]) : null;
                $empBirthdate = isset($row[7]) ? trim($row[7]) : null;
                $address = isset($row[8]) ? trim($row[8]) : null;
                $province = isset($row[9]) ? trim($row[9]) : null;
                $city = isset($row[10]) ? trim($row[10]) : null;
                $barangay = isset($row[11]) ? trim($row[11]) : null;
                $empSSSNum = isset($row[12]) ? trim($row[12]) : null;
                $empTinNum = isset($row[13]) ? trim($row[13]) : null;
                $empPagIbigNum = isset($row[14]) ? trim($row[14]) : null;


                // Validate the data before saving
                if (empty($empID) || empty($empFname) || empty($empLname))  continue;


                if ($empID !== null && $employee = Employee::where('empID', $empID)->first()) {
                    $employee->update([
                        'empPrefix' => $empPrefix,
                        'empFname' => $empFname,
                        'empMname' => $empMname,
                        'empLname' => $empLname,
                        'empSuffix' => $empSuffix,
                        'empGender' => $empGender,
                        'empBirthdate' => $this->parseDate($empBirthdate),
                        'address' => $address,
                        'province' => $province,
                        'city' => $city,
                        'barangay' => $barangay,
                        'empSSSNum' => $empSSSNum,
                        'empTinNum' => $empTinNum,
                        'empPagIbigNum' => $empPagIbigNum,
                    ]);
                } else {

                    // Check if there is an ID on users table

                    $user = User::where('empID', $empID)->first();
                    if ($user) {
                        Employee::create([
                            'empID' => $empID,
                            'user_id' => $user->id,
                            'empPrefix' => $empPrefix,
                            'empFname' => $empFname,
                            'empMname' => $empMname,
                            'empLname' => $empLname,
                            'empSuffix' => $empSuffix,
                            'empGender' => $empGender,
                            'empBirthdate' => $empBirthdate,
                            'address' => $address,
                            'province' => $province,
                            'city' => $city,
                            'barangay' => $barangay,
                            'empSSSNum' => $empSSSNum,
                            'empTinNum' => $empTinNum,
                            'empPagIbigNum' => $empPagIbigNum,
                        ]);
                    } else {
                        User::create([
                            'empID' => $empID,
                            'role' => 'employee',
                            'password' =>  Hash::make('temppass'),
                        ]);

                        $user = User::where('empID', $empID)->first();

                        Employee::create([
                            'empID' => $empID,
                            'user_id' => $user->id,
                            'empPrefix' => $empPrefix,
                            'empFname' => $empFname,
                            'empMname' => $empMname,
                            'empLname' => $empLname,
                            'empSuffix' => $empSuffix,
                            'empGender' => $empGender,
                            'empBirthdate' => $empBirthdate,
                            'address' => $address,
                            'province' => $province,
                            'city' => $city,
                            'barangay' => $barangay,
                            'empSSSNum' => $empSSSNum,
                            'empTinNum' => $empTinNum,
                            'empPagIbigNum' => $empPagIbigNum,
                        ]);
                    }
                }
            }
            // Log the import activity (assuming you have a Logs model)
            $currentUser = Auth::user();
            $employee = $currentUser->employee;
            $fullName = $employee
                ? trim("{$employee->empPrefix} {$employee->empFname} {$employee->empMname} {$employee->empLname} {$employee->empSuffix}")
                : 'Unknown Employee';
            $this->logActivity('Import', "User $fullName imported employees successfully.", $currentUser->id);
            return redirect()
                ->back()
                ->with('success', 'Employees imported successfully!');
        } catch (Exception $e) {
            // Log the error
            $currentUser = Auth::user();
            $employee = $currentUser->employee;
            $fullName = $employee
                ? trim("{$employee->empPrefix} {$employee->empFname} {$employee->empMname} {$employee->empLname} {$employee->empSuffix}")
                : 'Unknown Employee';
            $this->logActivity('Import', "User $fullName encountered an error while importing employees: " . $e->getMessage(), $currentUser->id);
            logger()->error('Failed to import employees: ' . $e->getMessage());
            return redirect()
                ->back()
                ->with('error', 'Failed to import employees. Please try again later. ' .
                    (config('app.debug') ? $e->getMessage() : ''));
        }
    }

    public function index(Request $request)
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

            $employeeToEdit = null;
            if ($request->has('edit')) {
                $employeeToEdit = Employee::find($request->edit);
            }

            return view('pages.hr.employee_management', [
                'employees' => $employees,
                'positions' => $positions,
                'departments' => $departments,
                'offices' => $offices,
                'employee' => $employeeToEdit,
            ]);
        } catch (Exception $e) {
            // Log the error
            $currentUser = Auth::user();
            $employee = $currentUser->employee;
            $fullName = $employee
                ? trim("{$employee->empPrefix} {$employee->empFname} {$employee->empMname} {$employee->empLname} {$employee->empSuffix}")
                : 'Unknown Employee';
            $this->logActivity('Import', "User $fullName encountered an error while fetching employees: " . $e->getMessage(), $currentUser->id);
            logger()->error('Failed to fetch employees: ' . $e->getMessage());
            return redirect()
                ->back()
                ->with('error', 'Failed to fetch employees. Please try again later.');
        }
    }


    public function edit($id)
    {
        try {
            $employee = Employee::with('assignments.position')->findOrFail($id);
            return response()->json($employee);
        } catch (Exception $e) {
            logger()->error('Failed to fetch employee for edit: ' . $e->getMessage());
            return redirect()
                ->back()
                ->with('error', 'Failed to fetch employee for edit. Please try again later.');
        }
    }


    public function update(Request $request, $id)
    {
        try {
            $employee = Employee::findOrFail($id);

            // Validate incoming data
            $validated = $request->validate([
                'empPrefix' => 'nullable|string|max:10',
                'empFname' => 'required|string|max:100',
                'empMname' => 'nullable|string|max:100',
                'empLname' => 'required|string|max:100',
                'empSuffix' => 'nullable|string|max:10',
                'empGender' => 'nullable|in:Male,Female',
                'empBirthdate' => 'nullable|date',
                'address' => 'nullable|string|max:255',
                'province' => 'nullable|string|max:100',
                'city' => 'nullable|string|max:100',
                'barangay' => 'nullable|string|max:100',
                'empSSSNum' => 'nullable|string|max:30',
                'empTinNum' => 'nullable|string|max:30',
                'empPagIbigNum' => 'nullable|string|max:30',
                'photo' => 'nullable|image|max:2048', // max 2MB
                'empDateHired' => 'nullable|date',
                'empDateResigned' => 'nullable|date',
                'empContactNo' => 'nullable|string|max:15',
                'empPersonelStatus' => 'nullable|string|max:50',
                'empEmployeerName' => 'nullable|string|max:100',
                'empEmployeerAddress' => 'nullable|string|max:255',
                'empCivilStatus' => 'nullable|string|max:50',
                'empBloodType' => 'nullable|string|max:5',
                'empEmergencyContactAddress' => 'nullable|string|max:255',
                'empEmergencyContactNo' => 'nullable|string|max:15',
                'empEmergencyContactName' => 'nullable|string|max:100',

            ]);

            unset($validated['photo']);

            // Handle file upload
            if ($request->hasFile('photo')) {
                if ($employee->photo && Storage::disk('public')->exists('employee_photos/' . $employee->photo)) {
                    Storage::disk('public')->delete('employee_photos/' . $employee->photo);
                }

                $filename = uniqid() . '.' . $request->file('photo')->getClientOriginalExtension();
                $request->file('photo')->storeAs('employee_photos', $filename, 'public');
                $employee->photo = $filename;
            }

            // Update other fields
            $employee->fill($validated);
            $employee->save();

            // Log the update activity (assuming you have a Logs model)
            $currentUser = Auth::user();
            $employee = $currentUser->employee;
            $fullName = $employee
                ? trim("{$employee->empPrefix} {$employee->empFname} {$employee->empMname} {$employee->empLname} {$employee->empSuffix}")
                : 'Unknown Employee';
            $this->logActivity('Update', "User $fullName updated employee with ID {$employee->empID}.", $currentUser->id);

            return redirect()->route('employee_management')->with('success', 'Employee updated successfully.');
        } catch (Exception $e) {
            // Log the error
            $currentUser = Auth::user();
            $employee = $currentUser->employee;
            $fullName = $employee
                ? trim("{$employee->empPrefix} {$employee->empFname} {$employee->empMname} {$employee->empLname} {$employee->empSuffix}")
                : 'Unknown Employee';
            $this->logActivity('Update', "User $fullName encountered an error while updating employee: " . $e->getMessage(), $currentUser->id);
            logger()->error('Failed to update employee: ' . $e->getMessage());
            return redirect()
                ->back()
                ->with('error', 'Failed to update employee. Please try again later.' . $e->getMessage());
        }
    }

    public function destroy(Employee $employee)
    {
        try {
            // Delete photo if exists
            if ($employee->photo) {
                Storage::disk('public')->delete($employee->photo);
            }

            //logs
            $currentUser = Auth::user();
            $employee = $currentUser->employee;
            $fullName = $employee
                ? trim("{$employee->empPrefix} {$employee->empFname} {$employee->empMname} {$employee->empLname} {$employee->empSuffix}")
                : 'Unknown Employee';
            $this->logActivity('Delete', "User $fullName deleted employee with ID {$employee->empID}.", $currentUser->id);

            // Delete the employee record
            $user = User::where('empID', $employee->empID)->first();
            if ($user) {
                $user->delete();
            }

            return redirect()->route('employee_management')
                ->with('success', 'Employee deleted successfully');
        } catch (Exception $e) {
            // Log the error
            $currentUser = Auth::user();
            $employee = $currentUser->employee;
            $fullName = $employee
                ? trim("{$employee->empPrefix} {$employee->empFname} {$employee->empMname} {$employee->empLname} {$employee->empSuffix}")
                : 'Unknown Employee';
            $this->logActivity('Delete', "User $fullName encountered an error while deleting employee: " . $e->getMessage(), $currentUser->id);
            logger()->error('Failed to delete employee: ' . $e->getMessage());
            return redirect()
                ->back()
                ->with('error', 'Failed to delete employee. Please try again later.');
        }
    }
}
