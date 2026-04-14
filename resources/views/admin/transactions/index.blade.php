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
                        <th class="px-4 py-3 text-uppercase small fw-bold text-secondary border-0 ls-1">Kurir</th>
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
                        </td>
                        
                        {{-- 3. Tipe Order --}}
                        <td class="px-4 py-3">
                            @if($t->delivery_type == 'pickup')
                                <span class="badge bg-light-warning text-warning border-0 rounded-pill px-3 py-2 fw-bold">Jemput</span>
                            @elseif($t->delivery_type == 'delivery')
                                <span class="badge bg-light-info text-info border-0 rounded-pill px-3 py-2 fw-bold">Antar</span>
                            @elseif($t->delivery_type == 'both')
                                <span class="badge bg-light-primary text-primary border-0 rounded-pill px-3 py-2 fw-bold">Full</span>
                            @else
                                <span class="badge bg-light-secondary text-secondary border-0 rounded-pill px-3 py-2 fw-bold">Walk-In</span>
                            @endif
                        </td>

                        {{-- 4. Kurir --}}
                        <td class="px-4 py-3">
                            @if($t->delivery_type == 'none')
                                <span class="text-muted opacity-50">-</span>
                            @else
                                @if($t->courier)
                                    <div class="d-flex align-items-center gap-2">
                                        <div class="bg-light-primary text-primary rounded-circle box-center" style="width: 32px; height: 32px; font-size: 0.8rem;">
                                            <b>{{ substr($t->courier->name, 0, 1) }}</b>
                                        </div>
                                        <span class="fw-bold d-block text-dark small">{{ explode(' ', $t->courier->name)[0] }}</span>
                                    </div>
                                @else
                                    <button type="button" class="btn btn-sm btn-outline-primary rounded-pill px-3 shadow-sm" data-bs-toggle="modal" data-bs-target="#modalCourier{{ $t->id }}">
                                        <i class="bi bi-person-plus-fill me-1"></i> Pilih
                                    </button>
                                @endif

                                {{-- MODAL PILIH KURIR --}}
                                <div class="modal fade" id="modalCourier{{ $t->id }}" tabindex="-1">
                                    <div class="modal-dialog modal-dialog-centered modal-sm">
                                        <div class="modal-content border-0 shadow-lg">
                                            <form action="{{ route('transactions.assignCourier', $t->id) }}" method="POST">
                                                @csrf
                                                @method('PUT')
                                                <div class="modal-header border-0 pb-0">
                                                    <h6 class="modal-title fw-bold">Tugaskan Kurir</h6>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                                </div>
                                                <div class="modal-body">
                                                    <div class="mb-3">
                                                        <label class="form-label small text-muted fw-bold">Pilih Driver Tersedia</label>
                                                        <select name="courier_id" class="form-select">
                                                            <option value="" disabled selected>-- Pilih --</option>
                                                            @foreach($drivers as $driver)
                                                                <option value="{{ $driver->id }}" {{ $t->courier_id == $driver->id ? 'selected' : '' }}>
                                                                    {{ $driver->name }}
                                                                </option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="modal-footer border-0 pt-0">
                                                    <button type="submit" class="btn btn-primary w-100 rounded-pill fw-bold">Simpan Penugasan</button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            @endif
                        </td>

                        {{-- 5. Status --}}
                        <td class="px-4 py-3">
                            @if($t->status == 'pending') 
                                <span class="badge bg-secondary bg-opacity-10 text-secondary px-3 py-2 rounded-pill fw-bold">Menunggu</span>
                            @elseif($t->status == 'process') 
                                <span class="badge bg-info bg-opacity-10 text-info px-3 py-2 rounded-pill fw-bold">Dicuci</span>
                            @elseif($t->status == 'ready') 
                                <span class="badge bg-warning bg-opacity-10 text-warning px-3 py-2 rounded-pill fw-bold">Siap</span>
                            @elseif($t->status == 'done') 
                                <span class="badge bg-success bg-opacity-10 text-success px-3 py-2 rounded-pill fw-bold">Selesai</span>
                            @endif
                        </td>
                        
                        {{-- 6. Total --}}
                        <td class="px-4 py-3 text-end text-dark fw-bold">
                            Rp {{ number_format($t->total_price, 0, ',', '.') }}
                        </td>

                        {{-- 7. Aksi --}}
                        <td class="px-4 py-3 text-center">
                            <div class="d-flex justify-content-center gap-2">
                                {{-- WA --}}
                                <a href="{{ $t->generateWaLink() }}" target="_blank" class="btn btn-icon btn-light-success text-success rounded-circle box-center shadow-sm" style="width: 34px; height: 34px;" title="WhatsApp">
                                    <i class="bi bi-whatsapp"></i>
                                </a>

                                {{-- Detail --}}
                                <a href="{{ route('transactions.show', $t->id) }}" class="btn btn-icon btn-light-info text-info rounded-circle box-center shadow-sm" style="width: 34px; height: 34px;" title="Detail">
                                    <i class="bi bi-eye-fill"></i>
                                </a>

                                {{-- STRUK (NEW CUSTOM OVERLAY TRIGGER) --}}
                                @if(!$t->details->isEmpty())
                                    <button type="button" onclick="showReceipt('{{ route('transactions.printThermal', $t->id) }}?popup=1')" class="btn btn-icon btn-light-primary text-primary rounded-circle box-center shadow-sm" style="width: 34px; height: 34px;" title="Cetak Struk">
                                        <i class="bi bi-printer-fill"></i>
                                    </button>
                                @endif

                                {{-- Surat Jalan --}}
                                @if($t->delivery_type != 'none' && !$t->details->isEmpty())
                                    <a href="{{ route('transactions.printDelivery', $t->id) }}" target="_blank" class="btn btn-icon btn-light-secondary text-secondary rounded-circle box-center shadow-sm" style="width: 34px; height: 34px;" title="Surat Jalan">
                                        <i class="bi bi-truck"></i>
                                    </a>
                                @endif

                                {{-- Edit --}}
                                @if($t->status != 'done' && !$t->details->isEmpty())
                                    <a href="{{ route('transactions.edit', $t->id) }}" class="btn btn-icon btn-light-warning text-warning rounded-circle box-center shadow-sm" style="width: 34px; height: 34px;" title="Proses">
                                        <i class="bi bi-pencil-fill"></i>
                                    </a>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="7" class="text-center py-5 text-muted fst-italic">Belum ada transaksi.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

{{-- CUSTOM OVERLAY PREVIEW STRUK (Tanpa Bootstrap Modal JS) --}}
<div id="receiptOverlay" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(15, 23, 42, 0.85); z-index: 9999; backdrop-filter: blur(5px);">
    <div style="position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%); width: 95%; max-width: 420px; background: white; border-radius: 24px; box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.5); overflow: hidden;">
        
        <!-- Header Overlay -->
        <div style="padding: 20px 25px; border-bottom: 1px solid #f1f5f9; display: flex; justify-content: space-between; align-items: center; background: #fff;">
            <h6 style="margin: 0; font-weight: 800; color: #2563EB; font-family: 'Outfit', sans-serif;">
                <i class="bi bi-printer-fill me-2"></i>PREVIEW STRUK
            </h6>
            <button onclick="closeReceipt()" style="border: none; background: #f1f5f9; width: 32px; height: 32px; border-radius: 50%; color: #64748b; transition: 0.3s;">
                <i class="bi bi-x-lg"></i>
            </button>
        </div>

        <!-- Body Overlay -->
        <div style="padding: 20px; background: #f8fafc;">
            <div id="receiptLoader" style="display: flex; justify-content: center; align-items: center; height: 400px;">
                <div class="spinner-border text-primary" role="status"></div>
            </div>
            <iframe id="receiptFrame" src="" style="width: 100%; height: 450px; border: none; border-radius: 12px; background: white; box-shadow: inset 0 2px 4px rgba(0,0,0,0.05); display: none;" onload="hideLoader()"></iframe>
        </div>

        <!-- Footer Overlay -->
        <div style="padding: 20px 25px; background: white; border-top: 1px solid #f1f5f9;">
            <button onclick="doPrint()" style="width: 100%; background: #2563EB; color: white; border: none; padding: 14px; border-radius: 15px; font-weight: 700; font-family: 'Plus Jakarta Sans', sans-serif; box-shadow: 0 10px 15px -3px rgba(37, 99, 235, 0.3); transition: 0.3s; cursor: pointer; margin-bottom: 10px;">
                <i class="bi bi-printer-fill me-2"></i>CETAK SEKARANG
            </button>
            <button onclick="closeReceipt()" style="width: 100%; background: #f1f5f9; color: #64748b; border: none; padding: 12px; border-radius: 15px; font-weight: 600; cursor: pointer;">
                BATAL
            </button>
        </div>
    </div>
</div>

<script>
    function showReceipt(url) {
        const overlay = document.getElementById('receiptOverlay');
        const frame = document.getElementById('receiptFrame');
        const loader = document.getElementById('receiptLoader');
        
        overlay.style.display = 'block';
        frame.style.display = 'none';
        loader.style.display = 'flex';
        
        frame.src = url;
    }

    function hideLoader() {
        document.getElementById('receiptLoader').style.display = 'none';
        document.getElementById('receiptFrame').style.display = 'block';
    }

    function closeReceipt() {
        document.getElementById('receiptOverlay').style.display = 'none';
        document.getElementById('receiptFrame').src = '';
    }

    function doPrint() {
        const frame = document.getElementById('receiptFrame');
        const frameWindow = frame.contentWindow;
        
        frameWindow.focus();
        setTimeout(() => {
            frameWindow.print();
        }, 250);
    }
</script>

<style>
    .hover-scale:hover { transform: translateY(-2px); }
    .btn-icon:hover { transform: translateY(-2px); }
    .table > :not(caption) > * > * { padding: 1rem 1.25rem; }
    .box-center { display: flex; align-items: center; justify-content: center; }
</style>
@endsection
