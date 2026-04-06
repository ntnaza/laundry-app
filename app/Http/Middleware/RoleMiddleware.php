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

        // Ambil Role user (pastikan bersih)
        $userRole = trim(Auth::user()->role);

        // Jika parameter dikirim sebagai satu string tunggal dengan koma (misal: "admin,staff")
        // Kita pecah dulu jadi array. Jika sudah array, kita biarkan.
        $flatRoles = [];
        foreach ($roles as $role) {
            if (str_contains($role, ',')) {
                $flatRoles = array_merge($flatRoles, explode(',', $role));
            } else {
                $flatRoles[] = $role;
            }
        }

        // Hapus spasi di tiap elemen role parameter
        $flatRoles = array_map('trim', $flatRoles);

        // Cek apakah Role user ada di dalam daftar yang dibolehkan?
        if (in_array($userRole, $flatRoles)) {
            return $next($request); // Silakan masuk
        }

        // Kalau tidak punya akses
        abort(403, 'Maaf, Anda tidak punya akses ke halaman ini. Role Anda: ' . $userRole . ', Dibutuhkan: ' . implode(', ', $flatRoles));
    }
}