<?php

namespace App\Http\Controllers\Auth;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Show the application's login form.
     *
     * @return \Illuminate\View\View
     */
    public function showLoginForm()
    {
        // Ambil 1 testimoni acak bintang >= 4
        $testimonial = \App\Models\Testimonial::with('user')
                        ->where('rate', '>=', 4)
                        ->inRandomOrder()
                        ->first();

        return view('auth.login', compact('testimonial'));
    }

    protected function credentials(Request $request)
    {
        return array_merge($request->only($this->username(), 'password'), ['is_active' => 1]);
    }

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected function authenticated(Request $request, $user)
{
    if ($user->role == 'admin' || $user->role == 'staff' || $user->role == 'owner') {
        return redirect()->route('dashboard'); // Ke Admin Panel
    } elseif ($user->role == 'driver') {
        return redirect()->route('driver.tasks'); // Ke Area Kurir
    } else {
        return redirect()->route('customer.dashboard'); // Ke Area Pelanggan
    }
}

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
        $this->middleware('auth')->only('logout');
    }
}
