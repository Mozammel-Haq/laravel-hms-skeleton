<?php

namespace App\Http\Controllers;

use App\Models\ActivityLog;
use Illuminate\Support\Facades\Gate;

class ActivityController extends Controller
{
    public function index()
    {
        Gate::authorize('view_reports');
        $logs = ActivityLog::with('user')->latest()->paginate(100);
        return view('activity.index', compact('logs'));
    }
}
