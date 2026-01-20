@php
    $setting = \App\Models\Setting::first();
@endphp

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - {{ $setting->shop_name ?? 'Laundry System' }}</title>
    
    <link rel="stylesheet" href="{{ asset('assets/compiled/css/app.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/compiled/css/app-dark.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/compiled/css/auth.css') }}">
</head>

<body>
    <script src="{{ asset('assets/static/js/initTheme.js') }}"></script>
    <div id="auth">
        <div class="row h-100">
            <div class="col-lg-5 col-12">
                <div id="auth-left">
                    <div class="auth-logo mb-4">
                        <a href="{{ url('/') }}" class="d-flex align-items-center gap-2 text-decoration-none">
                            @if($setting && $setting->logo)
                                <img src="{{ asset('storage/'.$setting->logo) }}" alt="Logo" style="height: 50px;">
                            @endif
                            <h3 class="m-0 text-primary">{{ $setting->shop_name ?? 'LaundryKuy' }}</h3>
                        </a>
                    </div>
                    
                    <h1 class="auth-title">Log in.</h1>
                    <p class="auth-subtitle mb-5">Masuk dengan data akun yang diberikan admin.</p>

                    <form action="{{ route('login') }}" method="POST">
                        @csrf
                        
                        <div class="form-group position-relative has-icon-left mb-4">
                            <input type="email" name="email" class="form-control form-control-xl @error('email') is-invalid @enderror" placeholder="Email" value="{{ old('email') }}" required>
                            <div class="form-control-icon">
                                <i class="bi bi-person"></i>
                            </div>
                            @error('email')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group position-relative has-icon-left mb-4">
                            <input type="password" name="password" class="form-control form-control-xl" placeholder="Password" required>
                            <div class="form-control-icon">
                                <i class="bi bi-shield-lock"></i>
                            </div>
                        </div>

                        <div class="form-check form-check-lg d-flex align-items-end">
                            <input class="form-check-input me-2" type="checkbox" name="remember" id="flexCheckDefault">
                            <label class="form-check-label text-gray-600" for="flexCheckDefault">
                                Ingat Saya
                            </label>
                        </div>
                        
                        <button class="btn btn-primary btn-block btn-lg shadow-lg mt-5">Masuk Aplikasi</button>
                    </form>
                    
                    <div class="text-center mt-5 text-lg fs-4">
                        <p class="text-gray-600">Pelanggan mau cek cucian? <a href="{{ url('/') }}" class="font-bold">Klik Disini</a>.</p>
                    </div>
                </div>
            </div>
            
            <div class="col-lg-7 d-none d-lg-block">
                <div id="auth-right" style="background: #435ebe; display: flex; align-items: center; justify-content: center;">
                   <div class="text-white text-center p-5">
                       <h2 class="text-white mb-4">Sistem Manajemen Laundry</h2>
                       <p class="lead">Kelola transaksi, pelanggan, dan laporan keuangan dalam satu pintu.</p>
                       <i class="bi bi-basket-fill" style="font-size: 10rem; opacity: 0.5;"></i>
                   </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>