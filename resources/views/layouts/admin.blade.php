<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title') - Laundry System</title>
    
    <link rel="stylesheet" href="{{ asset('assets/compiled/css/app.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/compiled/css/app-dark.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/compiled/css/iconly.css') }}">
    
    <link rel="shortcut icon" href="{{ asset('assets/static/images/logo/favicon.svg') }}" type="image/x-icon">
</head>

<body>
    <script src="{{ asset('assets/static/js/initTheme.js') }}"></script>
    <div id="app">
        <div id="sidebar">
            <div class="sidebar-wrapper active">
                <div class="sidebar-header position-relative">
                    <div class="d-flex justify-content-between align-items-center">
                        <div class="logo">
                            <a href="#">Laundry<span class="text-primary">Kuy</span></a>
                        </div>
                        <div class="theme-toggle d-flex gap-2  align-items-center mt-2">
                            <div class="form-check form-switch fs-6">
                                <input class="form-check-input  me-0" type="checkbox" id="toggle-dark" >
                                <label class="form-check-label" ></label>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="sidebar-menu">
                    <ul class="menu">
                        <li class="sidebar-title">Menu Utama</li>

                        <li class="sidebar-item">
                            <a href="{{ url('/admin/dashboard') }}" class='sidebar-link'>
                                <i class="bi bi-grid-fill"></i>
                                <span>Dashboard</span>
                            </a>
                        </li>
                        <li class="sidebar-item">
    <a href="{{ route('services.index') }}" class='sidebar-link'>
        <i class="bi bi-basket-fill"></i>
        <span>Paket Laundry</span>
    </a>
</li>

<li class="sidebar-item">
    <a href="{{ route('transactions.index') }}" class='sidebar-link'>
        <i class="bi bi-receipt"></i>
        <span>Transaksi Kasir</span>
    </a>
</li>
<li class="sidebar-item">
    <a href="{{ route('expenses.index') }}" class='sidebar-link'>
        <i class="bi bi-wallet2"></i>
        <span>Pengeluaran (Kas)</span>
    </a>
</li>

<li class="sidebar-item">
    <a href="{{ route('reports.index') }}" class='sidebar-link'>
        <i class="bi bi-file-earmark-bar-graph"></i>
        <span>Laporan Keuangan</span>
    </a>
</li>

<li class="sidebar-item">
    <a href="{{ route('customers.index') }}" class='sidebar-link'>
        <i class="bi bi-people-fill"></i>
        <span>Data Pelanggan</span>
    </a>
</li>
                        </ul>
                        
                </div>
            </div>
        </div>

        <div id="main">
            <header class="mb-3">
                <a href="#" class="burger-btn d-block d-xl-none">
                    <i class="bi bi-justify fs-3"></i>
                </a>
            </header>

            <div class="page-heading">
                <h3>@yield('page-title')</h3>
            </div>
            
            <div class="page-content">
                @yield('content')
            </div>
        </div>
    </div>
    
    <script src="{{ asset('assets/static/js/components/dark.js') }}"></script>
    <script src="{{ asset('assets/extensions/perfect-scrollbar/perfect-scrollbar.min.js') }}"></script>
    <script src="{{ asset('assets/compiled/js/app.js') }}"></script>
</body>
</html>