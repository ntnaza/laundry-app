@extends('layouts.customer')

@section('title', 'Dashboard Pelanggan')

@section('content')

{{-- HEADER MOBILE --}}
<div class="d-md-none mb-4">
    <div class="d-flex justify-content-between align-items-center">
        <div>
            <h3 class="fw-heading mb-0">Hai, {{ strtok(Auth::user()->name, " ") }}! 👋</h3>
            <p class="text-muted small mb-0">Pakaian bersih menantimu.</p>
        </div>
        <div class="bg-white text-primary fw-bold rounded-circle box-center shadow-sm" style="width: 45px; height: 45px;">
            {{ substr(Auth::user()->name, 0, 1) }}
        </div>
    </div>
</div>

{{-- BANNER BESAR --}}
<div class="card border-0 text-white shadow-soft mb-5 overflow-hidden rounded-4 position-relative" style="background: linear-gradient(120deg, #2563EB 0%, #1e40af 100%);">
    {{-- Pattern Dekoratif --}}
    <div class="position-absolute top-0 start-0 w-100 h-100 opacity-10" style="background-image: radial-gradient(#fff 1px, transparent 1px); background-size: 20px 20px;"></div>
    
    <div class="position-absolute bottom-0 end-0 opacity-25 p-4" style="transform: translate(10%, 10%);">
        <i class="bi bi-basket2-fill" style="font-size: 12rem;"></i>
    </div>

    <div class="card-body p-4 p-md-5 position-relative z-1">
        <div class="row align-items-center">
            <div class="col-md-7">
                <span class="badge bg-white bg-opacity-20 backdrop-blur text-white border border-white border-opacity-25 rounded-pill mb-3 px-3 py-2 fw-bold d-inline-flex align-items-center gap-2">
                    <i class="bi bi-stars"></i> Premium Laundry
                </span>
                <h2 class="fw-heading text-white mb-3 display-6">Cucian Numpuk? <br>Kami Jemput Sekarang!</h2>
                <p class="text-white-50 mb-4 fs-6">Layanan antar-jemput gratis untuk area terpilih. Cukup pesan, santai, dan tunggu pakaianmu wangi.</p>
                <div class="d-flex gap-3">
                    <a href="{{ route('customer.order.create') }}" class="btn btn-white text-primary rounded-pill px-4 py-3 fw-bold shadow-sm d-inline-flex align-items-center gap-2 hover-top transition-300">
                        <i class="bi bi-plus-lg fs-5"></i>
                        <span>Buat Pesanan</span>
                    </a>
                </div>
            </div>
            
            {{-- Statistik Mini di Banner --}}
            <div class="col-md-5 d-none d-md-block">
                <div class="row g-3">
                    <div class="col-6">
                        <div class="bg-white bg-opacity-10 backdrop-blur p-3 rounded-4 border border-white border-opacity-10 text-center">
                            <h2 class="text-white fw-bold mb-0">{{ $myOrders->where('status', 'process')->count() }}</h2>
                            <small class="text-white-50 small text-uppercase ls-1">Sedang Cuci</small>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="bg-white bg-opacity-10 backdrop-blur p-3 rounded-4 border border-white border-opacity-10 text-center">
                            <h2 class="text-white fw-bold mb-0">{{ $myOrders->where('status', 'ready')->count() }}</h2>
                            <small class="text-white-50 small text-uppercase ls-1">Siap Ambil</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row g-4">
    {{-- KOLOM KIRI (Info & Promo) --}}
    <div class="col-lg-4">
        
        <h5 class="fw-heading mb-3 d-flex align-items-center gap-2">
            <i class="bi bi-info-circle-fill text-primary"></i> Info Status
        </h5>
        
        <div class="row g-3 mb-4">
            <div class="col-6">
                <div class="card border-0 shadow-sm rounded-4 bg-white h-100 p-3 text-center hover-top transition-300">
                    <div class="bg-light-info text-info rounded-circle box-center mx-auto mb-3" style="width: 48px; height: 48px;">
                        <i class="bi bi-water fs-4"></i>
                    </div>
                    <h4 class="fw-bold text-dark mb-0">{{ $myOrders->where('status', 'process')->count() }}</h4>
                    <span class="text-muted small fw-bold text-uppercase" style="font-size: 0.65rem;">Dicuci</span>
                </div>
            </div>
            <div class="col-6">
                <div class="card border-0 shadow-sm rounded-4 bg-white h-100 p-3 text-center hover-top transition-300">
                    <div class="bg-light-warning text-warning rounded-circle box-center mx-auto mb-3" style="width: 48px; height: 48px;">
                        <i class="bi bi-bag-check-fill fs-4"></i>
                    </div>
                    <h4 class="fw-bold text-dark mb-0">{{ $myOrders->where('status', 'ready')->count() }}</h4>
                    <span class="text-muted small fw-bold text-uppercase" style="font-size: 0.65rem;">Siap</span>
                </div>
            </div>
        </div>

        {{-- Promo Card --}}
        <div class="card border-0 bg-primary bg-opacity-10 shadow-sm mb-4 rounded-4 overflow-hidden position-relative border border-primary border-opacity-10">
            <div class="card-body p-4 position-relative z-1">
                <div class="d-flex align-items-center gap-3">
                    <div class="bg-white text-primary rounded-circle shadow-sm box-center" style="width: 50px; height: 50px; font-size: 1.5rem;">
                        <i class="bi bi-ticket-perforated-fill"></i>
                    </div>
                    <div>
                        <h6 class="fw-bold text-dark mb-1">Diskon Spesial!</h6>
                        <p class="mb-0 text-dark opacity-75 small lh-sm">Gunakan kode promo untuk hemat lebih banyak.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- KOLOM KANAN (Riwayat Transaksi) --}}
    <div class="col-lg-8">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h5 class="fw-heading mb-0">Riwayat Pesanan</h5>
            <span class="badge bg-light text-muted border rounded-pill">{{ $myOrders->count() }} Transaksi</span>
        </div>

        <div class="d-flex flex-column gap-3">
            @forelse($myOrders as $order)
                <div class="card border-0 shadow-sm rounded-4 overflow-hidden hover-bg-light transition-300">
                    <div class="card-body p-3">
                        <div class="row align-items-center g-3">
                            {{-- Ikon Status --}}
                            <div class="col-auto">
                                <div class="{{ $order->status == 'done' ? 'bg-success bg-opacity-10 text-success' : 'bg-primary bg-opacity-10 text-primary' }} rounded-4 box-center" style="width: 60px; height: 60px; font-size: 1.5rem;">
                                    <i class="bi {{ $order->status == 'done' ? 'bi-check-lg' : 'bi-basket2' }}"></i>
                                </div>
                            </div>
                            
                            {{-- Info Utama --}}
                            <div class="col">
                                <div class="d-flex flex-wrap align-items-center gap-2 mb-1">
                                    <span class="fw-bold text-dark font-monospace">#{{ $order->invoice_code }}</span>
                                    
                                    @if($order->status == 'pending') 
                                        <span class="badge bg-secondary bg-opacity-10 text-secondary border border-secondary rounded-pill">Menunggu</span>
                                    @elseif($order->status == 'process') 
                                        <span class="badge bg-info bg-opacity-10 text-info border border-info rounded-pill">Sedang Dicuci</span>
                                    @elseif($order->status == 'ready') 
                                        <span class="badge bg-warning bg-opacity-10 text-warning border border-warning rounded-pill">Siap Ambil</span>
                                    @elseif($order->status == 'done') 
                                        <span class="badge bg-success bg-opacity-10 text-success border border-success rounded-pill">Selesai</span>
                                    @endif
                                </div>
                                <div class="text-muted small">
                                    <i class="bi bi-calendar3 me-1"></i> {{ $order->created_at->format('d M Y, H:i') }}
                                </div>
                            </div>

                            {{-- Harga & Aksi --}}
                            <div class="col-12 col-md-auto text-md-end border-top border-md-0 pt-3 pt-md-0 mt-2 mt-md-0">
                                <h6 class="fw-bold text-dark mb-1">
                                    {{ $order->total_price == 0 ? 'Menunggu Admin' : 'Rp '.number_format($order->total_price) }}
                                </h6>
                                
                                <div class="d-flex gap-2 justify-content-md-end mt-2">
                                    {{-- Tombol Detail --}}
                                    <button class="btn btn-sm btn-light-primary text-primary rounded-pill fw-bold px-3" data-bs-toggle="modal" data-bs-target="#detailModal{{ $order->id }}">
                                        Detail
                                    </button>

                                    {{-- Tombol Bayar / Status Bayar --}}
                                    @if($order->payment_status == 'paid')
                                        <span class="badge bg-success bg-opacity-10 text-success border border-success rounded-pill px-3 py-2">
                                            <i class="bi bi-check-circle-fill me-1"></i> Lunas
                                        </span>
                                    @elseif($order->total_price > 0 && $order->payment_proof == null)
                                        <button type="button" class="btn btn-sm btn-danger rounded-pill px-3 fw-bold" data-bs-toggle="modal" data-bs-target="#uploadModal{{ $order->id }}">
                                            <i class="bi bi-upload me-1"></i> Bayar
                                        </button>
                                    @elseif($order->payment_proof != null)
                                        <span class="badge bg-warning bg-opacity-10 text-warning border border-warning rounded-pill px-3 py-2">
                                            <i class="bi bi-hourglass-split me-1"></i> Cek Admin
                                        </span>
                                    @endif

                                    {{-- Tombol Review (Jika Selesai & Belum Review) --}}
                                    @if($order->status == 'done' && !$order->testimonial)
                                        <button type="button" class="btn btn-sm btn-warning text-dark rounded-pill fw-bold px-3 shadow-sm hover-top transition-300" data-bs-toggle="modal" data-bs-target="#reviewModal{{ $order->id }}">
                                            <i class="bi bi-star-fill me-1"></i> Ulas
                                        </button>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- MODAL REVIEW --}}
                <div class="modal fade" id="reviewModal{{ $order->id }}" tabindex="-1">
                    <div class="modal-dialog modal-dialog-centered">
                        <div class="modal-content rounded-4 border-0 shadow-lg">
                            <div class="modal-header border-0 pb-0">
                                <h5 class="modal-title fw-bold">Beri Ulasan</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                            </div>
                            <form action="{{ route('customer.order.review', $order->id) }}" method="POST">
                                @csrf
                                <div class="modal-body text-center">
                                    <p class="text-muted small">Bagaimana pengalaman laundry kamu?</p>
                                    
                                    <div class="rating-css">
                                        <div class="star-icon">
                                            @for($i=5; $i>=1; $i--)
                                                <input type="radio" name="rate" id="rating{{$order->id}}-{{$i}}" value="{{$i}}" required>
                                                <label for="rating{{$order->id}}-{{$i}}" class="bi bi-star-fill"></label>
                                            @endfor
                                        </div>
                                    </div>

                                    <div class="mt-3 text-start">
                                        <label class="form-label small fw-bold text-muted">Komentar</label>
                                        <textarea name="content" class="form-control" rows="3" placeholder="Ceritakan kepuasanmu..." required></textarea>
                                    </div>
                                </div>
                                <div class="modal-footer border-0 pt-0">
                                    <button type="button" class="btn btn-light rounded-pill px-4" data-bs-dismiss="modal">Batal</button>
                                    <button type="submit" class="btn btn-primary rounded-pill px-4 fw-bold">Kirim Ulasan</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

                {{-- MODAL DETAIL ITEM --}}
                <div class="modal fade" id="detailModal{{ $order->id }}" tabindex="-1">
                    <div class="modal-dialog modal-dialog-centered">
                        <div class="modal-content rounded-4 border-0 shadow-lg">
                            <div class="modal-header border-bottom-0 pb-0">
                                <h5 class="modal-title fw-bold">Rincian Pesanan</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                            </div>
                            <div class="modal-body">
                                <div class="bg-light p-3 rounded-3 mb-3">
                                    <div class="d-flex justify-content-between mb-1">
                                        <span class="text-muted small">Invoice</span>
                                        <span class="fw-bold font-monospace">{{ $order->invoice_code }}</span>
                                    </div>
                                    <div class="d-flex justify-content-between">
                                        <span class="text-muted small">Status</span>
                                        <span class="fw-bold text-primary">{{ ucfirst($order->status) }}</span>
                                    </div>
                                </div>

                                <h6 class="small fw-bold text-muted text-uppercase ls-1 mb-2">Item Laundry</h6>
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
                                        <li class="list-group-item text-center text-muted small py-3">Belum ada rincian item (menunggu admin menimbang).</li>
                                    @endforelse
                                </ul>

                                <div class="d-flex justify-content-between align-items-center pt-2 border-top">
                                    <span class="fw-bold text-dark">Total Tagihan</span>
                                    <span class="fw-heading text-primary fs-5">Rp {{ number_format($order->total_price) }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- MODAL UPLOAD BUKTI --}}
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
                                    <div class="alert alert-primary bg-opacity-10 border-primary border-opacity-10 d-flex gap-3 rounded-3">
                                        <i class="bi bi-info-circle-fill text-primary fs-4 mt-1"></i>
                                        <div class="small text-dark lh-sm">
                                            Silakan transfer sebesar <strong class="text-primary">Rp {{ number_format($order->total_price) }}</strong> ke rekening berikut:
                                            <div class="mt-2 p-2 bg-white rounded border">
                                                <span class="d-block fw-bold text-dark">BCA: 123-456-7890</span>
                                                <span class="d-block text-muted small">a.n LaundryKuy Official</span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label small fw-bold text-muted">Upload Bukti Transfer</label>
                                        <input type="file" name="payment_proof" class="form-control" required>
                                    </div>
                                </div>
                                <div class="modal-footer border-0 pt-0">
                                    <button type="button" class="btn btn-light rounded-pill px-4 fw-bold text-muted" data-bs-dismiss="modal">Batal</button>
                                    <button type="submit" class="btn btn-primary rounded-pill px-4 fw-bold hover-top transition-300">Kirim Bukti</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

            @empty
                {{-- EMPTY STATE --}}
                <div class="text-center py-5">
                    <div class="bg-light rounded-circle box-center mx-auto mb-3" style="width: 100px; height: 100px;">
                        <i class="bi bi-basket3 text-muted opacity-25" style="font-size: 3rem;"></i>
                    </div>
                    <h5 class="fw-bold text-dark">Belum Ada Pesanan</h5>
                    <p class="text-muted mb-4 small">Cucian numpuk? Yuk buat pesanan pertamamu sekarang!</p>
                    <a href="{{ route('customer.order.create') }}" class="btn btn-primary rounded-pill px-4 py-2 fw-bold shadow-sm hover-top transition-300">
                        <i class="bi bi-plus-lg me-2"></i> Buat Pesanan Baru
                    </a>
                </div>
            @endforelse
        </div>
    </div>
</div>

<style>
    .hover-top:hover { transform: translateY(-3px); }
    .backdrop-blur { backdrop-filter: blur(5px); }
    .btn-white { background-color: white; border: 1px solid white; color: var(--bs-primary); }
    .btn-white:hover { background-color: #f8f9fa; color: var(--bs-primary); }
    .btn-light-primary { background-color: rgba(37, 99, 235, 0.1); border: 1px solid rgba(37, 99, 235, 0.1); }
    .btn-light-primary:hover { background-color: var(--bs-primary); color: white !important; }

    /* RATING SYSTEM */
    .rating-css div { color: #ffe400; font-size: 30px; font-family: sans-serif; font-weight: 800; text-transform: uppercase; padding: 10px 0; }
    .rating-css input { display: none; }
    .rating-css input + label { font-size: 40px; text-shadow: 1px 1px 0 #8f8420; cursor: pointer; }
    .rating-css input:checked + label ~ label { color: #b4b4b4; }
    .rating-css label:active { transform: scale(0.8); transition: 0.3s ease; }
</style>

@endsection