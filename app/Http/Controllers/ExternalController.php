<?php

namespace App\Http\Controllers;

use App\Models\Activity;
use App\Models\External;
use Illuminate\Http\Request;

class ExternalController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name'  => 'required|string|max:255',
            'email'      => 'required|email|max:255',
            'activity_id'=> 'required|exists:activities,id',
        ]);

        $external = External::create([
            'first_name' => $validated['first_name'],
            'last_name'  => $validated['last_name'],
            'email'      => $validated['email'],
        ]);

        $activity = Activity::find($validated['activity_id']);
        $activity->externals()->attach($external->id);

        return redirect()->back()->with('success', 'You joined the activity!');
    }
}
