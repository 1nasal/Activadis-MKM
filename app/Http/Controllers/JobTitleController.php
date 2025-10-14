<?php

namespace App\Http\Controllers;

use App\Models\JobTitle;
use Illuminate\Http\Request;

class JobTitleController extends Controller
{
    // GET /job-titles  -> JSON for dropdown
    public function index(Request $request)
    {
        $q = $request->get('q');
        $query = JobTitle::query()->orderBy('name');
        if ($q) {
            $query->where('name', 'like', "%{$q}%");
        }
        return response()->json($query->get(['id','name']));
    }

    // POST /job-titles -> add
    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => ['required','string','max:255','unique:job_titles,name'],
        ]);

        $title = JobTitle::create(['name' => trim($data['name'])]);

        return response()->json(['id' => $title->id, 'name' => $title->name], 201);
    }

    // DELETE /job-titles/{jobTitle} -> delete
    public function destroy(JobTitle $jobTitle)
    {
        $jobTitle->delete();
        return response()->json(['ok' => true]);
    }

    public function update(Request $request, \App\Models\JobTitle $jobTitle)
{
    $data = $request->validate([
        'name' => ['required','string','max:255','unique:job_titles,name,' . $jobTitle->id],
    ]);

    $old = $jobTitle->name;
    $jobTitle->update(['name' => trim($data['name'])]);

    return response()->json([
        'id' => $jobTitle->id,
        'name' => $jobTitle->name,
        'old_name' => $old,
    ]);
}

}
