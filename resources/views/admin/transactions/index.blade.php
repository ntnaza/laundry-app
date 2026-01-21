@extends('layouts.admin')

@section('title', 'Riwayat Transaksi')
@section('page-title', 'Data Transaksi & Order Masuk')

@section('content')
<div class="card border-0 shadow-sm rounded-4">
    <div class="card-header bg-transparent border-0 pt-4 px-4 pb-3 d-flex justify-content-between align-items-center flex-wrap gap-3">
        <div>
            <h5 class="fw-bold mb-1">List Cucian Masuk</h5>
            <p class="text-muted small mb-0">Kelola semua pesanan laundry yang masuk.</p>
        </div>
        <a href="{{ route('transactions.create') }}" class="btn btn-primary rounded-pill px-4 fw-bold d-flex align-items-center gap-2 shadow-sm hover-scale">
            <i class="bi bi-plus-lg"></i> Transaksi Manual
        </a>
    </div>
    
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="bg-light">
                    <tr>
                        <th class="px-4 py-3 text-uppercase small fw-bold text-muted border-0">Invoice</th>
                        <th class="px-4 py-3 text-uppercase small fw-bold text-muted border-0">Pelanggan</th>
                        <th class="px-4 py-3 text-uppercase small fw-bold text-muted border-0">Layanan</th>
                        <th class="px-4 py-3 text-uppercase small fw-bold text-muted border-0">Status</th>
                        <th class="px-4 py-3 text-uppercase small fw-bold text-muted border-0 text-end">Total</th>
                        <th class="px-4 py-3 text-uppercase small fw-bold text-muted border-0 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($transactions as $t)
                    <tr class="border-bottom border-light">
                        {{-- 1. Invoice --}}
                        <td class="px-4 py-3">
                            <div class="d-flex align-items-center gap-2">
                                <i class="bi bi-receipt text-primary"></i>
                                <span class="fw-bold text-dark">{{ $t->invoice_code }}</span>
                            </div>
                            <small class="text-muted d-block mt-1">{{ $t->created_at->format('d M, H:i') }}</small>
                        </td>

                        {{-- 2. Pelanggan --}}
                        <td class="px-4 py-3">
                            <h6 class="mb-1 fw-bold text-dark">{{ Str::limit($t->customer->name, 20) }}</h6>
                            <div class="d-flex align-items-center gap-2">
                                @if(isset($t->customer->phone))
                                    <span class="badge bg-light text-muted border rounded-pill fw-normal">
                                        <i class="bi bi-whatsapp text-success"></i> {{ $t->customer->phone }}
                                    </span>
                                @endif
                            </div>
                            
                            {{-- Alamat (Jika ada delivery) --}}
                            @if($t->delivery_type != 'none' && $t->pickup_address)
                                <div class="mt-2 small text-muted d-flex align-items-start gap-1" style="max-width: 200px; line-height: 1.2;">
                                    <i class="bi bi-geo-alt-fill text-danger flex-shrink-0"></i> 
                                    <span>{{ Str::limit($t->pickup_address, 25) }}</span>
                                </div>
                            @endif
                        </td>
                        
                        {{-- 3. Tipe Order (Layanan) --}}
                        <td class="px-4 py-3">
                            @if($t->delivery_type == 'pickup')
                                <span class="badge bg-warning bg-opacity-10 text-warning border border-warning rounded-pill px-3 py-2">
                                    <i class="bi bi-box-seam me-1"></i> Jemput Saja
                                </span>
                            @elseif($t->delivery_type == 'delivery')
                                <span class="badge bg-info bg-opacity-10 text-info border border-info rounded-pill px-3 py-2">
                                    <i class="bi bi-send me-1"></i> Antar Balik
                                </span>
                            @elseif($t->delivery_type == 'both')
                                <span class="badge bg-primary bg-opacity-10 text-primary border border-primary rounded-pill px-3 py-2">
                                    <i class="bi bi-arrow-left-right me-1"></i> Antar-Jemput
                                </span>
                            @else
                                <span class="badge bg-secondary bg-opacity-10 text-secondary border border-secondary rounded-pill px-3 py-2">
                                    <i class="bi bi-shop me-1"></i> Datang Sendiri
                                </span>
                            @endif

                            {{-- Status Kurir --}}
                            @if($t->delivery_status == 'pending' && $t->delivery_type != 'none')
                                <div class="mt-2 badge bg-danger text-white blink rounded-pill">
                                    <i class="bi bi-exclamation-circle me-1"></i> BUTUH KURIR
                                </div>
                            @elseif($t->delivery_status == 'on_the_way')
                                <div class="mt-2 badge bg-primary text-white rounded-pill">
                                    <i class="bi bi-scooter me-1"></i> KURIR OTW
                                </div>
                            @endif
                        </td>

                        {{-- 4. Status Laundry --}}
                        <td class="px-4 py-3">
                            @if($t->status == 'pending') 
                                <span class="badge bg-secondary rounded-pill px-3">Menunggu</span>
                            @elseif($t->status == 'process') 
                                <span class="badge bg-info text-white rounded-pill px-3">Sedang Cuci</span>
                            @elseif($t->status == 'ready') 
                                <span class="badge bg-warning text-dark rounded-pill px-3">Siap Ambil</span>
                            @elseif($t->status == 'done') 
                                <span class="badge bg-success rounded-pill px-3">Selesai</span>
                            @endif
                        </td>
                        
                        {{-- 5. Total & Kasir --}}
                        <td class="px-4 py-3 text-end">
                            @if($t->total_price == 0)
                                <span class="text-danger fw-bold small fst-italic">Belum Ditimbang</span>
                            @else
                                <h6 class="fw-bold text-primary mb-0">Rp {{ number_format($t->total_price, 0, ',', '.') }}</h6>
                            @endif
                            <small class="text-muted d-block mt-1" style="font-size: 0.75rem;">
                                <i class="bi bi-person-circle me-1"></i> {{ $t->user->name ?? 'Online' }}
                            </small>
                        </td>

                        {{-- 6. Aksi --}}
                        <td class="px-4 py-3 text-center">
                            <div class="d-flex justify-content-center gap-2">
                                {{-- Tombol Detail --}}
                                <a href="{{ route('transactions.show', $t->id) }}" class="btn btn-icon btn-light-info text-info rounded-circle box-center" style="width: 38px; height: 38px;" title="Detail Transaksi">
                                    <i class="bi bi-eye-fill"></i>
                                </a>

                                {{-- Tombol Surat Jalan --}}
                                @if($t->delivery_type != 'none')
                                    <a href="{{ route('transactions.printDelivery', $t->id) }}" target="_blank" class="btn btn-icon btn-light-secondary text-secondary rounded-circle box-center" style="width: 38px; height: 38px;" title="Cetak Surat Jalan">
                                        <i class="bi bi-truck"></i>
                                    </a>
                                @endif

                                {{-- Tombol Proses (Jika belum ditimbang) --}}
                                @if($t->total_price == 0)
                                    <a href="{{ route('transactions.edit', $t->id) }}" class="btn btn-icon btn-light-warning text-warning rounded-circle box-center" style="width: 38px; height: 38px;" title="Proses / Timbang">
                                        <i class="bi bi-basket-fill"></i>
                                    </a>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="text-center py-5">
                            <div class="d-flex flex-column align-items-center">
                                <div class="bg-light rounded-circle box-center mb-3" style="width: 60px; height: 60px;">
                                    <i class="bi bi-inbox fs-2 opacity-25 text-muted"></i>
                                </div>
                                <h6 class="text-muted fw-bold">Belum ada transaksi masuk.</h6>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    
    {{-- Pagination (Jika ada) --}}
    @if(method_exists($transactions, 'links'))
        <div class="card-footer bg-white border-0 py-3 px-4">
            {{ $transactions->links() }}
        </div>
    @endif
</div>

<style>
    /* Styling Tambahan */
    .table thead th { font-family: 'Plus Jakarta Sans', sans-serif; letter-spacing: 0.5px; }
    .btn-icon { transition: all 0.2s; border: 1px solid transparent; }
    .btn-icon:hover { transform: translateY(-2px); border-color: currentColor; }
    
    .btn-light-info { background-color: rgba(51, 154, 240, 0.1); border: 1px solid rgba(51, 154, 240, 0.1); }
    .btn-light-secondary { background-color: rgba(108, 117, 125, 0.1); border: 1px solid rgba(108, 117, 125, 0.1); }
    .btn-light-warning { background-color: rgba(255, 193, 7, 0.1); border: 1px solid rgba(255, 193, 7, 0.1); }

    .hover-scale:hover { transform: translateY(-2px); }
    .blink { animation: blinker 1.5s linear infinite; }
    @keyframes blinker { 50% { opacity: 0.5; } }
</style>
@endsection