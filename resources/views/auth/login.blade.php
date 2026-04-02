@php
    $setting = \App\Models\Setting::first();
@endphp

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Masuk - {{ $setting->shop_name ?? 'Laundry System' }}</title>
    
    <link rel="icon" type="image/png" href="{{ asset('assets/static/images/logo/Laundry-app.png') }}?v=1.3">
    
    <link rel="stylesheet" href="{{ asset('assets/compiled/css/app.css') }}">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;700;800&family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">

    <style>
        :root {
            --primary: #2563EB;     
            --primary-dark: #1e40af;
            --dark: #0F172A;        
            --light: #F8FAFC;       
        }

        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background-color: #fff;
            height: 100vh;
            overflow: hidden;
        }

        h1, h2, h3, h4, h5 { font-family: 'Outfit', sans-serif; font-weight: 700; color: var(--dark); }

        /* Kiri: Form Area */
        .auth-left {
            padding: 50px 80px;
            display: flex;
            flex-direction: column;
            justify-content: center;
            height: 100%;
            position: relative;
        }

        /* --- THE ABSOLUTE FIX: FLEXBOX ARCHITECTURE --- */
        
        /* 1. Container Luar (Kapsul) */
        .input-group-premium {
            display: flex;              
            align-items: center;        /* Kunci Vertikal Center */
            background-color: #f1f5f9;
            border: 1px solid transparent;
            border-radius: 50px;
            height: 54px;               /* Tinggi Genap (Lebih mudah dibagi 2) */
            padding: 0 5px;             /* Padding dikit biar gak mepet border */
            width: 100%;
            transition: all 0.3s ease;
            overflow: hidden;
        }

        .input-group-premium:focus-within {
            background-color: #fff;
            border-color: var(--primary);
            box-shadow: 0 0 0 4px rgba(37, 99, 235, 0.1);
        }

        /* 2. Container Ikon (Kotak Persegi di Kiri) */
        .input-icon-wrapper {
            width: 45px;            /* Lebar fix */
            height: 100%;           /* Tinggi full ngikutin parent (54px) */
            display: flex;          
            align-items: center;    /* Center Y */
            justify-content: center;/* Center X */
            color: #94a3b8;
            font-size: 1.2rem;
            flex-shrink: 0;         /* Jangan sampe kegencet */
        }

        /* 3. Reset Ikon Bootstrap biar gak punya opinion sendiri */
        .input-icon-wrapper i, 
        .input-icon-wrapper .bi {
            line-height: 1 !important;
            vertical-align: middle !important;
            display: flex !important; 
            margin: 0 !important;
            padding: 0 !important;
        }

        /* 4. Input Field (Reset Total) */
        .input-field {
            flex: 1;                /* Ambil sisa ruang */
            background: transparent;
            border: none;
            height: 100%;           /* Full tinggi parent */
            color: var(--dark);
            font-size: 1rem;
            font-weight: 500;
            outline: none;
            
            /* RESET PADDING BAWAAN BROWSER */
            padding: 0 15px 0 0;    
            margin: 0;
            
            /* BIAR TEKS DI TENGAH SECARA ALAMI */
            line-height: normal; 
        }
        
        /* Reset Autofill Chrome */
        .input-field:-webkit-autofill,
        .input-field:-webkit-autofill:hover, 
        .input-field:-webkit-autofill:focus {
            -webkit-box-shadow: 0 0 0px 1000px #f1f5f9 inset;
            transition: background-color 5000s ease-in-out 0s;
        }
        .input-group-premium:focus-within .input-field:-webkit-autofill {
            -webkit-box-shadow: 0 0 0px 1000px #fff inset;
        }

        /* Tombol Login */
        .btn-login {
            background: linear-gradient(135deg, var(--primary), var(--primary-dark));
            color: white;
            border: none;
            height: 54px; 
            border-radius: 50px;
            font-weight: 700;
            letter-spacing: 0.5px;
            transition: 0.3s;
            width: 100%;
            box-shadow: 0 10px 20px rgba(37, 99, 235, 0.2);
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
        }
        .btn-login:hover {
            transform: translateY(-2px);
            box-shadow: 0 15px 30px rgba(37, 99, 235, 0.3);
            color: white;
        }

        /* Kanan: Image Area */
        .auth-right {
            background: url('https://images.unsplash.com/photo-1582735689369-4fe89db7114c?q=80&w=2070&auto=format&fit=crop');
            background-size: cover;
            background-position: center;
            position: relative;
            height: 100%;
        }
        .overlay {
            position: absolute;
            top: 0; left: 0; right: 0; bottom: 0;
            background: linear-gradient(to bottom, rgba(15, 23, 42, 0.4), rgba(15, 23, 42, 0.9));
            display: flex;
            flex-direction: column;
            justify-content: flex-end;
            padding: 60px;
            color: white;
        }

        .btn-back {
            position: absolute;
            top: 30px;
            left: 30px;
            text-decoration: none;
            color: #64748b;
            font-weight: 600;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            transition: 0.3s;
            background: #f8fafc;
            padding: 8px 20px;
            border-radius: 30px;
        }
        .btn-back:hover { color: var(--primary); background: #eff6ff; }
        
        /* Fix icon back */
        .btn-back i { 
            line-height: 1 !important;
            display: flex !important;
            align-items: center;
        }

        @media (max-width: 991px) {
            .auth-left { padding: 40px; }
            .auth-right { display: none; }
        }
    </style>
</head>

<body>
    <div class="container-fluid h-100 p-0">
        <div class="row h-100 g-0">
            
            <div class="col-lg-5 col-12">
                <div class="auth-left">
                    <a href="{{ url('/') }}" class="btn-back">
                        <i class="bi bi-arrow-left"></i> Kembali
                    </a>

                    <div class="mb-5 mt-4">
                        @if($setting && $setting->logo)
                            <div class="mb-4">
                                <img src="{{ asset('storage/' . $setting->logo) }}" alt="Logo" style="width: 200px; height: auto;" class="d-block">
                            </div>
                        @else
                            <div class="d-flex align-items-center gap-2 mb-3">
                                <div class="rounded-3 d-flex align-items-center justify-content-center shadow-sm overflow-hidden" style="width: 40px; height: 40px;">
                                    <div class="bg-primary w-100 h-100 d-flex align-items-center justify-content-center text-white">
                                        <i class="bi bi-basket-fill fs-5"></i>
                                    </div>
                                </div>
                                <h3 class="m-0 text-primary fw-bold">{{ $setting->shop_name ?? 'LaundryKuy' }}</h3>
                            </div>
                        @endif
                        <h1 class="mb-2">Selamat Datang Kembali!</h1>
                        <p class="text-muted">Masukan kredensial akun Anda untuk mengakses dashboard.</p>
                    </div>

                    <form action="{{ route('login') }}" method="POST">
                        @csrf
                        
                        <div class="mb-4">
                            <div class="input-group-premium @error('email') border border-danger @enderror">
                                <div class="input-icon-wrapper">
                                    <i class="bi bi-envelope"></i>
                                </div>
                                <input type="email" name="email" class="input-field" placeholder="Alamat Email" value="{{ old('email') }}" required>
                            </div>
                            @error('email')
                                <small class="text-danger ps-3 mt-1 d-block">{{ $message }}</small>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <div class="input-group-premium">
                                <div class="input-icon-wrapper">
                                    <i class="bi bi-lock"></i>
                                </div>
                                <input type="password" name="password" id="password" class="input-field" placeholder="Kata Sandi" required>
                                <div class="input-icon-wrapper" style="cursor: pointer;" onclick="togglePassword('password', this)">
                                    <i class="bi bi-eye"></i>
                                </div>
                            </div>
                        </div>

                        <script>
                            function togglePassword(inputId, iconEl) {
                                const input = document.getElementById(inputId);
                                const icon = iconEl.querySelector('i');
                                if (input.type === 'password') {
                                    input.type = 'text';
                                    icon.classList.replace('bi-eye', 'bi-eye-slash');
                                } else {
                                    input.type = 'password';
                                    icon.classList.replace('bi-eye-slash', 'bi-eye');
                                }
                            }
                        </script>

                        <div class="d-flex justify-content-between align-items-center mb-5">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="remember" id="flexCheckDefault">
                                <label class="form-check-label text-muted small" for="flexCheckDefault">
                                    Ingat Saya
                                </label>
                            </div>
                            @if (Route::has('password.request'))
                                <a href="{{ route('password.request') }}" class="text-primary small fw-bold text-decoration-none">Lupa Password?</a>
                            @endif
                        </div>

                        <button class="btn-login">MASUK SEKARANG <i class="bi bi-arrow-right"></i></button>
                    </form>

                    <div class="text-center mt-5">
                        <p class="text-muted small">Belum punya akun? <a href="{{ route('register') }}" class="text-primary fw-bold text-decoration-none">Daftar Member</a></p>
                    </div>
                </div>
            </div>
            
            <div class="col-lg-7 d-none d-lg-block">
                <div class="auth-right">
                    <div class="overlay">
                        @if(isset($testimonial) && $testimonial)
                            <div class="mb-4">
                                <div class="d-flex gap-1 text-warning mb-2">
                                    @for($i=1; $i<=5; $i++)
                                        <i class="bi bi-star-fill {{ $i <= $testimonial->rate ? '' : 'text-white-50' }}"></i>
                                    @endfor
                                </div>
                                <h2 class="text-white mb-3">"Kualitas Laundry Terbaik!"</h2>
                                <p class="text-white-50 fs-5">"{{ $testimonial->content }}"</p>
                            </div>
                            <div class="d-flex align-items-center gap-3">
                                <div class="rounded-circle overflow-hidden border border-2 border-white" style="width: 50px; height: 50px;">
                                    @if($testimonial->user->avatar)
                                        <img src="{{ asset('storage/' . $testimonial->user->avatar) }}" class="w-100 h-100 object-fit-cover" alt="User">
                                    @else
                                        <div class="w-100 h-100 bg-white text-primary d-flex align-items-center justify-content-center fw-bold fs-5">
                                            {{ substr($testimonial->user->name, 0, 1) }}
                                        </div>
                                    @endif
                                </div>
                                <div>
                                    <h6 class="text-white mb-0 fw-bold">{{ $testimonial->user->name }}</h6>
                                    <small class="text-white-50">Pelanggan Setia</small>
                                </div>
                            </div>
                        @else
                            <div class="mb-4">
                                <h2 class="text-white mb-3">Solusi Laundry Cerdas</h2>
                                <p class="text-white-50 fs-5">Bergabunglah dengan ribuan pelanggan yang telah mempercayakan kebersihan pakaian mereka kepada kami.</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

        </div>
    </div>
</body>
</html>