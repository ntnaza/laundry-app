<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;

class RedirectIfAuthenticated
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next, string ...$guards): Response
    {
        $guards = empty($guards) ? [null] : $guards;

        foreach ($guards as $guard) {
            if (Auth::guard($guard)->check()) {
                
                // --- LOGIKA KOH ENGKOH ---
                // Cek role user yang sedang login
                $user = Auth::user();

                if ($user->role === 'customer') {
                    // Kalau Customer -> Lempar ke Kandang Customer
                    return redirect()->route('customer.dashboard');
                }

                // Kalau Admin/Owner/Staff -> Lempar ke Dashboard Admin
                return redirect()->route('dashboard');
                // -------------------------
            }
        }

        return $next($request);
    }
}