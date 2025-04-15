<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\Employee;

class ProfileController extends Controller
{
    // Show profile page
    public function index()
    {
        $user = User::with('employee.assignments.position')->findOrFail(Auth::id());

        return view('pages.profile.userProfile', [
            'user' => $user,
            'employee' => $user->employee, // this can be null
            'assignments' => $user->employee?->assignments ?? [],
        ]);
    }

    public function displayEmpProfile()
    {
        try {
            $user = User::with('employee.assignments.position')->findOrFail(Auth::id());

            return view('pages.profile.userProfile', [
                'user' => $user,
                'employee' => $user->employee,
            ]);
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Error occurred while displaying profile: ' . $e->getMessage());
        }
    }


    public function update(Request $request)
    {
        try {

            $user = Auth::user();
            $employee = $user->employee;

            if (!$employee) {
                return redirect()->back()->with('error', 'Employee profile not found.');
            }

            $validatedData = $request->validate([
                'empID' => 'required|string|max:255',
                'empPrefix' => 'nullable|string|max:10',
                'empFirstName' => 'required|string|max:255',
                'empMiddleName' => 'nullable|string|max:255',
                'empLastName' => 'required|string|max:255',
                'empSuffix' => 'nullable|string|max:10',
                'empGender' => 'nullable|in:Male,Female',
                'empBdate' => 'nullable|date',
                'empAddress' => 'nullable|string|max:255',
                'empProvince' => 'nullable|string|max:255',
                'empCity' => 'nullable|string|max:255',
                'empBarangay' => 'nullable|string|max:255',
                'empSSS' => 'nullable|string|max:50',
                'empPagibig' => 'nullable|string|max:50',
                'empTIN' => 'nullable|string|max:50',
            ]);

            $employee->update([
                'empID' => $validatedData['empID'],
                'empPrefix' => $validatedData['empPrefix'],
                'empFname' => $validatedData['empFirstName'],
                'empMname' => $validatedData['empMiddleName'],
                'empLname' => $validatedData['empLastName'],
                'empSuffix' => $validatedData['empSuffix'],
                'empGender' => $validatedData['empGender'],
                'empBirthdate' => $validatedData['empBdate'],
                'address' => $validatedData['empAddress'],
                'province' => $validatedData['empProvince'],
                'city' => $validatedData['empCity'],
                'barangay' => $validatedData['empBarangay'],
                'empSSSNum' => $validatedData['empSSS'],
                'empPagIbigNum' => $validatedData['empPagibig'],
                'empTinNum' => $validatedData['empTIN'],
            ]);

            return redirect()->back()->with('success', 'Profile updated successfully.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Failed to update profile. ' . $e->getMessage());
        }
    }
}
