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
    
    <link rel="shortcut icon" href="{{ asset('assets/static/images/logo/favicon.svg') }}" type="image/x-icon">

    <style>
        /* Biar logo di sidebar gak gepeng */
        .logo-img {
            max-height: 40px;
            width: auto;
        }
    </style>
</head>

<body>
    <script src="{{ asset('assets/static/js/initTheme.js') }}"></script>
    
    @php
        $setting = \App\Models\Setting::first();
    @endphp

    <div id="app">
        <div id="sidebar">
            <div class="sidebar-wrapper active">
                <div class="sidebar-header position-relative">
                    <div class="d-flex justify-content-between align-items-center">
                        <div class="logo">
                            <a href="{{ route('dashboard') }}" class="d-flex align-items-center gap-2 text-decoration-none">
                                @if($setting && $setting->logo)
                                    <img src="{{ asset('storage/'.$setting->logo) }}" class="logo-img" alt="Logo">
                                @endif
                                <span class="fs-5">{{ $setting->shop_name ?? 'LaundryKuy' }}</span>
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
                        <li class="sidebar-title">Menu Utama</li>

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
                            <li class="sidebar-title">Manajemen</li>
                            
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
                            <li class="sidebar-title">Area Owner</li>

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

                        <li class="sidebar-title">Akun</li>
                        <li class="sidebar-item">
                            <a class="sidebar-link text-danger" href="{{ route('logout') }}"
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
                <nav class="navbar navbar-expand navbar-light bg-white shadow-sm py-3">
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
                                        <div class="d-flex align-items-center gap-3"> <div class="user-name text-end" style="line-height: 1.2;"> <h6 class="mb-0 text-gray-600 fw-bold" style="font-size: 0.9rem;">{{ auth()->user()->name }}</h6>
                                                <p class="mb-0 text-sm text-gray-600">{{ ucfirst(auth()->user()->role) }}</p>
                                            </div>
                                            
                                            <div class="avatar bg-primary text-white d-flex align-items-center justify-content-center shadow-sm" 
                                                 style="width: 40px; height: 40px; min-width: 40px; border-radius: 50%;">
                                                <span class="fw-bold" style="font-size: 1.1rem; margin-top: -2px;">{{ substr(auth()->user()->name, 0, 1) }}</span>
                                            </div>

                                        </div>
                                    </a>

                                    <ul class="dropdown-menu dropdown-menu-end shadow-sm" aria-labelledby="dropdownMenuButton" style="min-width: 200px;">
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
                    <div class="page-title">
                        <div class="row">
                            <div class="col-12 col-md-6 order-md-1 order-last">
                                <h3>@yield('page-title')</h3>
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
            }).showToast();
        @endif
    </script>
</body>
</html>