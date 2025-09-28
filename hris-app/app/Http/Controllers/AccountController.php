<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;
use Exception;
use Illuminate\Support\Str;
use App\Models\Employee;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use App\Traits\LogsActivity;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;


class AccountController extends Controller
{
    /**
     * Logs user activity.
     *
     * @param string $action
     * @param string $description
     * @param int|null $userId
     * @return void
     */

    use LogsActivity;

    public function defaultlogin()
    {
        try {
            $identifier = request('identifier');
            $password = request('password');

            // Check if both identifier and password are provided
            if (empty($identifier) || empty($password)) {
                return redirect()->route('login')->with('error', 'Please enter your Email or Employee ID and Password.');
            }

            $user = User::where('email', $identifier)
                ->orWhere('empID', $identifier)
                ->first();

            if (!$user || !$user->password) {
                return redirect()->route('login')->with('error', 'Invalid credentials. Please go to your HR for assistance.');
            }

            $storedPassword = $user->password;
            $isHashed = strlen($storedPassword) === 60 && str_starts_with($storedPassword, '$2y$');

            $passwordValid = false;

            if ($isHashed) {
                $passwordValid = Hash::check($password, $storedPassword);
            } else {
                $passwordValid = $password === $storedPassword;
            }

            if (!$passwordValid) {
                return redirect()->route('login')->with('error', 'Invalid credentials. Please go to your HR for assistance.');
            }

            // Optional: upgrade to bcrypt if it was plain text
            if (!$isHashed) {
                $user->password = Hash::make($password);
                $user->save();
                logger()->info('Upgraded plain-text password to hashed for user.', ['user_id' => $user->id]);
            }

            Auth::login($user);

            $user_id = Employee::where('user_id', $user->id)->first();

            if (!$user_id) {
                $user->employee()->create([
                    'user_id'  => $user->id,
                    'empID' => $user->empID,
                ]);
            }

            $currentUser = Auth::user();
            $employee = $currentUser->employee;

            if ($currentUser->role == 'admin') {
                $this->logActivity('Login', "Admin logged in successfully.", $currentUser->id);
            } else {
                // Handle case where employee record might be missing
                $fullName = $employee
                    ? trim("{$employee->empPrefix} {$employee->empFname} {$employee->empMname} {$employee->empLname} {$employee->empSuffix}")
                    : 'Unknown Employee';

                $this->logActivity('Login', "User $fullName logged in successfully.", $currentUser->id);
            }

            // Redirect based on role
            return match ($user->role) {
                'hr' => redirect()->route('myProfile')->with('success', 'Welcome HR!'),
                'employee' => redirect()->route('myProfile')->with('success', 'Welcome Employee!'),
                'admin' => redirect()->route('myProfile')->with('success', 'Welcome Admin!'),
                default => redirect()->route('login')->with('error', 'Invalid user role.'),
            };
        } catch (\Exception $e) {

            $this->logActivity('Login', "User logged in failed. {$e->getMessage()}");

            logger()->error('Login Error: ' . $e->getMessage());
            return redirect()->route('login')->with('error', 'Something went wrong. Please go to your HR for assistance.');
        }
    }

    //redirect to google login page
    public function googleLogin()
    {
        return Socialite::driver('google')->redirect();
    }

    //callback function after google login
    public function googleAuth()
    {
        try {
            $googleUser = Socialite::driver('google')->user();

            if (!$googleUser->email) {
                return redirect()->route('login')->with('error', 'Google email is required');
            }

            // Check if user exists
            $user = User::where('email', $googleUser->email)->first();
            $isFirstGoogleLogin = false;

            if (!$user->google_id) {
                $user->google_id = $googleUser->id;
                $user->save();

                // Refresh to get the updated model from the database
                $user->refresh();

                $isFirstGoogleLogin = true;
            }

            $user = User::where('id', $user->id)->first();

            // Login the user
            Auth::login($user);

            // Log for debugging
            logger()->info('Logged in via Google:', ['user_id' => $user->id, 'isFirstGoogleLogin' => $isFirstGoogleLogin]);

            $photoFilename = null;
            $shouldDownloadPhoto = false;

            // Check if we need to download the Google avatar
            if (!empty($googleUser->avatar)) {
                if ($isFirstGoogleLogin) {
                    // For first login, check if user already has a photo
                    $existingEmployee = Employee::where('user_id', $user->id)
                        ->orWhere('empID', $user->empID)
                        ->first();
                    
                    $shouldDownloadPhoto = !$existingEmployee || empty($existingEmployee->photo);
                } else {
                    // For subsequent logins, check if current employee has no photo
                    $currentEmployee = $user->employee;
                    $shouldDownloadPhoto = $currentEmployee && empty($currentEmployee->photo);
                }

                if ($shouldDownloadPhoto) {
                    try {
                        $imageContents = Http::get($googleUser->avatar)->body();
                        $photoFilename = uniqid('google_', true) . '.jpg';
                        Storage::disk('public')->put("employee_photos/{$photoFilename}", $imageContents);
                        logger()->info('Downloaded Google avatar:', ['filename' => $photoFilename]);
                    } catch (\Exception $e) {
                        logger()->error('Failed to download Google avatar: ' . $e->getMessage());
                    }
                } else {
                    logger()->info('Skipped downloading Google avatar - user already has a profile photo');
                }
            }

            // If employee or HR and it's the first Google login, create or update profile
            if (in_array($user->role, ['employee', 'hr', 'admin']) && $isFirstGoogleLogin) {
                $existingEmployee = Employee::where('user_id', $user->id)
                    ->orWhere('empID', $user->empID)
                    ->first();

                if ($existingEmployee) {
                    // Update
                    $photo = $existingEmployee->photo ?: $photoFilename;
                    $existingEmployee->update([
                        'user_id'  => $user->id,
                        'empID'    => $user->empID,
                        'empFname' => $existingEmployee->empFname ?? $googleUser->user['given_name'] ?? '',
                        'empLname' => $existingEmployee->empLname ?? $googleUser->user['family_name'] ?? '',
                        'photo'    => $photo,
                    ]);
                } else {
                    // Create new
                    $user->employee()->create([
                        'user_id'  => $user->id,
                        'empID'    => $user->empID,
                        'empFname' => $googleUser->user['given_name'] ?? '',
                        'empLname' => $googleUser->user['family_name'] ?? '',
                        'photo'    => $photoFilename,
                    ]);
                }
            }

            $currentUser = Auth::user();
            $employee = $currentUser->employee;

            // For non-first-time logins, update photo only if employee doesn't have one already and we downloaded a new one
            if (!$isFirstGoogleLogin && !empty($photoFilename) && $employee && empty($employee->photo)) {
                $employee->update(['photo' => $photoFilename]);
            }


            if ($currentUser->role == 'admin') {
                $this->logActivity('Login', "Admin logged in successfully.", $currentUser->id);
            } else {
                // Handle case where employee record might be missing
                $fullName = $employee
                    ? trim("{$employee->empPrefix} {$employee->empFname} {$employee->empMname} {$employee->empLname} {$employee->empSuffix}")
                    : 'Unknown Employee';

                $this->logActivity('Login', "User $fullName logged in successfully.", $currentUser->id);
            }

            // Redirect based on role
            return match ($user->role) {
                'hr' => redirect()->route('myProfile')->with('success', 'Welcome HR!'),
                'employee' => redirect()->route('myProfile')->with('success', 'Welcome Employee!'),
                'admin' => redirect()->route('myProfile')->with('success', 'Welcome Admin!'),
                default => redirect()->route('login')->with('error', 'Invalid user role.'),
            };
        } catch (Exception $e) {

            $this->logActivity('Login', "User google logged in failed. {$e->getMessage()}");

            logger()->error('Google Login Error: ' . $e->getMessage());
            return redirect()->route('login')->with('error', 'Google login failed. Please try again.');
        }
    }

    public function showChangeForm()
    {
        return view('auth.change-password');
    }


    public function updatePassword(Request $request)
    {
        try {
            $request->validate([
                'current_password' => 'required',
                'new_password' => 'required|min:8|confirmed',
            ]);

            $user = Auth::user();

            if (!Hash::check($request->current_password, $user->password)) {
                return back()->with('error', 'Current password is incorrect.');
            }

            if ($user instanceof \App\Models\User) {
                $user->password = Hash::make($request->new_password);
                $user->save();
            } else {
                return back()->with('error', 'User not found or invalid.');
            }
            $currentUser = Auth::user();
            $employee = $currentUser->employee;

            if ($currentUser->role == 'admin') {
                $this->logActivity('Login', "Admin logged in successfully.", $currentUser->id);
            } else {
                $fullName = $employee
                    ? trim("{$employee->empPrefix} {$employee->empFname} {$employee->empMname} {$employee->empLname} {$employee->empSuffix}")
                    : 'Unknown Employee';

                $this->logActivity('Update', "User $fullName updated password in successfully.", $currentUser->id);
            }

            return redirect()->back()->with('success', 'Password updated successfully.');
        } catch (\Exception $e) {
            $currentUser = Auth::user();
            $employee = $currentUser->employee;

            if ($currentUser->role == 'admin') {
                $this->logActivity('Login', "Admin logged in failed. {$e->getMessage()}", $currentUser->id);
            } else {
                $fullName = $employee
                    ? trim("{$employee->empPrefix} {$employee->empFname} {$employee->empMname} {$employee->empLname} {$employee->empSuffix}")
                    : 'Unknown Employee';

                $this->logActivity('Update', "User $fullName updated password in failed. {$e->getMessage()}", $currentUser->id);
            }

            logger()->error('Update Password Error: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Something went wrong. Please try again.');
        }
    }

    public function showLinkRequestForm()
    {
        return view('auth.passwords.email');
    }

    public function sendResetLinkEmail(Request $request)
    {
        $request->validate(['email' => 'required|email']);
        $status = Password::sendResetLink($request->only('email'));

        return $status === Password::RESET_LINK_SENT
            ? back()->with(['status' => __($status)])
            : back()->withErrors(['email' => __($status)]);
    }

    public function showResetForm(Request $request, $token = null)
    {
        return view('auth.passwords.reset', ['token' => $token, 'email' => $request->email]);
    }

    public function reset(Request $request)
    {
        $request->validate([
            'token' => 'required',
            'email' => 'required|email',
            'password' => 'required|confirmed|min:8',
        ]);

        $status = Password::reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function ($user, $password) {
                $user->forceFill([
                    'password' => Hash::make($password),
                    'remember_token' => Str::random(60),
                ])->save();
            }
        );

        return $status === Password::PASSWORD_RESET
            ? redirect()->route('login')->with('status', __($status))
            : back()->withErrors(['email' => [__($status)]]);
    }

    public function logout(Request $request)
    {
        $user = Auth::user();

        if ($user) {
            $employee = $user->employee;

            if ($user->role == 'admin') {
                $this->logActivity('Logout', "Admin logged out successfully.", $user->id);
            } else {
                $fullName = $employee
                    ? trim("{$employee->empPrefix} {$employee->empFname} {$employee->empMname} {$employee->empLname} {$employee->empSuffix}")
                    : 'Unknown Employee';

                $this->logActivity('Logout', "User $fullName logged out successfully.", $user->id);
            }
        }

        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login')->with('success', 'Logged out successfully.');
    }
}
