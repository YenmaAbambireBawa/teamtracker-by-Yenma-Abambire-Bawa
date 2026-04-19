<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Activity;
use App\Models\ActivityLog;

class ActivityController extends Controller
{
    public function index()
    {
        $activities = Activity::orderBy('category')->orderBy('title')->get();
        return view('activities.index', compact('activities'));
    }

    public function create()
    {
        return view('activities.form', ['activity' => null]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'title'       => 'required|string|max:255',
            'category'    => 'required|string|max:100',
            'description' => 'nullable|string|max:1000',
        ]);

        Activity::create([
            'title'       => trim($request->title),
            'category'    => trim($request->category),
            'description' => trim($request->description) ?: null,
            'is_active'   => true,
            'created_by'  => session('user_id'),
        ]);

        return redirect()->route('activities.index')
            ->with('success', 'Activity created successfully.');
    }

    public function edit($id)
    {
        $activity = Activity::findOrFail($id);
        return view('activities.form', compact('activity'));
    }

    public function update(Request $request, $id)
    {
        $activity = Activity::findOrFail($id);

        $request->validate([
            'title'    => 'required|string|max:255',
            'category' => 'required|string|max:100',
        ]);

        $activity->update([
            'title'       => trim($request->title),
            'category'    => trim($request->category),
            'description' => trim($request->description) ?: null,
            'is_active'   => $request->boolean('is_active', true),
        ]);

        return redirect()->route('activities.index')
            ->with('success', 'Activity updated successfully.');
    }

    public function destroy($id)
    {
        ActivityLog::where('activity_id', $id)->delete();
        Activity::findOrFail($id)->delete();

        return redirect()->route('activities.index')
            ->with('success', 'Activity removed.');
    }
}
