<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class TempUploadController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'image' => ['required', 'image', 'max:2048'], // 2MB
        ]);

        // store on the PUBLIC disk so we can preview via URL
        $dir  = 'tmp/'.Str::uuid();
        $name = Str::uuid().'.'.$request->file('image')->getClientOriginalExtension();
        $path = $request->file('image')->storeAs($dir, $name, 'public'); // e.g. tmp/uuid/uuid.jpg

        return response()->json([
            'path' => $path,
            'url'  => Storage::disk('public')->url($path), // /storage/tmp/uuid/uuid.jpg
        ]);
    }
}
