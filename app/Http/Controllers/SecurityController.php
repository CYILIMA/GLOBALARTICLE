<?php

namespace App\Http\Controllers;

use App\Models\LoginHistory;
use App\Models\ActivityLog;
use Illuminate\Http\Request;

class SecurityController extends Controller
{
    public function loginHistory()
    {
        $history = LoginHistory::with('user')
            ->latest('login_at')
            ->paginate(20);

        return view('admin.security.login-history', compact('history'));
    }

    public function activityLogs(Request $request)
    {
        $query = ActivityLog::with('user')->latest();

        // Filter by action
        if ($request->has('action') && $request->action != 'all') {
            $query->where('action', $request->action);
        }

        // Filter by model
        if ($request->has('model') && $request->model != 'all') {
            $query->where('model', $request->model);
        }

        // Filter by date
        if ($request->has('date') && $request->date) {
            $query->whereDate('created_at', $request->date);
        }

        $logs = $query->paginate(20);
        
        // Get unique actions and models for filters
        $actions = ActivityLog::distinct()->pluck('action');
        $models = ActivityLog::distinct()->pluck('model');

        return view('admin.security.activity-logs', compact('logs', 'actions', 'models'));
    }
}
