<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Employee; // Import the Employee model
use Exception;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Reader\Csv;
use Illuminate\Support\Facades\Storage;
use App\Models\User;

class EmployeeController extends Controller
{

    public function store(Request $request)
    {
        // Validate the request data
        $validatedData = $request->validate([
            'empFname' => 'required|string|max:255',
            'empLname' => 'required|string|max:255',
            'empGender' => 'required|in:male,female',
            'empBirthdate' => 'required|date',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:5120', // 5MB max
        ]);

        try {
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

            // Create employee record with explicit field mapping
            Employee::create([
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
            ]);

            // Return JSON if it's an AJAX request
            if ($request->expectsJson()) {
                return response()->json(['message' => 'Leave status updated successfully!']);
            }

            return redirect()
                ->route('employee_management')
                ->with('success', 'Employee data saved successfully!');
        } catch (Exception $e) {
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
                if (count($row) < 17) continue;

                $empID = trim($row[0]);
                $empPrefix = trim($row[1]);
                $empFname = trim($row[2]);
                $empMname = trim($row[3]);
                $empLname = trim($row[4]);
                $empSuffix = trim($row[5]);
                $empGender = trim($row[6]);
                $empBirthdate = trim($row[7]);
                $address = trim($row[8]);
                $province = trim($row[9]);
                $city = trim($row[10]);
                $barangay = trim($row[11]);
                $empSSSNum = trim($row[12]);
                $empTinNum = trim($row[13]);
                $empPagIbigNum = trim($row[14]);


                // Validate the data before saving
                if (empty($empID) || empty($empFname) || empty($empLname) || empty($empGender)) {
                    continue; // Skip invalid rows
                }

                // Check if employee already exists
                $existingEmployee = Employee::where('empID', $empID)->first();
                if ($existingEmployee) {
                    return redirect()
                        ->back()
                        ->with('error', 'Employee with ID ' . $empID . ' already exists.');
                }

                // Check if user already exists
                $existingUser = User::where('empID', $empID)->first();
                if (!$existingUser) {
                    // Create user if not exists
                    return redirect()
                        ->back()
                        ->with('error', 'User with ID ' . $empID . ' does not exist.');
                } 
                // Create employee record
                Employee::create([
                    'user_id' => $existingUser->id,
                    'empID' => $empID,
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
                    'empStatus' => 'active',
                ]);
            }

            return redirect()
                ->back()
                ->with('success', 'Employees imported successfully!');
        } catch (Exception $e) {
            logger()->error('Failed to import employees: ' . $e->getMessage());
            return redirect()
                ->back()
                ->with('error', 'Failed to import employees. Please try again later. ' .
                    (config('app.debug') ? $e->getMessage() : ''));
            // return redirect()
            //     ->back()
            //     ->with('error', 'Failed to import employees. Please try again later.');
        }
    }

    public function index(Request $request)
    {
        try {
            $query = Employee::query();

            if ($request->has('search')) {
                $search = $request->search;
                $query->where(function ($q) use ($search) {
                    $q->where('empFname', 'like', "%$search%")
                        ->orWhere('empLname', 'like', "%$search%")
                        ->orWhere('empSSSNum', 'like', "%$search%");
                });
            }

            $employees = $query->paginate(10);

            return view('pages.hr.employee_management', compact('employees'));
        } catch (Exception $e) {
            logger()->error('Failed to fetch employees: ' . $e->getMessage());
            return redirect()
                ->back()
                ->with('error', 'Failed to fetch employees. Please try again later.');
        }
    }


    public function edit(Employee $employee)
    {
        return view('pages.hr.employee_edit', compact('employee'));
    }

    public function update(Request $request, Employee $employee)
    {
        // Similar to store but with update logic
    }

    public function destroy(Employee $employee)
    {
        // Delete photo if exists
        if ($employee->photo) {
            Storage::disk('public')->delete($employee->photo);
        }

        $employee->delete();

        return redirect()->route('employee_management')
            ->with('success', 'Employee deleted successfully');
    }
}
