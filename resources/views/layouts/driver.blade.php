<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Driver Panel - Laundry System</title>
    
    <link rel="icon" type="image/png" href="{{ asset('assets/static/images/logo/Laundry-app.png') }}?v=1.3">
    
    <link rel="stylesheet" href="{{ asset('assets/compiled/css/app.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/compiled/css/app-dark.css') }}">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/toastify-js/src/toastify.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;700;800&family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">

    <style>
        body { 
            background-color: #F8FAFC; 
            font-family: 'Plus Jakarta Sans', sans-serif;
            padding-bottom: 80px; /* Space for bottom nav */
        }
        
        .fw-heading { font-family: 'Outfit', sans-serif; font-weight: 700; }
        .box-center { display: flex; align-items: center; justify-content: center; }
        
        /* Bottom Nav Mobile */
        .bottom-nav {
            position: fixed; bottom: 0; left: 0; right: 0;
            background: white; padding: 12px 20px;
            box-shadow: 0 -5px 20px rgba(0,0,0,0.05);
            display: flex; justify-content: space-around;
            z-index: 1000; border-top-left-radius: 20px; border-top-right-radius: 20px;
        }
        .nav-item-mobile {
            text-align: center; color: #94a3b8; text-decoration: none;
            font-size: 0.7rem; font-weight: 600;
            display: flex; flex-direction: column; align-items: center;
        }
        .nav-item-mobile i { font-size: 1.4rem; margin-bottom: 4px; }
        .nav-item-mobile.active { color: #2563EB; }

        /* Desktop Header */
        .desktop-header { background: white; padding: 1rem 0; box-shadow: 0 5px 20px rgba(0,0,0,0.03); }
    </style>
</head>

<body>
    
    {{-- DESKTOP HEADER --}}
    <nav class="desktop-header mb-4 d-none d-md-block">
        <div class="container d-flex justify-content-between align-items-center">
            <div class="d-flex align-items-center gap-3">
                <div class="bg-primary text-white rounded-3 box-center shadow-sm" style="width: 40px; height: 40px;">
                    <i class="bi bi-truck fs-5"></i>
                </div>
                <div>
                    <h5 class="fw-heading mb-0 text-dark">Driver Panel</h5>
                    <small class="text-muted">Kelola pengiriman</small>
                </div>
            </div>
            
            <div class="dropdown">
                <a href="#" class="d-flex align-items-center gap-3 text-decoration-none dropdown-toggle" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                    <div class="text-end">
                        <span class="fw-bold text-dark d-block">{{ Auth::user()->name }}</span>
                        <small class="text-muted">Kurir</small>
                    </div>
                    <div class="avatar bg-light-primary text-primary rounded-circle box-center border border-2 border-white shadow-sm" style="width: 40px; height: 40px;">
                        {{ substr(Auth::user()->name, 0, 1) }}
                    </div>
                </a>
                <ul class="dropdown-menu dropdown-menu-end border-0 shadow-lg animate__animated animate__fadeIn" aria-labelledby="navbarDropdown">
                    {{-- LINK PROFIL YANG BENAR --}}
                    <li><a class="dropdown-item py-2 small" href="{{ route('driver.profile.index') }}"><i class="bi bi-person me-2 text-muted"></i> Profil Saya</a></li>
                    <li><hr class="dropdown-divider my-1"></li>
                    <li>
                        <a class="dropdown-item py-2 small text-danger fw-bold" href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                            <i class="bi bi-box-arrow-right me-2"></i> Keluar
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    {{-- CONTENT --}}
    <div class="container">
        @yield('content')
    </div>

    {{-- BOTTOM NAV (MOBILE) --}}
    <div class="bottom-nav d-md-none">
        <a href="{{ route('driver.tasks') }}" class="nav-item-mobile {{ request()->routeIs('driver.tasks') ? 'active' : '' }}">
            <i class="bi bi-truck"></i> Tugas
        </a>
        <a href="{{ route('driver.history') }}" class="nav-item-mobile {{ request()->routeIs('driver.history') ? 'active' : '' }}">
            <i class="bi bi-clock-history"></i> Riwayat
        </a>
        {{-- LINK PROFIL YANG BENAR (MOBILE) --}}
        <a href="{{ route('driver.profile.index') }}" class="nav-item-mobile {{ request()->routeIs('driver.profile.index') ? 'active' : '' }}">
            <i class="bi bi-person-circle"></i> Profil
        </a>
        <a href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();" class="nav-item-mobile text-danger">
            <i class="bi bi-box-arrow-right"></i> Keluar
        </a>
    </div>

    <form id="logout-form" action="{{ url('/logout') }}" method="POST" class="d-none">@csrf</form>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/toastify-js"></script>
    <script>
        @if(session('success') || session('status'))
            Toastify({ text: "{{ session('success') ?? session('status') }}", duration: 3000, gravity: "top", position: "center", backgroundColor: "#10B981", className: "rounded-pill shadow-lg fw-bold" }).showToast();
        @endif
        @if(session('error'))
            Toastify({ text: "{{ session('error') }}", duration: 3000, gravity: "top", position: "center", backgroundColor: "#ef4444", className: "rounded-pill shadow-lg fw-bold" }).showToast();
        @endif
    </script>
    @stack('scripts')
</body>
</html>