@extends('layouts.admin')

@section('title', 'Proses Transaksi')
@section('page-title', 'Proses Order #' . $transaction->invoice_code)

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-8">
        <div class="card border-0 shadow-sm rounded-4">
            <div class="card-header bg-transparent border-0 pt-4 px-4 d-flex align-items-center gap-3">
                <a href="{{ route('transactions.index') }}" class="btn btn-light rounded-circle shadow-sm box-center" style="width: 40px; height: 40px;">
                    <i class="bi bi-arrow-left"></i>
                </a>
                <div>
                    <h5 class="fw-bold mb-0">Panel Kontrol Order</h5>
                    <p class="text-muted small mb-0">Update status cucian & pembayaran.</p>
                </div>
            </div>

            <div class="card-body p-4">
                
                {{-- INFO PELANGGAN --}}
                <div class="alert alert-light-primary border-0 rounded-4 d-flex align-items-start gap-3 p-4 mb-4">
                    <div class="avatar bg-primary text-white box-center rounded-circle flex-shrink-0 shadow-sm" style="width: 50px; height: 50px;">
                        <i class="bi bi-person-fill fs-4"></i>
                    </div>
                    <div>
                        <h6 class="fw-bold text-primary mb-2">Data Pelanggan</h6>
                        <div class="row g-2 text-dark small">
                            <div class="col-12"><i class="bi bi-person me-2 text-muted"></i> {{ $transaction->customer->name }}</div>
                            <div class="col-12"><i class="bi bi-whatsapp me-2 text-muted"></i> {{ $transaction->customer->phone ?? '-' }}</div>
                            <div class="col-12"><i class="bi bi-geo-alt me-2 text-muted"></i> {{ $transaction->pickup_address ?? 'Datang Sendiri' }}</div>
                        </div>
                    </div>
                </div>

                {{-- AREA BUKTI PEMBAYARAN --}}
                @if($transaction->payment_proof)
                    <div class="card border border-success border-opacity-25 bg-light-success rounded-4 mb-4 overflow-hidden">
                        <div class="card-body p-4 text-center">
                            <h6 class="text-success fw-bold mb-3"><i class="bi bi-check-circle-fill me-2"></i> Bukti Transfer Terlampir!</h6>
                            
                            <div class="position-relative d-inline-block group-hover-zoom">
                                <img src="{{ asset('storage/' . $transaction->payment_proof) }}" 
                                     class="img-fluid rounded-3 shadow-sm border" 
                                     style="max-height: 250px; cursor: zoom-in;" 
                                     onclick="window.open(this.src)"
                                     alt="Bukti Transfer">
                                <div class="position-absolute top-50 start-50 translate-middle badge bg-dark opacity-0 hover-opacity-100 rounded-pill px-3 py-2 transition-300">
                                    <i class="bi bi-zoom-in"></i> Lihat Full
                                </div>
                            </div>
                            
                            <p class="small text-muted mt-2 mb-0">Klik gambar untuk memperbesar.</p>
                        </div>
                    </div>
                @else
                    <div class="alert alert-light-secondary border-0 rounded-4 text-center py-4 mb-4">
                        <div class="d-inline-flex bg-white rounded-circle p-3 shadow-sm mb-2 text-muted">
                            <i class="bi bi-image fs-4"></i>
                        </div>
                        <p class="mb-0 small fw-bold text-muted">Belum ada bukti pembayaran.</p>
                    </div>
                @endif

                {{-- FORM UPDATE --}}
                <form action="{{ route('transactions.update', $transaction->id) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <h6 class="fw-bold mb-3 border-bottom pb-2">Detail Transaksi</h6>

                    {{-- 1. Input Harga (Highlight) --}}
                    <div class="mb-4">
                        <label class="form-label fw-bold text-dark small text-uppercase ls-1">TOTAL BIAYA (RP)</label>
                        <div class="input-group input-group-lg shadow-sm rounded-4 overflow-hidden">
                            <span class="input-group-text bg-white border-0 ps-4 text-muted">Rp</span>
                            <input type="number" name="total_price" class="form-control border-0 fw-bold fs-4 text-dark" 
                                   style="background: #fff;"
                                   value="{{ $transaction->total_price }}" placeholder="0" required>
                        </div>
                        <div class="form-text ms-2"><i class="bi bi-info-circle me-1"></i> Isi nominal setelah ditimbang.</div>
                    </div>

                    <div class="row g-3 mb-4">
                        {{-- 2. Status Laundry --}}
                        <div class="col-md-6">
                            <label class="form-label fw-bold small text-uppercase text-muted">Status Cucian</label>
                            <select name="status" class="form-select form-select-lg border-0 bg-light rounded-3 fs-6">
                                <option value="pending" {{ $transaction->status == 'pending' ? 'selected' : '' }}>🕒 Menunggu</option>
                                <option value="process" {{ $transaction->status == 'process' ? 'selected' : '' }}>🫧 Sedang Dicuci</option>
                                <option value="ready" {{ $transaction->status == 'ready' ? 'selected' : '' }}>🛍️ Siap Ambil</option>
                                <option value="done" {{ $transaction->status == 'done' ? 'selected' : '' }}>✅ Selesai</option>
                            </select>
                        </div>

                        {{-- 3. Status Kurir --}}
                        <div class="col-md-6">
                            <label class="form-label fw-bold small text-uppercase text-muted">Status Kurir</label>
                            <select name="delivery_status" class="form-select form-select-lg border-0 bg-light rounded-3 fs-6">
                                <option value="pending" {{ $transaction->delivery_status == 'pending' ? 'selected' : '' }}>🔍 Cari Kurir</option>
                                <option value="on_the_way" {{ $transaction->delivery_status == 'on_the_way' ? 'selected' : '' }}>🛵 Kurir Jalan (OTW)</option>
                                <option value="delivered" {{ $transaction->delivery_status == 'delivered' ? 'selected' : '' }}>🏁 Selesai Diantar</option>
                            </select>
                        </div>
                    </div>

                    {{-- 4. STATUS PEMBAYARAN (CRUCIAL) --}}
                    <div class="card border-primary border-opacity-25 bg-light-primary rounded-4 mb-4">
                        <div class="card-body p-3">
                            <label class="form-label fw-bold text-primary small text-uppercase mb-2">Status Pembayaran</label>
                            <div class="d-flex gap-2">
                                <input type="radio" class="btn-check" name="payment_status" id="unpaid" value="unpaid" {{ $transaction->payment_status == 'unpaid' ? 'checked' : '' }}>
                                <label class="btn btn-outline-danger border-0 w-100 py-3 rounded-3 fw-bold d-flex flex-column align-items-center justify-content-center gap-1" for="unpaid">
                                    <i class="bi bi-x-circle fs-5"></i> BELUM LUNAS
                                </label>

                                <input type="radio" class="btn-check" name="payment_status" id="paid" value="paid" {{ $transaction->payment_status == 'paid' ? 'checked' : '' }}>
                                <label class="btn btn-outline-success border-0 w-100 py-3 rounded-3 fw-bold d-flex flex-column align-items-center justify-content-center gap-1" for="paid">
                                    <i class="bi bi-check-circle fs-5"></i> SUDAH LUNAS
                                </label>
                            </div>
                        </div>
                    </div>

                    <div class="d-grid">
                        <button type="submit" class="btn btn-primary btn-lg rounded-pill fw-bold shadow-lg hover-scale">
                            <i class="bi bi-save me-2"></i> SIMPAN PERUBAHAN
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<style>
    /* Radio Button Style agar terlihat seperti kartu */
    .btn-check:checked + .btn-outline-danger {
        background-color: #dc3545; color: white; box-shadow: 0 5px 15px rgba(220, 53, 69, 0.3);
    }
    .btn-check:checked + .btn-outline-success {
        background-color: #198754; color: white; box-shadow: 0 5px 15px rgba(25, 135, 84, 0.3);
    }
    .btn-outline-danger, .btn-outline-success { background-color: white; }
    
    .hover-opacity-100:hover { opacity: 1 !important; }
    .hover-scale:hover { transform: translateY(-2px); }
    .ls-1 { letter-spacing: 1px; }
</style>
@endsection