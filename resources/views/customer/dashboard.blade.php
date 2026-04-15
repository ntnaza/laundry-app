@extends('layouts.customer')

@section('title', 'Dashboard Pelanggan')

@section('content')

{{-- Script Midtrans Simulator --}}
<script src="https://app.sandbox.midtrans.com/snap/snap.js" data-client-key="{{ config('midtrans.client_key') }}"></script>

{{-- HEADER MOBILE --}}
<div class="d-md-none mb-4 animate__animated animate__fadeInDown">
    <div class="d-flex justify-content-between align-items-center">
        <div>
            <h3 class="fw-heading mb-0">Hai, {{ strtok(Auth::user()->name, " ") }}! 👋</h3>
            <p class="text-muted small mb-0">Pakaian bersih menantimu.</p>
        </div>
        <div class="bg-white text-primary fw-bold rounded-circle box-center shadow-sm overflow-hidden" style="width: 45px; height: 45px; border: 2px solid #f1f5f9;">
            {!! Auth::user()->getAvatarHtml('45px', '1.2rem') !!}
        </div>
    </div>
</div>

<div class="row g-4">
    {{-- KOLOM KIRI (Banner & List) --}}
    <div class="col-lg-8">
        {{-- BANNER BESAR (V6 - DEEP CONTRAST STAR) --}}
        <div class="card border-0 text-white shadow-lg mb-4 overflow-hidden rounded-4 position-relative animate__animated animate__fadeIn" style="background: linear-gradient(135deg, var(--primary) 0%, #0f172a 100%); min-height: 240px;">
            
            {{-- Deep Shadow Decor (Biar Bintang Lebih Nyala) --}}
            <div class="position-absolute top-0 end-0 bg-dark opacity-20 rounded-circle" style="width: 500px; height: 500px; margin-top: -150px; margin-right: -150px; filter: blur(100px);"></div>

            <div class="card-body p-4 p-md-5 position-relative z-1 d-flex flex-column justify-content-center">
                <div class="row align-items-center">
                    <div class="col-md-8">
                        <div class="mb-3 d-flex align-items-center gap-2">
                            <span class="badge bg-white bg-opacity-10 border border-white border-opacity-10 rounded-pill px-3 py-1 fw-bold" style="font-size: 0.75rem; letter-spacing: 1px;">PREMIUM LAUNDRY</span>
                        </div>
                        
                        <h2 class="fw-heading text-white mb-3 display-6" style="letter-spacing: -0.5px;">Cucian Bersih & Wangi, <br>Tanpa Harus Repot.</h2>
                        <p class="text-white-50 mb-4 fs-6" style="max-width: 500px; opacity: 0.7;">Cukup pesan dari rumah, kurir kami jemput & antar kembali dalam keadaan rapi dan wangi.</p>
                        
                        <div class="d-flex gap-3">
                            <a href="{{ route('customer.order.create') }}" class="btn btn-white text-primary rounded-pill px-4 py-3 fw-bold shadow-lg d-inline-flex align-items-center gap-2 hover-up transition-300">
                                <i class="bi bi-plus-lg fs-5 d-flex align-items-center justify-content-center"></i>
                                <span>Pesan Sekarang</span>
                            </a>
                        </div>
                    </div>

                    {{-- Icon Bintang Kinclong (Golden Glow on Dark) --}}
                    <div class="col-md-4 d-none d-md-flex justify-content-center position-relative">
                        <div class="animate__animated animate__pulse animate__infinite" style="animation-duration: 4s;">
                            <i class="bi bi-stars text-warning" style="font-size: 10rem; filter: drop-shadow(0 0 30px rgba(245, 158, 11, 0.6));"></i>
                        </div>
                        {{-- Aksen Bintang Kecil --}}
                        <i class="bi bi-star-fill position-absolute text-warning opacity-50 animate__animated animate__flash animate__infinite" style="top: 15%; right: 15%; font-size: 1.5rem; animation-duration: 3s;"></i>
                        <i class="bi bi-star-fill position-absolute text-white opacity-20" style="bottom: 15%; left: 10%; font-size: 1rem;"></i>
                    </div>
                </div>
            </div>
        </div>

        <style>
            .hover-up:hover { transform: translateY(-3px); }
        </style>

        {{-- STATUS ORDERS ROW (PREMIUM VIBRANT) --}}
        <div class="row g-3 mb-4">
            {{-- Pending --}}
            <div class="col-6 col-md-3">
                <div class="card border-0 shadow-sm rounded-4 h-100 p-3 text-center hover-top transition-300 position-relative overflow-hidden bg-white">
                    <div class="mb-3">
                        <div class="rounded-circle box-center mx-auto shadow-sm text-white" style="width: 55px; height: 55px; background: linear-gradient(135deg, #64748b 0%, #475569 100%);">
                            <i class="bi bi-hourglass-split fs-4"></i>
                        </div>
                    </div>
                    <h3 class="fw-bold text-dark mb-0">{{ $myOrders->where('status', 'pending')->count() }}</h3>
                    <span class="text-muted small fw-bold text-uppercase ls-1" style="font-size: 0.7rem;">Pending</span>
                </div>
            </div>
            {{-- Dicuci --}}
            <div class="col-6 col-md-3">
                <div class="card border-0 shadow-sm rounded-4 h-100 p-3 text-center hover-top transition-300 position-relative overflow-hidden bg-white">
                    <div class="mb-3">
                        <div class="rounded-circle box-center mx-auto shadow-sm text-white" style="width: 55px; height: 55px; background: linear-gradient(135deg, var(--primary) 0%, var(--primary-dark) 100%);">
                            <i class="bi bi-water fs-4"></i>
                        </div>
                    </div>
                    <h3 class="fw-bold text-dark mb-0">{{ $myOrders->where('status', 'process')->count() }}</h3>
                    <span class="text-muted small fw-bold text-uppercase ls-1" style="font-size: 0.7rem;">Dicuci</span>
                </div>
            </div>
            {{-- Siap Ambil --}}
            <div class="col-6 col-md-3">
                <div class="card border-0 shadow-sm rounded-4 h-100 p-3 text-center hover-top transition-300 position-relative overflow-hidden bg-white">
                    <div class="mb-3">
                        <div class="rounded-circle box-center mx-auto shadow-sm text-white" style="width: 55px; height: 55px; background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);">
                            <i class="bi bi-bag-check-fill fs-4"></i>
                        </div>
                    </div>
                    <h3 class="fw-bold text-dark mb-0">{{ $myOrders->where('status', 'ready')->count() }}</h3>
                    <span class="text-muted small fw-bold text-uppercase ls-1" style="font-size: 0.7rem;">Siap Ambil</span>
                </div>
            </div>
            {{-- Selesai --}}
            <div class="col-6 col-md-3">
                <div class="card border-0 shadow-sm rounded-4 h-100 p-3 text-center hover-top transition-300 position-relative overflow-hidden bg-white">
                    <div class="mb-3">
                        <div class="rounded-circle box-center mx-auto shadow-sm text-white" style="width: 55px; height: 55px; background: linear-gradient(135deg, #10b981 0%, #059669 100%);">
                            <i class="bi bi-check-circle-fill fs-4"></i>
                        </div>
                    </div>
                    <h3 class="fw-bold text-dark mb-0">{{ $myOrders->where('status', 'done')->count() }}</h3>
                    <span class="text-muted small fw-bold text-uppercase ls-1" style="font-size: 0.7rem;">Selesai</span>
                </div>
            </div>
        </div>

        {{-- RIWAYAT PESANAN --}}
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h5 class="fw-heading mb-0">Riwayat Pesanan</h5>
            <span class="badge bg-light text-muted border rounded-pill px-3">{{ $myOrders->count() }} Transaksi</span>
        </div>

        <div class="d-flex flex-column gap-3 mb-5">
            @forelse($myOrders as $order)
                <div class="card border-0 shadow-sm rounded-4 overflow-hidden hover-bg-light transition-300 group-hover-parent">
                    <div class="card-body p-3">
                        <div class="row align-items-center g-3">
                            {{-- Ikon Status --}}
                            <div class="col-auto">
                                <div class="{{ $order->status == 'done' ? 'bg-success bg-opacity-10 text-success' : ($order->status == 'ready' ? 'bg-warning bg-opacity-10 text-warning' : 'bg-primary bg-opacity-10 text-primary') }} rounded-4 box-center shadow-sm" style="width: 60px; height: 60px; font-size: 1.5rem;">
                                    <i class="bi {{ $order->status == 'done' ? 'bi-check-lg' : ($order->status == 'ready' ? 'bi-bag-check' : 'bi-basket2') }}"></i>
                                </div>
                            </div>
                            
                            {{-- Info Utama --}}
                            <div class="col">
                                <div class="d-flex flex-wrap align-items-center gap-2 mb-1">
                                    <span class="fw-bold text-dark font-monospace">#{{ $order->invoice_code }}</span>
                                    
                                    {{-- LOGIKA STATUS DETAILED (DIKEMBALIKAN LENGKAP) --}}
                                    @if($order->status == 'pending')
                                        @if($order->delivery_status == 'on_the_way')
                                            <span class="badge bg-primary bg-opacity-10 text-primary border border-primary rounded-pill blink px-2">
                                                <i class="bi bi-scooter me-1"></i> Jemput
                                            </span>
                                        @else
                                            <span class="badge bg-secondary bg-opacity-10 text-secondary border border-secondary rounded-pill px-2">
                                                <i class="bi bi-hourglass-split me-1"></i> Menunggu
                                            </span>
                                        @endif

                                    @elseif($order->status == 'process') 
                                        <span class="badge bg-info bg-opacity-10 text-info border border-info rounded-pill px-2">
                                            <i class="bi bi-water me-1"></i> Dicuci
                                        </span>

                                    @elseif($order->status == 'ready')
                                        @if($order->delivery_status == 'on_the_way')
                                            <span class="badge bg-primary bg-opacity-10 text-primary border border-primary rounded-pill blink px-2">
                                                <i class="bi bi-truck me-1"></i> Diantar
                                            </span>
                                        @else
                                            <span class="badge bg-warning bg-opacity-10 text-warning border border-warning rounded-pill px-2">
                                                <i class="bi bi-box-seam me-1"></i> Siap
                                            </span>
                                        @endif

                                    @elseif($order->status == 'done') 
                                        <span class="badge bg-success bg-opacity-10 text-success border border-success rounded-pill px-2">
                                            <i class="bi bi-check-circle-fill me-1"></i> Selesai
                                        </span>
                                    @endif
                                </div>
                                <div class="text-muted small d-flex gap-2 align-items-center">
                                    <span><i class="bi bi-calendar3 me-1"></i> {{ $order->created_at->format('d M') }}</span>
                                    <span class="vr"></span>
                                    {{-- Info Bayar --}}
                                    @if($order->payment_status == 'paid')
                                        <span class="text-success fw-bold"><i class="bi bi-check-all me-1"></i> Lunas</span>
                                    @elseif($order->payment_status == 'unpaid' && $order->total_price > 0)
                                        <span class="text-danger fw-bold"><i class="bi bi-exclamation-circle me-1"></i> Belum Bayar</span>
                                    @else
                                        <span class="text-muted">Menunggu Admin</span>
                                    @endif
                                </div>
                            </div>

                            {{-- Harga & Aksi (Dikembalikan Lengkap) --}}
                            <div class="col-12 col-md-auto text-md-end border-top border-md-0 pt-3 pt-md-0 mt-2 mt-md-0">
                                @php
                                    // HITUNG MANUAL BIAR PASTI (Subtotal - Diskon)
                                    // Abaikan total_price dari DB yang mungkin tercemar ongkir data lama
                                    $realTotal = $order->subtotal - $order->discount_amount;
                                    if($realTotal < 0) $realTotal = 0;
                                @endphp

                                @if($order->subtotal > 0)
                                    @if($order->discount_amount > 0)
                                        <small class="text-muted text-decoration-line-through d-block" style="font-size: 0.75rem;">
                                            Rp {{ number_format($order->subtotal) }}
                                        </small>
                                    @endif
                                    <h6 class="fw-bold text-primary mb-1">
                                        Rp {{ number_format($realTotal) }}
                                    </h6>
                                @else
                                    <h6 class="fw-bold text-muted mb-1">-</h6>
                                @endif
                                
                                <div class="d-flex gap-2 justify-content-md-end mt-2">
                                    {{-- Tombol Detail --}}
                                    <button class="btn btn-sm btn-light text-primary rounded-pill fw-bold px-3 border" data-bs-toggle="modal" data-bs-target="#detailModal{{ $order->id }}">
                                        Detail
                                    </button>

                                    {{-- LOGIKA TOMBOL AKSI (PERBAIKAN) --}}
                                    @if($order->status == 'draft')
                                        {{-- Masih Draft artinya Ongkir Belum Dibayar --}}
                                        <button type="button" class="btn btn-sm btn-outline-primary rounded-pill px-3 fw-bold shadow-sm" onclick="payOrder({{ $order->id }})">
                                            Bayar Ongkir
                                        </button>

                                    @elseif($order->status == 'pending' && $order->details->count() == 0)
                                        {{-- Sudah Bayar Ongkir (Pending) tapi item kosong -> Lanjut Input Item --}}
                                        <a href="{{ route('customer.order.create', ['resume_id' => $order->id]) }}" class="btn btn-sm btn-warning rounded-pill px-3 fw-bold shadow-sm hover-top text-dark">
                                            <i class="bi bi-cart-plus me-1"></i> Lanjut
                                        </a>

                                    @elseif($order->payment_status == 'unpaid' && $order->total_price > 0)
                                        {{-- Sudah ada total (sudah ditimbang/input) tapi belum lunas --}}
                                        @if($order->payment_method == 'cash')
                                            <span class="badge bg-warning bg-opacity-10 text-dark border border-warning rounded-pill px-3 py-2">
                                                <i class="bi bi-cash-stack me-1"></i> COD
                                            </span>
                                        @else
                                            <button type="button" class="btn btn-sm btn-primary rounded-pill px-3 fw-bold shadow-sm hover-top" onclick="payOrder({{ $order->id }})">
                                                Bayar Cucian
                                            </button>
                                        @endif
                                    @endif

                                    {{-- Tombol Review --}}
                                    @if($order->status == 'done' && !$order->testimonial)
                                        <button type="button" class="btn btn-sm btn-outline-warning text-dark rounded-pill fw-bold px-3 shadow-sm hover-top" data-bs-toggle="modal" data-bs-target="#reviewModal{{ $order->id }}">
                                            <i class="bi bi-star-fill me-1"></i> Ulas
                                        </button>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- MODAL REVIEW (Dikembalikan) --}}
                <div class="modal fade" id="reviewModal{{ $order->id }}" tabindex="-1">
                    <div class="modal-dialog modal-dialog-centered">
                        <div class="modal-content rounded-4 border-0 shadow-lg">
                            <div class="modal-body text-center p-5">
                                <div class="mb-4">
                                    <i class="bi bi-star-fill text-warning" style="font-size: 4rem;"></i>
                                </div>
                                <h4 class="fw-heading mb-2">Beri Rating Layanan</h4>
                                <p class="text-muted small mb-4">Seberapa puas kamu dengan hasil cucian invoice #{{ $order->invoice_code }}?</p>
                                
                                <form action="{{ route('customer.order.review', $order->id) }}" method="POST">
                                    @csrf
                                    <div class="rating-input mb-4">
                                        @for($i=5; $i>=1; $i--)
                                            <input type="radio" name="rate" id="r{{$order->id}}-{{$i}}" value="{{$i}}" required>
                                            <label for="r{{$order->id}}-{{$i}}" class="fs-2">
                                                <i class="bi bi-star-fill"></i>
                                            </label>
                                        @endfor
                                    </div>
                                    <div class="form-floating mb-4">
                                        <textarea name="content" class="form-control bg-light border-0 rounded-3" placeholder="Tulis komentar..." style="height: 100px" required></textarea>
                                        <label>Tulis pengalamanmu...</label>
                                    </div>
                                    <div class="d-flex gap-2">
                                        <button type="button" class="btn btn-light w-50 rounded-pill fw-bold text-muted" data-bs-dismiss="modal">Batal</button>
                                        <button type="submit" class="btn btn-primary w-50 rounded-pill fw-bold shadow-sm">Kirim</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- MODAL DETAIL --}}
                <div class="modal fade" id="detailModal{{ $order->id }}" tabindex="-1">
                    <div class="modal-dialog modal-dialog-centered">
                        <div class="modal-content rounded-4 border-0 shadow-lg">
                            <div class="modal-header border-bottom-0 pb-0">
                                <h5 class="modal-title fw-bold">Rincian Pesanan #{{ $order->invoice_code }}</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                            </div>
                            <div class="modal-body">
                                <div class="bg-light p-3 rounded-3 mb-3 d-flex justify-content-between align-items-center">
                                    <span class="text-muted small">Status</span>
                                    <span class="badge {{ $order->status == 'done' ? 'bg-success' : 'bg-primary' }} rounded-pill">{{ ucfirst($order->status) }}</span>
                                </div>
                                <ul class="list-group list-group-flush border rounded-3 mb-3">
                                    @forelse($order->details as $item)
                                        <li class="list-group-item d-flex justify-content-between align-items-center">
                                            <div>
                                                <span class="fw-bold text-dark">{{ $item->service->name }}</span>
                                                <small class="text-muted d-block">{{ $item->qty }} {{ $item->service->unit }} x {{ number_format($item->price_per_unit) }}</small>
                                            </div>
                                            <span class="fw-bold">Rp {{ number_format($item->subtotal) }}</span>
                                        </li>
                                    @empty
                                        <li class="list-group-item text-center text-muted small py-3">Menunggu penimbangan admin.</li>
                                    @endforelse
                                </ul>
                                {{-- Rincian Biaya --}}
                                <div class="border-top pt-3">
                                    <div class="d-flex justify-content-between mb-1 small text-muted">
                                        <span>Subtotal</span>
                                        <span>Rp {{ number_format($order->subtotal) }}</span>
                                    </div>
                                    
                                    {{-- TAMPILKAN ONGKIR (LUNAS) --}}
                                    @if($order->delivery_fee > 0)
                                        <div class="d-flex justify-content-between mb-1 small text-muted">
                                            <span>Ongkos Kirim <span class="badge bg-success bg-opacity-10 text-success border border-success rounded-pill" style="font-size: 0.6rem; padding: 2px 6px;">LUNAS</span></span>
                                            <span>Rp {{ number_format($order->delivery_fee) }}</span>
                                        </div>
                                    @endif

                                    @if($order->discount_amount > 0)
                                        <div class="d-flex justify-content-between mb-2 small text-danger fw-bold">
                                            <span>Diskon ({{ $order->promo->code ?? 'Voucher' }})</span>
                                            <span>- Rp {{ number_format($order->discount_amount) }}</span>
                                        </div>
                                    @endif
                                    <div class="d-flex justify-content-between align-items-center pt-2 border-top">
                                        <span class="fw-bold text-dark">Total Tagihan Laundry</span>
                                        <span class="fw-heading text-primary fs-4">
                                            Rp {{ number_format($order->subtotal - $order->discount_amount) }}
                                        </span>
                                    </div>
                                </div>
                                <div class="mt-4 d-grid gap-2">
                                    @if($order->total_price > 0 && $order->payment_status == 'unpaid' && $order->payment_method != 'cash')
                                        <button onclick="payOrder({{ $order->id }})" class="btn btn-primary rounded-pill fw-bold">Bayar Sekarang</button>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            @empty
                <div class="text-center py-5 bg-white rounded-4 shadow-sm border border-light">
                    <div class="bg-light rounded-circle box-center mx-auto mb-3" style="width: 80px; height: 80px;">
                        <i class="bi bi-basket3 text-muted opacity-25" style="font-size: 2.5rem;"></i>
                    </div>
                    <h6 class="fw-bold text-dark">Belum Ada Pesanan</h6>
                    <p class="text-muted mb-3 small">Yuk buat pesanan pertamamu sekarang!</p>
                </div>
            @endforelse
        </div>
    </div>

    {{-- KOLOM KANAN (Widgets) --}}
    <div class="col-lg-4">
        {{-- Point Reward Card --}}
        <div class="card border-0 shadow-lg mb-4 rounded-4 overflow-hidden position-relative bg-dark text-white hover-top transition-300">
            {{-- Decorative Trophy (Fixed: Top Right, Very Faint, No Overlap) --}}
            <div class="position-absolute top-0 end-0" style="opacity: 0.05; margin-right: -10px; margin-top: -5px; pointer-events: none; z-index: 0;">
                <i class="bi bi-trophy-fill text-white" style="font-size: 8rem; font-style: normal !important;"></i>
            </div>
            
            <div class="card-body p-4 position-relative" style="z-index: 2;">
                <div class="d-flex align-items-center gap-3 mb-3">
                    <div class="bg-warning text-dark rounded-circle shadow-sm box-center" style="width: 50px; height: 50px; font-size: 1.5rem;">
                        <i class="bi bi-coin"></i>
                    </div>
                    <div>
                        <h6 class="fw-bold text-white mb-0 opacity-75 small text-uppercase ls-1">Poin Member</h6>
                        <h3 class="fw-bold text-warning mb-0">{{ number_format(Auth::user()->customer->points ?? 0) }} <small class="text-white opacity-50 fs-6 fw-normal">Poin</small></h3>
                    </div>
                </div>
                <div class="progress bg-white bg-opacity-10 rounded-pill" style="height: 6px;">
                    <div class="progress-bar bg-warning shadow-sm" role="progressbar" style="width: {{ min((Auth::user()->customer->points ?? 0) / 1000 * 100, 100) }}%"></div>
                </div>
                <p class="mt-3 mb-0 text-white opacity-75 small lh-sm">Kumpulkan <span class="text-warning fw-bold">1.000 poin</span> untuk klaim diskon spesial!</p>
            </div>
        </div>

        {{-- Promo Carousel Widget --}}
        <div class="card border-0 shadow-sm mb-4 rounded-4 overflow-hidden position-relative">
            <div class="card-body p-0">
                @if($activePromos->count() > 0)
                    <div id="promoCarouselWidget" class="carousel slide" data-bs-ride="carousel">
                        {{-- Indicators (Dots) --}}
                        <div class="carousel-indicators mb-2">
                            @foreach($activePromos as $key => $promo)
                                <button type="button" data-bs-target="#promoCarouselWidget" data-bs-slide-to="{{ $key }}" class="{{ $key == 0 ? 'active' : '' }}" aria-label="Slide {{ $key+1 }}"></button>
                            @endforeach
                        </div>

                        <div class="carousel-inner">
                            @foreach($activePromos as $key => $promo)
                                <div class="carousel-item {{ $key == 0 ? 'active' : '' }} position-relative" data-bs-interval="5000">
                                    {{-- Background Image / Gradient --}}
                                    @if($promo->image)
                                        <div style="height: 200px; background-image: url('{{ asset('storage/' . $promo->image) }}'); background-size: cover; background-position: center;">
                                            <div class="position-absolute top-0 start-0 w-100 h-100" style="background: linear-gradient(to top, rgba(0,0,0,0.8), transparent);"></div>
                                        </div>
                                    @else
                                        <div class="d-flex align-items-center justify-content-center bg-primary" style="height: 200px; background: linear-gradient(45deg, var(--primary), #60a5fa);">
                                            <i class="bi bi-ticket-perforated text-white opacity-25" style="font-size: 8rem; transform: rotate(-15deg);"></i>
                                        </div>
                                    @endif

                                    {{-- Content Overlay --}}
                                    <div class="carousel-caption text-start p-3 w-100" style="bottom: 0; left: 0; right: 0;">
                                        <span class="badge bg-warning text-dark mb-2">Promo Spesial</span>
                                        <h5 class="fw-bold text-white mb-1 text-shadow">{{ $promo->description ?? 'Diskon Spesial Untukmu!' }}</h5>
                                        <div class="d-flex justify-content-between align-items-end mt-2">
                                            <div>
                                                <small class="text-white-50 d-block mb-1" style="font-size: 0.65rem; letter-spacing: 0.5px;">KODE VOUCHER</small>
                                                <div class="bg-white rounded px-3 py-1 d-inline-block cursor-pointer shadow-sm" onclick="copyCode('{{ $promo->code }}')" title="Salin Kode">
                                                    <span class="font-monospace fw-bold text-dark">{{ $promo->code }}</span> 
                                                    <i class="bi bi-copy ms-1 small text-dark opacity-50"></i>
                                                </div>
                                            </div>
                                            <div class="text-end">
                                                <span class="display-6 fw-bold text-warning" style="font-size: 1.5rem;">
                                                    {{ $promo->type == 'percentage' ? $promo->value.'%' : number_format($promo->value/1000).'K' }}
                                                </span>
                                                <small class="text-white d-block lh-1" style="font-size: 0.6rem;">OFF</small>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                        
                        {{-- Controls --}}
                        <button class="carousel-control-prev" type="button" data-bs-target="#promoCarouselWidget" data-bs-slide="prev">
                            <span class="carousel-control-prev-icon small" aria-hidden="true" style="filter: invert(1);"></span>
                            <span class="visually-hidden">Previous</span>
                        </button>
                        <button class="carousel-control-next" type="button" data-bs-target="#promoCarouselWidget" data-bs-slide="next">
                            <span class="carousel-control-next-icon small" aria-hidden="true" style="filter: invert(1);"></span>
                            <span class="visually-hidden">Next</span>
                        </button>
                    </div>
                @else
                    {{-- Empty State --}}
                    <div class="p-4 text-center">
                        <div class="bg-light rounded-circle box-center mx-auto mb-3" style="width: 60px; height: 60px;">
                            <i class="bi bi-tag text-muted opacity-50 fs-3"></i>
                        </div>
                        <h6 class="fw-bold text-dark">Belum Ada Promo</h6>
                        <p class="text-muted small mb-0">Nantikan diskon menarik segera!</p>
                    </div>
                @endif
            </div>
        </div>

        <script>
            function copyCode(code) {
                navigator.clipboard.writeText(code);
                Toastify({ text: "Kode " + code + " disalin!", duration: 2000, gravity: "bottom", position: "center", backgroundColor: "#435ebe", className: "rounded-pill shadow-lg" }).showToast();
            }
        </script>

        {{-- Info Layanan --}}
        <div class="card border-0 bg-white shadow-sm rounded-4 overflow-hidden">
            <div class="card-body p-4">
                <h6 class="fw-bold mb-3">Info Layanan</h6>
                <div class="d-flex align-items-center gap-3 mb-3">
                    <i class="bi bi-clock-history text-muted fs-5"></i>
                    <div>
                        <span class="d-block fw-bold text-dark small">Jam Operasional</span>
                        <small class="text-muted">{{ $setting->operating_hours ?? '08:00 - 21:00' }}</small>
                    </div>
                </div>
                <div class="d-flex align-items-center gap-3">
                    <i class="bi bi-whatsapp text-success fs-5"></i>
                    <div>
                        <span class="d-block fw-bold text-dark small">Butuh Bantuan?</span>
                        @php
                            $wa = $setting->phone ?? '628123456789';
                            if(str_starts_with($wa, '0')) $wa = '62'.substr($wa, 1);
                        @endphp
                        <a href="https://wa.me/{{ preg_replace('/[^0-9]/','',$wa) }}?text=Halo%20Admin,%20saya%20butuh%20bantuan%20terkait%20pesanan%20saya." target="_blank" class="text-decoration-none small text-success">Chat Admin via WhatsApp</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    function payOrder(orderId) {
        fetch(`/customer/order/${orderId}/pay`, {
            headers: { 'X-Requested-With': 'XMLHttpRequest' }
        })
        .then(res => res.json())
        .then(data => {
            if(data.snap_token) {
                snap.pay(data.snap_token, {
                    onSuccess: function(result){ location.reload(); },
                    onPending: function(result){ alert("Menunggu pembayaran!"); },
                    onError: function(result){ alert("Pembayaran gagal!"); }
                });
            } else {
                alert('Gagal memproses pembayaran: ' + (data.error || 'Token invalid'));
            }
        });
    }
</script>

<style>
    .hover-top:hover { transform: translateY(-3px); }
    .backdrop-blur { backdrop-filter: blur(5px); }
    .btn-white { background-color: white; border: 1px solid white; color: var(--primary); }
    .btn-white:hover { background-color: #f8f9fa; color: var(--primary); }
    
    /* Rating Star Logic - Robust Implementation */
    .rating-input {
        display: flex;
        flex-direction: row-reverse; /* Important for sibling selector logic */
        justify-content: center;
        gap: 0.5rem;
    }
    
    /* Strictly hide the radio button (removes the bullet) */
    .rating-input input {
        appearance: none;
        -webkit-appearance: none;
        display: none; 
    }
    
    .rating-input label {
        color: #e2e8f0; /* Default Gray */
        cursor: pointer;
        transition: color 0.2s ease-in-out;
    }
    
    /* Hover & Checked Effects */
    /* Because of row-reverse:
       - The DOM order is 5, 4, 3, 2, 1
       - Visually it renders as 1, 2, 3, 4, 5
       - Hovering a star (e.g. 3) selects it and its FOLLOWING siblings in DOM (2, 1)
       - So visually 1, 2, 3 light up.
    */
    .rating-input label:hover,
    .rating-input label:hover ~ label,
    .rating-input input:checked ~ label,
    .rating-input input:checked ~ label ~ label {
        color: #F59E0B !important; /* Yellow-400 */
    }
</style>

@endsection