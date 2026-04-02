@php
    $setting = \App\Models\Setting::first();
@endphp

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Atur Ulang Password - {{ $setting->shop_name ?? 'Laundry System' }}</title>
    
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

        .auth-left {
            padding: 50px 80px;
            display: flex;
            flex-direction: column;
            justify-content: center;
            height: 100%;
            position: relative;
        }

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

        /* Fix Icon Alignment */
        .input-icon-wrapper i, .btn-reset i {
            line-height: 1 !important;
            display: flex !important;
            align-items: center;
            justify-content: center;
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
            padding: 0 10px 0 0;    
        }

        .btn-reset {
            background: linear-gradient(135deg, var(--primary), var(--primary-dark));
            color: white;
            border: none;
            height: 54px; 
            border-radius: 50px;
            font-weight: 700;
            letter-spacing: 0.5px;
            transition: 0.3s;
            width: 100%;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
            box-shadow: 0 10px 20px rgba(37, 99, 235, 0.2);
        }
        .btn-reset:hover {
            transform: translateY(-2px);
            box-shadow: 0 15px 30px rgba(37, 99, 235, 0.3);
            color: white;
        }

        .auth-right {
            background: url('https://images.unsplash.com/photo-1635839735313-088852f8217f?q=80&w=2070&auto=format&fit=crop');
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
                    <div class="mb-5 mt-4">
                        <h1 class="mb-2">Buat Password Baru</h1>
                        <p class="text-muted">Langkah terakhir untuk mengamankan kembali akun Anda. Pastikan password baru Anda kuat dan mudah diingat.</p>
                    </div>

                    <form method="POST" action="{{ route('password.update') }}">
                        @csrf
                        <input type="hidden" name="token" value="{{ $token }}">
                        
                        {{-- Email Field (ReadOnly) --}}
                        <div class="mb-3">
                            <div class="input-group-premium">
                                <div class="input-icon-wrapper">
                                    <i class="bi bi-envelope"></i>
                                </div>
                                <input type="email" name="email" class="input-field" value="{{ $email ?? old('email') }}" readonly required>
                            </div>
                        </div>

                        {{-- Password Baru --}}
                        <div class="mb-3">
                            <div class="input-group-premium @error('password') border border-danger @enderror">
                                <div class="input-icon-wrapper">
                                    <i class="bi bi-lock"></i>
                                </div>
                                <input type="password" name="password" id="password" class="input-field" placeholder="Password Baru" required autofocus>
                                <div class="input-icon-wrapper" style="cursor: pointer;" onclick="togglePassword('password', this)">
                                    <i class="bi bi-eye"></i>
                                </div>
                            </div>
                            @error('password')
                                <small class="text-danger ps-3 mt-1 d-block">{{ $message }}</small>
                            @enderror
                        </div>

                        {{-- Konfirmasi Password --}}
                        <div class="mb-4">
                            <div class="input-group-premium">
                                <div class="input-icon-wrapper">
                                    <i class="bi bi-shield-lock"></i>
                                </div>
                                <input type="password" name="password_confirmation" id="password_confirmation" class="input-field" placeholder="Ulangi Password Baru" required>
                                <div class="input-icon-wrapper" style="cursor: pointer;" onclick="togglePassword('password_confirmation', this)">
                                    <i class="bi bi-eye"></i>
                                </div>
                            </div>
                        </div>

                        <button type="submit" class="btn-reset">
                            SIMPAN PASSWORD BARU <i class="bi bi-check-circle-fill"></i>
                        </button>
                    </form>
                </div>
            </div>
            
            <div class="col-lg-7 d-none d-lg-block">
                <div class="auth-right">
                    <div class="overlay">
                        <h2 class="text-white mb-3">Keamanan Tanpa Kompromi</h2>
                        <p class="text-white-50 fs-5">Layanan kami melindungi setiap akun dengan standar keamanan modern agar transaksi Anda selalu aman.</p>
                    </div>
                </div>
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
</body>
</html>