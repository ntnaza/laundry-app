@extends('layouts.admin')

@section('title', 'Profil Pelanggan')
@section('page-title', 'Detail Pelanggan')

@section('content')
<div class="row g-4">
    {{-- PROFIL CARD --}}
    <div class="col-lg-4">
        <div class="card border-0 shadow-soft rounded-4 overflow-hidden h-100">
            <div class="card-body text-center p-5">
                <div class="mb-4">
                    <div class="avatar rounded-circle bg-light-primary text-primary fw-bold mx-auto d-flex align-items-center justify-content-center shadow-sm border border-light" style="width: 80px; height: 80px; font-size: 2rem;">
                        {{ substr($customer->name, 0, 1) }}
                    </div>
                </div>
                
                <h5 class="fw-heading text-dark mb-1">{{ $customer->name }}</h5>
                <p class="text-muted small mb-4">Member ID: #{{ $customer->id }}</p>
                
                <div class="d-grid mb-4">
                    <a href="https://wa.me/{{ $customer->phone }}" target="_blank" class="btn btn-success rounded-pill fw-bold shadow-sm hover-top transition-300">
                        <i class="bi bi-whatsapp me-2"></i> Chat WhatsApp
                    </a>
                </div>

                <div class="text-start bg-light rounded-4 p-4">
                    <div class="mb-3">
                        <small class="text-muted text-uppercase fw-bold ls-1 d-block mb-1" style="font-size: 0.7rem;">Nomor Telepon</small>
                        <span class="fw-bold text-dark">{{ $customer->phone }}</span>
                    </div>
                    <div class="mb-3">
                        <small class="text-muted text-uppercase fw-bold ls-1 d-block mb-1" style="font-size: 0.7rem;">Alamat</small>
                        <span class="text-dark small">{{ $customer->address ?? '-' }}</span>
                    </div>
                    <div>
                        <small class="text-muted text-uppercase fw-bold ls-1 d-block mb-1" style="font-size: 0.7rem;">Bergabung Sejak</small>
                        <span class="fw-bold text-dark">{{ $customer->created_at?->format('d M Y') ?? '-' }}</span>
                    </div>
                </div>
            </div>
            
            <div class="card-footer bg-white border-top border-light p-3 text-center">
                <a href="{{ route('customers.edit', $customer->id) }}" class="text-decoration-none small fw-bold text-muted hover-text-primary transition-300">
                    <i class="bi bi-pencil-square me-1"></i> Edit Profil
                </a>
            </div>
        </div>
    </div>

    {{-- RIWAYAT TRANSAKSI --}}
    <div class="col-lg-8">
        <div class="card border-0 shadow-soft rounded-4 overflow-hidden h-100">
            <div class="card-header bg-white border-bottom border-light pt-4 px-4 pb-3">
                <h5 class="fw-heading mb-0 d-flex align-items-center gap-2">
                    <i class="bi bi-clock-history text-primary"></i> Riwayat Laundry
                </h5>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="bg-light">
                            <tr>
                                <th class="ps-4 py-3 text-uppercase small fw-bold text-muted border-0 ls-1">Tanggal</th>
                                <th class="py-3 text-uppercase small fw-bold text-muted border-0 ls-1">Invoice</th>
                                <th class="py-3 text-uppercase small fw-bold text-muted border-0 ls-1">Total</th>
                                <th class="py-3 text-uppercase small fw-bold text-muted border-0 ls-1">Status</th>
                                <th class="pe-4 py-3 text-uppercase small fw-bold text-muted border-0 ls-1 text-end">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($history as $t)
                            <tr class="transition-300 border-bottom border-light-subtle">
                                <td class="ps-4 py-3 small text-muted">{{ $t->created_at->format('d M Y') }}</td>
                                <td class="py-3 fw-bold text-dark font-monospace">{{ $t->invoice_code }}</td>
                                <td class="py-3 fw-bold text-primary">Rp {{ number_format($t->total_price) }}</td>
                                <td class="py-3">
                                    @if($t->status == 'done') 
                                        <span class="badge bg-success-subtle text-success border border-success rounded-pill px-3">Selesai</span>
                                    @elseif($t->status == 'pending') 
                                        <span class="badge bg-secondary-subtle text-secondary border border-secondary rounded-pill px-3">Pending</span>
                                    @elseif($t->status == 'process') 
                                        <span class="badge bg-info-subtle text-info border border-info rounded-pill px-3">Proses</span>
                                    @else 
                                        <span class="badge bg-warning-subtle text-warning border border-warning rounded-pill px-3">{{ ucfirst($t->status) }}</span>
                                    @endif
                                </td>
                                <td class="pe-4 py-3 text-end">
                                    <a href="{{ route('transactions.show', $t->id) }}" class="btn btn-sm btn-light-primary text-primary rounded-pill px-3 fw-bold shadow-sm hover-top transition-300">
                                        Detail
                                    </a>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="5" class="text-center py-5">
                                    <div class="d-flex flex-column align-items-center justify-content-center">
                                        <div class="bg-light rounded-circle box-center mb-3" style="width: 60px; height: 60px;">
                                            <i class="bi bi-inbox fs-2 opacity-25 text-muted"></i>
                                        </div>
                                        <p class="text-muted small fw-bold mb-0">Belum ada riwayat transaksi.</p>
                                    </div>
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
    .hover-top:hover { transform: translateY(-3px); }
    .hover-text-primary:hover { color: var(--bs-primary) !important; }
    
    .bg-success-subtle { background-color: #f0fdf4 !important; }
    .bg-info-subtle { background-color: #ecfeff !important; }
    .bg-warning-subtle { background-color: #fefce8 !important; }
    .bg-secondary-subtle { background-color: #f8fafc !important; }
    
    .btn-light-primary { background-color: rgba(37, 99, 235, 0.1); }
    .btn-light-primary:hover { background-color: var(--bs-primary); color: white !important; }
</style>
@endsection