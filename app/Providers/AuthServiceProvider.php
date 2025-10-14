<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;

class AuthServiceProvider extends ServiceProvider
{
    protected $policies = [
        // 'App\Models\Model' => 'App\Policies\ModelPolicy',
    ];

    public function boot(): void
    {
        Gate::define('manage-users', fn ($user) => $user && $user->role === 'admin');
        Gate::define('manage-activities', fn ($user) => $user && $user->role === 'admin');

        // (optioneel) alle abilities toestaan voor admins:
        // Gate::before(fn($user, $ability) => $user->role === 'admin' ? true : null);
    }
}
