@extends('layouts.customer')

@section('title', 'Dashboard Pelanggan')

@section('content')

{{-- Header Sapaan (Mobile & Desktop) --}}
<div class="d-md-none mb-4 mt-2">
    <h4 class="mb-0 fw-bold text-dark">Hai, {{ strtok(Auth::user()->name, " ") }}! 👋</h4>
    <p class="text-muted small">Mau cuci apa hari ini?</p>
</div>

{{-- BANNER BESAR (Desktop Only) --}}
<div class="d-none d-md-block mb-4">
    <div class="card bg-primary text-white border-0 shadow" style="background: linear-gradient(45deg, #435ebe, #25396f);">
        <div class="card-body p-5 d-flex justify-content-between align-items-center">
            <div>
                <h2 class="fw-bold text-white">Laundry Bersih, Hidup Santai</h2>
                <p class="mb-0 text-white-50 fs-5">Serahkan cucian kotor, kami jemput & antar kembali wangi.</p>
            </div>
            <div>
                <a href="{{ route('customer.order.create') }}" class="btn btn-light text-primary btn-lg fw-bold shadow-sm rounded-pill px-4">
                    <i class="bi bi-plus-lg me-2"></i> Buat Pesanan Baru
                </a>
            </div>
        </div>
    </div>
</div>

<div class="row g-4">
    {{-- KOLOM KIRI: STATUS & MENU --}}
    <div class="col-md-4">
        
        {{-- Statistik Grid --}}
        <div class="row g-3 mb-4">
            <div class="col-6">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-body text-center py-4">
                        <div class="avatar avatar-lg bg-light-primary text-primary mb-2 mx-auto">
                            <i class="bi bi-basket-fill fs-4"></i>
                        </div>
                        <h3 class="fw-bold mb-0">{{ $myOrders->where('status', 'process')->count() }}</h3>
                        <small class="text-muted fw-bold">DIPROSES</small>
                    </div>
                </div>
            </div>
            <div class="col-6">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-body text-center py-4">
                        <div class="avatar avatar-lg bg-light-success text-success mb-2 mx-auto">
                            <i class="bi bi-check-lg fs-4"></i>
                        </div>
                        <h3 class="fw-bold mb-0">{{ $myOrders->where('status', 'ready')->count() }}</h3>
                        <small class="text-muted fw-bold">SIAP AMBIL</small>
                    </div>
                </div>
            </div>
        </div>

        {{-- Kartu Promo (Dipercantik) --}}
        <div class="card border-0 bg-light-warning shadow-sm mb-4">
            <div class="card-body p-4">
                <div class="d-flex align-items-start gap-3">
                    <div class="p-3 bg-white rounded-circle shadow-sm text-warning">
                        <i class="bi bi-megaphone-fill fs-4"></i>
                    </div>
                    <div>
                        <h6 class="fw-bold text-dark mb-1">Promo Pengguna Baru! 🎉</h6>
                        <p class="mb-0 text-muted small lh-sm">Gratis antar-jemput untuk pesanan pertamamu (Min. 5kg).</p>
                    </div>
                </div>
            </div>
        </div>

        {{-- Tombol Mobile Only --}}
        <div class="d-block d-md-none">
            <a href="{{ route('customer.order.create') }}" class="btn btn-primary w-100 py-3 fw-bold rounded-3 shadow">
                <i class="bi bi-truck me-2"></i> PANGGIL KURIR
            </a>
        </div>
    </div>

    {{-- KOLOM KANAN: RIWAYAT TRANSAKSI (DIBUNGKUS CARD BIAR RAPI) --}}
    <div class="col-md-8">
        <div class="card border-0 shadow-sm h-100" style="min-height: 400px;">
            <div class="card-header bg-white border-0 pt-4 px-4 d-flex justify-content-between align-items-center">
                <h5 class="fw-bold mb-0"><i class="bi bi-clock-history text-primary me-2"></i> Riwayat Pesanan</h5>
                @if($myOrders->count() > 0)
                    <span class="badge bg-light text-muted border">{{ $myOrders->count() }} Transaksi</span>
                @endif
            </div>
            
            <div class="card-body px-4 pb-4">
                @forelse($myOrders as $order)
                    {{-- Item List Transaksi --}}
                    <div class="card border mb-3 hover-scale transition-300">
                        <div class="card-body p-3">
                            <div class="row align-items-center">
                                <div class="col-auto">
                                    <div class="rounded p-3 text-center text-white {{ $order->status == 'done' ? 'bg-success' : 'bg-primary' }}" style="width: 60px; height: 60px; display: grid; place-items: center;">
                                        <i class="bi {{ $order->status == 'done' ? 'bi-check-lg' : 'bi-basket' }} fs-3"></i>
                                    </div>
                                </div>
                                <div class="col">
                                    <div class="d-flex justify-content-between mb-1">
                                        <h6 class="fw-bold mb-0 text-dark">Invoice #{{ $order->invoice_code }}</h6>
                                        <small class="text-muted">{{ $order->created_at->diffForHumans() }}</small>
                                    </div>
                                    <div class="d-flex gap-2 mb-2">
                                        {{-- Badge Status Laundry --}}
                                        @if($order->status == 'pending') <span class="badge bg-light-secondary text-secondary">Menunggu</span>
                                        @elseif($order->status == 'process') <span class="badge bg-light-info text-info">Dicuci</span>
                                        @elseif($order->status == 'ready') <span class="badge bg-light-warning text-warning">Siap Ambil</span>
                                        @elseif($order->status == 'done') <span class="badge bg-light-success text-success">Selesai</span>
                                        @endif

                                        {{-- Badge Status Kurir --}}
                                        @if($order->delivery_type != 'none')
                                            @if($order->delivery_status == 'pending') <span class="badge bg-warning text-dark"><i class="bi bi-search"></i> Cari Kurir</span>
                                            @elseif($order->delivery_status == 'on_the_way') <span class="badge bg-primary"><i class="bi bi-scooter"></i> OTW</span>
                                            @endif
                                        @endif
                                    </div>
                                </div>
                                <div class="col-auto text-end">
                                    <h5 class="fw-bold text-primary mb-0">
                                        {{ $order->total_price == 0 ? '-' : 'Rp '.number_format($order->total_price/1000).'rb' }}
                                    </h5>
                                    <small class="text-muted d-block" style="font-size: 0.75rem">Total Biaya</small>
                                </div>
                            </div>
                        </div>
                    </div>
                @empty
                    {{-- EMPTY STATE (Tampilan Kalau Kosong) --}}
                    <div class="text-center py-5 mt-4">
                        <div class="mb-3">
                            <div class="avatar avatar-xl bg-light text-muted p-4 rounded-circle">
                                <i class="bi bi-clipboard-x fs-1"></i>
                            </div>
                        </div>
                        <h5 class="fw-bold text-dark">Belum Ada Pesanan</h5>
                        <p class="text-muted mb-4">Cucian numpuk? Yuk panggil kurir sekarang,<br>biar kami yang beresin!</p>
                        <a href="{{ route('customer.order.create') }}" class="btn btn-outline-primary rounded-pill px-4">
                            Buat Pesanan Pertama
                        </a>
                    </div>
                @endforelse
            </div>
        </div>
    </div>
</div>

<style>
    .hover-scale:hover { transform: translateY(-3px); box-shadow: 0 .5rem 1rem rgba(0,0,0,.05)!important; border-color: #435ebe!important; }
    .transition-300 { transition: all 0.3s ease; }
</style>

@endsection