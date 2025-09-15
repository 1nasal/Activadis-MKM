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
                'job_title' => ['required', 'string', 'max:255'],
                'role' => ['required', 'string', 'max:255'],
            ]);

            $user = new User();
            $user->first_name = $validated['first_name'];
            $user->last_name = $validated['last_name'];
            $user->email = $validated['email'];
            $user->job_title = $validated['job_title'];
            $user->role = $validated['role'];
            $user->activated = false;

            //hier moet hij reset link sturen
            // Password::sendResetLink(['email' => $user->email]);

            $user->save();

            return redirect()->route('users.index')->with('success', 'User created!');
        } catch (\Exception $e) {
            dd($e);
        }
    }

    public function destroy(User $user)
    {
        $user->delete();

        return redirect()->route('users.index')->with('success', 'User deleted!');
    }

    public function show($id)
    {
        $user = User::findOrFail($id);

        return view('users.show', compact('user'));
    }

    public function edit(User $user)
    {
        return view('users.edit', compact('user'));
    }

    public function update(Request $request, User $user)
    {
        try {
            $validated = $request->validate([
                'first_name' => ['required', 'string', 'max:255'],
                'last_name' => ['required', 'string', 'max:255'],
                'email' => ['required', 'string', 'max:255', 'email'],
                'job_title' => ['required', 'string', 'max:255'],
                'role' => ['required', 'string', 'max:255'],
            ]);

            $user->first_name = $validated['first_name'];
            $user->last_name = $validated['last_name'];
            $user->email = $validated['email'];
            $user->job_title = $validated['job_title'];
            $user->role = $validated['role'];
            $user->save();

            return redirect()->route('users.index')->with('success', 'User updated!');
        } catch (\Exception $e) {
            dd($e);
        }
    }
}
