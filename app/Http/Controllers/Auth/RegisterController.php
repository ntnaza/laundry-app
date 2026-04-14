<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    use RegistersUsers;

    /**
     * Show the application registration form.
     *
     * @return \Illuminate\View\View
     */
    public function showRegistrationForm()
    {
        // Ambil 1 testimoni acak bintang >= 4
        $testimonial = \App\Models\Testimonial::with('user')
                        ->where('rate', '>=', 4)
                        ->inRandomOrder()
                        ->first();

        return view('auth.register', compact('testimonial'));
    }

    /**
     * Where to redirect users after registration.
     *
     * @var string
     */
    public function redirectTo()
    {
        $user = auth()->user();
        $role = trim($user->role);

        // Kalau yang daftar itu Boss/Karyawan -> Ke Admin Panel
        if ($role == 'admin' || $role == 'staff' || $role == 'owner') {
            return route('dashboard');
        }
        
        // Kalau Driver -> Ke Area Kurir
        if ($role == 'driver') {
            return route('driver.tasks');
        }
        
        // Kalau Customer -> Ke Halaman Pelanggan
        return route('customer.dashboard');
    }

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return \App\Models\User
     */
    protected function create(array $data)
{
    return User::create([
        'name' => $data['name'],
        'email' => $data['email'],
        'password' => Hash::make($data['password']),
        'role' => 'customer', // <--- INI KUNCINYA! Paksa jadi customer
    ]);
}
}
