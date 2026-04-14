@php
    $setting = \App\Models\Setting::first();
@endphp

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verifikasi Akun - {{ $setting->shop_name ?? 'Laundry System' }}</title>
    
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
            background-color: #F8FAFC;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }

        h1, h2, h3, h4, h5 { font-family: 'Outfit', sans-serif; font-weight: 700; color: var(--dark); }

        .verify-card {
            background: white;
            border-radius: 24px;
            box-shadow: 0 20px 40px rgba(0,0,0,0.05);
            width: 100%;
            max-width: 450px;
            padding: 40px;
            text-align: center;
        }

        .icon-box {
            width: 80px;
            height: 80px;
            background: rgba(37, 99, 235, 0.1);
            color: var(--primary);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 24px;
            font-size: 2.5rem;
        }

        .otp-input {
            width: 100%;
            height: 54px;
            border-radius: 12px;
            border: 2px solid #e2e8f0;
            text-align: center;
            font-size: 1.5rem;
            font-weight: 800;
            letter-spacing: 10px;
            transition: 0.3s;
            background: #f8fafc;
            color: var(--dark);
        }

        .otp-input:focus {
            border-color: var(--primary);
            background: white;
            outline: none;
            box-shadow: 0 0 0 4px rgba(37, 99, 235, 0.1);
        }

        .btn-verify {
            background: linear-gradient(135deg, var(--primary), var(--primary-dark));
            color: white;
            border: none;
            height: 50px;
            border-radius: 12px;
            font-weight: 700;
            width: 100%;
            margin-top: 20px;
            box-shadow: 0 10px 20px rgba(37, 99, 235, 0.2);
            transition: 0.3s;
        }

        .btn-verify:hover {
            transform: translateY(-2px);
            box-shadow: 0 15px 30px rgba(37, 99, 235, 0.3);
        }

        .resend-link {
            color: var(--primary);
            text-decoration: none;
            font-weight: 700;
            font-size: 0.9rem;
        }

        .resend-link:hover { text-decoration: underline; }
    </style>
</head>

<body>
    <div class="verify-card">
        <div class="icon-box">
            <i class="bi bi-shield-check"></i>
        </div>
        
        <h2>Verifikasi Akun</h2>
        <p class="text-muted small mb-4">Kami telah mengirimkan 6 digit kode verifikasi ke email <strong>{{ auth()->user()->email }}</strong>.</p>

        @if (session('resent'))
            <div class="alert alert-success border-0 rounded-3 small mb-4" role="alert">
                <i class="bi bi-check-circle-fill me-2"></i> Kode baru telah dikirimkan ke email Anda.
            </div>
        @endif

        <form method="POST" action="{{ route('verification.verify') }}">
            @csrf
            <div class="mb-3">
                <input type="text" name="code" class="otp-input @error('code') border-danger @enderror" 
                       placeholder="000000" maxlength="6" autocomplete="off" autofocus required>
                @error('code')
                    <small class="text-danger d-block mt-2 fw-bold">{{ $message }}</small>
                @enderror
            </div>

            <button type="submit" class="btn-verify">VERIFIKASI SEKARANG</button>
        </form>

        <div class="mt-4 pt-3 border-top">
            <p class="text-muted small mb-2">Tidak menerima kode?</p>
            <form method="POST" action="{{ route('verification.resend') }}" class="d-inline">
                @csrf
                <button type="submit" class="btn btn-link resend-link p-0 border-0 bg-transparent align-baseline">
                    Kirim Ulang Kode
                </button>
            </form>
        </div>

        <div class="mt-3">
            <a href="{{ url('/') }}" class="text-muted small text-decoration-none">
                <i class="bi bi-arrow-left"></i> Kembali ke Beranda
            </a>
        </div>
    </div>

    <script>
        // Batasi input hanya angka
        document.querySelector('.otp-input').addEventListener('input', function(e) {
            this.value = this.value.replace(/[^0-9]/g, '');
        });
    </script>
</body>
</html>
