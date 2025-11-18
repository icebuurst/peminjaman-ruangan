<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Gate;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Gate for admin
        Gate::define('isAdmin', function ($user) {
            return $user->role === 'admin';
        });

        // Gate for admin or petugas
        Gate::define('isAdminOrPetugas', function ($user) {
            return in_array($user->role, ['admin', 'petugas']);
        });

        // Gate for peminjam
        Gate::define('isPeminjam', function ($user) {
            return $user->role === 'peminjam';
        });
    }
}
