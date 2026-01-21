@extends('layouts.customer')

@section('title', 'Dashboard Pelanggan')

@section('content')

{{-- HEADER MOBILE --}}
<div class="d-md-none mb-4">
    <div class="d-flex justify-content-between align-items-center">
        <div>
            <h3 class="fw-heading mb-0">Hai, {{ strtok(Auth::user()->name, " ") }}! 👋</h3>
            <p class="text-muted small mb-0">Pakaian bersih menunggu.</p>
        </div>
        <div class="bg-white text-primary fw-bold rounded-circle box-center shadow-sm" style="width: 45px; height: 45px;">
            {{ substr(Auth::user()->name, 0, 1) }}
        </div>
    </div>
</div>

{{-- BANNER BESAR --}}
<div class="card border-0 text-white shadow-lg mb-5 overflow-hidden rounded-4" style="background: linear-gradient(135deg, var(--primary), var(--primary-dark));">
    <div class="position-absolute top-0 end-0 opacity-10" style="transform: translate(30%, -30%);">
        <i class="bi bi-basket-fill" style="font-size: 15rem;"></i>
    </div>
    <div class="card-body p-4 p-md-5 position-relative z-1">
        <div class="row align-items-center">
            <div class="col-md-8">
                <span class="badge bg-white text-primary rounded-pill mb-3 px-3 py-2 fw-bold d-inline-flex align-items-center gap-2">
                    <i class="bi bi-stars"></i> Premium Service
                </span>
                <h2 class="fw-heading text-white mb-2">Cucian Numpuk? Santai Aja!</h2>
                <p class="text-white-50 mb-4 fs-5" style="max-width: 500px;">Kami jemput pakaian kotormu dan antar kembali dalam keadaan bersih, wangi, dan rapi.</p>
                <div class="d-flex gap-3">
                    {{-- TOMBOL BUAT PESANAN --}}
                    <a href="{{ route('customer.order.create') }}" class="btn btn-light text-primary rounded-pill px-4 py-2 fw-bold shadow-sm d-inline-flex align-items-center gap-2 hover-scale">
                        <i class="bi bi-plus-lg fs-5" style="display: block; line-height: 1;"></i>
                        <span>Buat Pesanan</span>
                    </a>
                </div>
            </div>
            <div class="col-md-4 d-none d-md-block text-center">
                <div class="bg-white bg-opacity-10 p-4 rounded-4 backdrop-blur">
                    <h1 class="text-white fw-bold mb-0">{{ $myOrders->where('status', 'process')->count() }}</h1>
                    <small class="text-white-50">Sedang Dicuci</small>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row g-4">
    {{-- KOLOM KIRI --}}
    <div class="col-lg-4">
        
        <h5 class="fw-heading mb-3">Status Cucian</h5>
        <div class="row g-3 mb-4">
            <div class="col-6">
                <div class="card-premium p-3 h-100 d-flex flex-column align-items-start">
                    {{-- Avatar Status --}}
                    <div class="bg-light-primary text-primary mb-3 rounded-4 box-center" style="width: 50px; height: 50px; font-size: 1.5rem;">
                        <i class="bi bi-water"></i>
                    </div>
                    <h3 class="fw-bold mb-0 text-dark">{{ $myOrders->where('status', 'process')->count() }}</h3>
                    <small class="text-muted fw-bold" style="font-size: 0.7rem;">SEDANG DICUCI</small>
                </div>
            </div>
            <div class="col-6">
                <div class="card-premium p-3 h-100 d-flex flex-column align-items-start">
                    {{-- Avatar Status --}}
                    <div class="bg-light-warning text-warning mb-3 rounded-4 box-center" style="width: 50px; height: 50px; font-size: 1.5rem;">
                        <i class="bi bi-bag-check-fill"></i>
                    </div>
                    <h3 class="fw-bold mb-0 text-dark">{{ $myOrders->where('status', 'ready')->count() }}</h3>
                    <small class="text-muted fw-bold" style="font-size: 0.7rem;">SIAP DIAMBIL</small>
                </div>
            </div>
        </div>

        {{-- Promo Card --}}
        <div class="card border-0 bg-light-info shadow-sm mb-4 rounded-4 overflow-hidden position-relative">
            <div class="card-body p-4 position-relative z-1">
                <div class="d-flex align-items-center gap-3">
                    {{-- Promo Icon --}}
                    <div class="bg-white text-info rounded-circle shadow-sm box-center" style="width: 50px; height: 50px; font-size: 1.5rem;">
                        <i class="bi bi-ticket-perforated-fill"></i>
                    </div>
                    <div>
                        <h6 class="fw-bold text-dark mb-1">Diskon Member Baru</h6>
                        <p class="mb-0 text-muted small lh-sm">Potongan 20% untuk transaksi pertamamu.</p>
                    </div>
                </div>
            </div>
            <div class="position-absolute bottom-0 end-0 opacity-25" style="transform: translate(20%, 20%);">
                <i class="bi bi-ticket-fill" style="font-size: 6rem; color: var(--bs-info);"></i>
            </div>
        </div>
    </div>

    {{-- KOLOM KANAN --}}
    <div class="col-lg-8">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h5 class="fw-heading mb-0">Riwayat Pesanan Terakhir</h5>
            @if($myOrders->count() > 0)
                <span class="badge bg-white text-muted border rounded-pill">{{ $myOrders->count() }} Total</span>
            @endif
        </div>

        <div class="d-flex flex-column gap-3">
            @forelse($myOrders as $order)
                <div class="card-premium p-3">
                    <div class="row align-items-center g-3">
                        <div class="col-auto">
                            <div class="{{ $order->status == 'done' ? 'bg-light-success text-success' : 'bg-light-primary text-primary' }} rounded-4 box-center" style="width: 55px; height: 55px; font-size: 1.5rem;">
                                <i class="bi {{ $order->status == 'done' ? 'bi-check-lg' : 'bi-basket' }}"></i>
                            </div>
                        </div>
                        
                        <div class="col">
                            <div class="d-flex flex-wrap align-items-center gap-2 mb-1">
                                <span class="fw-bold text-dark fs-6">#{{ $order->invoice_code }}</span>
                                @if($order->status == 'pending') <span class="badge rounded-pill bg-light-secondary text-secondary border">Menunggu</span>
                                @elseif($order->status == 'process') <span class="badge rounded-pill bg-light-info text-info border">Dicuci</span>
                                @elseif($order->status == 'ready') <span class="badge rounded-pill bg-light-warning text-warning border">Siap Ambil</span>
                                @elseif($order->status == 'done') <span class="badge rounded-pill bg-light-success text-success border">Selesai</span>
                                @endif
                            </div>
                            <div class="d-flex align-items-center gap-1 text-muted small">
                                <i class="bi bi-calendar3"></i> 
                                <span>{{ $order->created_at->format('d M Y, H:i') }}</span>
                            </div>
                        </div>

                        <div class="col-12 col-md-auto text-md-end border-top border-md-0 pt-3 pt-md-0 mt-2 mt-md-0">
                            <h5 class="fw-bold text-primary mb-1">
                                {{ $order->total_price == 0 ? '-' : 'Rp '.number_format($order->total_price) }}
                            </h5>
                            @if($order->payment_status == 'paid')
                                <span class="badge bg-success bg-opacity-10 text-success border border-success rounded-pill px-3 py-2 d-inline-flex align-items-center gap-1">
                                    <i class="bi bi-check-circle-fill"></i> Lunas
                                </span>
                            @elseif($order->total_price > 0 && $order->payment_proof == null)
                                <button type="button" class="btn btn-sm btn-outline-danger rounded-pill px-3 fw-bold d-inline-flex align-items-center gap-1" data-bs-toggle="modal" data-bs-target="#uploadModal{{ $order->id }}">
                                    <i class="bi bi-upload"></i> Bayar
                                </button>
                            @elseif($order->payment_proof != null)
                                <span class="badge bg-warning bg-opacity-10 text-warning border border-warning rounded-pill px-3 py-2 d-inline-flex align-items-center gap-1">
                                    <i class="bi bi-hourglass-split"></i> Cek Admin
                                </span>
                            @endif
                        </div>
                    </div>
                </div>

                {{-- MODAL UPLOAD --}}
                <div class="modal fade" id="uploadModal{{ $order->id }}" tabindex="-1">
                    <div class="modal-dialog modal-dialog-centered">
                        <div class="modal-content rounded-4 border-0 shadow-lg">
                            <div class="modal-header border-0 pb-0">
                                <h5 class="modal-title fw-bold">Konfirmasi Pembayaran</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                            </div>
                            <form action="{{ route('customer.order.uploadProof', $order->id) }}" method="POST" enctype="multipart/form-data">
                                @csrf
                                <div class="modal-body">
                                    <div class="alert alert-light-primary border-0 d-flex align-items-center gap-3 rounded-3">
                                        <i class="bi bi-info-circle-fill text-primary fs-4"></i>
                                        <div class="small text-muted lh-sm">
                                            Transfer sebesar <strong class="text-dark">Rp {{ number_format($order->total_price) }}</strong> ke <br>
                                            <span class="text-dark fw-bold">BCA 123456789 (LaundryKuy)</span>
                                        </div>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label small fw-bold text-muted">UPLOAD BUKTI TRANSFER</label>
                                        <input type="file" name="payment_proof" class="form-control" required>
                                    </div>
                                </div>
                                <div class="modal-footer border-0 pt-0">
                                    <button type="button" class="btn btn-light rounded-pill px-4" data-bs-dismiss="modal">Batal</button>
                                    <button type="submit" class="btn btn-primary rounded-pill px-4 fw-bold">Kirim Bukti</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

            @empty
                {{-- EMPTY STATE (FIX KERANJANG) --}}
                <div class="text-center py-5">
                    {{-- 
                       Gunakan class .box-center yang sama dengan icon di Login
                       Set ukuran fix 120px 
                    --}}
                    <div class="bg-white rounded-4 shadow-sm box-center mb-3" style="width: 120px; height: 120px;">
                        <i class="bi bi-basket3 text-muted opacity-25" style="font-size: 4rem;"></i>
                    </div>
                    
                    <h5 class="fw-bold text-dark">Belum Ada Pesanan</h5>
                    <p class="text-muted mb-4">Cucian numpuk? Yuk buat pesanan pertamamu sekarang!</p>
                    
                    {{-- 
                        TOMBOL BUAT PESANAN (FIX PLUS)
                        - Gunakan class d-inline-flex, align-items-center, gap-2 (standar bootstrap flex)
                        - Hapus style icon yang aneh-aneh, biarkan bootstrap flex mengatur.
                    --}}
                    <a href="{{ route('customer.order.create') }}" class="btn btn-primary rounded-pill px-4 py-2 fw-bold shadow-sm d-inline-flex align-items-center gap-2">
                        <i class="bi bi-plus-lg"></i> 
                        <span>Buat Pesanan Baru</span>
                    </a>
                </div>
            @endforelse
        </div>
    </div>
</div>

<style>
    .hover-scale:hover { transform: translateY(-2px); }
    .backdrop-blur { backdrop-filter: blur(5px); }
</style>

@endsection