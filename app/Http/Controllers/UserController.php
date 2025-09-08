<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function index()
    {
        return view('users.index', [
            'users' => User::where('role', '!=', 'admin')->get()
        ]);
    }

    public function create()
    {
        return view('users.create');
    }

    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'first_name' => ['required', 'string', 'max:255'],
                'last_name' => ['required', 'string', 'max:255'],
                'email' => ['required', 'string', 'max:255', 'email', 'unique:users,email'],
                'password' => ['required', 'string', 'min:8', 'confirmed'],
                'job_title' => ['required', 'string', 'max:255'],
                'role' => ['required', 'string', 'max:255'],
            ]);

            $user = new User();
            $user->first_name = $validated['first_name'];
            $user->last_name = $validated['last_name'];
            $user->email = $validated['email'];
            $user->password = Hash::make($validated['password']);
            $user->job_title = $validated['job_title'];
            $user->role = $validated['role'];

            $user->save();

            return redirect()->route('users.index')->with('success', 'User created!');
        } catch (\Exception $e) {
            dd($e);
        }
    }
}
