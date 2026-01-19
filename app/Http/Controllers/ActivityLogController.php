<?php

namespace App\Http\Controllers;

use App\Models\ActivityLog;
use Illuminate\Http\Request;

class ActivityLogController extends Controller
{
    public function index()
    {
        if (!auth()->user()->hasAnyRole(['Clinic Admin', 'Super Admin'])) {
            abort(403);
        }
        $activities = ActivityLog::with('user')->latest()->paginate(20);
        return view('activity_logs.index', compact('activities'));
    }
}
