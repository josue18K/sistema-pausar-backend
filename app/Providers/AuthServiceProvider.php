<?php

namespace App\Providers;

// use Illuminate\Support\Facades\Gate;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The model to policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        //
    ];

    /**
     * Register any authentication / authorization services.
     */
    public function boot(): void
    {
        Gate::define('es-admin', function ($user) {
            return $user->rol === 'admin';
        });

        Gate::define('es-almacen', function ($user) {
            return $user->rol === 'almacen';
        });

        Gate::define('es-responsable', function ($user) {
            return $user->rol === 'responsable';
        });

        Gate::define('es-docente', function ($user) {
            return $user->rol === 'docente';
        });

        Gate::define('es-auditor', function ($user) {
            return $user->rol === 'auditor';
        });
    }
}
