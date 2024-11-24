<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RedirectIfNotAdminOrEmployee
{
    public function handle(Request $request, Closure $next)
    {
        if (Auth::check() && (Auth::user()->role()->first()->name == 'Admin' || Auth::user()->role()->first()->name == 'Employee')) {
            return redirect()->route('dashboard');
        }

        return redirect()->route('home');
    }
}
