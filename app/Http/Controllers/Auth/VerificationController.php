<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class VerificationController extends Controller
{
    /**
     * Tampilkan Halaman Input Kode
     */
    public function show(Request $request)
    {
        return $request->user()->hasVerifiedEmail()
                        ? redirect($this->redirectPath())
                        : view('auth.verify');
    }

    /**
     * Verifikasi Kode yang diinput
     */
    public function verify(Request $request)
    {
        $request->validate([
            'code' => 'required|string|size:6',
        ]);

        $user = $request->user();

        if ($request->code == $user->verification_code) {
            $user->markEmailAsVerified();
            $user->update(['verification_code' => null]);

            return redirect($this->redirectPath())->with('verified', true);
        }

        return back()->withErrors(['code' => 'Kode verifikasi yang Anda masukkan salah.']);
    }

    /**
     * Kirim Ulang Kode
     */
    public function resend(Request $request)
    {
        if ($request->user()->hasVerifiedEmail()) {
            return redirect($this->redirectPath());
        }

        $request->user()->generateVerificationCode();
        $request->user()->sendEmailVerificationNotification();

        return back()->with('resent', true);
    }

    /**
     * Jalur Redirect
     */
    protected function redirectPath()
    {
        if (Auth::user()->role == 'admin' || Auth::user()->role == 'staff' || Auth::user()->role == 'owner') {
            return route('dashboard');
        }
        return route('customer.dashboard');
    }

    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('throttle:6,1')->only('verify', 'resend');
    }
}
