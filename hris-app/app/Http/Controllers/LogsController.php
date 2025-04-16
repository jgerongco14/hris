<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Logs;
use Illuminate\Support\Facades\Auth;

class LogsController extends Controller
{

    public function viewActivityLogs()
    {
        $logs = Logs::with('user')->latest()->paginate(10);
        return view('pages.admin.monitoring.logs', compact('logs'));
    }
}
