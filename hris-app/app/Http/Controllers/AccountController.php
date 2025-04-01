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

            // Fetch the user using email or empID
            $user = User::where('email', $identifier)
                ->orWhere('empID', $identifier)
                ->first();

            // If no user or password doesn't match
            if (!$user || !Hash::check($password, $user->password)) {
                return redirect()->route('login')->with('error', 'Invalid credentials. Please go to your HR for assistance.');
            }

            // Login the user
            Auth::login($user);

            // Redirect based on role
            switch ($user->role) {
                case 'hr':
                    return redirect()->route('leave_management')->with('success', 'Welcome HR!');
                case 'employee':
                    return redirect()->route('myProfile')->with('success', 'Welcome Employee!');
                case 'admin':
                    return redirect()->route('admin.dashboard')->with('success', 'Welcome Admin!');
                default:
                    return redirect()->route('login')->with('error', 'Invalid user role.');
            }
        } catch (\Exception $e) {
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

            if ($user) {
                // Update google_id if not yet saved
                if (!$user->google_id) {
                    $user->google_id = $googleUser->id;

                    // $randomPassword = Str::random(12);
                    // $user->password = Hash::make($randomPassword);

                    $user->save();
                    $isFirstGoogleLogin = true;
                }
            } else {
                return redirect()->route('login')->with('error', 'User not found. Please go to your HR for assistance.');
            }

            // Login the user
            Auth::login($user);

            // Log for debugging
            logger()->info('Logged in via Google:', ['user_id' => $user->id, 'isFirstGoogleLogin' => $isFirstGoogleLogin]);

            // If employee or HR and it's the first Google login, create or update profile
            if (in_array($user->role, ['employee', 'hr']) && $isFirstGoogleLogin) {
                $user->employee()->updateOrCreate(
                    ['user_id' => $user->id],
                    [
                        'empID'    => $user->empID,
                        'empFname' => $user->employee->empFname ?? $googleUser->user['given_name'] ?? '',
                        'empLname' => $user->employee->empLname ?? $googleUser->user['family_name'] ?? '',
                        'photo'    => $user->employee->photo ?? $googleUser->avatar ?? '',
                    ]
                );

                logger()->info('Employee profile created/updated.', ['user_id' => $user->id]);
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
            return redirect()->route('login')->with('error', 'Google login failed. Please try again.');
        }
    }



    public function logout()
    {
        Auth::logout();
        return redirect()->route('login');
    }
}
