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

        $query = ActivityLog::with('user');

        if (request()->filled('action')) {
            if (request('action') !== 'all') {
                $query->where('action', request('action'));
            }
        }

        if (request()->filled('search')) {
            $search = request('search');
            $query->where(function ($q) use ($search) {
                $q->where('action', 'like', "%{$search}%")
                    ->orWhere('description', 'like', "%{$search}%")
                    ->orWhereHas('user', function ($u) use ($search) {
                        $u->where('name', 'like', "%{$search}%");
                    });
            });
        }

        if (request()->filled('from')) {
            $query->whereDate('created_at', '>=', request('from'));
        }

        if (request()->filled('to')) {
            $query->whereDate('created_at', '<=', request('to'));
        }

        $activities = $query->latest()->paginate(20)->withQueryString();
        return view('activity_logs.index', compact('activities'));
    }
}
