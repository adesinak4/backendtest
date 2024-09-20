<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * Register any authentication / authorization services.
     */
    public function boot()
    {
        $this->registerPolicies();

        // Define a gate to check for "maker" role
        Gate::define('is-maker', function ($user) {
            return $user->role === 'maker';
        });

        // Define a gate to check for "checker" role
        Gate::define('is-checker', function ($user) {
            return $user->role === 'checker';
        });
    }
}
