<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Employee; // Import the Employee model
use Exception;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

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
