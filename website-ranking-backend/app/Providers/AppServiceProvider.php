<?php

namespace App\Providers;

use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        // Register application services here if needed.
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Define the admin gate
        Gate::define('admin', function ($user) {
            return $user->role === 'admin'; // Replace with your logic for identifying admins
        });
    }
}
