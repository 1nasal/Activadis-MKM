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
     * Show activity details
     */
    public function show(Activity $activity)
    {
        $activity->load(['users', 'externals']);
        
        return view('activityList', compact('activity'));
    }
}