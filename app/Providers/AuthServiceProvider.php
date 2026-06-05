<?php

namespace App\Providers;

use App\Models\User;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        // Super-admins implicitly pass every gate.
        Gate::before(function (User $user, string $ability) {
            return $user->hasRole(User::ROLE_ADMIN) ? true : null;
        });

        // Register one gate per permission, backed by the role => permission map.
        foreach (array_keys(User::PERMISSIONS) as $permission) {
            Gate::define($permission, fn (User $user) => $user->hasPermission($permission));
        }
    }
}
