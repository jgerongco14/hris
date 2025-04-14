<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Employee; // Import the Employee model
use App\Models\User; // Import the User model
use Exception;
use Illuminate\Support\Facades\Hash;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Reader\Csv;

class AdminController extends Controller
{
    //Admin Side
    public function showUserManagement(Request $request)
    {
        $query = User::with('employee');

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

                $empID = isset($row[0]) ? trim($row[0]) : null;
                $email = isset($row[1]) && trim($row[1]) !== '' ? trim($row[1]) : null;
                $role = isset($row[2]) ? trim($row[2]) : 'employee';
                $password = isset($row[3]) && trim($row[3]) !== '' ? trim($row[3]) : 'password123'; // FIXED

                // Skip if empID is missing
                if (empty($empID)) continue;

                // Skip if email exists
                if ($email !== null && User::where('email', $email)->exists()) {
                    continue;
                }

                // Skip if empID exists
                if (User::where('empID', $empID)->exists()) {
                    continue;
                }

                // Create Users
                $user = User::create([
                    'empID' => $empID,
                    'email' => $email,
                    'role' => $role,
                    'password' =>  Hash::make($password),
                ]);


                $user_id = Employee::where('user_id', $user->id)->first();

                if (!$user_id) {
                    $user->employee()->create([
                        'user_id'  => $user->id,
                        'empID' => $empID,
                    ]);
                }
            }

            return redirect()->back()->with('success', 'User data imported successfully.');
        } catch (Exception $e) {
            return redirect()->back()->with('error', 'Error importing user data: ' . $e->getMessage());
        }
    }

    public function editUser($id)
    {
        try {
            $user = User::with('employee')->findOrFail($id);

            return view('pages.admin.edit_user', compact('user'));
        } catch (Exception $e) {
            return redirect()->back()->with('error', 'Error fetching user data: ' . $e->getMessage());
        }
    }
    public function updateUser(Request $request, $id)
    {
        try {
            $user = User::with('employee')->findOrFail($id);

            $user->empID = $request->input('empID');
            $user->email = $request->input('email');
            $user->role = $request->input('role');
            if ($user->employee) {
                $user->employee->status = $request->input('status');
                $user->employee->save();
            }

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
