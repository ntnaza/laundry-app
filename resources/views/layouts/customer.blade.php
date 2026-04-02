<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title') - LaundryKuy</title>
    
    <link rel="icon" type="image/png" href="{{ asset('assets/static/images/logo/Laundry-app.png') }}?v=1.3">
    
    <link rel="stylesheet" href="{{ asset('assets/compiled/css/app.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/compiled/css/app-dark.css') }}">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/toastify-js/src/toastify.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;700;800&family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">

    <style>
        :root {
            --primary: #435ebe;     
            --primary-dark: #374b9d;
            --dark: #0F172A;        
            --light: #F8FAFC;       
        }

        body { 
            background-color: #F8FAFC; 
            font-family: 'Plus Jakarta Sans', sans-serif;
            color: #475569;
        }

        h1, h2, h3, h4, h5, .fw-heading { font-family: 'Outfit', sans-serif; font-weight: 700; color: var(--dark); }

        /* --- THE HOLY GRAIL CENTER (ADAPTASI DARI LOGIN) --- */
        /* Class ini meniru .input-icon-wrapper di login */
        .box-center {
            display: flex !important;
            align-items: center !important;
            justify-content: center !important;
            padding: 0 !important; 
            margin: 0 auto !important; /* Auto kiri kanan biar center horizontal di container */
        }

        /* RESET ICON BIAR NURUT (SAMA KAYAK LOGIN) */
        .box-center i, 
        .box-center .bi {
            line-height: 1 !important;
            display: flex !important; /* Pakai Flex, bukan Block */
            align-items: center !important;
            justify-content: center !important;
            margin: 0 !important;
            padding: 0 !important;
            transform: none !important; /* HAPUS SEMUA TRANSFORM */
        }

        /* --- NAVBAR & UI LAIN --- */
        .desktop-nav { 
            background: rgba(255, 255, 255, 0.95); 
            backdrop-filter: blur(15px); 
            border-bottom: 1px solid rgba(0,0,0,0.05);
            padding: 0.8rem 0;
        }
        
        .bottom-nav { 
            display: none; position: fixed; bottom: 0; left: 0; right: 0; 
            background: white; padding: 12px 20px; 
            box-shadow: 0 -5px 20px rgba(0,0,0,0.05); 
            justify-content: space-between; z-index: 999;
            border-top-left-radius: 20px; border-top-right-radius: 20px;
        }

        .nav-item-mobile { 
            text-align: center; color: #94a3b8; text-decoration: none; 
            font-size: 0.7rem; font-weight: 600; flex: 1;
            display: flex; flex-direction: column; align-items: center; justify-content: center;
        }
        .nav-item-mobile i { font-size: 1.4rem; margin-bottom: 4px; transition: 0.3s; }
        .nav-item-mobile.active { color: var(--primary); }
        .nav-item-mobile.active i { transform: translateY(-3px); }

        .fab-container { position: relative; top: -35px; }
        .fab-btn {
            width: 55px; height: 55px;
            background: linear-gradient(135deg, var(--primary), var(--primary-dark));
            border-radius: 50%;
            /* Terapkan box-center logic disini juga */
            display: flex; align-items: center; justify-content: center;
            color: white; font-size: 1.8rem;
            box-shadow: 0 10px 20px rgba(37, 99, 235, 0.3); border: 4px solid #fff;
        }

        .content-area { padding-top: 100px; padding-bottom: 50px; min-height: 90vh; }

        @media (max-width: 768px) {
            .desktop-nav { display: none; }
            .bottom-nav { display: flex; }
            .content-area { padding-top: 30px; padding-bottom: 120px; }
        }
        
        .card-premium {
            background: white; border: 1px solid #f1f5f9; border-radius: 20px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.02); transition: 0.3s;
        }
        .card-premium:hover { transform: translateY(-3px); box-shadow: 0 15px 40px rgba(0,0,0,0.05); }
    </style>
</head>

<body>
    <nav class="navbar navbar-expand-lg desktop-nav fixed-top">
        <div class="container">
            <a class="navbar-brand d-flex align-items-center gap-2" href="{{ route('customer.dashboard') }}">
                @if($setting && $setting->logo)
                    <img src="{{ asset('storage/' . $setting->logo) }}" style="height: 40px; width: auto; max-width: 180px;" alt="Logo" class="object-fit-contain">
                @else
                    <div class="rounded-3 box-center shadow-sm overflow-hidden" style="width: 38px; height: 38px;">
                        <div class="bg-primary w-100 h-100 box-center text-white">
                            <i class="bi bi-basket-fill fs-6"></i>
                        </div>
                    </div>
                    <span class="fw-heading fs-5 text-primary">{{ $setting->shop_name ?? 'LaundryKuy' }}</span>
                @endif
            </a>
            
            <div class="d-flex align-items-center gap-3">
                <a href="{{ route('customer.profile.index') }}" class="text-decoration-none d-flex align-items-center gap-3">
                    <div class="text-end d-none d-md-block">
                        <small class="text-muted d-block" style="font-size: 0.7rem; margin-bottom: -2px;">Selamat Datang,</small>
                        <span class="fw-bold text-dark">{{ Auth::user()->name }}</span>
                    </div>
                    <div class="bg-light-primary text-primary fw-bold rounded-circle box-center border border-2 border-white shadow-sm overflow-hidden" style="width: 42px; height: 42px;">
                        @if(Auth::user()->avatar)
                            <img src="{{ asset('storage/' . Auth::user()->avatar) }}" class="w-100 h-100 object-fit-cover">
                        @else
                            {{ substr(Auth::user()->name, 0, 1) }}
                        @endif
                    </div>
                </a>
                
                {{-- TOMBOL LOGOUT FIX --}}
                <a href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();" class="btn btn-sm btn-light text-danger rounded-circle fw-bold ms-2 box-center shadow-sm border" style="width: 38px; height: 38px;">
                    <i class="bi bi-box-arrow-right fs-6"></i>
                </a>
            </div>
        </div>
    </nav>

    <div class="container content-area">
        @yield('content')
    </div>

    <div class="bottom-nav">
        <a href="{{ route('customer.dashboard') }}" class="nav-item-mobile {{ request()->routeIs('customer.dashboard') ? 'active' : '' }}">
            <i class="bi bi-house-door-fill"></i> Home
        </a>
        <div class="fab-container">
            <a href="{{ route('customer.order.create') }}" class="fab-btn">
                <i class="bi bi-plus-lg"></i>
            </a>
        </div>
        <a href="{{ route('customer.profile.index') }}" class="nav-item-mobile {{ request()->routeIs('customer.profile.index') ? 'active' : '' }}">
            <i class="bi bi-person-circle"></i> Profil
        </a>
    </div>

    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">@csrf</form>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/toastify-js"></script>
    <script>
        @if(session('success'))
            Toastify({ text: "{{ session('success') }}", duration: 3000, gravity: "top", position: "center", backgroundColor: "#10B981", className: "rounded-pill shadow-lg fw-bold" }).showToast();
        @endif
    </script>
</body>
</html>