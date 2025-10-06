<?php
// app/Http/Controllers/ActivityController.php

namespace App\Http\Controllers;

use App\Models\Activity;
use App\Models\ActivityImage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use App\Mail\ActivityJoinedMail;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Auth;

class ActivityController extends Controller
{
    /**
     * Display a listing of activities for users
     */
    public function home(Request $request)
    {
        $sortBy = $request->get('sort', 'start_time');
        $sortOrder = $request->get('order', 'asc');

        $activities = $this->getActivitiesQuery($sortBy, $sortOrder)->paginate(12);

        // Append query parameters to pagination links
        $activities->appends($request->query());

        return view('activityList', compact('activities', 'sortBy', 'sortOrder'));
    }

    /**
     * Display listing for activities index (/activities)
     */
    public function index(Request $request)
    {
        $sortBy = $request->get('sort', 'start_time');
        $sortOrder = $request->get('order', 'desc');

        $query = Activity::with(['users', 'externals', 'images']);

        $validSortColumns = ['start_time', 'name', 'participants'];
        $validSortOrders = ['asc', 'desc'];

        if (!in_array($sortBy, $validSortColumns)) {
            $sortBy = 'start_time';
        }

        if (!in_array($sortOrder, $validSortOrders)) {
            $sortOrder = 'desc';
        }

        switch ($sortBy) {
            case 'participants':
                $query->withCount(['users', 'externals'])
                      ->orderByRaw('(users_count + externals_count) ' . $sortOrder);
                break;
            default:
                $query->orderBy($sortBy, $sortOrder);
                break;
        }

        $activities = $query->paginate(12);
        $activities->appends($request->query());

        return view('activity.index', compact('activities', 'sortBy', 'sortOrder'));
    }

    /**
     * Get activities query with sorting applied
     */
    private function getActivitiesQuery($sortBy, $sortOrder)
    {
        $query = Activity::with(['users', 'externals', 'images'])
            ->where('start_time', '>', now());

        $validSortColumns = ['start_time', 'name', 'participants'];
        $validSortOrders = ['asc', 'desc'];

        if (!in_array($sortBy, $validSortColumns)) {
            $sortBy = 'start_time';
        }

        if (!in_array($sortOrder, $validSortOrders)) {
            $sortOrder = 'asc';
        }

        switch ($sortBy) {
            case 'participants':
                $query->withCount(['users', 'externals'])
                    ->orderByRaw('(users_count + externals_count) ' . $sortOrder);
                break;
            default:
                $query->orderBy($sortBy, $sortOrder);

                if ($sortBy !== 'start_time') {
                    $query->orderBy('start_time', 'asc');
                }
                break;
        }

        return $query;
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
            'images.*' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
        ]);

        $activity = Activity::create($validated);

        if ($request->hasFile('images')) {
            $this->handleImageUploads($request->file('images'), $activity);
        }

        return redirect()->route('activities.index')->with('success', 'Activiteit succesvol aangemaakt!');
    }

    /**
     * Show activity details
     */
    public function show(Activity $activity)
    {
        $activity->load(['users', 'externals', 'images']);

        return view('activity.show', compact('activity'));
    }

    /**
     * Show the form for editing the specified activity
     */
    public function edit(Activity $activity)
    {
        $activity->load('images');
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
            'images.*' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            'remove_images' => 'nullable|array',
            'remove_images.*' => 'integer|exists:activity_images,id',
        ]);

        $activity->update($validated);

        if ($request->has('remove_images')) {
            $this->removeImages($request->remove_images);
        }

        if ($request->hasFile('images')) {
            $this->handleImageUploads($request->file('images'), $activity);
        }

        return redirect()->route('activities.index')->with('success', 'Activiteit succesvol bijgewerkt!');
    }

    /**
     * Remove the specified activity from storage
     */
    public function destroy(Activity $activity)
    {
        foreach ($activity->images as $image) {
            Storage::disk('public')->delete($image->path);
        }

        $activity->delete();

        return redirect()->route('activities.index')->with('success', 'Activiteit succesvol verwijderd!');
    }

    /**
     * Handle multiple image uploads
     */
    private function handleImageUploads(array $images, Activity $activity): void
    {
        $sortOrder = $activity->images()->max('sort_order') + 1;

        foreach ($images as $image) {
            $filename = Str::uuid() . '.' . $image->getClientOriginalExtension();
            $path = $image->storeAs('activity-images', $filename, 'public');

            ActivityImage::create([
                'activity_id' => $activity->id,
                'filename' => $filename,
                'original_name' => $image->getClientOriginalName(),
                'path' => $path,
                'file_size' => $image->getSize(),
                'mime_type' => $image->getMimeType(),
                'sort_order' => $sortOrder++,
            ]);
        }
    }

    /**
     * Remove images from storage and database
     */
    private function removeImages(array $imageIds): void
    {
        $images = ActivityImage::whereIn('id', $imageIds)->get();

        foreach ($images as $image) {
            Storage::disk('public')->delete($image->path);
            $image->delete();
        }
    }

    public function join(Request $request, Activity $activity)
    {
        if (Auth::guest()) {
            $validated = $request->validate([
                'first_name' => 'required|string|max:255',
                'last_name' => 'required|string|max:255',
                'email' => 'required|email|max:255',
            ]);

            $external = \App\Models\External::firstOrCreate(
                ['email' => $validated['email']],
                [
                    'first_name' => $validated['first_name'],
                    'last_name' => $validated['last_name'],
                ]
            );

            if ($activity->externals()->where('external_id', $external->id)->exists()) {
                return back()->with('error', 'Je hebt al deelgenomen aan deze activiteit als externe.');
            }

            $activity->externals()->attach($external->id);

            Mail::to($external->email)->send(new ActivityJoinedMail($activity, $external->first_name));

            return back()->with('success', 'Je neemt nu deel aan de activiteit!');
        }

        $user = Auth::user();

        if ($activity->users()->where('user_id', $user->id)->exists()) {
            return back()->with('error', 'Je neemt al deel aan deze activiteit.');
        }

        $activity->users()->attach($user->id);

        Mail::to($user->email)->send(new ActivityJoinedMail($activity, $user->first_name));

        return back()->with('success', 'Je neemt nu deel aan de activiteit!');
    }

    public function myActivities(Request $request)
    {
        $user = Auth::user();
        
        $sortBy = $request->get('sort', 'start_time');
        $sortOrder = $request->get('order', 'asc');
        
        $query = $user->activities()->with(['users', 'externals', 'images']);
        
        $validSortColumns = ['start_time', 'name', 'participants'];
        $validSortOrders = ['asc', 'desc'];

        if (!in_array($sortBy, $validSortColumns)) {
            $sortBy = 'start_time';
        }

        if (!in_array($sortOrder, $validSortOrders)) {
            $sortOrder = 'asc';
        }

        switch ($sortBy) {
            case 'participants':
                $query->withCount(['users', 'externals'])
                      ->orderByRaw('(users_count + externals_count) ' . $sortOrder);
                break;
            default:
                $query->orderBy($sortBy, $sortOrder);
                break;
        }
        
        $activities = $query->get();
        
        return view('dashboard', compact('activities'));
    }

    public function leave(Activity $activity)
    {
        $user = Auth::user();
        
        if (!$activity->users()->where('user_id', $user->id)->exists()) {
            return back()->with('error', 'Je neemt niet deel aan deze activiteit.');
        }
        
        $activity->users()->detach($user->id);
        
        return back()->with('success', 'Je bent uitgeschreven voor de activiteit.');
    }
}