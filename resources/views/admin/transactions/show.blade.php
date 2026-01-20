@php
    $setting = \App\Models\Setting::first();
@endphp

@extends('layouts.admin')

@section('title', 'Detail Transaksi')
@section('page-title', 'Detail & Cetak Nota')

@section('content')
<style>
    /* CSS Khusus Cetak: Sembunyikan tombol & sidebar saat diprint */
    @media print {
        body * {
            visibility: hidden;
        }
        #printableArea, #printableArea * {
            visibility: visible;
        }
        #printableArea {
            position: absolute;
            left: 0;
            top: 0;
            width: 100%;
        }
        .no-print {
            display: none !important;
        }
        /* Hilangkan background warna saat print biar hemat tinta */
        .card {
            border: 1px solid #ccc;
            box-shadow: none;
        }
    }
</style>

<div class="row">
    <div class="col-md-8 offset-md-2">
        
        <div class="mb-3 no-print text-end">
            <a href="{{ route('transactions.index') }}" class="btn btn-secondary me-2">Kembali</a>
            <a href="https://wa.me/{{ $transaction->customer->phone }}?text=Halo Kak {{ $transaction->customer->name }},%0A%0ATerima kasih sudah laundry di LaundryKuy.%0AInvoice: {{ $transaction->invoice_code }}%0ATotal: Rp {{ number_format($transaction->total_price) }}%0AStatus: {{ strtoupper($transaction->status) }}%0A%0ACek status cucianmu disini:%0A{{ route('track') }}" target="_blank" class="btn btn-success me-2">
        <i class="bi bi-whatsapp"></i> Kirim WA
    </a>
            <a href="{{ route('transactions.printThermal', $transaction->id) }}" target="_blank" class="btn btn-secondary me-2">
    <i class="bi bi-printer"></i> Struk Thermal
</a>
        </div>


        @if(session('success'))
            <div class="alert alert-success no-print">{{ session('success') }}</div>
        @endif

        <div class="card mb-4 no-print border-top-primary shadow-sm">
            <div class="card-body">
                <h5 class="card-title mb-3">⚙️ Update Status Pengerjaan</h5>
                
                <form action="{{ route('transactions.updateStatus', $transaction->id) }}" method="POST">
                    @csrf
                    @method('PUT')
                    
                    <div class="row align-items-end">
                        <div class="col-md-4 mb-2">
                            <label class="form-label">Status Laundry</label>
                            <select name="status" class="form-select">
                                <option value="pending" {{ $transaction->status == 'pending' ? 'selected' : '' }}>⏳ Baru Masuk / Pending</option>
                                <option value="process" {{ $transaction->status == 'process' ? 'selected' : '' }}>🫧 Sedang Dicuci</option>
                                <option value="ready" {{ $transaction->status == 'ready' ? 'selected' : '' }}>✅ Selesai (Siap Ambil)</option>
                                <option value="done" {{ $transaction->status == 'done' ? 'selected' : '' }}>📦 Sudah Diambil</option>
                            </select>
                        </div>

                        <div class="col-md-4 mb-2">
                            <label class="form-label">Status Pembayaran</label>
                            <select name="payment_status" class="form-select">
                                <option value="unpaid" {{ $transaction->payment_status == 'unpaid' ? 'selected' : '' }}>❌ Belum Bayar</option>
                                <option value="paid" {{ $transaction->payment_status == 'paid' ? 'selected' : '' }}>💰 LUNAS</option>
                            </select>
                        </div>

                        <div class="col-md-4 mb-2">
                            <button type="submit" class="btn btn-success w-100">Simpan Perubahan</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <div class="card" id="printableArea">
            <div class="card-body p-5">
                
                <div class="text-center mb-5">
    
    @if($setting->logo)
        <img src="{{ asset('storage/'.$setting->logo) }}" alt="Logo" style="width: 80px; margin-bottom: 10px;">
    @endif

    <h2 class="text-primary fw-bold" style="letter-spacing: 2px;">{{ strtoupper($setting->shop_name) }}</h2>
    
    <p class="text-muted">
        {{ $setting->address }}<br>
        WhatsApp: {{ $setting->phone }}
    </p>
    <hr>
</div>

                <div class="row mb-4">
                    <div class="col-6">
                        <p class="mb-0 text-muted">No. Invoice:</p>
                        <h5 class="fw-bold">{{ $transaction->invoice_code }}</h5>
                        <p class="mb-0 text-muted mt-2">Tanggal Masuk:</p>
                        <span>{{ $transaction->created_at->format('d M Y, H:i') }}</span>
                    </div>
                    <div class="col-6 text-end">
                        <p class="mb-0 text-muted">Pelanggan:</p>
                        <h5 class="fw-bold">{{ $transaction->customer->name }}</h5>
                        <span>{{ $transaction->customer->phone }}</span>
                        <p class="mt-2">{{ $transaction->customer->address }}</p>
                    </div>
                </div>

                <table class="table table-bordered mb-4">
                    <thead class="table-light">
                        <tr>
                            <th>Layanan</th>
                            <th class="text-center">Qty</th>
                            <th class="text-end">Harga</th>
                            <th class="text-end">Subtotal</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($transaction->details as $item)
                        <tr>
                            <td>
                                {{ $item->service->name }}
                                <br><small class="text-muted">{{ $item->service->type == 'kiloan' ? '(Kiloan)' : '(Satuan)' }}</small>
                            </td>
                            <td class="text-center">{{ $item->qty }} {{ $item->service->unit }}</td>
                            <td class="text-end">Rp {{ number_format($item->price_per_unit) }}</td>
                            <td class="text-end">Rp {{ number_format($item->subtotal) }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                    <tfoot>
                        <tr>
                            <td colspan="3" class="text-end"><strong>Total Tagihan</strong></td>
                            <td class="text-end"><strong class="fs-5">Rp {{ number_format($transaction->total_price) }}</strong></td>
                        </tr>
                    </tfoot>
                </table>

                <div class="row mt-5">
                    <div class="col-6">
                        <p class="mb-1">Status Pembayaran:</p>
                        @if($transaction->payment_status == 'paid')
                            <h3 class="text-success fw-bold" style="border: 2px solid green; display:inline-block; padding: 5px 10px; border-radius: 8px;">LUNAS</h3>
                        @else
                            <h3 class="text-danger fw-bold" style="border: 2px solid red; display:inline-block; padding: 5px 10px; border-radius: 8px;">BELUM LUNAS</h3>
                        @endif
                    </div>
                    <div class="col-6 text-end mt-3">
                        <p class="text-muted text-sm">Terima kasih telah mempercayakan kebersihan pakaian Anda kepada kami.</p>
                        <br><br>
                        <p class="fw-bold">( {{ $transaction->user->name ?? 'Admin' }} )</p>
                    </div>
                </div>

            </div>
        </div>

        <div class="card mt-4 no-print shadow-sm">
            <div class="card-header bg-light">
                <h6 class="mb-0">📜 Riwayat Pengerjaan (Audit Log)</h6>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead class="table-light">
                            <tr>
                                <th class="ps-4">Waktu</th>
                                <th>Status Berubah Menjadi</th>
                                <th>Diupdate Oleh</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($transaction->logs as $log)
                            <tr>
                                <td class="ps-4">{{ $log->created_at->format('d M Y, H:i') }}</td>
                                <td>
                                    @if($log->status == 'pending') <span class="badge bg-secondary">Pending</span>
                                    @elseif($log->status == 'process') <span class="badge bg-info">Sedang Dicuci</span>
                                    @elseif($log->status == 'ready') <span class="badge bg-warning text-dark">Siap Ambil</span>
                                    @elseif($log->status == 'done') <span class="badge bg-success">Selesai/Diambil</span>
                                    @else <span class="badge bg-light text-dark">{{ $log->status }}</span>
                                    @endif
                                </td>
                                <td>{{ $log->user->name ?? 'Sistem' }}</td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="3" class="text-center text-muted py-3">Belum ada riwayat perubahan status.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

    </div>
</div>
@endsection