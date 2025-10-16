<?php
// app/Http/Controllers/ActivityController.php

namespace App\Http\Controllers;

use App\Mail\ActivityConfirmationMail;
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
        $search = $request->get('search');

        $activities = $this->getActivitiesQuery($sortBy, $sortOrder, $search)->paginate(12);

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
        $search = $request->get('search');

        $query = Activity::with(['users', 'externals', 'images']);

        // Apply search filter
        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', '%' . $search . '%')
                    ->orWhere('description', 'like', '%' . $search . '%')
                    ->orWhere('location', 'like', '%' . $search . '%');
            });
        }

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
     * Get activities query with sorting and search applied
     */
    private function getActivitiesQuery($sortBy, $sortOrder, $search = null)
    {
        $query = Activity::with(['users', 'externals', 'images'])
            ->where('start_time', '>', now());

        // Apply search filter
        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', '%' . $search . '%')
                    ->orWhere('description', 'like', '%' . $search . '%')
                    ->orWhere('location', 'like', '%' . $search . '%');
            });
        }

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
        // CHANGED: accept temp_images[] (strings) in addition to classic images[]
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
            'temp_images' => 'nullable|array',
            'temp_images.*' => 'string'
        ]);

        $activity = Activity::create($validated);

        // Move any temp images to final location
        if ($request->filled('temp_images')) {
            $this->persistTempImages($request->input('temp_images', []), $activity);
        }

        // Still support direct uploads if you also submit images[]
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
        // CHANGED: accept temp_images[] here as well
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
            'temp_images' => 'nullable|array',
            'temp_images.*' => 'string'
        ]);

        $activity->update($validated);

        if ($request->has('remove_images')) {
            $this->removeImages($request->remove_images);
        }

        // Move temp images added during edit
        if ($request->filled('temp_images')) {
            $this->persistTempImages($request->input('temp_images', []), $activity);
        }

        // Plus any newly uploaded files directly
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
     * Handle multiple image uploads (classic non-temp)
     */
    private function handleImageUploads(array $images, Activity $activity): void
    {
        $sortOrder = (int) $activity->images()->max('sort_order');
        $sortOrder = $sortOrder ? $sortOrder + 1 : 1;

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
     * CHANGED: Persist temp images saved on the public disk (tmp/uuid/filename)
     *          to the final folder and create ActivityImage records.
     */
    private function persistTempImages(array $tempPaths, Activity $activity): void
    {
        $disk = Storage::disk('public');

        $sortOrder = (int) $activity->images()->max('sort_order');
        $sortOrder = $sortOrder ? $sortOrder + 1 : 1;

        foreach ($tempPaths as $tempPath) {
            // Expecting paths like "tmp/{uuid}/file.jpg"
            if (!$tempPath || !$disk->exists($tempPath)) {
                continue; // skip missing/invalid
            }

            $orig = basename($tempPath);
            $finalFilename = Str::uuid() . '_' . $orig;
            $finalPath = 'activity-images/' . $finalFilename;

            // Move within the same disk
            $disk->move($tempPath, $finalPath);

            // Try to get size/mime (best-effort)
            $size = null;
            $mime = null;
            try { $size = $disk->size($finalPath); } catch (\Throwable $e) {}
            try { $mime = $disk->mimeType($finalPath); } catch (\Throwable $e) {}

            ActivityImage::create([
                'activity_id'   => $activity->id,
                'filename'      => $finalFilename,
                'original_name' => $orig,
                'path'          => $finalPath,
                'file_size'     => $size,
                'mime_type'     => $mime,
                'sort_order'    => $sortOrder++,
            ]);

            // OPTIONAL: clean now-empty tmp folder (tmp/{uuid})
            $dir = dirname($tempPath);
            try {
                $filesLeft = collect($disk->files($dir));
                if ($filesLeft->isEmpty()) {
                    $disk->deleteDirectory($dir);
                }
            } catch (\Throwable $e) {
                // ignore cleanup errors
            }
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

            if (
                $activity->externals()
                    ->where('externals.id', $external->id)
                    ->wherePivot('confirmed', true)
                    ->exists()
            ) {
                return back()->with('error', 'Je bent al ingeschreven voor deze activiteit als externe.');
            }

            $existing = $activity->externals()
                ->where('externals.id', $external->id)
                ->wherePivot('confirmed', false)
                ->first();

            $token = Str::random(32);

            if ($existing) {
                $activity->externals()->updateExistingPivot($external->id, [
                    'confirmation_token' => $token,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            } else {
                // Create new pending registration
                $activity->externals()->attach($external->id, [
                    'confirmed' => false,
                    'confirmation_token' => $token,
                ]);
            }

            Mail::to($external->email)->send(new ActivityConfirmationMail($activity, $external, $token));

            return back()->with('success', 'Nieuwe bevestigingsmail is verstuurd! Check je inbox om je inschrijving te bevestigen.');
        }

        $user = Auth::user();

        if ($activity->users()->where('user_id', $user->id)->exists()) {
            return back()->with('error', 'Je bent al ingeschreven voor deze activiteit.');
        }

        $activity->users()->attach($user->id);

        Mail::to($user->email)->send(new ActivityJoinedMail($activity, $user->first_name));

        return back()->with('success', 'Je bent nu ingeschreven voor de activiteit!');
    }

    public function myActivities(Request $request)
    {
        $user = Auth::user();

        $sortBy = $request->get('sort', 'start_time');
        $sortOrder = $request->get('order', 'asc');
        $search = $request->get('search');

        $query = $user->activities()->with(['users', 'externals', 'images']);

        // Apply search filter
        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', '%' . $search . '%')
                    ->orWhere('description', 'like', '%' . $search . '%')
                    ->orWhere('location', 'like', '%' . $search . '%');
            });
        }

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

    public function confirm($token)
    {
        $pivot = \DB::table('activity_external')->where('confirmation_token', $token)->first();

        if (!$pivot) {
            return redirect('/')->with('error', 'Ongeldige bevestigingslink.');
        }

        $leaveToken = Str::random(32);

        $external = \App\Models\External::find($pivot->external_id);
        $activity = \App\Models\Activity::find($pivot->activity_id);

        Mail::to($external->email)
            ->send(new ActivityJoinedMail($activity, $external->first_name, $leaveToken));

        \DB::table('activity_external')
            ->where('id', $pivot->id)
            ->update([
                'confirmed' => true,
                'confirmation_token' => null,
                'leave_token' => $leaveToken
            ]);

        return redirect('/')->with('success', 'Je inschrijving is bevestigd! Je ontvangt een mail met je deelname en een uitschrijf link.');
    }

    public function leaveExternal($token)
    {
        $pivot = \DB::table('activity_external')->where('leave_token', $token)->first();

        if (!$pivot) {
            return redirect('/')->with('error', 'Ongeldige uitschrijflink.');
        }

        $external = \App\Models\External::find($pivot->external_id);
        $activity = \App\Models\Activity::find($pivot->activity_id);

        // verwijder de koppeling
        \DB::table('activity_external')->where('id', $pivot->id)->delete();

        return redirect('/')->with('success', 'Je bent uitgeschreven voor de activiteit: ' . $activity->name);
    }
  
    public function duplicate(Activity $activity)
    {
        $activity->load('images');

        $prefill = [
            'name'             => $activity->name,
            'location'         => $activity->location,
            'includes_food'    => (bool) $activity->includes_food,
            'description'      => $activity->description,
            'start_time'       => $activity->start_time,
            'end_time'         => $activity->end_time,
            'cost'             => $activity->cost,
            'max_participants' => $activity->max_participants,
            'min_participants' => $activity->min_participants,
            'image'            => $activity->image,
            'requirements'     => $activity->requirements,
        ];

        $tempImages  = $this->stageImagesAsTemp($activity);
        $duplicateOf = $activity->id;

        // prevent stale "old()" from overriding our prefill
        session()->forget('_old_input');
        session()->forget('errors');

        return view('activity.create', compact('prefill', 'tempImages', 'duplicateOf'));
    }


    /**
     * Copy Activity images to public/tmp/{uuid}/ and return their tmp paths.
     */
    private function stageImagesAsTemp(Activity $activity): array
    {
        $disk = Storage::disk('public');
        $uuid = (string) Str::uuid();
        $baseTmp = "tmp/{$uuid}";

        $disk->makeDirectory($baseTmp);
        $paths = [];

        foreach ($activity->images as $img) {
            if (!$img->path || !$disk->exists($img->path)) {
                continue;
            }
            $tmpFilename = $img->filename ?: (Str::uuid() . '.jpg');
            $tmpPath = "{$baseTmp}/{$tmpFilename}";

            // copy original to tmp
            $disk->copy($img->path, $tmpPath);
            $paths[] = $tmpPath;
        }

        return $paths;
    }
}
