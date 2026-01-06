<?php

namespace App\Http\Controllers\Extras;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;

class ActivityController extends Controller
{
    public function index()
    {
        $logs = ActivityLog::with('user')->latest()->take(100)->get();
        return view('activity.index', compact('logs'));
    }
}
