<?php

namespace App\Providers;

use App\Models\Cart;
use App\Models\User; // <-- Pastikan ini ada
use App\Policies\CartPolicy;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate; // <-- TAMBAHKAN INI

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The model to policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        Cart::class => CartPolicy::class,
    ];

    /**
     * Register any authentication / authorization services.
     */
    public function boot(): void
    {
        // Mendefinisikan Gate 'is-admin'
        // Gate ini akan mengembalikan true jika role pengguna adalah 'admin'
        Gate::define('is-admin', function (User $user) {
            return $user->role === 'admin';
        });
    }
}
