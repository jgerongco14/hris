<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Employee; // Import the Employee model
use App\Models\User; // Import the User model
use Exception;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Reader\Csv;

class AdminController extends Controller
{
    //Admin Side
    public function showUserManagement(Request $request)
    {
        $query = User::query();

        // Filter by role
        if ($request->has('role') && $request->role != '') {
            $query->where('role', $request->role);
        }

        // Search by email or EmpID
        if ($request->has('search') && $request->search != '') {
            $query->where(function ($q) use ($request) {
                $q->where('email', 'like', '%' . $request->search . '%')
                    ->orWhere('empID', 'like', '%' . $request->search . '%');
            });
        }

        $users = $query->where('role', '!=', 'admin')->paginate(10);

        return view('pages.admin.user_management', compact('users'));
    }

    public function createUser()
    {
        try {

            $user = new User();
            $user->empID = request('empID');
            $user->email = request('email');
            $user->role = request('role');

            // Validate the data before saving
            if (empty($user->empID) || empty($user->email) || empty($user->role)) {
                return redirect()->back()->with('error', 'All fields are required.');
            }

            // Check if user already exists
            $existingUser = User::where('email', $user->email)->first();
            $existingEmpID = User::where('empID', $user->empID)->first();

            if ($existingUser) {
                return redirect()
                    ->back()
                    ->with('error', 'User with email ' . $user->email . ' already exists.');
            } else if ($existingEmpID) {
                return redirect()
                    ->back()
                    ->with('error', 'User with Employee ID ' . $user->empID . ' already exists.');
            }

            // Create Users
            $user->save();

            return redirect()->back()->with('success', 'User created successfully.');
        } catch (Exception $e) {
            return redirect()->back()->with('error', 'Error creating user: ' . $e->getMessage());
        }
    }

    public function importUserData(Request $request)
    {
        try {
            $request->validate([
                'user_file' => 'required|file|mimes:csv,xlsx,xls|max:2048',
            ]);

            $file = $request->file('user_file');
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
                if (count($row) < 3) continue;

                $empID = trim($row[0]);
                $email = trim($row[1]);
                $role = trim($row[2]);

                // Validate the data before saving
                if (empty($empID) || empty($email) || empty($role)) {
                    continue; // Skip invalid rows
                }

                // Check if user already exists
                $existingUser = User::where('email', $email)->first();
                if ($existingUser) {
                    return redirect()
                        ->back()
                        ->with('error', 'User with email ' . $email . ' already exists.');
                }

                // Create Users
                User::create([
                    'empID' => $empID,
                    'email' => $email,
                    'role' => $role,
                ]);
            }

            return redirect()->back()->with('success', 'User data imported successfully.');
        } catch (Exception $e) {
            return redirect()->back()->with('error', 'Error importing user data: ' . $e->getMessage());
        }
    }

    public function editUser($id)
    {
        try {
            $user = User::findOrFail($id);
            return view('pages.admin.edit_user', compact('user'));
        } catch (Exception $e) {
            return redirect()->back()->with('error', 'Error fetching user data: ' . $e->getMessage());
        }
    }
    public function updateUser(Request $request, $id)
    {
        try {
            $user = User::findOrFail($id);
            $user->empID = $request->input('empID');
            $user->email = $request->input('email');
            $user->role = $request->input('role');

            // Validate the data before saving
            if (empty($user->empID) || empty($user->email) || empty($user->role)) {
                return redirect()->back()->with('error', 'All fields are required.');
            }

            // Check if user already exists
            $existingUser = User::where('email', $user->email)->first();
            if ($existingUser && $existingUser->id !== $id) {
                return redirect()
                    ->back()
                    ->with('error', 'User with email ' . $user->email . ' already exists.');
            }

            // Update Users
            $user->save();

            return redirect()->back()->with('success', 'User updated successfully.');
        } catch (Exception $e) {
            return redirect()->back()->with('error', 'Error updating user: ' . $e->getMessage());
        }
    }

    public function deleteUser($id)
    {
        try {
            $user = User::findOrFail($id);
            $user->delete();

            return redirect()->back()->with('success', 'User deleted successfully.');
        } catch (Exception $e) {
            return redirect()->back()->with('error', 'Error deleting user: ' . $e->getMessage());
        }
    }
}
