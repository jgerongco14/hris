<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\Employee;
use Illuminate\Support\Facades\Storage;
use App\Traits\LogsActivity;
use Exception;
use Carbon\Carbon;

class ProfileController extends Controller
{
    use LogsActivity;

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
                'email' => 'nullable|email|max:255|unique:users,email,' . $user->id,
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
                'empCivilStatus' => 'nullable|string|max:50',
                'empBloodType' => 'nullable|string|max:10',
                'empContactNo' => 'nullable|string|max:20',
                'empFatherName' => 'nullable|string|max:100',
                'empMotherName' => 'nullable|string|max:100',
                'empSpouseName' => 'nullable|string|max:100',
                'empSpouseBdate' => 'nullable|date',
                'children' => 'nullable|array',
                'children.*.name' => 'nullable|string|max:255',
                'children.*.birthdate' => 'nullable|date',
                'empEmergencyContactName' => 'nullable|string|max:100',
                'empEmergencyContactAddress' => 'nullable|string|max:500',
                'empEmergencyContactNo' => 'nullable|string|max:20',
            ]);


            // Prepare children fields
            $childrenNames = [];
            $childrenBdates = [];

            foreach ($request->input('children', []) as $child) {
                if (!empty($child['name']) || !empty($child['birthdate'])) {
                    $childrenNames[] = $child['name'] ?? '';
                    $childrenBdates[] = !empty($child['birthdate'])
                        ? Carbon::createFromFormat('Y-m-d', $child['birthdate'])->format('d/m/Y')
                        : '';
                }
            }


            $childrenData = $request->input('children', []);
            $formattedChildren = [];

            foreach ($childrenData as $child) {
                // Include only if name or birthdate is filled
                if (!empty($child['name']) || !empty($child['birthdate'])) {
                    $formattedChildren[] = [
                        'name' => $child['name'] ?? null,
                        'birthdate' => $child['birthdate'] ?? null,
                    ];
                }
            }


            $user = Auth::user();
            if ($user instanceof \App\Models\User && !empty($validatedData['email']) && $validatedData['email'] !== $user->email) {
                $user->email = $validatedData['email'];
                $user->google_id = null;
                $user->save();
            }



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
                'empCivilStatus' => $validatedData['empCivilStatus'] ?? null,
                'empBloodType' => $validatedData['empBloodType'] ?? null,
                'empContactNo' => $validatedData['empContactNo'] ?? null,
                'empFatherName' => $validatedData['empFatherName'] ?? null,
                'empMotherName' => $validatedData['empMotherName'] ?? null,
                'empSpouseName' => $validatedData['empSpouseName'] ?? null,
                'empSpouseBdate' => $validatedData['empSpouseBdate'] ?? null,
                'empChildrenName' => json_encode($childrenNames),
                'empChildrenBdate' => json_encode($childrenBdates),
                'empEmergencyContactName' => $validatedData['empEmergencyContactName'] ?? null,
                'empEmergencyContactAddress' => $validatedData['empEmergencyContactAddress'] ?? null,
                'empEmergencyContactNo' => $validatedData['empEmergencyContactNo'] ?? null,
            ]);

            // Log the activity
            $currentUser = Auth::user();
            $employeeActor = $currentUser->employee;
            $fullName = $employeeActor
                ? trim("{$employeeActor->empPrefix} {$employeeActor->empFname} {$employeeActor->empMname} {$employeeActor->empLname} {$employeeActor->empSuffix}")
                : 'Unknown Employee';
            $this->logActivity('Update', "User $fullName updated profile successfully.", $currentUser->id);

            return redirect()->back()->with('success', 'Profile updated successfully.');
        } catch (\Exception $e) {
            // Log the error
            $currentUser = Auth::user();
            $employeeActor = $currentUser->employee;
            $fullName = $employeeActor
                ? trim("{$employeeActor->empPrefix} {$employeeActor->empFname} {$employeeActor->empMname} {$employeeActor->empLname} {$employeeActor->empSuffix}")
                : 'Unknown Employee';
            $this->logActivity('Update', "User $fullName encountered an error while updating profile: " . $e->getMessage(), $currentUser->id);
            return redirect()->back()->with('error', 'Failed to update profile. ' . $e->getMessage());
        }
    }


    public function updatePhoto(Request $request)
    {
        try {
            $request->validate([
                'photo' => 'required|image|mimes:jpg,jpeg,png|max:2048'
            ]);

            $employee = Auth::user()->employee;

            // Delete old photo if it exists
            if ($employee->photo && Storage::disk('public')->exists('employee_photos/' . $employee->photo)) {
                Storage::disk('public')->delete('employee_photos/' . $employee->photo);
            }

            // Generate new filename
            $filename = uniqid() . '.' . $request->file('photo')->getClientOriginalExtension();

            // Store the new photo
            $request->file('photo')->storeAs('employee_photos', $filename, 'public');

            // Update employee record
            $employee->update(['photo' => $filename]);

            // Log the activity
            $currentUser = Auth::user();
            $employeeActor = $currentUser->employee;
            $fullName = $employeeActor
                ? trim("{$employeeActor->empPrefix} {$employeeActor->empFname} {$employeeActor->empMname} {$employeeActor->empLname} {$employeeActor->empSuffix}")
                : 'Unknown Employee';
            $this->logActivity('Update', "User $fullName updated profile picture successfully.", $currentUser->id);

            return back()->with('success', 'Profile picture updated successfully!');
        } catch (Exception $e) {
            // Log the error
            $currentUser = Auth::user();
            $employeeActor = $currentUser->employee;
            $fullName = $employeeActor
                ? trim("{$employeeActor->empPrefix} {$employeeActor->empFname} {$employeeActor->empMname} {$employeeActor->empLname} {$employeeActor->empSuffix}")
                : 'Unknown Employee';
            $this->logActivity('Update', "User $fullName encountered an error while updating profile picture: " . $e->getMessage(), $currentUser->id);
            return redirect()->back()->with('error', 'Failed to update profile picture.');
        }
    }
}
