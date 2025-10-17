<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Validation\ValidationException;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->get('search');

        $query = User::where('role', '!=', 'admin');

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('first_name', 'like', "%{$search}%")
                ->orWhere('last_name', 'like', "%{$search}%")
                ->orWhere('email', 'like', "%{$search}%")
                ->orWhere('job_title', 'like', "%{$search}%");
            });
        }

        $users = $query->orderBy('first_name')->paginate(10);
        $users->appends($request->query());

        return view('users.index', compact('users'));
    }

    public function create()
    {
        return view('users.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'first_name' => ['required', 'string', 'max:255'],
            'last_name'  => ['required', 'string', 'max:255'],
            'email'      => ['required', 'string', 'max:255', 'email', 'unique:users,email'],
            'job_title'  => ['required', 'string', 'max:255'],
            'is_admin'   => ['nullable', 'boolean'],
        ], [
            'first_name.required' => 'Voornaam is verplicht.',
            'last_name.required'  => 'Achternaam is verplicht.',
            'email.required'      => 'E-mailadres is verplicht.',
            'email.email'         => 'Vul een geldig e-mailadres in.',
            'email.unique'        => 'Dit e-mailadres is al in gebruik.',
            'job_title.required'  => 'Functietitel is verplicht.',
        ]);

        try {
            $user = new User();
            $user->first_name = $validated['first_name'];
            $user->last_name  = $validated['last_name'];
            $user->email      = $validated['email'];
            $user->job_title  = $validated['job_title'];
            $user->role       = $request->has('is_admin') && $request->is_admin ? 'admin' : 'user';
            $user->activated  = false;

            $user->save();

            // reset link sturen naar aangemaakte gebruiker
            Password::sendResetLink(['email' => $user->email]);

            return redirect()->route('users.index')->with('success', 'Gebruiker succesvol aangemaakt en e-mail verzonden!');
        } catch (\Exception $e) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Er is iets misgegaan bij het aanmaken van de gebruiker. Probeer het opnieuw.' . $e);
        }
    }

    public function destroy(User $user)
    {
        try {
            $user->delete();
            return redirect()->route('users.index')->with('success', 'Gebruiker succesvol verwijderd!');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Er is iets misgegaan bij het verwijderen van de gebruiker.');
        }
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
        $validated = $request->validate([
            'first_name' => ['required', 'string', 'max:255'],
            'last_name'  => ['required', 'string', 'max:255'],
            'email'      => ['required', 'string', 'max:255', 'email', 'unique:users,email,' . $user->id],
            'job_title'  => ['required', 'string', 'max:255'],
            'is_admin'   => ['nullable', 'boolean'],
        ], [
            'first_name.required' => 'Voornaam is verplicht.',
            'last_name.required'  => 'Achternaam is verplicht.',
            'email.required'      => 'E-mailadres is verplicht.',
            'email.email'         => 'Vul een geldig e-mailadres in.',
            'email.unique'        => 'Dit e-mailadres is al in gebruik.',
            'job_title.required'  => 'Functietitel is verplicht.',
        ]);

        try {
            $user->first_name = $validated['first_name'];
            $user->last_name  = $validated['last_name'];
            $user->email      = $validated['email'];
            $user->job_title  = $validated['job_title'];
            $user->role       = $request->has('is_admin') && $request->is_admin ? 'admin' : 'user';
            $user->save();

            return redirect()->route('users.index')->with('success', 'Gebruiker succesvol bijgewerkt!');
        } catch (\Exception $e) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Er is iets misgegaan bij het bijwerken van de gebruiker.');
        }
    }
}
