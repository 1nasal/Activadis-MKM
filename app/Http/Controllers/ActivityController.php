<?php
namespace App\Http\Controllers;

use App\Models\Activity;
use Illuminate\Http\Request;

class ActivityController extends Controller
{
    /**
     * Display a listing of activities for users
     */
    public function index()
    {
        $activities = Activity::with(['users', 'externals'])
            ->where('start_time', '>', now())
            ->orderBy('start_time', 'asc')
            ->paginate(12);
        return view('activityList', compact('activities'));
    }

    /**
     * Show the form for creating a new activity
     */
    public function create()
    {
        return view('activity.create');
    }

    /**
     * Store a newly created activity in storage
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'location' => 'required|string|max:255',
            'includes_food' => 'required|boolean',
            'description' => 'required|string',
            'start_time' => 'required|date',
            'end_time' => 'required|date|after_or_equal:start_time',
            'cost' => 'required|numeric',
            'max_participants' => 'nullable|integer',
            'min_participants' => 'nullable|integer',
            'image' => 'nullable|string',
            'requirements' => 'nullable|string',
        ]);

        Activity::create($validated);

        return redirect()->route('activities.index')->with('success', 'Activity created!');
    }

    /**
     * Show activity details
     */
    public function show(Activity $activity)
    {
        $activity->load(['users', 'externals']);
        
        return view('activity.show', compact('activity'));
    }

    /**
     * Show the form for editing the specified activity
     */
    public function edit(Activity $activity)
    {
        return view('activity.edit', compact('activity'));
    }

    /**
     * Update the specified activity in storage
     */
    public function update(Request $request, Activity $activity)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'location' => 'required|string|max:255',
            'includes_food' => 'required|boolean',
            'description' => 'required|string',
            'start_time' => 'required|date',
            'end_time' => 'required|date|after_or_equal:start_time',
            'cost' => 'required|numeric',
            'max_participants' => 'nullable|integer',
            'min_participants' => 'nullable|integer',
            'image' => 'nullable|string',
            'requirements' => 'nullable|string',
        ]);

        $activity->update($validated);

        return redirect()->route('activities.index')->with('success', 'Activity updated!');
    }

    /**
     * Remove the specified activity from storage
     */
    public function destroy(Activity $activity)
    {
        $activity->delete();

        return redirect()->route('activities.index')->with('success', 'Activity deleted!');
    }
}