<?php

namespace App\Http\Middleware;

use App\Providers\RouteServiceProvider;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class RedirectIfAuthenticated
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, string ...$guards): Response
    {
        $guards = empty($guards) ? [null] : $guards;

        foreach ($guards as $guard) {
            if (Auth::guard($guard)->check()) {
                // Get home path
                $homePath = RouteServiceProvider::getHomePath();

                // Debugging output to log the type and value
                logger()->info('Redirecting to home path', ['homePath' => $homePath, 'type' => gettype($homePath)]);

                // Ensure the path is a string
                return redirect((string) RouteServiceProvider::getHomePath());

            }
        }

        return $next($request);
    }
}
