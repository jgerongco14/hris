<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;
use Exception;


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
            // Get the user details from Google
            $user = Socialite::driver('google')->user();

            // Check if the user already exists
            $existingUser = User::where('google_id', $user->id)->first();

            if ($existingUser) {
                // Log in the existing user
                Auth::login($existingUser);
            } else {
                // Create a new user
                $newUser = User::create([
                    'username' => $user->name,
                    'email' => $user->email,
                    'google_id' => $user->id,
                    'password' => bcrypt('123456dummy'),  // Hash the password
                ]);

                // Log in the newly created user
                Auth::login($newUser);
            }

            // Redirect to the leave management page
            return redirect()->route('leave_management');
        } catch (Exception $e) {
            // Handle any exceptions
            dd($e->getMessage());  // Dump the exception message for debugging
        }
    }
}
