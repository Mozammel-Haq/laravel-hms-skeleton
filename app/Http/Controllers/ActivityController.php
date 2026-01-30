<?php

namespace App\Http\Controllers;

use App\Models\ActivityLog;
use Illuminate\Support\Facades\Gate;

/**
 * ActivityController
 *
 * Displays activity logs for system auditing.
 * Allows filtering by action, user, and date range.
 */
class ActivityController extends Controller
{
    /**
     * Display a listing of activity logs.
     *
     * Supports filtering by:
     * - Action: Filter by specific action type.
     * - Search: Search by action name or user name.
     * - Date Range: Filter by start and end dates.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        Gate::authorize('view_reports');
        $query = ActivityLog::with(['user', 'subject']);

        if (request()->filled('action')) {
            if (request('action') !== 'all') {
                $query->where('action', request('action'));
            }
        }

        if (request()->filled('search')) {
            $search = request('search');
            $query->where(function ($q) use ($search) {
                $q->where('action', 'like', "%{$search}%")
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

        $logs = $query->latest()->paginate(100)->withQueryString();
        return view('activity.index', compact('logs'));
    }
}
