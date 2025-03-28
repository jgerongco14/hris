<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;
use Exception;
use Illuminate\Support\Str;
use App\Models\Employee;


class AccountController extends Controller
{
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
                    $user->save();
                    $isFirstGoogleLogin = true;
                }
            } else {
                // Create new user with default 'employee' role
                $user = User::create([
                    'username' => $googleUser->name,
                    'email' => $googleUser->email,
                    'google_id' => $googleUser->id,
                    'password' => bcrypt(Str::random(16)),
                    'role' => 'employee',
                ]);
                $isFirstGoogleLogin = true;
            }

            // Login the user
            Auth::login($user);

            // Log for debugging
            logger()->info('Logged in via Google:', ['user_id' => $user->id, 'isFirstGoogleLogin' => $isFirstGoogleLogin]);

            // If employee or HR and it's the first Google login, create or update profile
            if (in_array($user->role, ['employee', 'hr']) && $isFirstGoogleLogin) {
                if (method_exists($user, 'employee')) {
                    $user->employee()->updateOrCreate(
                        ['user_id' => $user->id],
                        [
                            'empFname' => $googleUser->user['given_name'] ?? '',
                            'empLname' => $googleUser->user['family_name'] ?? '',
                            'photo' => $googleUser->avatar ?? '',
                        ]
                    );
                    logger()->info('Employee profile created/updated.', ['user_id' => $user->id]);
                } else {
                    logger()->warning('User model is missing employee() relationship.');
                }
            }

            // Redirect based on role
            return match ($user->role) {
                'hr' => redirect()->route('leave_management')->with('success', 'Welcome HR!'),
                'employee' => redirect()->route('myProfile')->with('success', 'Welcome Employee!'),
                'admin' => redirect()->route('admin.dashboard')->with('success', 'Welcome Admin!'),
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
