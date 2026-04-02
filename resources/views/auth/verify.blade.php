@php
    $setting = \App\Models\Setting::first();
@endphp

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verifikasi Email - {{ $setting->shop_name ?? 'Laundry System' }}</title>
    
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

        .btn-verify {
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
        .btn-verify:hover {
            transform: translateY(-2px);
            box-shadow: 0 15px 30px rgba(37, 99, 235, 0.3);
            color: white;
        }

        .auth-right {
            background: url('https://images.unsplash.com/photo-1545173168-9f1947eebb7f?q=80&w=2071&auto=format&fit=crop');
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

        .icon-box {
            width: 80px;
            height: 80px;
            background: #eff6ff;
            color: var(--primary);
            border-radius: 20px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 2.5rem;
            margin-bottom: 30px;
        }

        /* Fix Icon Alignment */
        .icon-box i, .btn-verify i {
            line-height: 1 !important;
            display: flex !important;
            align-items: center;
            justify-content: center;
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
                    <div class="icon-box">
                        <i class="bi bi-envelope-check"></i>
                    </div>

                    <h1 class="mb-3">Verifikasi Email Anda</h1>
                    <p class="text-muted mb-4 fs-5">
                        Sebelum melanjutkan, harap periksa email Anda untuk tautan verifikasi. 
                        Jika Anda tidak menerima email tersebut, klik tombol di bawah ini untuk mengirim ulang.
                    </p>

                    @if (session('resent'))
                        <div class="alert alert-success border-0 rounded-4 mb-4 shadow-sm d-flex align-items-center gap-3" role="alert">
                            <i class="bi bi-check-circle-fill fs-4"></i>
                            <div>
                                Tautan verifikasi baru telah dikirim ke alamat email Anda.
                            </div>
                        </div>
                    @endif

                    <form class="d-inline" method="POST" action="{{ route('verification.resend') }}">
                        @csrf
                        <button type="submit" class="btn-verify">
                            KIRIM ULANG VERIFIKASI <i class="bi bi-send-fill"></i>
                        </button>
                    </form>

                    <div class="text-center mt-5">
                        <form action="{{ route('logout') }}" method="POST" class="d-inline">
                            @csrf
                            <button type="submit" class="btn btn-link text-muted text-decoration-none small fw-bold">
                                <i class="bi bi-box-arrow-left me-1"></i> Keluar & Daftar Ulang
                            </button>
                        </form>
                    </div>
                </div>
            </div>
            
            <div class="col-lg-7 d-none d-lg-block">
                <div class="auth-right">
                    <div class="overlay">
                        <h2 class="text-white mb-3">Keamanan Adalah Prioritas</h2>
                        <p class="text-white-50 fs-5">Kami memastikan setiap akun terverifikasi untuk memberikan layanan terbaik dan perlindungan data yang maksimal bagi pelanggan kami.</p>
                    </div>
                </div>
            </div>

        </div>
    </div>
</body>
</html>