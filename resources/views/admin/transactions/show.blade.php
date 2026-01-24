@php
    $setting = \App\Models\Setting::first();
@endphp

@extends('layouts.admin')

@section('title', 'Detail Transaksi')
@section('page-title', 'Detail & Cetak Nota')

@section('content')
<style>
    /* CSS KHUSUS CETAK (STRUK THERMAL STYLE) */
    @media print {
        @page { margin: 0; size: auto; }
        body * { visibility: hidden; }
        #printableArea, #printableArea * { visibility: visible; }
        #printableArea {
            position: absolute; left: 0; top: 0; width: 100%;
            font-family: 'Courier New', Courier, monospace !important; /* Font Struk */
            color: #000 !important;
        }
        .no-print { display: none !important; }
        .card { border: none !important; box-shadow: none !important; }
        .bg-light { background-color: #fff !important; }
        .text-primary, .text-success, .text-danger { color: #000 !important; } /* Hitam semua saat print */
        hr { border-top: 1px dashed #000 !important; opacity: 1; } /* Garis putus-putus ala struk */
    }
</style>

<div class="row justify-content-center">
    <div class="col-lg-8">
        
        {{-- TOMBOL AKSI (NO PRINT) --}}
        <div class="d-flex justify-content-between align-items-center mb-4 no-print flex-wrap gap-2">
            <a href="{{ route('transactions.index') }}" class="btn btn-light rounded-circle shadow-sm box-center transition-300" style="width: 42px; height: 42px;">
                <i class="bi bi-arrow-left"></i>
            </a>
            <div class="d-flex gap-2">
                <a href="{{ $transaction->generateWaLink() }}" target="_blank" class="btn btn-success rounded-pill px-4 fw-bold shadow-sm hover-scale d-flex align-items-center gap-2">
                    <i class="bi bi-whatsapp"></i> <span class="d-none d-md-inline">Kirim WA</span>
                </a>
                <a href="{{ route('transactions.printThermal', $transaction->id) }}" target="_blank" class="btn btn-dark rounded-pill px-4 fw-bold shadow-sm hover-scale d-flex align-items-center gap-2">
                    <i class="bi bi-printer"></i> <span class="d-none d-md-inline">Cetak Struk</span>
                </a>
            </div>
        </div>

        @if(session('success'))
            <div class="alert alert-success border-0 shadow-sm rounded-4 mb-4 no-print d-flex align-items-center gap-3 animate__animated animate__fadeIn">
                <div class="bg-white text-success rounded-circle box-center" style="width: 32px; height: 32px;">
                    <i class="bi bi-check-lg"></i>
                </div>
                <div class="fw-bold">{{ session('success') }}</div>
            </div>
        @endif

        {{-- FORM UPDATE STATUS (NO PRINT) --}}
        <div class="card border-0 shadow-soft rounded-4 mb-4 no-print overflow-hidden">
            <div class="card-header bg-white border-bottom border-light py-3 px-4">
                <h6 class="fw-heading text-dark mb-0 d-flex align-items-center gap-2">
                    <i class="bi bi-sliders text-primary"></i> Update Status
                </h6>
            </div>
            <div class="card-body p-4 bg-light bg-opacity-50">
                <form action="{{ route('transactions.updateStatus', $transaction->id) }}" method="POST">
                    @csrf
                    @method('PUT')
                    
                    <div class="row g-3 align-items-end">
                        <div class="col-md-4">
                            <label class="form-label small fw-bold text-muted text-uppercase ls-1">Status Laundry</label>
                            <select name="status" class="form-select border-0 bg-white shadow-sm rounded-3">
                                <option value="pending" {{ $transaction->status == 'pending' ? 'selected' : '' }}>⏳ Menunggu</option>
                                <option value="process" {{ $transaction->status == 'process' ? 'selected' : '' }}>🫧 Sedang Dicuci</option>
                                <option value="ready" {{ $transaction->status == 'ready' ? 'selected' : '' }}>🛍️ Siap Ambil</option>
                                <option value="done" {{ $transaction->status == 'done' ? 'selected' : '' }}>✅ Selesai</option>
                            </select>
                        </div>

                        <div class="col-md-4">
                            <label class="form-label small fw-bold text-muted text-uppercase ls-1">Pembayaran</label>
                            <select name="payment_status" class="form-select border-0 bg-white shadow-sm rounded-3">
                                <option value="unpaid" {{ $transaction->payment_status == 'unpaid' ? 'selected' : '' }}>❌ Belum Lunas</option>
                                <option value="paid" {{ $transaction->payment_status == 'paid' ? 'selected' : '' }}>💰 LUNAS</option>
                            </select>
                        </div>

                        <div class="col-md-4">
                            <button type="submit" class="btn btn-primary w-100 rounded-pill fw-bold shadow-sm hover-top transition-300">
                                <i class="bi bi-save2-fill me-1"></i> Simpan Perubahan
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        {{-- AREA NOTA / STRUK (PRINTABLE) --}}
        <div class="card border-0 shadow-lg rounded-4 overflow-hidden" id="printableArea">
            <div class="card-body p-5">
                
                {{-- KOP NOTA --}}
                <div class="text-center mb-4">
                    @if($setting->logo)
                        <img src="{{ asset('storage/'.$setting->logo) }}" alt="Logo" style="width: 70px; margin-bottom: 10px;" class="rounded-3 shadow-sm">
                    @else
                        <div class="bg-primary text-white rounded-4 box-center shadow-sm mx-auto mb-3" style="width: 56px; height: 56px;">
                            <i class="bi bi-basket-fill fs-3"></i>
                        </div>
                    @endif

                    <h4 class="fw-heading text-dark ls-1 mb-1">{{ strtoupper($setting->shop_name) }}</h4>
                    <p class="text-muted small mb-0">{{ $setting->address }}</p>
                    <p class="text-muted small fw-bold">WA: {{ $setting->phone }}</p>
                </div>

                <hr class="border-secondary border-opacity-10 border-dashed my-4">

                {{-- INFO TRANSAKSI --}}
                <div class="row mb-4 small text-dark g-3">
                    <div class="col-6">
                        <div class="mb-3">
                            <p class="mb-1 text-muted text-uppercase" style="font-size: 0.7rem;">No. Invoice</p>
                            <h6 class="fw-bold font-monospace text-dark">{{ $transaction->invoice_code }}</h6>
                        </div>
                        <div>
                            <p class="mb-1 text-muted text-uppercase" style="font-size: 0.7rem;">Tanggal Masuk</p>
                            <span class="fw-bold">{{ $transaction->created_at->format('d M Y, H:i') }}</span>
                        </div>
                    </div>
                    <div class="col-6 text-end">
                        <p class="mb-1 text-muted text-uppercase" style="font-size: 0.7rem;">Pelanggan</p>
                        <h6 class="fw-bold text-dark">{{ $transaction->customer->name }}</h6>
                        <span class="d-block text-muted">{{ $transaction->customer->phone }}</span>
                        @if($transaction->customer->address)
                            <span class="d-block text-muted mt-1 fst-italic" style="font-size: 0.75rem; line-height: 1.2;">{{ Str::limit($transaction->customer->address, 40) }}</span>
                        @endif
                    </div>
                </div>

                {{-- TABEL ITEM --}}
                <div class="table-responsive mb-4 rounded-3 border border-light-subtle">
                    <table class="table table-borderless align-middle mb-0">
                        <thead class="bg-light text-uppercase small text-muted">
                            <tr>
                                <th class="ps-3 py-2">Layanan</th>
                                <th class="text-center py-2">Qty</th>
                                <th class="text-end py-2">Harga</th>
                                <th class="text-end pe-3 py-2">Total</th>
                            </tr>
                        </thead>
                        <tbody class="border-bottom border-light-subtle">
                            @foreach($transaction->details as $item)
                            <tr>
                                <td class="ps-3 py-3">
                                    <span class="fw-bold text-dark d-block">{{ $item->service->name }}</span>
                                    <span class="text-muted small fst-italic" style="font-size: 0.75rem;">{{ $item->service->type == 'kiloan' ? 'Layanan Kiloan' : 'Layanan Satuan' }}</span>
                                </td>
                                <td class="text-center py-3 fw-bold text-secondary">{{ $item->qty }} {{ $item->service->unit }}</td>
                                <td class="text-end py-3 text-muted">{{ number_format($item->price_per_unit) }}</td>
                                <td class="text-end pe-3 py-3 fw-bold text-dark">{{ number_format($item->subtotal) }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                        <tfoot class="bg-light-primary bg-opacity-10">
                            <tr>
                                <td colspan="3" class="text-end pt-3 pb-3 fw-bold text-uppercase small text-primary">Total Tagihan</td>
                                <td class="text-end pe-3 pt-3 pb-3 fw-heading fs-5 text-primary">Rp {{ number_format($transaction->total_price) }}</td>
                            </tr>
                        </tfoot>
                    </table>
                </div>

                {{-- FOOTER NOTA --}}
                <div class="row align-items-center mt-5">
                    <div class="col-6">
                        <p class="small fw-bold text-muted text-uppercase mb-1" style="font-size: 0.7rem;">Status Pembayaran</p>
                        @if($transaction->payment_status == 'paid')
                            <div class="badge bg-success-subtle text-success border border-success px-3 py-2 rounded-pill d-inline-flex align-items-center gap-1">
                                <i class="bi bi-check-circle-fill"></i> LUNAS
                            </div>
                        @else
                            <div class="badge bg-danger-subtle text-danger border border-danger px-3 py-2 rounded-pill d-inline-flex align-items-center gap-1">
                                <i class="bi bi-x-circle-fill"></i> BELUM LUNAS
                            </div>
                        @endif
                    </div>
                    <div class="col-6 text-end">
                        <p class="text-muted small fst-italic mb-4 opacity-75">"Terima kasih telah mempercayakan kebersihan pakaian Anda kepada kami."</p>
                        <p class="small fw-bold mb-0 text-dark">Kasir: <span class="text-primary">{{ $transaction->user->name ?? 'Admin' }}</span></p>
                    </div>
                </div>

            </div>
        </div>

        {{-- RIWAYAT LOG (NO PRINT) --}}
        <div class="card mt-4 border-0 shadow-soft rounded-4 no-print overflow-hidden">
            <div class="card-header bg-white border-bottom border-light pt-4 px-4 pb-3">
                <h6 class="fw-heading mb-0 d-flex align-items-center gap-2">
                    <i class="bi bi-clock-history text-muted"></i> Riwayat Pengerjaan
                </h6>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover mb-0 align-middle">
                        <thead class="bg-light small text-muted text-uppercase">
                            <tr>
                                <th class="ps-4 py-3 border-0">Waktu</th>
                                <th class="py-3 border-0">Perubahan Status</th>
                                <th class="py-3 border-0">Oleh</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($transaction->logs as $log)
                            <tr class="transition-300">
                                <td class="ps-4 small text-muted">{{ $log->created_at->format('d M, H:i') }}</td>
                                <td>
                                    @if($log->status == 'pending') <span class="badge bg-secondary bg-opacity-10 text-secondary border-0 rounded-pill px-3">Pending</span>
                                    @elseif($log->status == 'process') <span class="badge bg-info bg-opacity-10 text-info border-0 rounded-pill px-3">Sedang Dicuci</span>
                                    @elseif($log->status == 'ready') <span class="badge bg-warning bg-opacity-10 text-warning border-0 rounded-pill px-3">Siap Ambil</span>
                                    @elseif($log->status == 'done') <span class="badge bg-success bg-opacity-10 text-success border-0 rounded-pill px-3">Selesai</span>
                                    @else <span class="badge bg-light text-dark border">{{ $log->status }}</span>
                                    @endif
                                </td>
                                <td class="small fw-bold text-dark">{{ $log->user->name ?? 'Sistem' }}</td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="3" class="text-center py-5 text-muted small">
                                    <i class="bi bi-hourglass-split d-block fs-4 mb-2 opacity-50"></i>
                                    Belum ada riwayat aktivitas.
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

    </div>
</div>

<style>
    .hover-scale:hover { transform: translateY(-2px); }
    .hover-top:hover { transform: translateY(-3px); }
    .ls-1 { letter-spacing: 1px; }
    .border-dashed { border-style: dashed !important; }
</style>
@endsection