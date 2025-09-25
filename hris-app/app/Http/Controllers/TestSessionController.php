<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class TestSessionController extends Controller
{
    public function testSession(Request $request)
    {
        return response()->json([
            'authenticated' => Auth::check(),
            'session_id' => Session::getId(),
            'user' => Auth::user() ? [
                'id' => Auth::user()->id,
                'email' => Auth::user()->email,
                'role' => Auth::user()->role,
            ] : null,
            'session_data' => Session::all(),
        ]);
    }
}