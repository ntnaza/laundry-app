<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>LaundryKuy - #1 Premium Laundry Service</title>
    <link rel="icon" type="image/png" href="{{ asset('assets/static/images/logo/Laundry-app.png') }}?v=1.3">
    
    <link rel="stylesheet" href="{{ asset('assets/compiled/css/app.css') }}">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/toastify-js/src/toastify.min.css">
    {{-- Menggunakan CDN Bootstrap Icons terbaru untuk kompatibilitas lebih baik --}}
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;700;800&family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">

    <style>
        /* --- 1. SETTING VARIABEL & BASE FONT --- */
        :root {
            --primary: #435ebe;     
            --primary-dark: #374b9d;
            --dark: #0F172A;        
            --light: #F8FAFC;       
            --accent: #F59E0B;      
        }

        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            color: #475569;
            background-color: #fff;
            overflow-x: hidden;
            font-size: 0.95rem;
            line-height: 1.6;
        }

        h1, h2, h3, h4, h5, .font-heading { font-family: 'Outfit', sans-serif; font-weight: 700; color: var(--dark); letter-spacing: -0.5px; }
        
        .display-4 { font-size: 2.75rem; font-weight: 800; }
        .display-6 { font-size: 2rem; font-weight: 700; }

        /* --- GLOBAL ICON FIX V2 (LEBIH PRESISI) --- */
        /* Kita pastikan container yang membungkus icon dan teks selalu menggunakan flex center */
        .d-flex-center {
            display: flex;
            align-items: center;
        }
        
        /* Reset default line-height icon biar ga nambah spasi aneh */
        .bi {
            line-height: 1;
            display: inline-flex; /* Ganti ke inline-flex agar align-items berfungsi internal */
            align-items: center;
            justify-content: center;
            vertical-align: middle; 
            font-style: normal !important; /* Paksa NON-ITALIC */
        }

        /* Khusus untuk icon di dalam tombol atau badge */
        .btn .bi, .badge .bi {
             /* Hapus transform manual, biarkan flexbox yang mengatur */
             transform: none; 
        }


        /* --- 2. NAVBAR MAGIC --- */
        .navbar { 
            padding: 1rem 0; 
            background: transparent; 
            transition: all 0.4s ease;
            position: fixed;
            width: 100%;
            z-index: 1000;
        }

        .navbar-scrolled {
            background: rgba(255, 255, 255, 0.9);
            backdrop-filter: blur(15px);
            padding: 0.8rem 0;
            box-shadow: 0 5px 30px rgba(0,0,0,0.05);
        }

        .nav-link { font-weight: 600; color: var(--dark); margin: 0 1rem; font-size: 0.9rem; transition: 0.3s; }
        .nav-link:hover { color: var(--primary); }
        
        .btn-nav { padding: 0.5rem 1.5rem; font-size: 0.85rem; border-radius: 50px; font-weight: 600; transition: 0.3s; }
        .btn-login { border: 1px solid var(--primary); color: var(--primary); background: transparent; }
        .btn-login:hover { background: var(--primary); color: white; }
        .btn-signup { background: var(--primary); color: white; border: 1px solid var(--primary); box-shadow: 0 4px 12px rgba(37, 99, 235, 0.2); }
        .btn-signup:hover { background: var(--primary-dark); color: white; transform: translateY(-1px); }

        /* --- 3. HERO SECTION --- */
        .hero { padding: 160px 0 80px; position: relative; overflow: hidden; }
        .hero-bg-blob { position: absolute; top: -100px; right: -100px; width: 600px; height: 600px; background: radial-gradient(circle, rgba(37,99,235,0.05) 0%, rgba(255,255,255,0) 70%); z-index: -1; }
        
        .hero-img { 
            width: 100%; 
            border-radius: 30px; 
            box-shadow: 0 20px 40px rgba(0,0,0,0.08); 
            transform: perspective(1000px) rotateY(-5deg);
            transition: 0.5s;
        }
        .hero-img:hover { transform: perspective(1000px) rotateY(0deg); }

        /* --- 4. MARQUEE / KERETA BERJALAN --- */
        .marquee-section { padding: 30px 0; background: #fff; border-bottom: 1px solid #f1f5f9; overflow: hidden; white-space: nowrap; }
        .marquee-content { display: inline-block; animation: scroll 30s linear infinite; }
        .brand-logo { height: 35px; margin: 0 40px; opacity: 1; filter: none; transition: 0.3s; }
        .brand-logo:hover { transform: scale(1.1); }

        @keyframes scroll {
            0% { transform: translateX(0); }
            100% { transform: translateX(-50%); }
        }

        /* --- 5. HOW IT WORKS (ALUR) --- */
        .step-card { text-align: left; padding: 30px; border-radius: 20px; background: white; border: 1px solid #f1f5f9; height: 100%; transition: 0.3s; position: relative; overflow: hidden; }
        .step-card:hover { border-color: var(--primary); transform: translateY(-5px); box-shadow: 0 10px 30px rgba(37, 99, 235, 0.05); }
        .step-number { font-size: 3rem; font-weight: 800; color: #f1f5f9; position: absolute; top: 10px; right: 20px; line-height: 1; }
        /* Icon box dipastikan flex center sempurna */
        .step-icon { width: 50px; height: 50px; background: var(--primary); color: white; border-radius: 12px; display: flex; align-items: center; justify-content: center; font-size: 1.5rem; margin-bottom: 20px; position: relative; z-index: 2; }

        /* --- 6. TESTIMONIALS --- */
        .testi-card { background: #f8fafc; padding: 30px; border-radius: 20px; border: 1px solid transparent; transition: 0.3s; height: 100%; }
        .testi-card:hover { background: white; border-color: #e2e8f0; box-shadow: 0 10px 30px rgba(0,0,0,0.03); }
        .stars { color: var(--accent); font-size: 0.9rem; margin-bottom: 15px; }

        /* --- 7. GENERAL COMPONENTS --- */
        .section-padding { padding: 80px 0; }
        /* Feature card icon box dipastikan flex center sempurna */
        .feature-card { padding: 30px; border-radius: 20px; background: white; border: 1px solid #f1f5f9; transition: 0.3s; height: 100%; }
        .feature-card:hover { border-color: var(--primary); box-shadow: 0 15px 30px rgba(37, 99, 235, 0.05); transform: translateY(-5px); }
        .icon-box { width: 50px; height: 50px; border-radius: 12px; display: flex; align-items: center; justify-content: center; font-size: 1.5rem; margin-bottom: 20px; }
        
        .track-wrapper { background: white; padding: 40px; border-radius: 24px; box-shadow: 0 20px 60px rgba(0,0,0,0.05); border: 1px solid #f1f5f9; }
        .track-input { background: #f8fafc; border: 1px solid #e2e8f0; padding: 1rem 1.5rem; border-radius: 12px; width: 100%; font-size: 1rem; transition: 0.3s; }
        .track-input:focus { outline: none; border-color: var(--primary); background: white; }

        /* Footer Compact */
        footer { background: #f8fafc; padding: 60px 0 30px; border-top: 1px solid #e2e8f0; font-size: 0.9rem; }
        .footer-link { color: #64748b; text-decoration: none; margin-bottom: 10px; display: block; transition: 0.2s; }
        .footer-link:hover { color: var(--primary); transform: translateX(5px); }
    </style>
</head>

<body>

    <nav class="navbar navbar-expand-lg" id="mainNav">
        <div class="container">
            <a class="navbar-brand d-flex-center gap-2" href="#">
                @if(isset($setting) && $setting->logo)
                    <img src="{{ asset('storage/' . $setting->logo) }}" alt="Logo" style="height: 45px; width: auto; max-width: 200px;" class="object-fit-contain">
                @else
                    <div class="rounded-3 d-flex-center justify-content-center shadow-sm overflow-hidden" style="width: 36px; height: 36px;">
                        <div class="bg-primary w-100 h-100 d-flex align-items-center justify-content-center text-white">
                            <i class="bi bi-basket-fill fs-6"></i>
                        </div>
                    </div>
                    <span class="font-heading fs-5">{{ $setting->shop_name ?? 'LaundryKuy' }}</span>
                @endif
            </a>
            <button class="navbar-toggler border-0 shadow-none" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav mx-auto align-items-center">
                    <li class="nav-item"><a class="nav-link" href="#home">Beranda</a></li>
                    <li class="nav-item"><a class="nav-link" href="#features">Keunggulan</a></li>
                    <li class="nav-item"><a class="nav-link" href="#pricing">Harga</a></li>
                    <li class="nav-item"><a class="nav-link" href="#testimonials">Testimoni</a></li>
                </ul>
                <div class="d-flex gap-2">
                    @auth
                        @if(Auth::user()->role == 'customer')
                            <a href="{{ route('customer.dashboard') }}" class="btn btn-nav btn-signup">Dashboard</a>
                        @else
                            <a href="{{ route('dashboard') }}" class="btn btn-nav btn-signup">Admin Panel</a>
                        @endif
                    @else
                        <a href="{{ route('login') }}" class="btn btn-nav btn-login">Masuk</a>
                        <a href="{{ route('register') }}" class="btn btn-nav btn-signup">Daftar</a>
                    @endauth
                </div>
            </div>
        </div>
    </nav>

    <section id="home" class="hero">
        <div class="hero-bg-blob"></div>
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-6 mb-5 mb-lg-0" data-aos="fade-up">
                    <div class="d-inline-flex align-items-center gap-2 px-3 py-1 rounded-pill bg-white border shadow-sm mb-4">
                        <span class="badge bg-success rounded-pill">Baru</span>
                        <span class="small fw-bold text-muted">Layanan Express 6 Jam Aktif!</span>
                    </div>
                    <h1 class="display-4 mb-3">Laundry Bersih,<br>Hidup Lebih <span class="text-primary">Santai.</span></h1>
                    <p class="text-muted mb-4 pe-lg-5 fs-6">Platform laundry on-demand #1. Kami menjemput pakaian kotor dan mengantarkannya kembali bersih dalam 24 jam. Garansi higienis dan wangi.</p>
                    
                    <div class="d-flex gap-3">
                        <a href="#tracking" class="btn btn-primary rounded-pill px-4 py-3 fw-bold shadow-sm hover-top d-flex-center gap-2">
                            <i class="bi bi-search"></i> Lacak Pesanan
                        </a>
                        <a href="#pricing" class="btn btn-white border rounded-pill px-4 py-3 fw-bold shadow-sm text-dark hover-top">
                            Lihat Harga
                        </a>
                    </div>

                    <div class="d-flex align-items-center gap-4 mt-5">
                        <div class="d-flex align-items-center gap-2">
                            <h2 class="mb-0 fw-bold text-dark">{{ $totalCustomers > 1000 ? ($totalCustomers/1000).'k+' : $totalCustomers }}</h2>
                            <small class="text-muted lh-1">Pelanggan<br>Aktif</small>
                        </div>
                        <div class="vr opacity-25"></div>
                        <div class="d-flex align-items-center gap-2">
                            <h2 class="mb-0 fw-bold text-dark">{{ number_format($avgRating, 1) }}</h2>
                            <small class="text-muted lh-1">Rating<br>Layanan</small>
                        </div>
                    </div>
                </div>
                
                <div class="col-lg-6" data-aos="fade-left" data-aos-delay="100">
                    <div class="position-relative">
                        <img src="https://images.unsplash.com/photo-1521566652839-697aa473761a?q=80&w=2071&auto=format&fit=crop" class="hero-img" alt="Laundry Service">
                        
                        <div class="position-absolute bg-white p-3 rounded-4 shadow-lg d-flex align-items-center gap-3 animate__animated animate__fadeInUp" style="bottom: 30px; left: -30px; border: 1px solid #f1f5f9;">
                            <div class="bg-light-success text-success p-2 rounded-circle d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
                                <i class="bi bi-shield-check-fill fs-5"></i>
                            </div>
                            <div>
                                <h6 class="fw-bold mb-0">Terverifikasi</h6>
                                <small class="text-muted">ISO 9001 Certified</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="marquee-section">
        <div class="marquee-content">
            <img src="https://www.vectorlogo.zone/logos/samsung/samsung-ar21.svg" class="brand-logo" alt="Samsung">
            <img src="https://www.vectorlogo.zone/logos/lg/lg-ar21.svg" class="brand-logo" alt="LG">
            <img src="https://cdn.worldvectorlogo.com/logos/unilever-2.svg" class="brand-logo" alt="Unilever">
            <img src="https://cdn.worldvectorlogo.com/logos/panasonic-1.svg" class="brand-logo" alt="Panasonic">
            <img src="https://cdn.worldvectorlogo.com/logos/electrolux.svg" class="brand-logo" alt="Electrolux">
            <img src="https://static.cdnlogo.com/logos/r/4/rinso.svg" class="brand-logo" alt="Rinso">
            
            <!-- Loop -->
            <img src="https://www.vectorlogo.zone/logos/samsung/samsung-ar21.svg" class="brand-logo" alt="Samsung">
            <img src="https://www.vectorlogo.zone/logos/lg/lg-ar21.svg" class="brand-logo" alt="LG">
            <img src="https://cdn.worldvectorlogo.com/logos/unilever-2.svg" class="brand-logo" alt="Unilever">
            <img src="https://cdn.worldvectorlogo.com/logos/panasonic-1.svg" class="brand-logo" alt="Panasonic">
            <img src="https://cdn.worldvectorlogo.com/logos/electrolux.svg" class="brand-logo" alt="Electrolux">
            <img src="https://static.cdnlogo.com/logos/r/4/rinso.svg" class="brand-logo" alt="Rinso">
        </div>
    </section>

    <section id="features" class="section-padding">
        <div class="container">
            <div class="row justify-content-center text-center mb-5">
                <div class="col-lg-6">
                    <small class="text-primary fw-bold text-uppercase ls-1">Keunggulan Kami</small>
                    <h2 class="display-6 mt-2">Lebih Dari Sekadar Mencuci</h2>
                </div>
            </div>
            
            <div class="row g-4">
                <div class="col-md-4" data-aos="fade-up" data-aos-delay="100">
                    <div class="feature-card">
                        <div class="icon-box bg-light-primary text-primary">
                            <i class="bi bi-truck"></i>
                        </div>
                        <h5 class="fw-bold">Antar Jemput Gratis</h5>
                        <p class="text-muted small">Kurir kami siap menjemput dan mengantar cucian ke depan pintu rumah Anda tanpa biaya tambahan.</p>
                    </div>
                </div>
                <div class="col-md-4" data-aos="fade-up" data-aos-delay="200">
                    <div class="feature-card">
                        <div class="icon-box bg-light-warning text-warning">
                            <i class="bi bi-shield-check"></i>
                        </div>
                        <h5 class="fw-bold">Garansi Kehilangan</h5>
                        <p class="text-muted small">Kami memberikan asuransi ganti rugi hingga 10x lipat jika ada pakaian yang hilang atau rusak.</p>
                    </div>
                </div>
                <div class="col-md-4" data-aos="fade-up" data-aos-delay="300">
                    <div class="feature-card">
                        <div class="icon-box bg-light-success text-success">
                            <i class="bi bi-stopwatch"></i>
                        </div>
                        <h5 class="fw-bold">Tepat Waktu</h5>
                        <p class="text-muted small">Sistem kami terintegrasi untuk memastikan cucian selesai sesuai estimasi waktu yang dijanjikan.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    @if($activePromos->count() > 0)
    <section class="py-5 bg-light">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-10">
                    <div id="promoCarousel" class="carousel slide rounded-4 overflow-hidden shadow-lg" data-bs-ride="carousel">
                        <div class="carousel-indicators">
                            @foreach($activePromos as $key => $promo)
                                <button type="button" data-bs-target="#promoCarousel" data-bs-slide-to="{{ $key }}" class="{{ $key == 0 ? 'active' : '' }}" aria-current="{{ $key == 0 ? 'true' : 'false' }}" aria-label="Slide {{ $key + 1 }}"></button>
                            @endforeach
                        </div>
                        <div class="carousel-inner">
                            @foreach($activePromos as $key => $promo)
                                <div class="carousel-item {{ $key == 0 ? 'active' : '' }}">
                                    @if($promo->image)
                                        {{-- Tampilan JIKA ADA GAMBAR --}}
                                        <div class="position-relative" style="height: 400px;">
                                            <img src="{{ asset('storage/' . $promo->image) }}" class="d-block w-100 h-100 object-fit-cover" alt="Promo {{ $promo->code }}">
                                            <div class="position-absolute top-0 start-0 w-100 h-100 d-flex align-items-center" style="background: linear-gradient(90deg, rgba(0,0,0,0.7) 0%, rgba(0,0,0,0) 60%);">
                                                <div class="px-5 text-white col-lg-6" data-aos="fade-right">
                                                    <span class="badge bg-warning text-dark mb-3 animate__animated animate__fadeInDown">Promo Spesial</span>
                                                    <h2 class="display-5 fw-bold mb-3">{{ $promo->type == 'percentage' ? 'Diskon '.$promo->value.'%' : 'Potongan Rp '.number_format($promo->value/1000).'rb' }}</h2>
                                                    <p class="mb-4 lead">Gunakan kode: <span class="fw-bold text-warning font-monospace bg-dark px-2 rounded">{{ $promo->code }}</span></p>
                                                    <p class="small opacity-75 mb-4">{{ $promo->end_date ? 'Berlaku s/d '.\Carbon\Carbon::parse($promo->end_date)->format('d M Y') : 'Periode Terbatas' }}</p>
                                                    <a href="{{ route('register') }}" class="btn btn-primary rounded-pill px-4 fw-bold shadow-sm">Ambil Sekarang</a>
                                                </div>
                                            </div>
                                        </div>
                                    @else
                                        {{-- Tampilan JIKA TIDAK ADA GAMBAR (Fallback Gradient) --}}
                                        <div class="bg-primary p-5 text-white position-relative overflow-hidden" style="height: 400px; display: flex; align-items: center;">
                                            <div class="position-absolute rounded-circle bg-white opacity-10" style="width: 400px; height: 400px; top: -100px; right: -50px;"></div>
                                            <div class="position-absolute rounded-circle bg-warning opacity-25" style="width: 150px; height: 150px; bottom: 20px; left: 50px; filter: blur(30px);"></div>
                                            
                                            <div class="row align-items-center position-relative z-1 w-100 m-0">
                                                <div class="col-lg-7 ps-lg-5">
                                                    <span class="badge bg-warning text-dark mb-3">Promo Spesial</span>
                                                    <h2 class="display-4 fw-bold mb-3">
                                                        {{ $promo->type == 'percentage' ? 'Diskon '.$promo->value.'%' : 'Hemat Rp '.number_format($promo->value/1000).'rb' }}
                                                    </h2>
                                                    <p class="opacity-75 mb-4 lead">Kode Voucher: <strong class="bg-white text-primary px-3 py-1 rounded font-monospace">{{ $promo->code }}</strong></p>
                                                    <p class="small opacity-75 mb-4">{{ $promo->end_date ? 'Berlaku s/d '.\Carbon\Carbon::parse($promo->end_date)->format('d M Y') : 'Promo Selamanya!' }}</p>
                                                    <a href="{{ route('register') }}" class="btn btn-light text-primary rounded-pill px-5 py-2 fw-bold shadow-sm">
                                                        Ambil Promo
                                                    </a>
                                                </div>
                                                <div class="col-lg-5 d-none d-lg-block text-center">
                                                    <i class="bi bi-gift-fill text-white opacity-25" style="font-size: 12rem;"></i>
                                                </div>
                                            </div>
                                        </div>
                                    @endif
                                </div>
                            @endforeach
                        </div>
                        <button class="carousel-control-prev" type="button" data-bs-target="#promoCarousel" data-bs-slide="prev">
                            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                            <span class="visually-hidden">Previous</span>
                        </button>
                        <button class="carousel-control-next" type="button" data-bs-target="#promoCarousel" data-bs-slide="next">
                            <span class="carousel-control-next-icon" aria-hidden="true"></span>
                            <span class="visually-hidden">Next</span>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </section>
    @endif

    <section id="process" class="section-padding bg-light">
        <div class="container">
            <div class="text-center mb-5">
                <small class="text-primary fw-bold text-uppercase ls-1">Cara Kerja</small>
                <h2 class="display-6 mt-2">5 Langkah Mudah & Cepat</h2>
            </div>

            <div class="row g-4 justify-content-center">
                <!-- Step 1 -->
                <div class="col-6 col-md-2 text-center" data-aos="fade-up" data-aos-delay="100">
                    <div class="step-icon mx-auto bg-white shadow-sm text-primary">
                        <i class="bi bi-phone-vibrate"></i>
                    </div>
                    <h6 class="fw-bold">1. Pesan & DP</h6>
                    <p class="small text-muted lh-sm">Pilih layanan & bayar ongkir sebagai tanda jadi.</p>
                </div>
                
                <!-- Arrow -->
                <div class="col-md-1 d-none d-md-flex align-items-center justify-content-center">
                    <i class="bi bi-arrow-right fs-3 text-muted opacity-25"></i>
                </div>

                <!-- Step 2 -->
                <div class="col-6 col-md-2 text-center" data-aos="fade-up" data-aos-delay="200">
                    <div class="step-icon mx-auto bg-white shadow-sm text-warning">
                        <i class="bi bi-truck"></i>
                    </div>
                    <h6 class="fw-bold">2. Jemput</h6>
                    <p class="small text-muted lh-sm">Kurir kami mengambil cucian kotor ke lokasi Anda.</p>
                </div>

                <!-- Arrow -->
                <div class="col-md-1 d-none d-md-flex align-items-center justify-content-center">
                    <i class="bi bi-arrow-right fs-3 text-muted opacity-25"></i>
                </div>

                <!-- Step 3 -->
                <div class="col-6 col-md-2 text-center" data-aos="fade-up" data-aos-delay="300">
                    <div class="step-icon mx-auto bg-white shadow-sm text-info">
                        <i class="bi bi-basket"></i>
                    </div>
                    <h6 class="fw-bold">3. Timbang</h6>
                    <p class="small text-muted lh-sm">Admin menimbang berat & update total tagihan.</p>
                </div>

                <!-- Arrow -->
                <div class="col-md-1 d-none d-md-flex align-items-center justify-content-center">
                    <i class="bi bi-arrow-right fs-3 text-muted opacity-25"></i>
                </div>

                <!-- Step 4 -->
                <div class="col-6 col-md-2 text-center" data-aos="fade-up" data-aos-delay="400">
                    <div class="step-icon mx-auto bg-white shadow-sm text-success">
                        <i class="bi bi-wallet2"></i>
                    </div>
                    <h6 class="fw-bold">4. Pelunasan</h6>
                    <p class="small text-muted lh-sm">Cucian selesai, Anda lunasi sisa pembayaran.</p>
                </div>

                <!-- Arrow -->
                <div class="col-md-1 d-none d-md-flex align-items-center justify-content-center">
                    <i class="bi bi-arrow-right fs-3 text-muted opacity-25"></i>
                </div>

                <!-- Step 5 -->
                <div class="col-6 col-md-2 text-center" data-aos="fade-up" data-aos-delay="500">
                    <div class="step-icon mx-auto bg-white shadow-sm text-primary">
                        <i class="bi bi-box-seam-fill"></i>
                    </div>
                    <h6 class="fw-bold">5. Antar</h6>
                    <p class="small text-muted lh-sm">Baju bersih wangi diantar kembali ke rumah.</p>
                </div>
            </div>
        </div>
    </section>

    <section id="pricing" class="section-padding">
        <div class="container">
            <div class="text-center mb-5">
                <small class="text-primary fw-bold text-uppercase ls-1">Daftar Harga</small>
                <h2 class="display-6 mt-2">Transparan, Tanpa Biaya Gaib</h2>
            </div>

            <div class="row g-4 justify-content-center">
                @forelse($services as $service)
                <div class="col-md-6 col-lg-3">
                    <div class="feature-card text-center p-4 d-flex flex-column">
                        
                        {{-- THUMBNAIL LAYANAN --}}
                        <div class="mb-3 rounded-4 overflow-hidden position-relative bg-light" style="height: 160px;">
                            @if($service->image)
                                <img src="{{ asset('storage/' . $service->image) }}" class="w-100 h-100 object-fit-cover" alt="{{ $service->name }}">
                            @else
                                <div class="w-100 h-100 d-flex align-items-center justify-content-center text-muted opacity-25 overflow-hidden">
                                    @if(isset($setting) && $setting->logo)
                                        <img src="{{ asset('storage/' . $setting->logo) }}" class="w-100 h-100 object-fit-cover opacity-50" style="filter: grayscale(100%);">
                                    @else
                                        <i class="bi bi-basket-fill" style="font-size: 3rem;"></i>
                                    @endif
                                </div>
                            @endif
                            <span class="position-absolute top-0 end-0 badge bg-white text-dark shadow-sm m-2 border rounded-pill" style="font-size: 0.7rem;">
                                <i class="bi bi-clock me-1"></i> {{ $service->estimate_duration }} Jam
                            </span>
                        </div>

                        <h6 class="fw-bold text-dark mb-2">{{ $service->name }}</h6>
                        <div class="d-flex justify-content-center align-items-baseline mb-3">
                            <span class="fs-5 fw-bold text-muted">Rp</span>
                            <span class="display-6 fw-bold text-primary mx-1">{{ number_format($service->price/1000) }}k</span>
                        </div>
                        <span class="badge bg-light text-muted rounded-pill mb-4 px-3 align-self-center">per {{ $service->unit }}</span>
                        
                        <a href="{{ route('login') }}" class="btn btn-outline-primary w-100 rounded-pill btn-sm fw-bold mt-auto">Pilih Paket</a>
                    </div>
                </div>
                @empty
                <div class="col-12 text-center text-muted">Belum ada paket tersedia.</div>
                @endforelse
            </div>
        </div>
    </section>

    <section id="testimonials" class="section-padding bg-light">
        <div class="container">
            <div class="text-center mb-5">
                <small class="text-primary fw-bold text-uppercase ls-1">Testimoni</small>
                <h2 class="display-6 mt-2">Kata Mereka</h2>
            </div>
            <div class="row g-4">
                @forelse($reviews as $review)
                <div class="col-md-4">
                    <div class="testi-card h-100">
                        <div class="stars mb-3">
                            @for($i=1; $i<=5; $i++)
                                <i class="bi bi-star-fill {{ $i <= $review->rate ? 'text-warning' : 'text-muted opacity-25' }}"></i>
                            @endfor
                        </div>
                        <p class="text-muted small mb-4">"{{ $review->content }}"</p>
                        <div class="d-flex-center gap-3">
                            <div class="rounded-circle overflow-hidden box-center border border-light shadow-sm" style="width: 45px; height: 45px;">
                                @if($review->user->avatar)
                                    <img src="{{ asset('storage/' . $review->user->avatar) }}" class="w-100 h-100 object-fit-cover" alt="{{ $review->user->name }}">
                                @else
                                    <div class="w-100 h-100 bg-primary text-white d-flex align-items-center justify-content-center fw-bold">
                                        {{ substr($review->user->name, 0, 1) }}
                                    </div>
                                @endif
                            </div>
                            <div>
                                <h6 class="fw-bold mb-0 text-dark">{{ $review->user->name }}</h6>
                                <small class="text-muted" style="font-size: 0.75rem;">Pelanggan Setia</small>
                            </div>
                        </div>
                    </div>
                </div>
                @empty
                <div class="col-12 text-center text-muted">Belum ada ulasan. Jadilah yang pertama!</div>
                @endforelse
            </div>
        </div>
    </section>

    <section id="tracking" class="section-padding">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-8">
                    <div class="track-wrapper text-center">
                        <div class="mb-4">
                            <div class="bg-light-primary text-primary mx-auto rounded-circle d-flex-center justify-content-center" style="width: 60px; height: 60px; font-size: 1.5rem;">
                                <i class="bi bi-qr-code-scan"></i>
                            </div>
                        </div>
                        <h3 class="fw-bold">Lacak Status Cucian</h3>
                        <p class="text-muted small mb-4">Masukkan kode invoice yang tertera pada struk pembayaran.</p>
                        
                        <form id="trackingForm" action="{{ route('track') }}" method="POST">
                            @csrf
                            <div class="position-relative">
                                <input type="text" name="invoice_code" id="invoiceInput" class="track-input text-center fw-bold" placeholder="Contoh: TRX-12345" required>
                                <button type="submit" id="btnTrack" class="btn btn-primary rounded-pill position-absolute top-0 end-0 m-1 px-4 fw-bold" style="height: calc(100% - 8px);">
                                    <span class="normal-state">Lacak</span>
                                    <span class="loading-state d-none"><span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span></span>
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- MODAL TRACKING RESULT (NEW) --}}
    <div class="modal fade" id="trackingModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 rounded-4 shadow-lg overflow-hidden">
                <div class="modal-header bg-primary text-white border-0 py-3 px-4">
                    <h5 class="modal-title fw-bold"><i class="bi bi-receipt me-2"></i>Status Pesanan</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body p-4 text-center">
                    
                    {{-- Status Icon Animation --}}
                    <div class="mb-4">
                        <div class="d-inline-flex align-items-center justify-content-center rounded-circle bg-light shadow-sm" style="width: 80px; height: 80px;">
                            <i id="modalStatusIcon" class="bi bi-box-seam fs-1 text-primary"></i>
                        </div>
                        <h4 class="mt-3 fw-bold mb-0 text-dark" id="modalStatusText">Memproses...</h4>
                        <span class="badge bg-light text-muted border rounded-pill px-3 mt-2" id="modalInvoice">INV-XXXXX</span>
                    </div>

                    {{-- Details --}}
                    <div class="bg-light rounded-3 p-3 text-start">
                        <div class="d-flex justify-content-between mb-2">
                            <span class="text-muted small">Nama Pelanggan</span>
                            <span class="fw-bold text-dark" id="modalCustomer">-</span>
                        </div>
                        <div class="d-flex justify-content-between mb-2">
                            <span class="text-muted small">Tanggal Masuk</span>
                            <span class="fw-bold text-dark" id="modalDate">-</span>
                        </div>
                        <div class="border-top my-2"></div>
                        <div class="d-flex justify-content-between align-items-center">
                            <span class="fw-bold text-dark">Total Tagihan</span>
                            <span class="fs-5 fw-bold text-primary" id="modalTotal">Rp 0</span>
                        </div>
                    </div>

                    <div class="mt-4">
                        <button type="button" class="btn btn-outline-secondary rounded-pill px-4 w-100 fw-bold" data-bs-dismiss="modal">Tutup</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const form = document.getElementById('trackingForm');
            const btn = document.getElementById('btnTrack');
            const normalState = btn.querySelector('.normal-state');
            const loadingState = btn.querySelector('.loading-state');
            const modal = new bootstrap.Modal(document.getElementById('trackingModal'));

            // Elements in Modal
            const elStatusIcon = document.getElementById('modalStatusIcon');
            const elStatusText = document.getElementById('modalStatusText');
            const elInvoice = document.getElementById('modalInvoice');
            const elCustomer = document.getElementById('modalCustomer');
            const elDate = document.getElementById('modalDate');
            const elTotal = document.getElementById('modalTotal');

            form.addEventListener('submit', function(e) {
                e.preventDefault(); // Stop reload

                // UI Loading
                btn.disabled = true;
                normalState.classList.add('d-none');
                loadingState.classList.remove('d-none');

                const formData = new FormData(form);

                fetch("{{ route('track') }}", {
                    method: 'POST',
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest'
                    },
                    body: formData
                })
                .then(response => response.json())
                .then(data => {
                    // Reset UI
                    btn.disabled = false;
                    normalState.classList.remove('d-none');
                    loadingState.classList.add('d-none');

                    if(data.status === 'success') {
                        const trx = data.data;
                        
                        // Update Content
                        elInvoice.textContent = '#' + trx.invoice;
                        elCustomer.textContent = trx.customer;
                        elDate.textContent = trx.date;
                        elTotal.textContent = 'Rp ' + trx.total;

                        // Logic Icon & Status Text
                        let iconClass = 'bi-hourglass-split';
                        let colorClass = 'text-secondary';
                        let statusLabel = 'Menunggu';

                        if(trx.status === 'pending') {
                            iconClass = 'bi-hourglass-split';
                            colorClass = 'text-secondary';
                            statusLabel = 'Menunggu Konfirmasi';
                        } else if(trx.status === 'process') {
                            iconClass = 'bi-water';
                            colorClass = 'text-info';
                            statusLabel = 'Sedang Dicuci';
                        } else if(trx.status === 'ready') {
                            iconClass = 'bi-box-seam';
                            colorClass = 'text-warning';
                            statusLabel = 'Siap Diambil/Diantar';
                        } else if(trx.status === 'done') {
                            iconClass = 'bi-check-circle-fill';
                            colorClass = 'text-success';
                            statusLabel = 'Selesai';
                        }

                        elStatusIcon.className = `bi ${iconClass} fs-1 ${colorClass}`;
                        elStatusText.textContent = statusLabel;
                        elStatusText.className = `mt-3 fw-bold mb-0 ${colorClass}`;

                        // Show Modal
                        modal.show();
                    } else {
                        // Error (Not Found)
                        Toastify({
                            text: "❌ " + (data.message || "Data tidak ditemukan"),
                            duration: 3000,
                            gravity: "top", 
                            position: "center",
                            backgroundColor: "#ef4444",
                            className: "rounded-pill shadow-lg fw-bold"
                        }).showToast();
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    btn.disabled = false;
                    normalState.classList.remove('d-none');
                    loadingState.classList.add('d-none');
                    alert("Terjadi kesalahan sistem. Coba lagi nanti.");
                });
            });
        });
    </script>

    <section id="faq" class="section-padding bg-white">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-8">
                    <div class="text-center mb-5">
                        <small class="text-primary fw-bold text-uppercase ls-1">Tanya Jawab</small>
                        <h2 class="display-6 mt-2">Sering Ditanyakan</h2>
                    </div>

                    <div class="accordion accordion-flush" id="accordionFAQ">
                        <!-- FAQ 1 -->
                        <div class="accordion-item border-0 mb-3 rounded-4 shadow-sm bg-light overflow-hidden">
                            <h2 class="accordion-header">
                                <button class="accordion-button collapsed fw-bold bg-light py-3 px-4" type="button" data-bs-toggle="collapse" data-bs-target="#faq1">
                                    Berapa lama proses pencucian?
                                </button>
                            </h2>
                            <div id="faq1" class="accordion-collapse collapse" data-bs-parent="#accordionFAQ">
                                <div class="accordion-body px-4 pb-4 text-muted small">
                                    Untuk layanan Reguler estimasi 24-48 jam. Kami juga menyediakan layanan <strong>Express (6 Jam)</strong> dan <strong>Kilat (3 Jam)</strong> dengan biaya tambahan.
                                </div>
                            </div>
                        </div>

                        <!-- FAQ 2 -->
                        <div class="accordion-item border-0 mb-3 rounded-4 shadow-sm bg-light overflow-hidden">
                            <h2 class="accordion-header">
                                <button class="accordion-button collapsed fw-bold bg-light py-3 px-4" type="button" data-bs-toggle="collapse" data-bs-target="#faq2">
                                    Apakah deterjennya halal dan aman?
                                </button>
                            </h2>
                            <div id="faq2" class="accordion-collapse collapse" data-bs-parent="#accordionFAQ">
                                <div class="accordion-body px-4 pb-4 text-muted small">
                                    Tentu! Kami menggunakan deterjen premium bersertifikat ramah lingkungan, anti-bakteri, dan wanginya tahan lama. Aman untuk kulit sensitif dan bayi.
                                </div>
                            </div>
                        </div>

                        <!-- FAQ 3 -->
                        <div class="accordion-item border-0 mb-3 rounded-4 shadow-sm bg-light overflow-hidden">
                            <h2 class="accordion-header">
                                <button class="accordion-button collapsed fw-bold bg-light py-3 px-4" type="button" data-bs-toggle="collapse" data-bs-target="#faq3">
                                    Bagaimana jika pakaian hilang atau rusak?
                                </button>
                            </h2>
                            <div id="faq3" class="accordion-collapse collapse" data-bs-parent="#accordionFAQ">
                                <div class="accordion-body px-4 pb-4 text-muted small">
                                    Keamanan adalah prioritas kami. Jika terjadi kehilangan atau kerusakan akibat kelalaian kami, kami memberikan <strong>garansi ganti rugi hingga 10x lipat</strong> biaya cuci.
                                </div>
                            </div>
                        </div>

                        <!-- FAQ 4 -->
                        <div class="accordion-item border-0 mb-3 rounded-4 shadow-sm bg-light overflow-hidden">
                            <h2 class="accordion-header">
                                <button class="accordion-button collapsed fw-bold bg-light py-3 px-4" type="button" data-bs-toggle="collapse" data-bs-target="#faq4">
                                    Apakah ada minimal order untuk antar-jemput?
                                </button>
                            </h2>
                            <div id="faq4" class="accordion-collapse collapse" data-bs-parent="#accordionFAQ">
                                <div class="accordion-body px-4 pb-4 text-muted small">
                                    Ya, minimal transaksi untuk <strong>Gratis Ongkir</strong> adalah Rp 30.000. Di bawah itu akan dikenakan biaya jemput flat Rp 10.000 (area terjangkau).
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section id="location" class="py-5">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-10">
                    <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
                        @if($setting && $setting->latitude && $setting->longitude)
                            <iframe 
                                src="https://maps.google.com/maps?q={{ $setting->latitude }},{{ $setting->longitude }}&z=15&output=embed" 
                                width="100%" 
                                height="350" 
                                style="border:0;" 
                                allowfullscreen="" 
                                loading="lazy">
                            </iframe>
                        @else
                            <div class="bg-light d-flex align-items-center justify-content-center" style="height: 350px;">
                                <div class="text-center">
                                    <i class="bi bi-geo-alt fs-1 text-muted opacity-25"></i>
                                    <p class="text-muted small mt-2">Koordinat toko belum diatur.</p>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </section>

    <footer>
        <div class="container">
            <div class="row gy-4">
                <div class="col-lg-4">
                    <div class="mb-3">
                        @if(isset($setting) && $setting->logo)
                            <img src="{{ asset('storage/' . $setting->logo) }}" alt="Logo" style="width: 180px; height: auto;" class="d-block mb-3">
                        @else
                            <div class="d-flex align-items-center gap-2">
                                <div class="rounded-circle d-flex align-items-center justify-content-center overflow-hidden shadow-lg border border-4 border-white" style="width: 60px; height: 60px;">
                                    <div class="bg-primary w-100 h-100 d-flex align-items-center justify-content-center text-white">
                                        <i class="bi bi-basket-fill fs-3"></i>
                                    </div>
                                </div>
                                <h6 class="fw-bold mb-0 text-dark">{{ $setting->shop_name ?? 'LaundryKuy' }}</h6>
                            </div>
                        @endif
                    </div>
                    <p class="text-muted small">Solusi laundry modern untuk generasi anti ribet. Bersih, wangi, dan tepat waktu.</p>
                    <div class="d-flex gap-2 mt-3">
                        <a href="#" class="btn btn-sm btn-light border rounded-circle"><i class="bi bi-instagram"></i></a>
                        <a href="#" class="btn btn-sm btn-light border rounded-circle"><i class="bi bi-facebook"></i></a>
                        <a href="#" class="btn btn-sm btn-light border rounded-circle"><i class="bi bi-tiktok"></i></a>
                    </div>
                </div>
                <div class="col-6 col-lg-2">
                    <h6 class="fw-bold text-dark mb-3">Menu</h6>
                    <a href="#home" class="footer-link">Beranda</a>
                    <a href="#features" class="footer-link">Keunggulan</a>
                    <a href="#pricing" class="footer-link">Harga</a>
                    <a href="#faq" class="footer-link">FAQ</a>
                </div>
                <div class="col-6 col-lg-2">
                    <h6 class="fw-bold text-dark mb-3">Layanan</h6>
                    <a href="#" class="footer-link">Kiloan</a>
                    <a href="#" class="footer-link">Satuan</a>
                    <a href="#" class="footer-link">Cuci Sepatu</a>
                    <a href="#" class="footer-link">Karpet & Bedcover</a>
                </div>
                <div class="col-lg-4">
                    <h6 class="fw-bold text-dark mb-3">Lokasi Workshop</h6>
                    <p class="small text-muted mb-2"><i class="bi bi-geo-alt-fill me-2 text-danger"></i>{{ $setting->address ?? 'Alamat belum diatur.' }}</p>
                    <p class="small text-muted"><i class="bi bi-clock-fill me-2 text-primary"></i>Buka Setiap Hari: 07.00 - 21.00</p>
                </div>
            </div>
            <div class="border-top mt-5 pt-4 text-center">
                <small class="text-muted">&copy; {{ date('Y') }} {{ $setting->shop_name ?? 'LaundryKuy' }} Inc. All rights reserved.</small>
            </div>
        </div>
    </footer>

    <!-- FLOATING WHATSAPP -->
    @php
        $wa_number = $setting->phone ?? '6281234567890';
        // Pastikan format diawali 62
        if(str_starts_with($wa_number, '0')) {
            $wa_number = '62' . substr($wa_number, 1);
        }
    @endphp
    <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $wa_number) }}?text=Halo%20{{ urlencode($setting->shop_name ?? 'LaundryKuy') }},%20saya%20mau%20tanya%20layanan%20laundry..." target="_blank" class="btn-float-wa d-flex align-items-center justify-content-center shadow-lg" data-bs-toggle="tooltip" title="Chat Kami via WA">
        <i class="bi bi-whatsapp"></i>
    </a>

    <style>
        /* Floating WA Style */
        .btn-float-wa {
            position: fixed;
            bottom: 30px;
            right: 30px;
            width: 60px;
            height: 60px;
            background-color: #25D366;
            color: white;
            border-radius: 50%;
            font-size: 30px;
            z-index: 9999;
            transition: all 0.3s ease;
            animation: pulse-wa 2s infinite;
        }
        .btn-float-wa:hover {
            background-color: #128C7E;
            transform: scale(1.1);
            color: white;
        }
        @keyframes pulse-wa {
            0% { box-shadow: 0 0 0 0 rgba(37, 211, 102, 0.7); }
            70% { box-shadow: 0 0 0 15px rgba(37, 211, 102, 0); }
            100% { box-shadow: 0 0 0 0 rgba(37, 211, 102, 0); }
        }
    </style>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
    <script>
        AOS.init({ duration: 800, once: true });

        // Navbar Blur Scroll
        const navbar = document.getElementById('mainNav');
        window.addEventListener('scroll', () => {
            if (window.scrollY > 50) {
                navbar.classList.add('navbar-scrolled');
            } else {
                navbar.classList.remove('navbar-scrolled');
            }
        });
    </script>
</body>
</html> 