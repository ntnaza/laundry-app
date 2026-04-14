<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;
use Illuminate\Support\Str;
use Exception;

class GoogleController extends Controller
{
    /**
     * Redirect ke Google Login
     */
    public function redirectToGoogle()
    {
        return Socialite::driver('google')->redirect();
    }

    /**
     * Handle Callback dari Google
     */
    public function handleGoogleCallback()
    {
        try {
            $googleUser = Socialite::driver('google')->user();
            
            // Cari user berdasarkan google_id atau email
            $user = User::where('google_id', $googleUser->id)
                        ->orWhere('email', $googleUser->email)
                        ->first();

            if ($user) {
                // Jika user ada tapi belum punya google_id (misal daftar manual sebelumnya)
                if (!$user->google_id) {
                    $user->update([
                        'google_id' => $googleUser->id,
                        'avatar' => $googleUser->avatar,
                    ]);
                }
                
                Auth::login($user);
            } else {
                // Jika user belum ada, buat baru
                $newUser = User::create([
                    'name' => $googleUser->name,
                    'email' => $googleUser->email,
                    'google_id' => $googleUser->id,
                    'avatar' => $googleUser->avatar,
                    'password' => null, // Biar gak bisa login manual kecuali reset pass
                    'role' => 'customer', // Default sebagai customer
                    'email_verified_at' => now(), // Google sudah verifikasi emailnya
                ]);

                Auth::login($newUser);
            }

            // Redirect sesuai role (Sama seperti RegisterController)
            $user = Auth::user();
            $role = trim($user->role);

            if ($role == 'admin' || $role == 'staff' || $role == 'owner') {
                return redirect()->route('dashboard');
            } elseif ($role == 'driver') {
                return redirect()->route('driver.tasks');
            }

            return redirect()->route('customer.dashboard');

        } catch (Exception $e) {
            return redirect('/login')->with('error', 'Terjadi kesalahan saat login dengan Google: ' . $e->getMessage());
        }
    }
}
