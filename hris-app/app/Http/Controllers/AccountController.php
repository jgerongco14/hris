<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;
use Exception;
use Illuminate\Support\Str;
use App\Models\Employee;
use Illuminate\Support\Facades\Hash;


class AccountController extends Controller
{

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

            // Redirect based on role
            return match ($user->role) {
                'hr' => redirect()->route('myProfile')->with('success', 'Welcome HR!'),
                'employee' => redirect()->route('myProfile')->with('success', 'Welcome Employee!'),
                'admin' => redirect()->route('myProfile')->with('success', 'Welcome Admin!'),
                default => redirect()->route('login')->with('error', 'Invalid user role.'),
            };
        } catch (\Exception $e) {
            logger()->error('Login Error: ' . $e->getMessage());
            return redirect()->route('login')->with('error', 'Something went wrong. Please go to your HR for assistance.' . $e->getMessage());
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

            // If employee or HR and it's the first Google login, create or update profile
            if (in_array($user->role, ['employee', 'hr']) && $isFirstGoogleLogin) {
                $existingEmployee = \App\Models\Employee::where('user_id', $user->id)
                    ->orWhere('empID', $user->empID)
                    ->first();

                if ($existingEmployee) {
                    // Update
                    $photo = $existingEmployee->photo ?: ($googleUser->avatar ?? '');
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
                        'photo'    => $googleUser->avatar ?? '',
                    ]);
                }

                logger()->info('Employee profile created or updated.', ['user_id' => $user->id]);
            }

            // Redirect based on role
            return match ($user->role) {
                'hr' => redirect()->route('myProfile')->with('success', 'Welcome HR!'),
                'employee' => redirect()->route('myProfile')->with('success', 'Welcome Employee!'),
                'admin' => redirect()->route('myProfile')->with('success', 'Welcome Admin!'),
                default => redirect()->route('login')->with('error', 'Invalid user role.'),
            };
        } catch (Exception $e) {
            logger()->error('Google Login Error: ' . $e->getMessage());
            return redirect()->route('login')->with('error', 'Google login failed. Please try again.' . $e->getMessage());
        }
    }



    public function logout()
    {
        Auth::logout();
        return redirect()->route('login')->with('success', 'Logged out successfully.');
    }
}
