<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title') - Laundry System</title>
    
    <link rel="stylesheet" href="{{ asset('assets/compiled/css/app.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/compiled/css/app-dark.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/compiled/css/iconly.css') }}">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/toastify-js/src/toastify.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;700;800&family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
    
    <link rel="shortcut icon" href="{{ asset('assets/static/images/logo/favicon.svg') }}" type="image/x-icon">

    <style>
        :root {
            --primary: #2563EB;
            --primary-dark: #1e40af;
            --dark: #0F172A;
            --light: #F8FAFC;
            --sidebar-bg: #ffffff;
            --sidebar-active: #eff6ff;
        }

        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background-color: #F1F5F9;
        }

        h1, h2, h3, h4, h5, .fw-heading { font-family: 'Outfit', sans-serif; font-weight: 700; color: var(--dark); }

        /* --- LOGO --- */
        .logo-img { max-height: 40px; width: auto; }

        /* --- GLOBAL ANTI-DENGDEK (BOX CENTER) --- */
        .box-center {
            display: flex !important;
            align-items: center !important;
            justify-content: center !important;
            padding: 0 !important; 
            margin: 0 auto !important;
        }
        
        .box-center i, .box-center .bi {
            line-height: 1 !important;
            display: flex !important;
            align-items: center !important;
            justify-content: center !important;
            margin: 0 !important;
            padding: 0 !important;
        }

        /* --- SIDEBAR PREMIUM --- */
        #sidebar { background-color: var(--sidebar-bg); border-right: 1px solid rgba(0,0,0,0.05); }
        .sidebar-wrapper { box-shadow: none !important; }
        
        .sidebar-item.active .sidebar-link {
            background-color: var(--primary) !important;
            box-shadow: 0 4px 12px rgba(37, 99, 235, 0.3);
        }
        
        .sidebar-link {
            border-radius: 12px !important;
            transition: 0.3s;
            font-weight: 500;
        }
        .sidebar-link:hover { background-color: var(--sidebar-active); color: var(--primary); }
        .sidebar-link i { font-size: 1.1rem; vertical-align: middle; display: inline-flex; }

        /* --- CARD STYLING --- */
        .card {
            border: none;
            border-radius: 16px;
            box-shadow: 0 5px 20px rgba(0,0,0,0.03);
            transition: 0.3s;
        }
        .card:hover { transform: translateY(-3px); box-shadow: 0 10px 30px rgba(0,0,0,0.06); }
        .card-header { background: transparent; border-bottom: 1px solid #f1f5f9; padding: 1.5rem; }
        .card-body { padding: 1.5rem; }

        /* --- TABLE STYLING --- */
        .table thead th {
            font-family: 'Outfit', sans-serif;
            font-size: 0.85rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            color: #64748b;
            border-bottom: 2px solid #f1f5f9;
            padding-bottom: 1rem;
        }
        .table td { vertical-align: middle; padding: 1rem 0.5rem; }
    </style>
</head>

<body>
    <script src="{{ asset('assets/static/js/initTheme.js') }}"></script>
    
    @php
        $setting = \App\Models\Setting::first();
    @endphp

    <div id="app">
        <div id="sidebar" class="active">
            <div class="sidebar-wrapper active">
                <div class="sidebar-header position-relative">
                    <div class="d-flex justify-content-between align-items-center">
                        <div class="logo">
                            <a href="{{ route('dashboard') }}" class="d-flex align-items-center gap-2 text-decoration-none">
                                @if($setting && $setting->logo)
                                    <img src="{{ asset('storage/'.$setting->logo) }}" class="logo-img" alt="Logo">
                                @else
                                    <div class="bg-primary text-white rounded-3 box-center shadow-sm" style="width: 36px; height: 36px;">
                                        <i class="bi bi-basket-fill fs-6"></i>
                                    </div>
                                @endif
                                <span class="fw-heading fs-5 text-primary">{{ $setting->shop_name ?? 'LaundryKuy' }}</span>
                            </a>
                        </div>
                        <div class="theme-toggle d-flex gap-2 align-items-center mt-2">
                            <div class="form-check form-switch fs-6">
                                <input class="form-check-input me-0" type="checkbox" id="toggle-dark">
                                <label class="form-check-label"></label>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="sidebar-menu">
                    <ul class="menu">
                        <li class="sidebar-title small text-muted fw-bold">Menu Utama</li>

                        <li class="sidebar-item {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                            <a href="{{ route('dashboard') }}" class='sidebar-link'>
                                <i class="bi bi-grid-fill"></i> <span>Dashboard</span>
                            </a>
                        </li>

                        <li class="sidebar-item {{ request()->routeIs('transactions*') ? 'active' : '' }}">
                            <a href="{{ route('transactions.index') }}" class='sidebar-link'>
                                <i class="bi bi-receipt"></i> <span>Transaksi Kasir</span>
                            </a>
                        </li>

                        <li class="sidebar-item {{ request()->routeIs('customers*') ? 'active' : '' }}">
                            <a href="{{ route('customers.index') }}" class='sidebar-link'>
                                <i class="bi bi-people-fill"></i> <span>Data Pelanggan</span>
                            </a>
                        </li>

                        @if(auth()->user()->role !== 'staff')
                            <li class="sidebar-title small text-muted fw-bold mt-3">Manajemen</li>
                            
                            <li class="sidebar-item {{ request()->routeIs('services*') ? 'active' : '' }}">
                                <a href="{{ route('services.index') }}" class='sidebar-link'>
                                    <i class="bi bi-basket-fill"></i> <span>Paket Laundry</span>
                                </a>
                            </li>

                            <li class="sidebar-item {{ request()->routeIs('reports*') ? 'active' : '' }}">
                                <a href="{{ route('reports.index') }}" class='sidebar-link'>
                                    <i class="bi bi-file-earmark-bar-graph"></i> <span>Laporan Keuangan</span>
                                </a>
                            </li>
                        @endif

                        @if(auth()->user()->role === 'owner')
                            <li class="sidebar-title small text-muted fw-bold mt-3">Area Owner</li>

                            <li class="sidebar-item {{ request()->routeIs('expenses*') ? 'active' : '' }}">
                                <a href="{{ route('expenses.index') }}" class='sidebar-link'>
                                    <i class="bi bi-wallet2"></i> <span>Pengeluaran (Kas)</span>
                                </a>
                            </li>

                            <li class="sidebar-item {{ request()->routeIs('users*') ? 'active' : '' }}">
                                <a href="{{ route('users.index') }}" class='sidebar-link'>
                                    <i class="bi bi-person-badge-fill"></i> <span>Kelola Pengguna</span>
                                </a>
                            </li>

                            <li class="sidebar-item {{ request()->routeIs('settings*') ? 'active' : '' }}">
                                <a href="{{ route('settings.index') }}" class='sidebar-link'>
                                    <i class="bi bi-gear-fill"></i> <span>Pengaturan</span>
                                </a>
                            </li>
                        @endif

                        <li class="sidebar-title small text-muted fw-bold mt-3">Akun</li>
                        <li class="sidebar-item">
                            <a class="sidebar-link text-danger bg-light-danger" href="{{ route('logout') }}"
                               onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                <i class="bi bi-box-arrow-left"></i>
                                <span>Logout</span>
                            </a>
                            <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                @csrf
                            </form>
                        </li>
                    </ul>
                </div>
            </div>
        </div>

        <div id="main" class='layout-navbar'>
            <header class="mb-3">
                <nav class="navbar navbar-expand navbar-light bg-white shadow-sm py-3 rounded-4 mx-3 mt-3">
                    <div class="container-fluid">
                        <a href="#" class="burger-btn d-block">
                            <i class="bi bi-justify fs-3"></i>
                        </a>

                        <button class="navbar-toggler" type="button" data-bs-toggle="collapse"
                            data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent"
                            aria-expanded="false" aria-label="Toggle navigation">
                            <span class="navbar-toggler-icon"></span>
                        </button>

                        <div class="collapse navbar-collapse" id="navbarSupportedContent">
                            <ul class="navbar-nav ms-auto mb-2 mb-lg-0">
                                <li class="nav-item dropdown me-1">
                                    <a class="nav-link active text-gray-600" href="#" data-bs-toggle="dropdown" aria-expanded="false">
                                        <div class="d-flex align-items-center gap-3"> 
                                            <div class="user-name text-end" style="line-height: 1.2;"> 
                                                <h6 class="mb-0 text-gray-600 fw-bold" style="font-size: 0.9rem;">{{ auth()->user()->name }}</h6>
                                                <p class="mb-0 text-sm text-gray-600">{{ ucfirst(auth()->user()->role) }}</p>
                                            </div>
                                            
                                            <div class="avatar bg-primary text-white box-center rounded-circle shadow-sm" 
                                                 style="width: 40px; height: 40px; min-width: 40px;">
                                                <span class="fw-bold" style="font-size: 1.1rem;">{{ substr(auth()->user()->name, 0, 1) }}</span>
                                            </div>
                                        </div>
                                    </a>

                                    <ul class="dropdown-menu dropdown-menu-end shadow-sm border-0 rounded-4 mt-2" aria-labelledby="dropdownMenuButton" style="min-width: 200px;">
                                        <li>
                                            <h6 class="dropdown-header">Halo, {{ auth()->user()->name }}!</h6>
                                        </li>
                                        <li>
                                            <a class="dropdown-item" href="{{ route('profile.index') }}">
                                                <i class="icon-mid bi bi-person me-2 text-primary"></i> Profil Saya
                                            </a>
                                        </li>
                                        <li><hr class="dropdown-divider"></li>
                                        <li>
                                            <a class="dropdown-item text-danger" href="{{ route('logout') }}"
                                               onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                                <i class="icon-mid bi bi-box-arrow-left me-2"></i> Logout
                                            </a>
                                        </li>
                                    </ul>
                                </li>
                            </ul>
                        </div>
                    </div>
                </nav>
            </header>

            <div id="main-content">
                <div class="page-heading">
                    <div class="page-title mb-4">
                        <div class="row">
                            <div class="col-12 col-md-6 order-md-1 order-last">
                                <h3 class="fw-heading">@yield('page-title')</h3>
                            </div>
                        </div>
                    </div>
                </div>
                
                <section class="section">
                    @yield('content')
                </section>
            </div>

            <footer>
                <div class="footer clearfix mb-0 text-muted">
                    <div class="float-start">
                        <p>2026 &copy; {{ $setting->shop_name ?? 'Laundry System' }}</p>
                    </div>
                    <div class="float-end">
                        <p>Crafted with <span class="text-danger"><i class="bi bi-heart-fill"></i></span> by <a href="#">Koh Engkoh</a></p>
                    </div>
                </div>
            </footer>
        </div>
    </div>
    
    <script src="{{ asset('assets/static/js/components/dark.js') }}"></script>
    <script src="{{ asset('assets/extensions/perfect-scrollbar/perfect-scrollbar.min.js') }}"></script>
    <script src="{{ asset('assets/compiled/js/app.js') }}"></script>

    <script src="https://cdn.jsdelivr.net/npm/toastify-js"></script>

    <script>
        @if(session('success'))
            Toastify({
                text: "{{ session('success') }}",
                duration: 3000,
                close: true,
                gravity: "top", 
                position: "right", 
                backgroundColor: "#4fbe87",
                className: "rounded-pill fw-bold shadow-sm"
            }).showToast();
        @endif

        @if(session('error'))
            Toastify({
                text: "{{ session('error') }}",
                duration: 3000,
                close: true,
                gravity: "top", 
                position: "right", 
                backgroundColor: "#f56c6d",
                className: "rounded-pill fw-bold shadow-sm"
            }).showToast();
        @endif
    </script>
    
    {{-- AUDIO NOTIFIKASI --}}
    <audio id="notifSound" src="https://assets.mixkit.co/active_storage/sfx/2869/2869-preview.mp3" preload="auto"></audio>

    <script>
        let lastOrderCount = -1; 

        function checkOrders() {
            fetch("{{ route('admin.check_orders') }}")
                .then(response => response.json())
                .then(data => {
                    let currentCount = data.new_orders;

                    if (lastOrderCount === -1) {
                        lastOrderCount = currentCount;
                    }

                    if (currentCount > lastOrderCount) {
                        var audio = document.getElementById("notifSound");
                        audio.play().catch(error => console.log("Audio play blocked: " + error));

                        Toastify({
                            text: "🔔 " + (currentCount - lastOrderCount) + " ORDERAN BARU MASUK!\nSegera cari kurir.",
                            duration: 10000,
                            close: true,
                            gravity: "top", 
                            position: "center", 
                            backgroundColor: "#dc3545",
                            stopOnFocus: true, 
                            onClick: function(){ 
                                window.location.href = "{{ route('transactions.index') }}"; 
                            },
                            className: "rounded-3 fw-bold shadow-lg"
                        }).showToast();
                    }

                    lastOrderCount = currentCount;
                })
                .catch(error => console.error('Error checking orders:', error));
        }

        setInterval(checkOrders, 10000);
        checkOrders();
    </script>
</body>
</html>