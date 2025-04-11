<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

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


    // Update profile info (name/photo)
    public function update(Request $request)
    {
        try {
            $request->validate([
                'empFname' => 'required|string|max:255',
                'empMname' => 'nullable|string|max:255',
                'empLname' => 'required|string|max:255',
                'photo' => 'nullable|image|max:2048', // max 2MB
            ]);

            $employee = Auth::user()->employee;

            $employee->empFname = $request->empFname;
            $employee->empMname = $request->empMname;
            $employee->empLname = $request->empLname;

            // Handle profile photo upload
            if ($request->hasFile('photo')) {
                $photoPath = $request->file('photo')->store('attachments/employee_photos', 'public');
                $employee->photo = basename($photoPath); // Store only filename if needed
            }

            $employee->save();

            return redirect()->back()->with('success', 'Profile updated successfully!');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Error occurred while updating profile: ' . $e->getMessage());
        }
    }
}
