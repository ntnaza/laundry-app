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
        <div class="d-flex justify-content-between align-items-center mb-4 no-print">
            <a href="{{ route('transactions.index') }}" class="btn btn-light rounded-circle shadow-sm box-center" style="width: 40px; height: 40px;">
                <i class="bi bi-arrow-left"></i>
            </a>
            <div class="d-flex gap-2">
                <a href="https://wa.me/{{ $transaction->customer->phone }}?text=Halo Kak {{ $transaction->customer->name }},%0A%0ATerima kasih sudah laundry di {{ $setting->shop_name }}.%0AInvoice: {{ $transaction->invoice_code }}%0ATotal: Rp {{ number_format($transaction->total_price) }}%0AStatus: {{ strtoupper($transaction->status) }}%0A%0ACek status cucianmu disini:%0A{{ route('track') }}" target="_blank" class="btn btn-success rounded-pill px-4 fw-bold shadow-sm hover-scale">
                    <i class="bi bi-whatsapp me-2"></i> Kirim WA
                </a>
                <a href="{{ route('transactions.printThermal', $transaction->id) }}" target="_blank" class="btn btn-dark rounded-pill px-4 fw-bold shadow-sm hover-scale">
                    <i class="bi bi-printer me-2"></i> Cetak Struk
                </a>
            </div>
        </div>

        @if(session('success'))
            <div class="alert alert-success border-0 shadow-sm rounded-4 mb-4 no-print d-flex align-items-center gap-3">
                <i class="bi bi-check-circle-fill fs-4"></i>
                <div>{{ session('success') }}</div>
            </div>
        @endif

        {{-- FORM UPDATE STATUS (NO PRINT) --}}
        <div class="card border-0 shadow-sm rounded-4 mb-4 no-print overflow-hidden">
            <div class="card-header bg-light-primary py-3 px-4 border-0">
                <h6 class="fw-bold text-primary mb-0 d-flex align-items-center gap-2">
                    <i class="bi bi-gear-fill"></i> Update Status Pengerjaan
                </h6>
            </div>
            <div class="card-body p-4">
                <form action="{{ route('transactions.updateStatus', $transaction->id) }}" method="POST">
                    @csrf
                    @method('PUT')
                    
                    <div class="row g-3 align-items-end">
                        <div class="col-md-4">
                            <label class="form-label small fw-bold text-muted text-uppercase">Status Laundry</label>
                            <select name="status" class="form-select border-0 bg-light rounded-3">
                                <option value="pending" {{ $transaction->status == 'pending' ? 'selected' : '' }}>⏳ Menunggu</option>
                                <option value="process" {{ $transaction->status == 'process' ? 'selected' : '' }}>🫧 Sedang Dicuci</option>
                                <option value="ready" {{ $transaction->status == 'ready' ? 'selected' : '' }}>🛍️ Siap Ambil</option>
                                <option value="done" {{ $transaction->status == 'done' ? 'selected' : '' }}>✅ Selesai</option>
                            </select>
                        </div>

                        <div class="col-md-4">
                            <label class="form-label small fw-bold text-muted text-uppercase">Pembayaran</label>
                            <select name="payment_status" class="form-select border-0 bg-light rounded-3">
                                <option value="unpaid" {{ $transaction->payment_status == 'unpaid' ? 'selected' : '' }}>❌ Belum Lunas</option>
                                <option value="paid" {{ $transaction->payment_status == 'paid' ? 'selected' : '' }}>💰 LUNAS</option>
                            </select>
                        </div>

                        <div class="col-md-4">
                            <button type="submit" class="btn btn-primary w-100 rounded-pill fw-bold shadow-sm">
                                <i class="bi bi-check2-circle me-1"></i> Simpan
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
                        <img src="{{ asset('storage/'.$setting->logo) }}" alt="Logo" style="width: 70px; margin-bottom: 10px;" class="rounded-3">
                    @else
                        <div class="bg-primary text-white rounded-3 box-center shadow-sm mx-auto mb-3" style="width: 50px; height: 50px;">
                            <i class="bi bi-basket-fill fs-4"></i>
                        </div>
                    @endif

                    <h4 class="fw-bold text-dark ls-1 mb-1">{{ strtoupper($setting->shop_name) }}</h4>
                    <p class="text-muted small mb-0">{{ $setting->address }}</p>
                    <p class="text-muted small">WA: {{ $setting->phone }}</p>
                </div>

                <hr class="border-secondary border-opacity-25 border-dashed my-4">

                {{-- INFO TRANSAKSI --}}
                <div class="row mb-4 small text-dark">
                    <div class="col-6">
                        <p class="mb-1 text-muted">No. Invoice</p>
                        <h6 class="fw-bold">{{ $transaction->invoice_code }}</h6>
                        
                        <p class="mb-1 mt-3 text-muted">Tanggal Masuk</p>
                        <span class="fw-bold">{{ $transaction->created_at->format('d M Y, H:i') }}</span>
                    </div>
                    <div class="col-6 text-end">
                        <p class="mb-1 text-muted">Pelanggan</p>
                        <h6 class="fw-bold">{{ $transaction->customer->name }}</h6>
                        <span class="d-block">{{ $transaction->customer->phone }}</span>
                        <span class="d-block text-muted mt-1" style="font-size: 0.8rem; line-height: 1.2;">{{ $transaction->customer->address }}</span>
                    </div>
                </div>

                {{-- TABEL ITEM --}}
                <div class="table-responsive mb-4">
                    <table class="table table-borderless align-middle mb-0">
                        <thead class="bg-light text-uppercase small text-muted">
                            <tr>
                                <th class="ps-3 py-2 rounded-start">Layanan</th>
                                <th class="text-center py-2">Qty</th>
                                <th class="text-end py-2">Harga</th>
                                <th class="text-end pe-3 py-2 rounded-end">Total</th>
                            </tr>
                        </thead>
                        <tbody class="border-bottom">
                            @foreach($transaction->details as $item)
                            <tr>
                                <td class="ps-3 py-3">
                                    <span class="fw-bold text-dark d-block">{{ $item->service->name }}</span>
                                    <span class="text-muted small fst-italic">{{ $item->service->type == 'kiloan' ? 'Kiloan' : 'Satuan' }}</span>
                                </td>
                                <td class="text-center py-3">{{ $item->qty }} {{ $item->service->unit }}</td>
                                <td class="text-end py-3">{{ number_format($item->price_per_unit) }}</td>
                                <td class="text-end pe-3 py-3 fw-bold">{{ number_format($item->subtotal) }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                        <tfoot class="bg-light">
                            <tr>
                                <td colspan="3" class="text-end pt-3 pb-3 fw-bold text-uppercase small">Total Tagihan</td>
                                <td class="text-end pe-3 pt-3 pb-3 fw-bold fs-5 text-primary">Rp {{ number_format($transaction->total_price) }}</td>
                            </tr>
                        </tfoot>
                    </table>
                </div>

                {{-- FOOTER NOTA --}}
                <div class="row align-items-center mt-5">
                    <div class="col-6">
                        <p class="small fw-bold text-muted text-uppercase mb-1">Status Pembayaran</p>
                        @if($transaction->payment_status == 'paid')
                            <div class="badge bg-success bg-opacity-10 text-success border border-success rounded-pill px-3 py-2">
                                <i class="bi bi-check-circle-fill me-1"></i> LUNAS
                            </div>
                        @else
                            <div class="badge bg-danger bg-opacity-10 text-danger border border-danger rounded-pill px-3 py-2">
                                <i class="bi bi-x-circle-fill me-1"></i> BELUM LUNAS
                            </div>
                        @endif
                    </div>
                    <div class="col-6 text-end">
                        <p class="text-muted small fst-italic mb-4">"Terima kasih telah mempercayakan kebersihan pakaian Anda kepada kami."</p>
                        <p class="small fw-bold mb-0">Kasir: {{ $transaction->user->name ?? 'Admin' }}</p>
                    </div>
                </div>

            </div>
        </div>

        {{-- RIWAYAT LOG (NO PRINT) --}}
        <div class="card mt-4 border-0 shadow-sm rounded-4 no-print overflow-hidden">
            <div class="card-header bg-white border-bottom pt-4 px-4 pb-3">
                <h6 class="fw-bold mb-0 d-flex align-items-center gap-2">
                    <i class="bi bi-clock-history text-muted"></i> Riwayat Pengerjaan
                </h6>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover mb-0 align-middle">
                        <thead class="bg-light small text-muted text-uppercase">
                            <tr>
                                <th class="ps-4 py-3">Waktu</th>
                                <th class="py-3">Perubahan Status</th>
                                <th class="py-3">Oleh</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($transaction->logs as $log)
                            <tr>
                                <td class="ps-4 small text-muted">{{ $log->created_at->format('d M, H:i') }}</td>
                                <td>
                                    @if($log->status == 'pending') <span class="badge bg-light text-dark border rounded-pill">Pending</span>
                                    @elseif($log->status == 'process') <span class="badge bg-info bg-opacity-10 text-info border border-info rounded-pill">Sedang Dicuci</span>
                                    @elseif($log->status == 'ready') <span class="badge bg-warning bg-opacity-10 text-warning border border-warning rounded-pill">Siap Ambil</span>
                                    @elseif($log->status == 'done') <span class="badge bg-success bg-opacity-10 text-success border border-success rounded-pill">Selesai</span>
                                    @else <span class="badge bg-light text-dark border">{{ $log->status }}</span>
                                    @endif
                                </td>
                                <td class="small fw-bold">{{ $log->user->name ?? 'Sistem' }}</td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="3" class="text-center py-4 text-muted small">Belum ada riwayat.</td>
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
    .ls-1 { letter-spacing: 1px; }
    .border-dashed { border-style: dashed !important; }
</style>
@endsection