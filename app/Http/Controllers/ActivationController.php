<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class ActivationController extends Controller
{
    public function show($token)
    {
        $user = User::where('activation_token', $token)
            ->where('activated', false)
            ->first();

        if (!$user) {
            return redirect()->route('login')->with('error', 'Ongeldige activatielink.');
        }

        if ($user->activation_token_expires_at && $user->activation_token_expires_at < now()) {
            return redirect()->route('login')->with('error', 'Deze activatielink is verlopen.');
        }

        return view('auth.activate', compact('user', 'token'));
    }

    public function activate(Request $request, $token)
    {
        $user = User::where('activation_token', $token)
            ->where('activated', false)
            ->first();

        if (!$user) {
            return redirect()->route('login')->with('error', 'Ongeldige activatielink.');
        }

        if ($user->activation_token_expires_at && $user->activation_token_expires_at < now()) {
            return redirect()->route('login')->with('error', 'Deze activatielink is verlopen.');
        }

        $validated = $request->validate([
            'password' => ['required', 'confirmed', Password::min(8)],
        ], [
            'password.required' => 'Wachtwoord is verplicht.',
            'password.confirmed' => 'Wachtwoorden komen niet overeen.',
            'password.min' => 'Wachtwoord moet minimaal 8 tekens bevatten.',
        ]);

        $user->password = Hash::make($validated['password']);
        $user->activated = true;
        $user->activation_token = null;
        $user->activation_token_expires_at = null;
        $user->save();

        return redirect()->route('login')->with('success', 'Account succesvol geactiveerd! Je kunt nu inloggen.');
    }
}