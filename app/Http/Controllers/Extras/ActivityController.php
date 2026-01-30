<?php

namespace App\Http\Controllers\Extras;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;

/**
 * Class ActivityController
 *
 * Manages the display of activity logs.
 *
 * @package App\Http\Controllers\Extras
 */
class ActivityController extends Controller
{
    /**
     * Display a listing of activity logs.
     *
     * Supports filtering by:
     * - Search: Action, Description, or User Name
     * - Date Range: From and To dates
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $query = ActivityLog::with('user');

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

        $logs = $query->latest()->paginate(20)->withQueryString();
        return view('activity.index', compact('logs'));
    }
}
