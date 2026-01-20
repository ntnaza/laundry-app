<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title') - LaundryKuy</title>
    
    <link rel="stylesheet" href="{{ asset('assets/compiled/css/app.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/compiled/css/app-dark.css') }}">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/toastify-js/src/toastify.min.css">
    <link rel="stylesheet" href="{{ asset('assets/compiled/css/iconly.css') }}">

    <style>
        body { background-color: #f2f7ff; }
        
        /* Default: Mode Desktop */
        .bottom-nav { display: none; } /* Sembunyikan menu bawah di laptop */
        .desktop-nav { display: block; box-shadow: 0 2px 10px rgba(0,0,0,0.05); background: white; }
        .content-area { padding-top: 30px; padding-bottom: 30px; min-height: 80vh; }
        .header-mobile-only { display: none; }

        /* Mode HP (Layar < 768px) */
        @media (max-width: 768px) {
            .desktop-nav { display: none; } /* Sembunyikan navbar atas di HP */
            .bottom-nav { 
                display: flex; position: fixed; bottom: 0; left: 0; right: 0; 
                background: white; padding: 12px; box-shadow: 0 -5px 20px rgba(0,0,0,0.05); 
                justify-content: space-around; z-index: 999;
            }
            .content-area { padding-top: 20px; padding-bottom: 100px; }
            .header-mobile-only { display: block; }
            
            .nav-item { text-align: center; color: #a0a0a0; text-decoration: none; font-size: 0.75rem; }
            .nav-item.active { color: #435ebe; font-weight: bold; }
            .nav-item i { font-size: 1.4rem; display: block; margin-bottom: 2px; }
            .floating-btn { font-size: 2.5rem; color: #435ebe; transform: translateY(-15px); filter: drop-shadow(0 4px 6px rgba(67, 94, 190, 0.3)); }
        }
    </style>
</head>

<body>
    {{-- 1. NAVBAR DESKTOP (Hanya Muncul di Laptop) --}}
    <nav class="navbar navbar-expand-lg desktop-nav fixed-top">
        <div class="container">
            <a class="navbar-brand fw-bold text-primary" href="{{ route('customer.dashboard') }}">
                <i class="bi bi-basket-fill"></i> LaundryKuy
            </a>
            <div class="d-flex align-items-center gap-3">
                <span class="text-muted small d-none d-md-block">Halo, {{ Auth::user()->name }}</span>
                <div class="avatar bg-light-primary text-primary fw-bold rounded-circle d-flex align-items-center justify-content-center" style="width: 35px; height: 35px;">
                    {{ substr(Auth::user()->name, 0, 1) }}
                </div>
                <a href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();" class="btn btn-sm btn-outline-danger ms-2">
                    Logout
                </a>
            </div>
        </div>
    </nav>
    <div class="d-none d-md-block" style="height: 60px;"></div> {{-- Spacer buat navbar --}}

    <div class="container content-area">
        @yield('content')
    </div>

    {{-- 2. BOTTOM NAV (Hanya Muncul di HP) --}}
    <div class="bottom-nav">
        <a href="{{ route('customer.dashboard') }}" class="nav-item {{ request()->routeIs('customer.dashboard') ? 'active' : '' }}">
            <i class="bi bi-house-door-fill"></i> Home
        </a>
        <a href="{{ route('customer.order.create') }}" class="nav-item">
            <i class="bi bi-plus-circle-fill floating-btn"></i>
        </a>
        <a href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();" class="nav-item">
            <i class="bi bi-box-arrow-right"></i> Keluar
        </a>
    </div>

    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">@csrf</form>

    <script src="https://cdn.jsdelivr.net/npm/toastify-js"></script>
    <script>
        @if(session('success'))
            Toastify({ text: "{{ session('success') }}", duration: 3000, gravity: "top", position: "center", backgroundColor: "#4fbe87" }).showToast();
        @endif
    </script>
</body>
</html>