<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;

class RoleMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, ...$roles): Response
    {
        // Cek apakah user sudah login?
        if (!Auth::check()) {
            return redirect('/login');
        }

        // Cek apakah Role user ada di dalam daftar yang dibolehkan?
        $userRole = Auth::user()->role;

        if (in_array($userRole, $roles)) {
            return $next($request); // Silakan masuk
        }

        // Kalau tidak punya akses
        abort(403, 'Maaf, Anda tidak punya akses ke halaman ini.');
    }
}