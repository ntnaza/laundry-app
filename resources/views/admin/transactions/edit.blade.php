@extends('layouts.admin')

@section('title', 'Proses Pesanan')
@section('page-title', 'Order #' . $transaction->invoice_code)

@section('content')
<form action="{{ route('transactions.update', $transaction->id) }}" method="POST">
    @csrf
    @method('PUT')

    {{-- Ambil data service utama (asumsi single service transaction) --}}
    @php
        $detail = $transaction->details->first();
        $serviceName = $detail && $detail->service ? $detail->service->name : 'Layanan Custom';
        $pricePerKg = $detail && $detail->service ? $detail->service->price : 0;
        
        // Berat awal (jika ada, atau 0)
        $initialWeight = $detail ? $detail->qty : 0;
    @endphp

    {{-- Simpan Harga Satuan di Hidden Input buat JS --}}
    <input type="hidden" id="pricePerKg" value="{{ $pricePerKg }}">

    <div class="row g-4">
        {{-- SECTION UTAMA (KIRI) --}}
        <div class="col-xl-8 col-lg-7">
            
            {{-- 1. INPUT BERAT & AUTO CALCULATE --}}
            <div class="card border-0 shadow-soft rounded-4 mb-4 overflow-hidden">
                {{-- HEADER: INFO LAYANAN --}}
                <div class="card-header bg-white border-bottom border-light p-4">
                    <div class="d-flex align-items-center justify-content-between flex-wrap gap-3">
                        {{-- Kiri: Icon & Nama Paket --}}
                        <div class="d-flex align-items-center gap-3">
                            <div class="d-flex align-items-center justify-content-center bg-primary text-white rounded-4 shadow-sm flex-shrink-0" style="width: 52px; height: 52px;">
                                <i class="bi bi-basket2-fill fs-4"></i>
                            </div>
                            <div>
                                <small class="text-muted text-uppercase fw-bold ls-1 d-block mb-1" style="font-size: 0.65rem;">Paket Laundry</small>
                                <h5 class="fw-heading text-dark mb-0">{{ $serviceName }}</h5>
                            </div>
                        </div>
                        
                        {{-- Kanan: Badge Harga Satuan --}}
                        <div class="bg-light px-3 py-2 rounded-pill border border-light d-flex align-items-center gap-2">
                            <i class="bi bi-tag-fill text-primary"></i>
                            <span class="text-muted small text-uppercase fw-bold">Rate:</span>
                            <span class="text-dark fw-bold">Rp {{ number_format($pricePerKg, 0, ',', '.') }}<span class="text-muted fw-normal small">/kg</span></span>
                        </div>
                    </div>
                </div>

                <div class="card-body p-4">
                    <div class="row g-4 align-items-center">
                        {{-- INPUT BERAT (MAIN STAR) --}}
                        <div class="col-md-5">
                            <label class="form-label text-muted fw-bold small text-uppercase ls-1">Berat Cucian (Kg)</label>
                            <div class="input-group">
                                <input type="number" step="0.1" name="weight" id="weightInput"
                                       class="form-control fw-heading text-dark fs-2 border-primary focus-ring rounded-3 py-2" 
                                       value="{{ $initialWeight > 0 ? $initialWeight : '' }}" 
                                       placeholder="0.0" required autofocus>
                                <span class="input-group-text bg-primary text-white fw-bold border-primary px-3">Kg</span>
                            </div>
                        </div>

                        <div class="col-md-1 text-center d-none d-md-block">
                            <i class="bi bi-arrow-right fs-3 text-muted opacity-50"></i>
                        </div>

                        {{-- DISPLAY TOTAL HARGA (READONLY) --}}
                        <div class="col-md-6 text-md-end">
                            <label class="form-label text-muted fw-bold small text-uppercase ls-1">Estimasi Total</label>
                            <div class="bg-light-primary rounded-4 p-3 border border-primary border-opacity-10 text-end">
                                <h3 class="fw-heading text-primary mb-0" id="displayTotal">Rp {{ number_format($transaction->total_price, 0, ',', '.') }}</h3>
                            </div>
                            {{-- Hidden Input untuk kirim ke Controller --}}
                            <input type="hidden" name="total_price" id="realTotalPrice" value="{{ $transaction->total_price }}">
                        </div>
                    </div>
                </div>
            </div>

            {{-- 2. STATUS WORKFLOW --}}
            <div class="card border-0 shadow-soft rounded-4 mb-4">
                <div class="card-body p-4">
                    <h6 class="fw-bold text-dark mb-3 small text-uppercase ls-1">Status Pengerjaan</h6>
                    <div class="row g-2">
                        @foreach([
                            'pending' => ['icon' => 'bi-clock', 'label' => 'Pending', 'color' => 'secondary'],
                            'process' => ['icon' => 'bi-water', 'label' => 'Proses', 'color' => 'info'],
                            'ready'   => ['icon' => 'bi-basket', 'label' => 'Selesai', 'color' => 'warning'],
                            'done'    => ['icon' => 'bi-check-all', 'label' => 'Diambil', 'color' => 'success']
                        ] as $key => $meta)
                        <div class="col-6 col-md-3">
                            <input type="radio" class="btn-check status-radio" name="status" id="status_{{ $key }}" value="{{ $key }}" {{ $transaction->status == $key ? 'checked' : '' }}>
                            <label class="btn btn-outline-light border border-light-subtle text-dark w-100 p-3 rounded-4 d-flex flex-column align-items-center justify-content-center gap-2 h-100 transition-300" for="status_{{ $key }}">
                                <i class="bi {{ $meta['icon'] }} fs-4 {{ $transaction->status == $key ? 'text-white' : 'text-muted' }} icon-transition"></i>
                                <span class="small fw-bold">{{ $meta['label'] }}</span>
                            </label>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>

            {{-- 3. STATUS LOGISTIK --}}
            @if($transaction->delivery_type != 'none')
            <div class="card border-0 shadow-soft rounded-4">
                <div class="card-body p-4">
                    <h6 class="fw-bold text-dark mb-3 small text-uppercase ls-1">Status Pengiriman</h6>
                    <div class="row g-2">
                        @foreach([
                            'pending'     => ['icon' => 'bi-search', 'label' => 'Cari Kurir'],
                            'on_the_way'  => ['icon' => 'bi-scooter', 'label' => 'Sedang OTW'],
                            'delivered'   => ['icon' => 'bi-geo-alt-fill', 'label' => 'Sampai']
                        ] as $key => $meta)
                        <div class="col-4">
                            <input type="radio" class="btn-check delivery-radio" name="delivery_status" id="del_{{ $key }}" value="{{ $key }}" {{ $transaction->delivery_status == $key ? 'checked' : '' }}>
                            <label class="btn btn-outline-light border border-light-subtle text-dark w-100 p-2 rounded-3 d-flex flex-column align-items-center justify-content-center gap-1 transition-300" for="del_{{ $key }}">
                                <i class="bi {{ $meta['icon'] }} fs-6 text-muted"></i>
                                <span class="small fw-medium" style="font-size: 0.75rem;">{{ $meta['label'] }}</span>
                            </label>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
            @endif

        </div>

        {{-- SECTION SIDEBAR (KANAN) --}}
        <div class="col-xl-4 col-lg-5">
            
            {{-- TOMBOL AKSI & STATUS BAYAR --}}
            <div class="card border-0 shadow-soft rounded-4 mb-4">
                <div class="card-body p-4">
                    <h6 class="fw-bold text-dark mb-3 small text-uppercase ls-1">Pembayaran</h6>
                    
                    {{-- TOGGLE PEMBAYARAN --}}
                    <div class="bg-light rounded-pill p-1 d-flex position-relative user-select-none border border-light mb-4" style="height: 50px;">
                        <input type="radio" class="btn-check" name="payment_status" id="unpaid" value="unpaid" {{ $transaction->payment_status == 'unpaid' ? 'checked' : '' }}>
                        <label class="btn btn-sm btn-transparent w-50 rounded-pill fw-bold text-muted d-flex align-items-center justify-content-center z-1 transition-300" for="unpaid">
                            Belum Lunas
                        </label>

                        <input type="radio" class="btn-check" name="payment_status" id="paid" value="paid" {{ $transaction->payment_status == 'paid' ? 'checked' : '' }}>
                        <label class="btn btn-sm btn-transparent w-50 rounded-pill fw-bold text-muted d-flex align-items-center justify-content-center z-1 transition-300" for="paid">
                            Lunas
                        </label>
                        
                        <div class="slider-bg bg-white shadow-sm rounded-pill position-absolute top-0 bottom-0 m-1" style="width: calc(50% - 8px); transition: 0.3s cubic-bezier(0.4, 0, 0.2, 1);"></div>
                    </div>

                    <div class="d-grid gap-2">
                        <button type="submit" class="btn btn-primary rounded-pill fw-bold shadow-lg py-3 hover-top transition-300">
                            <i class="bi bi-save2-fill me-2"></i> Update Transaksi
                        </button>
                        <a href="{{ route('transactions.index') }}" class="btn btn-light rounded-pill fw-bold text-muted py-2">
                            Batal
                        </a>
                    </div>
                </div>
            </div>

            {{-- INFO CUSTOMER --}}
            <div class="card border-0 shadow-soft rounded-4 mb-4">
                <div class="card-body p-4">
                    <h6 class="fw-bold text-dark mb-3 small text-uppercase ls-1">Info Pelanggan</h6>
                    <div class="d-flex align-items-center gap-3 mb-3">
                        <div class="avatar rounded-circle bg-light-primary text-primary d-flex align-items-center justify-content-center flex-shrink-0 fw-bold" style="width: 48px; height: 48px; font-size: 1.2rem;">
                            {{ substr($transaction->customer->name, 0, 1) }}
                        </div>
                        <div class="overflow-hidden">
                            <h6 class="fw-bold text-dark mb-0 text-truncate">{{ $transaction->customer->name }}</h6>
                            <small class="text-muted d-block text-truncate">{{ $transaction->customer->phone ?? '-' }}</small>
                        </div>
                    </div>
                    
                    <div class="d-flex gap-2 align-items-start small text-secondary bg-light p-3 rounded-3">
                        <i class="bi bi-geo-alt-fill text-danger mt-1"></i>
                        <span class="lh-sm">{{ $transaction->pickup_address ?? 'Datang ke Outlet' }}</span>
                    </div>
                </div>
            </div>

            {{-- NOTE & BUKTI --}}
            @if($transaction->note)
            <div class="card border-0 shadow-soft rounded-4 mb-4 bg-light-warning">
                <div class="card-body p-3">
                    <small class="fw-bold text-warning-emphasis d-flex align-items-center gap-2 mb-1">
                        <i class="bi bi-sticky-fill"></i> Catatan
                    </small>
                    <p class="mb-0 text-dark opacity-75 small fst-italic">"{{ $transaction->note }}"</p>
                </div>
            </div>
            @endif

            @if($transaction->payment_proof)
            <div class="card border-0 shadow-soft rounded-4 overflow-hidden">
                <div class="card-header bg-white border-0 py-3">
                    <h6 class="fw-bold mb-0 small text-uppercase ls-1">Bukti Pembayaran</h6>
                </div>
                <div class="position-relative bg-light text-center p-2">
                    <img src="{{ asset('storage/' . $transaction->payment_proof) }}" 
                         class="img-fluid rounded-3 shadow-sm" 
                         style="max-height: 200px; cursor: zoom-in;"
                         onclick="window.open(this.src)"
                         alt="Bukti">
                </div>
            </div>
            @endif

        </div>
    </div>
</form>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const weightInput = document.getElementById('weightInput');
        const pricePerKg = parseFloat(document.getElementById('pricePerKg').value);
        const displayTotal = document.getElementById('displayTotal');
        const realTotalPrice = document.getElementById('realTotalPrice');

        function formatRupiah(number) {
            return new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR', minimumFractionDigits: 0 }).format(number);
        }

        function calculate() {
            let weight = parseFloat(weightInput.value);
            if (isNaN(weight) || weight < 0) weight = 0;

            const total = Math.ceil(weight * pricePerKg); 

            displayTotal.innerText = formatRupiah(total);
            realTotalPrice.value = total;
        }

        weightInput.addEventListener('input', calculate);
    });
</script>

<style>
    /* UTILS */
    .hover-top:hover { transform: translateY(-3px); }
    
    /* REMOVE INPUT NUMBER SPINNER */
    input[type=number]::-webkit-inner-spin-button, 
    input[type=number]::-webkit-outer-spin-button { 
        -webkit-appearance: none; margin: 0; 
    }

    /* PAYMENT TOGGLE */
    #paid:checked ~ .slider-bg { transform: translateX(100%); }
    #paid:checked ~ label[for="paid"] { color: var(--bs-success) !important; }
    #unpaid:checked ~ label[for="unpaid"] { color: var(--bs-danger) !important; }
    
    /* STATUS CARDS */
    .status-radio:checked + label {
        border-color: var(--bs-primary) !important;
        background-color: var(--bs-primary) !important;
        color: white !important;
        box-shadow: 0 10px 20px rgba(37, 99, 235, 0.2);
    }
    .status-radio:checked + label .text-muted { color: white !important; }
    
    /* DELIVERY CARDS */
    .delivery-radio:checked + label {
        border-color: var(--bs-dark) !important;
        background-color: var(--bs-dark) !important;
        color: white !important;
    }
    .delivery-radio:checked + label i { color: white !important; }

</style>
@endsection