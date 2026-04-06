@extends('layouts.admin')

@section('title', 'Kode Promo')
@section('page-title', 'Manajemen Diskon')

@section('content')
<div class="card border-0 shadow-soft rounded-4 overflow-hidden">
    <div class="card-header bg-white border-bottom border-light pt-4 px-4 pb-3 d-flex justify-content-between align-items-center flex-wrap gap-3">
        <div>
            <h5 class="fw-heading mb-1">Daftar Voucher Diskon</h5>
            <p class="text-muted small mb-0">Buat kode promo untuk menarik pelanggan.</p>
        </div>
        <a href="{{ route('promos.create') }}" class="btn btn-primary rounded-pill px-4 fw-bold d-flex align-items-center gap-2 shadow-sm hover-top transition-300">
            <i class="bi bi-plus-lg"></i> Buat Promo Baru
        </a>
    </div>

    <div class="card-body p-0">

        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="bg-light">
                    <tr>
                        <th class="ps-4 py-3 text-uppercase small fw-bold text-muted border-0 ls-1">Kode Promo</th>
                        <th class="py-3 text-uppercase small fw-bold text-muted border-0 ls-1">Nilai Potongan</th>
                        <th class="py-3 text-uppercase small fw-bold text-muted border-0 ls-1">Masa Berlaku</th>
                        <th class="py-3 text-uppercase small fw-bold text-muted border-0 ls-1">Status</th>
                        <th class="pe-4 py-3 text-uppercase small fw-bold text-muted border-0 ls-1 text-end">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($promos as $p)
                    <tr class="border-bottom border-light-subtle transition-300">
                        <td class="ps-4 py-3">
                            <div class="d-flex align-items-center gap-3">
                                @if($p->image)
                                    <div class="rounded border overflow-hidden" style="width: 60px; height: 35px;">
                                        <img src="{{ asset('storage/' . $p->image) }}" alt="Promo" class="w-100 h-100 object-fit-cover">
                                    </div>
                                @endif
                                <div>
                                    <span class="badge bg-light-primary text-primary border border-primary border-opacity-25 rounded-pill px-3 py-2 fw-bold font-monospace fs-6">
                                        {{ $p->code }}
                                    </span>
                                    @if($p->min_spend > 0)
                                        <div class="small text-muted mt-1" style="font-size: 0.7rem;">Min. Belanja: Rp {{ number_format($p->min_spend) }}</div>
                                    @endif
                                </div>
                            </div>
                        </td>
                        <td class="py-3">
                            @if($p->type == 'percentage')
                                <span class="fw-bold text-dark fs-5">{{ $p->value }}%</span>
                                @if($p->max_discount)
                                    <small class="text-muted d-block">Max: Rp {{ number_format($p->max_discount) }}</small>
                                @endif
                            @else
                                <span class="fw-bold text-dark fs-5">Rp {{ number_format($p->value) }}</span>
                            @endif
                        </td>
                        <td class="py-3 text-secondary small">
                            @if($p->start_date && $p->end_date)
                                <div><i class="bi bi-calendar-event me-1"></i> {{ \Carbon\Carbon::parse($p->start_date)->format('d M') }} - {{ \Carbon\Carbon::parse($p->end_date)->format('d M Y') }}</div>
                            @else
                                <span class="fst-italic text-muted">Selamanya</span>
                            @endif
                        </td>
                        <td class="py-3">
                            @if(!$p->is_active)
                                <span class="badge bg-secondary-subtle text-secondary rounded-pill px-3">Nonaktif</span>
                            @elseif($p->end_date && now()->gt($p->end_date))
                                <span class="badge bg-danger-subtle text-danger rounded-pill px-3">Kadaluarsa</span>
                            @else
                                <span class="badge bg-success-subtle text-success rounded-pill px-3">Aktif</span>
                            @endif
                        </td>
                        <td class="pe-4 py-3 text-end">
                            <div class="d-flex justify-content-end gap-2">
                                <a href="{{ route('promos.edit', $p->id) }}" class="btn btn-icon btn-light-warning text-warning rounded-circle box-center shadow-sm" style="width: 36px; height: 36px;">
                                    <i class="bi bi-pencil-fill"></i>
                                </a>
                                <form action="{{ route('promos.destroy', $p->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Hapus promo ini?')">
                                    @csrf @method('DELETE')
                                    <button class="btn btn-icon btn-light-danger text-danger rounded-circle box-center shadow-sm" style="width: 36px; height: 36px;">
                                        <i class="bi bi-trash-fill"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="text-center py-5 text-muted">
                            <i class="bi bi-ticket-perforated fs-1 d-block mb-2 opacity-25"></i>
                            Belum ada promo aktif.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<style>
    .hover-top:hover { transform: translateY(-3px); }
    .bg-light-primary { background-color: rgba(37, 99, 235, 0.1); }
</style>
@endsection