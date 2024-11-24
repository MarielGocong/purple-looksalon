<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Auth;
use App\Providers\RouteServiceProvider;

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
    public function boot()
    {
        // Register a custom method to handle the home path
        app()->singleton('fortifyHomePath', function () {
            if (Auth::check() && (Auth::user()->role->name === 'Admin' || Auth::user()->role->name === 'Employee')) {
                return RouteServiceProvider::DASHBOARD;
            } else {
                return RouteServiceProvider::HOME;
            }
        });
    }
}
