    @extends('layouts.admin')

    @section('title', 'Riwayat Transaksi')
    @section('page-title', 'Data Transaksi & Order Masuk')

    @section('content')
    <div class="card border-0 shadow-soft rounded-4 overflow-hidden">
        <div class="card-header bg-white border-bottom border-light pt-4 px-4 pb-3 d-flex justify-content-between align-items-center flex-wrap gap-3">
            <div>
                <h5 class="fw-heading mb-1">List Cucian Masuk</h5>
                <p class="text-muted small mb-0">Kelola semua pesanan laundry yang masuk.</p>
            </div>
            <a href="{{ route('transactions.create') }}" class="btn btn-primary rounded-pill px-4 fw-bold d-flex align-items-center gap-2 shadow-sm hover-scale transition-300">
                <i class="bi bi-plus-lg"></i> Transaksi Manual
            </a>
        </div>
        
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="bg-light">
                        <tr>
                            <th class="px-4 py-3 text-uppercase small fw-bold text-secondary border-0 ls-1">Invoice</th>
                            <th class="px-4 py-3 text-uppercase small fw-bold text-secondary border-0 ls-1">Pelanggan</th>
                            <th class="px-4 py-3 text-uppercase small fw-bold text-secondary border-0 ls-1">Layanan</th>
                            <th class="px-4 py-3 text-uppercase small fw-bold text-secondary border-0 ls-1">Status</th>
                            <th class="px-4 py-3 text-uppercase small fw-bold text-secondary border-0 ls-1 text-end">Total</th>
                            <th class="px-4 py-3 text-uppercase small fw-bold text-secondary border-0 ls-1 text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($transactions as $t)
                        <tr class="border-bottom border-light transition-300">
                            {{-- 1. Invoice --}}
                            <td class="px-4 py-3">
                                <div class="d-flex align-items-center gap-2">
                                    <div class="bg-light-primary text-primary rounded-circle box-center" style="width: 32px; height: 32px;">
                                        <i class="bi bi-receipt small"></i>
                                    </div>
                                    <span class="fw-bold text-dark font-monospace">{{ $t->invoice_code }}</span>
                                </div>
                                <small class="text-muted d-block mt-1 ps-5">{{ $t->created_at->format('d M, H:i') }}</small>
                            </td>

                            {{-- 2. Pelanggan --}}
                            <td class="px-4 py-3">
                                <div class="d-flex flex-column">
                                    <span class="fw-bold text-dark">{{ Str::limit($t->customer->name, 20) }}</span>
                                    @if(isset($t->customer->phone))
                                        <div class="d-flex align-items-center gap-1 mt-1 text-muted small">
                                            <i class="bi bi-whatsapp text-success"></i> 
                                            <span>{{ $t->customer->phone }}</span>
                                        </div>
                                    @endif
                                </div>
                                
                                {{-- Alamat (Jika ada delivery) --}}
                                @if($t->delivery_type != 'none' && $t->pickup_address)
                                    <div class="mt-2 small text-muted d-flex align-items-start gap-1 p-2 rounded bg-light" style="max-width: 220px; line-height: 1.3;">
                                        <i class="bi bi-geo-alt-fill text-danger flex-shrink-0 mt-1"></i> 
                                        <span class="fst-italic" style="font-size: 0.75rem;">{{ Str::limit($t->pickup_address, 35) }}</span>
                                    </div>
                                @endif
                            </td>
                            
                            {{-- 3. Tipe Order (Layanan) --}}
                            <td class="px-4 py-3">
                                @if($t->delivery_type == 'pickup')
                                    <span class="badge bg-light-warning text-warning border-0 rounded-pill px-3 py-2 fw-bold">
                                        <i class="bi bi-box-seam me-1"></i> Jemput
                                    </span>
                                @elseif($t->delivery_type == 'delivery')
                                    <span class="badge bg-light-info text-info border-0 rounded-pill px-3 py-2 fw-bold">
                                        <i class="bi bi-send me-1"></i> Antar
                                    </span>
                                @elseif($t->delivery_type == 'both')
                                    <span class="badge bg-light-primary text-primary border-0 rounded-pill px-3 py-2 fw-bold">
                                        <i class="bi bi-arrow-left-right me-1"></i> Full Service
                                    </span>
                                @else
                                    <span class="badge bg-light-secondary text-secondary border-0 rounded-pill px-3 py-2 fw-bold">
                                        <i class="bi bi-shop me-1"></i> Walk-In
                                    </span>
                                @endif
                                
                                @if($t->note)
                                    <div class="mt-2" data-bs-toggle="tooltip" title="{{ $t->note }}">
                                       <i class="bi bi-info-circle text-muted"></i> <span class="text-muted small fst-italic">Ada Catatan</span>
                                   </div>
                                @endif

                                {{-- Status Kurir --}}
                                @if($t->delivery_status == 'pending' && $t->delivery_type != 'none')
                                    <div class="mt-2 badge bg-danger text-white blink rounded-pill border-0 shadow-sm">
                                        <i class="bi bi-exclamation-circle me-1"></i> Butuh Kurir
                                    </div>
                                @elseif($t->delivery_status == 'on_the_way')
                                    <div class="mt-2 badge bg-primary text-white rounded-pill border-0 shadow-sm">
                                        <i class="bi bi-scooter me-1"></i> Kurir OTW
                                    </div>
                                @endif
                            </td>

                            {{-- 4. Status Laundry --}}
                            <td class="px-4 py-3">
                                {{-- KONDISI KHUSUS: BARU BAYAR ONGKIR --}}
                                @if($t->status == 'pending' && $t->details->isEmpty())
                                    <span class="badge bg-warning text-dark px-3 py-2 rounded-pill fw-bold border border-warning shadow-sm blink">
                                        <i class="bi bi-hourglass-split me-1"></i> Menunggu Customer
                                    </span>
                                @elseif($t->status == 'pending') 
                                    <span class="badge bg-secondary bg-opacity-10 text-secondary px-3 py-2 rounded-pill fw-bold">Menunggu</span>
                                @elseif($t->status == 'process') 
                                    <span class="badge bg-info bg-opacity-10 text-info px-3 py-2 rounded-pill fw-bold">Dicuci</span>
                                @elseif($t->status == 'ready') 
                                    <span class="badge bg-warning bg-opacity-10 text-warning px-3 py-2 rounded-pill fw-bold">Siap Ambil</span>
                                @elseif($t->status == 'done') 
                                    <span class="badge bg-success bg-opacity-10 text-success px-3 py-2 rounded-pill fw-bold">Selesai</span>
                                @endif
                            </td>
                            
                            {{-- 5. Total & Kasir --}}
                            <td class="px-4 py-3 text-end">
                                @if($t->total_price == 0 || $t->details->isEmpty())
                                    <span class="badge bg-light-danger text-danger border-0 rounded-pill px-2">Belum Timbang / Kosong</span>
                                @else
                                    <h6 class="fw-bold text-dark mb-0">Rp {{ number_format($t->total_price, 0, ',', '.') }}</h6>
                                @endif
                                <small class="text-muted d-block mt-1" style="font-size: 0.75rem;">
                                    <i class="bi bi-person-circle me-1"></i> {{ $t->user->name ?? 'System' }}
                                </small>
                            </td>

                            {{-- 6. Aksi --}}
                            <td class="px-4 py-3 text-center">
                                <div class="d-flex justify-content-center gap-2">
                                    {{-- Tombol WA --}}
                                    <a href="{{ $t->generateWaLink() }}" target="_blank" class="btn btn-icon btn-light-success text-success rounded-circle box-center shadow-sm" style="width: 34px; height: 34px;" data-bs-toggle="tooltip" title="Kirim WhatsApp">
                                        <i class="bi bi-whatsapp"></i>
                                    </a>

                                    {{-- Tombol Detail --}}
                                    <a href="{{ route('transactions.show', $t->id) }}" class="btn btn-icon btn-light-info text-info rounded-circle box-center shadow-sm" style="width: 34px; height: 34px;" data-bs-toggle="tooltip" title="Detail">
                                        <i class="bi bi-eye-fill"></i>
                                    </a>

                                    {{-- Tombol Surat Jalan --}}
                                    @if($t->delivery_type != 'none' && !$t->details->isEmpty())
                                        <a href="{{ route('transactions.printDelivery', $t->id) }}" target="_blank" class="btn btn-icon btn-light-secondary text-secondary rounded-circle box-center shadow-sm" style="width: 34px; height: 34px;" data-bs-toggle="tooltip" title="Surat Jalan">
                                            <i class="bi bi-truck"></i>
                                        </a>
                                    @endif

                                    {{-- Tombol Proses / Edit (Selama belum selesai & SUDAH ADA ITEM) --}}
                                    @if($t->status != 'done' && !$t->details->isEmpty())
                                        <a href="{{ route('transactions.edit', $t->id) }}" class="btn btn-icon btn-light-warning text-warning rounded-circle box-center shadow-sm" style="width: 34px; height: 34px;" data-bs-toggle="tooltip" title="Proses / Edit Pesanan">
                                            <i class="bi bi-pencil-fill"></i>
                                        </a>
                                    @endif
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="text-center py-5">
                                <div class="d-flex flex-column align-items-center justify-content-center p-5">
                                    <div class="bg-light rounded-circle box-center mb-3" style="width: 80px; height: 80px;">
                                        <i class="bi bi-inbox fs-1 text-muted opacity-25"></i>
                                    </div>
                                    <h6 class="text-muted fw-bold">Belum ada transaksi masuk.</h6>
                                    <p class="text-muted small mb-0">Klik tombol "Transaksi Manual" untuk membuat pesanan baru.</p>
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
            <div class="card-footer bg-white border-top border-light py-3 px-4">
                {{ $transactions->links() }}
            </div>
        @endif
    </div>

    <style>
        .hover-scale:hover { transform: translateY(-2px); }
        .blink { animation: blinker 1.5s linear infinite; }
        @keyframes blinker { 50% { opacity: 0.5; } }
        
        /* Custom Table Spacing */
        .table > :not(caption) > * > * { padding: 1rem 1.25rem; }
    </style>
    @endsection