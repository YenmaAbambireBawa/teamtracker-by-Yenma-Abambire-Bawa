<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Activity;
use App\Models\ActivityLog;
use App\Models\User;

class ReportController extends Controller
{
    public function index(Request $request)
    {
        $fromDate = $request->query('from', now()->subDays(7)->toDateString());
        $toDate   = $request->query('to',   now()->toDateString());

        $query = ActivityLog::with(['user', 'activity'])
            ->whereBetween('log_date', [$fromDate, $toDate]);

        if ($request->filled('activity_id')) {
            $query->where('activity_id', $request->activity_id);
        }
        if ($request->filled('user_id')) {
            $query->where('user_id', $request->user_id);
        }
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $logs = $query->orderByDesc('log_date')
                      ->orderByDesc('created_at')
                      ->limit(500)
                      ->get();

        $summary = [
            'total'   => $logs->count(),
            'done'    => $logs->where('status', 'done')->count(),
            'pending' => $logs->where('status', 'pending')->count(),
        ];

        $activities = Activity::orderBy('title')->get();
        $users      = User::orderBy('name')->get();

        return view('reports.index', compact(
            'logs', 'summary', 'activities', 'users',
            'fromDate', 'toDate'
        ));
    }
}
