@php
    $setting = \App\Models\Setting::first();
@endphp

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar - {{ $setting->shop_name ?? 'Laundry System' }}</title>
    
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
            padding: 40px 80px; 
            display: flex;
            flex-direction: column;
            justify-content: center;
            height: 100%;
            position: relative;
            overflow-y: auto; 
        }

        /* --- THE ABSOLUTE FIX: FLEXBOX ARCHITECTURE (ANTI-DENGDEK) --- */
        
        .input-group-premium {
            display: flex;              
            align-items: center;        
            background-color: #f1f5f9;
            border: 1px solid transparent;
            border-radius: 50px;
            height: 54px;               
            padding: 0 5px;             
            width: 100%;
            transition: all 0.3s ease;
            overflow: hidden;
        }

        .input-group-premium:focus-within {
            background-color: #fff;
            border-color: var(--primary);
            box-shadow: 0 0 0 4px rgba(37, 99, 235, 0.1);
        }

        .input-icon-wrapper {
            width: 45px;            
            height: 100%;           
            display: flex;          
            align-items: center;    
            justify-content: center;
            color: #94a3b8;
            font-size: 1.2rem;
            flex-shrink: 0;         
        }

        .input-icon-wrapper i, 
        .input-icon-wrapper .bi {
            line-height: 1 !important;
            vertical-align: middle !important;
            display: flex !important; 
            margin: 0 !important;
            padding: 0 !important;
        }

        .input-field {
            flex: 1;                
            background: transparent;
            border: none;
            height: 100%;           
            color: var(--dark);
            font-size: 1rem;
            font-weight: 500;
            outline: none;
            padding: 0 15px 0 0;    
            margin: 0;
            line-height: normal; 
        }
        
        .input-field:-webkit-autofill,
        .input-field:-webkit-autofill:hover, 
        .input-field:-webkit-autofill:focus {
            -webkit-box-shadow: 0 0 0px 1000px #f1f5f9 inset;
            transition: background-color 5000s ease-in-out 0s;
        }
        .input-group-premium:focus-within .input-field:-webkit-autofill {
            -webkit-box-shadow: 0 0 0px 1000px #fff inset;
        }

        /* Tombol Daftar */
        .btn-register {
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
        .btn-register:hover {
            transform: translateY(-2px);
            box-shadow: 0 15px 30px rgba(37, 99, 235, 0.3);
            color: white;
        }

        /* Kanan: Image Area (GAMBAR BARU YANG LEBIH STABIL) */
        .auth-right {
            /* Gambar: Clean Aesthetic Laundry Room */
            background: url('https://images.unsplash.com/photo-1567113463300-102a7eb3cb26?q=80&w=2070&auto=format&fit=crop');
            background-size: cover;
            background-position: center;
            position: relative;
            height: 100%;
        }
        .overlay {
            position: absolute;
            top: 0; left: 0; right: 0; bottom: 0;
            background: linear-gradient(to bottom, rgba(15, 23, 42, 0.3), rgba(15, 23, 42, 0.9));
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
            display: flex;
            align-items: center;
            gap: 8px;
            transition: 0.3s;
            background: #f8fafc;
            padding: 8px 20px;
            border-radius: 30px;
            z-index: 10;
        }
        .btn-back:hover { color: var(--primary); background: #eff6ff; }
        
        .btn-back i { display: flex; align-items: center; line-height: 0; }

        @media (max-width: 991px) {
            .auth-left { padding: 40px; overflow-y: auto; }
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

                    <div class="mb-4 mt-5 pt-3">
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
                        <h1 class="mb-2">Buat Akun Baru</h1>
                        <p class="text-muted">Bergabunglah dan nikmati kemudahan laundry antar-jemput.</p>
                    </div>

                    <form action="{{ route('register') }}" method="POST">
                        @csrf
                        
                        <div class="mb-3">
                            <div class="input-group-premium @error('name') border border-danger @enderror">
                                <div class="input-icon-wrapper">
                                    <i class="bi bi-person"></i>
                                </div>
                                <input type="text" name="name" class="input-field" placeholder="Nama Lengkap" value="{{ old('name') }}" required>
                            </div>
                            @error('name')
                                <small class="text-danger ps-3 mt-1 d-block">{{ $message }}</small>
                            @enderror
                        </div>

                        <div class="mb-3">
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

                        <div class="mb-3">
                            <div class="input-group-premium @error('password') border border-danger @enderror">
                                <div class="input-icon-wrapper">
                                    <i class="bi bi-lock"></i>
                                </div>
                                <input type="password" name="password" class="input-field" placeholder="Buat Kata Sandi" required>
                            </div>
                            @error('password')
                                <small class="text-danger ps-3 mt-1 d-block">{{ $message }}</small>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <div class="input-group-premium">
                                <div class="input-icon-wrapper">
                                    <i class="bi bi-shield-lock"></i>
                                </div>
                                <input type="password" name="password_confirmation" class="input-field" placeholder="Ulangi Kata Sandi" required>
                            </div>
                        </div>

                        <button class="btn-register">DAFTAR SEKARANG <i class="bi bi-arrow-right"></i></button>
                    </form>

                    <div class="text-center mt-4 mb-4">
                        <p class="text-muted small">Sudah punya akun? <a href="{{ route('login') }}" class="text-primary fw-bold text-decoration-none">Masuk Disini</a></p>
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
                                <h2 class="text-white mb-3">"Solusi Cerdas Laundry!"</h2>
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
                                    <small class="text-white-50">Member Setia</small>
                                </div>
                            </div>
                        @else
                            <div class="mb-4">
                                <h2 class="text-white mb-3">Gabung Sekarang!</h2>
                                <p class="text-white-50 fs-5">Nikmati kemudahan layanan laundry premium dengan harga terjangkau.</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

        </div>
    </div>
</body>
</html>