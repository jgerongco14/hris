<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;
use Exception;
use Illuminate\Support\Str;


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
            $user = Socialite::driver('google')->user();

            // Add validation
            if (!$user->email) {
                return redirect()->route('login')->with('error', 'Email not provided by Google');
            }

            $existingUser = User::where('google_id', $user->id)
                ->orWhere('email', $user->email)
                ->first();

            if ($existingUser) {
                $existingUser->update(['google_id' => $user->id]);
                Auth::login($existingUser);
            } else {
                $newUser = User::create([
                    'username' => $user->name ?? $user->email,
                    'email' => $user->email,
                    'google_id' => $user->id,
                    'password' => bcrypt(Str::random(16)), // More secure dummy password
                ]);
                Auth::login($newUser);
            }

            return redirect()->route('leave_management');
        } catch (Exception $e) {
            logger()->error('Google auth error: ' . $e->getMessage());
            return redirect()->route('login')->with('error', 'Login failed, please try again');
        }
    }

    public function logout()
    {
        Auth::logout();
        return redirect()->route('login'); // Change 'login' to your login route name
    }
}
