<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class StartSession
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Start the session if not already started
        if (!$request->hasSession()) {
            Session::start();
        }

        // Check if user is authenticated and redirect accordingly
        if (Auth::check()) {
            // User is authenticated
            if ($request->routeIs('login') || $request->is('/')) {
                // Redirect authenticated users away from login page
                return redirect()->route('myProfile');
            }
        } else {
            // User is not authenticated
            $protectedRoutes = [
                'myProfile', 'user_management', 'leave_management', 
                'attendance_management', 'employee_management', 'reports',
                'contribution_management', 'departments_offices_management',
                'finance', 'training', 'attendance', 'leave_application'
            ];
            
            if ($request->routeIs($protectedRoutes)) {
                return redirect()->route('login');
            }
        }

        $response = $next($request);

        // Ensure session is saved
        Session::save();

        return $response;
    }
}
