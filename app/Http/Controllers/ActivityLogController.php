<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Activity;
use App\Models\ActivityLog;
use App\Models\User;
use Carbon\Carbon;

class ActivityLogController extends Controller
{
    /** GET /logs/daily?date=YYYY-MM-DD */
    public function daily(Request $request)
    {
        $date = $request->query('date', now()->toDateString());

        $activities = Activity::where('is_active', true)
            ->orderBy('category')
            ->orderBy('title')
            ->get();

        // Attach the latest log for each activity on the given date
        $activities = $activities->map(function ($activity) use ($date) {
            $latestLog = ActivityLog::with('user')
                ->where('activity_id', $activity->id)
                ->where('log_date', $date)
                ->latest('created_at')
                ->first();

            $activity->latest_log = $latestLog;
            return $activity;
        });

        // Group by category
        $grouped = $activities->groupBy('category');

        $totalCount   = $activities->count();
        $doneCount    = $activities->filter(fn($a) => $a->latest_log && $a->latest_log->status === 'done')->count();
        $pendingCount = $totalCount - $doneCount;

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'activities' => $activities,
                'date'       => $date,
            ]);
        }

        return view('logs.daily', compact('grouped', 'date', 'totalCount', 'doneCount', 'pendingCount'));
    }

    /** POST /logs */
    public function store(Request $request)
    {
        $request->validate([
            'activity_id' => 'required|exists:activities,id',
            'status'      => 'required|in:done,pending',
            'log_date'    => 'required|date',
            'remark'      => 'nullable|string|max:1000',
        ]);

        ActivityLog::create([
            'activity_id' => $request->activity_id,
            'user_id'     => session('user_id'),
            'status'      => $request->status,
            'remark'      => $request->remark ?: null,
            'log_date'    => $request->log_date,
            'created_at'  => now(),
        ]);

        return redirect()->route('logs.daily', ['date' => $request->log_date])
            ->with('success', 'Activity status updated.');
    }

    /** GET /activities/{id}/update?date=YYYY-MM-DD */
    public function updateForm(Request $request, $id)
    {
        $activity = Activity::findOrFail($id);
        $date     = $request->query('date', now()->toDateString());

        $latestLog = ActivityLog::with('user')
            ->where('activity_id', $id)
            ->where('log_date', $date)
            ->latest('created_at')
            ->first();

        $currentUser = User::find(session('user_id'));

        return view('logs.update', compact('activity', 'latestLog', 'date', 'currentUser'));
    }

    /** GET /activities/{id}/history?date=YYYY-MM-DD */
    public function history(Request $request, $id)
    {
        $activity = Activity::findOrFail($id);
        $date     = $request->query('date', now()->toDateString());

        $logs = ActivityLog::with('user')
            ->where('activity_id', $id)
            ->where('log_date', $date)
            ->oldest('created_at')
            ->get();

        return view('logs.history', compact('activity', 'logs', 'date'));
    }
}
