<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title') - Laundry System</title>
    
    <link rel="stylesheet" href="{{ asset('assets/compiled/css/app.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/compiled/css/iconly.css') }}">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/toastify-js/src/toastify.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;700;800&family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
    
    <link rel="shortcut icon" href="{{ asset('assets/static/images/logo/favicon.svg') }}" type="image/x-icon">

    <style>
        :root {
            --primary: #2563EB;
            --primary-gradient: linear-gradient(135deg, #2563EB 0%, #1D4ED8 100%);
            --dark: #0F172A;
            --light: #F8FAFC;
            --sidebar-width: 260px; /* Ukuran lebih ramping */
        }

        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background-color: #F1F5F9;
            color: #475569;
            overflow-x: hidden;
            font-size: 0.9rem; /* Base font agak kecil dikit */
        }

        h1, h2, h3, h4, h5, .fw-heading { font-family: 'Outfit', sans-serif; font-weight: 700; color: var(--dark); }

        /* --- 1. SIDEBAR YANG BENAR-BENAR BARU (FLOATING COMPACT) --- */
        #sidebar {
            position: fixed;
            top: 15px; /* Jarak atas dikurangi */
            bottom: 15px; /* Jarak bawah dikurangi */
            left: 15px;
            width: var(--sidebar-width);
            background: #ffffff;
            border-radius: 24px; /* Radius dikurangi dikit biar ga terlalu bulat di layar kecil */
            box-shadow: 0 10px 40px rgba(0,0,0,0.04);
            z-index: 1000;
            display: flex;
            flex-direction: column;
            transition: all 0.3s ease;
            border: 1px solid rgba(255,255,255,0.5);
        }

        .sidebar-header {
            padding: 1.5rem 1.5rem 1rem 1.5rem; /* Padding header dikurangi */
        }

        .sidebar-menu {
            flex-grow: 1;
            overflow-y: auto;
            padding: 0 1rem; /* Padding kiri-kanan menu dikurangi */
            -ms-overflow-style: none;
            scrollbar-width: none;
        }
        .sidebar-menu::-webkit-scrollbar { display: none; }

        .sidebar-footer {
            padding: 1rem 1.5rem; /* Footer lebih tipis */
            border-top: 1px solid #f1f5f9;
            background: #fafafa;
            border-bottom-left-radius: 24px;
            border-bottom-right-radius: 24px;
        }

        /* --- 2. MENU ITEM (COMPACT PILL) --- */
        .sidebar-title {
            font-size: 0.65rem;
            font-weight: 800;
            color: #94a3b8;
            letter-spacing: 1.2px;
            margin: 1.2rem 0 0.4rem 0.8rem;
            text-transform: uppercase;
            font-family: 'Outfit', sans-serif;
        }

        .sidebar-item { list-style: none; margin-bottom: 0.2rem; }

        .sidebar-link {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 0.75rem 1rem; /* Padding tombol lebih tipis */
            border-radius: 50px;
            color: #64748b;
            text-decoration: none;
            font-weight: 600;
            font-size: 0.9rem; /* Font size menu pas */
            transition: all 0.3s cubic-bezier(0.2, 0.8, 0.2, 1);
        }

        .sidebar-link i {
            font-size: 1.15rem; /* Icon dikecilkan sedikit */
            display: flex;
            align-items: center;
            justify-content: center;
            color: #94a3b8;
            transition: 0.3s;
        }

        /* Hover Effect */
        .sidebar-link:hover {
            background-color: #f1f5f9;
            color: var(--primary);
            transform: translateX(3px);
        }
        .sidebar-link:hover i { color: var(--primary); }

        /* ACTIVE STATE (GRADIENT) */
        .sidebar-item.active .sidebar-link {
            background: var(--primary-gradient);
            color: white;
            box-shadow: 0 6px 15px rgba(37, 99, 235, 0.25);
        }
        .sidebar-item.active .sidebar-link i { color: white; }

        /* --- 3. MAIN CONTENT ADJUSTMENT --- */
        #main {
            margin-left: calc(var(--sidebar-width) + 30px); /* Adjust margin main content */
            padding: 1.5rem 1.5rem 1.5rem 0;
            min-height: 100vh;
            transition: margin-left 0.3s ease;
        }

        /* Responsive Mobile */
        @media (max-width: 1199px) {
            #sidebar {
                transform: translateX(-120%);
                left: 0; top: 0; bottom: 0;
                border-radius: 0;
            }
            #sidebar.active { transform: translateX(0); }
            #main { margin-left: 0; padding: 1rem; }
        }

        /* --- 4. UTILS --- */
        .box-center { display: flex !important; align-items: center !important; justify-content: center !important; }
        .bi { line-height: 1 !important; display: inline-flex; }
        
        .avatar-ring {
            padding: 2px;
            border: 2px solid var(--primary);
            border-radius: 50%;
        }

        /* Navbar Clean */
        .navbar-clean {
            background: transparent;
            padding: 0.5rem 0;
            margin-bottom: 2rem;
        }
    </style>
</head>

<body>
    <script src="{{ asset('assets/static/js/initTheme.js') }}"></script>
    
    @php
        $setting = \App\Models\Setting::first();
    @endphp

    <div id="app">
        
        <!-- SIDEBAR FLOATING -->
        <div id="sidebar" class="active">
            <!-- Header Logo -->
            <div class="sidebar-header d-flex align-items-center gap-3">
                <div class="bg-primary text-white rounded-4 box-center shadow-lg" style="width: 48px; height: 48px;">
                    <i class="bi bi-basket-fill fs-4"></i>
                </div>
                <div style="line-height: 1.2;">
                    <h5 class="fw-heading mb-0 text-dark">{{ $setting->shop_name ?? 'LaundryKuy' }}</h5>
                    <small class="text-muted fw-bold" style="font-size: 0.7rem; letter-spacing: 1px;">DASHBOARD</small>
                </div>
            </div>

            <!-- Menu List -->
            <div class="sidebar-menu">
                <ul class="list-unstyled mt-2">
                    <li class="sidebar-title">Utama</li>
                    <li class="sidebar-item {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                        <a href="{{ route('dashboard') }}" class="sidebar-link">
                            <i class="bi bi-grid-fill"></i> <span>Overview</span>
                        </a>
                    </li>
                    <li class="sidebar-item {{ request()->routeIs('transactions*') ? 'active' : '' }}">
                        <a href="{{ route('transactions.index') }}" class="sidebar-link">
                            <i class="bi bi-receipt"></i> <span>Transaksi</span>
                        </a>
                    </li>
                    <li class="sidebar-item {{ request()->routeIs('customers*') ? 'active' : '' }}">
                        <a href="{{ route('customers.index') }}" class="sidebar-link">
                            <i class="bi bi-people-fill"></i> <span>Pelanggan</span>
                        </a>
                    </li>

                    @if(auth()->user()->role !== 'staff')
                        <li class="sidebar-title">Manajemen</li>
                        <li class="sidebar-item {{ request()->routeIs('services*') ? 'active' : '' }}">
                            <a href="{{ route('services.index') }}" class="sidebar-link">
                                <i class="bi bi-basket2-fill"></i> <span>Paket Laundry</span>
                            </a>
                        </li>
                        <li class="sidebar-item {{ request()->routeIs('reports*') ? 'active' : '' }}">
                            <a href="{{ route('reports.index') }}" class="sidebar-link">
                                <i class="bi bi-file-earmark-bar-graph-fill"></i> <span>Laporan</span>
                            </a>
                        </li>
                    @endif

                    @if(auth()->user()->role === 'owner')
                        <li class="sidebar-title">Admin Zone</li>
                        <li class="sidebar-item {{ request()->routeIs('expenses*') ? 'active' : '' }}">
                            <a href="{{ route('expenses.index') }}" class="sidebar-link">
                                <i class="bi bi-wallet-fill"></i> <span>Pengeluaran</span>
                            </a>
                        </li>
                        <li class="sidebar-item {{ request()->routeIs('users*') ? 'active' : '' }}">
                            <a href="{{ route('users.index') }}" class="sidebar-link">
                                <i class="bi bi-shield-lock-fill"></i> <span>Users & Role</span>
                            </a>
                        </li>
                        <li class="sidebar-item {{ request()->routeIs('settings*') ? 'active' : '' }}">
                            <a href="{{ route('settings.index') }}" class="sidebar-link">
                                <i class="bi bi-gear-fill"></i> <span>Pengaturan</span>
                            </a>
                        </li>
                    @endif
                </ul>
            </div>

            <!-- Footer Profile -->
            <div class="sidebar-footer">
                <div class="d-flex align-items-center justify-content-between">
                    <div class="d-flex align-items-center gap-3">
                        <div class="avatar-ring">
                            <div class="avatar bg-primary text-white rounded-circle box-center" style="width: 38px; height: 38px;">
                                <span class="fw-bold">{{ substr(auth()->user()->name, 0, 1) }}</span>
                            </div>
                        </div>
                        <div style="line-height: 1.3;">
                            <h6 class="mb-0 fw-bold text-dark fs-6">{{ explode(' ', auth()->user()->name)[0] }}</h6>
                            <small class="text-muted d-block" style="font-size: 0.7rem;">{{ ucfirst(auth()->user()->role) }}</small>
                        </div>
                    </div>
                    
                    <a href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();" 
                       class="btn btn-light rounded-circle text-danger shadow-sm box-center" style="width: 36px; height: 36px;" data-bs-toggle="tooltip" title="Logout">
                        <i class="bi bi-box-arrow-right"></i>
                    </a>
                </div>
            </div>
        </div>

        <!-- MAIN CONTENT -->
        <div id="main">
            <!-- Navbar Mobile Only -->
            <header class="d-xl-none mb-4">
                <nav class="navbar navbar-light bg-white rounded-4 shadow-sm p-3">
                    <div class="container-fluid p-0">
                        <a href="#" class="burger-btn d-block text-primary">
                            <i class="bi bi-justify fs-2"></i>
                        </a>
                        <span class="fw-bold text-dark">LaundryKuy</span>
                    </div>
                </nav>
            </header>

            <div class="d-flex justify-content-between align-items-center mb-4 ps-2">
                <div>
                    <h3 class="fw-heading mb-1">@yield('page-title')</h3>
                    <p class="text-muted small mb-0">Selamat datang kembali, mari produktif hari ini!</p>
                </div>
                <!-- Action Button Optional -->
                <div class="d-none d-md-block">
                    <div class="bg-white px-3 py-2 rounded-pill shadow-sm border d-flex align-items-center gap-2">
                        <i class="bi bi-calendar-check text-primary"></i>
                        <span class="fw-bold text-dark small">{{ date('d M Y') }}</span>
                    </div>
                </div>
            </div>

            <section class="section">
                @yield('content')
            </section>
        </div>
    </div>
    
    <!-- Logout Form -->
    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">@csrf</form>

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

        // Tooltip Init
        var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
        var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl)
        })
    </script>
</body>
</html>