@extends('layouts.admin')

@section('title', 'Laporan Pengeluaran')
@section('page-title', 'Data Pengeluaran Operasional')

@section('content')
<div class="row g-4">
    {{-- FORM INPUT PENGELUARAN (KIRI) --}}
    <div class="col-lg-4">
        <div class="card border-0 shadow-soft rounded-4 overflow-hidden">
            <div class="card-header bg-white border-bottom border-light pt-4 px-4 pb-3">
                <h5 class="fw-heading mb-1 text-danger">
                    <i class="bi bi-plus-circle-fill me-1"></i> Catat Baru
                </h5>
                <p class="text-muted small mb-0">Input pengeluaran biaya operasional.</p>
            </div>
            <div class="card-body p-4">
                <form action="{{ route('expenses.store') }}" method="POST">
                    @csrf
                    
                    <div class="mb-4">
                        <label class="form-label small fw-bold text-muted text-uppercase ls-1">Nama Pengeluaran</label>
                        <div class="input-group">
                            <span class="input-group-text bg-light border-light shadow-sm text-muted ps-3"><i class="bi bi-tag-fill"></i></span>
                            <input type="text" name="description" class="form-control border-light shadow-sm bg-white" placeholder="Contoh: Beli Deterjen, Listrik..." required>
                        </div>
                    </div>

                    <div class="mb-4">
                        <label class="form-label small fw-bold text-muted text-uppercase ls-1">Nominal (Rp)</label>
                        <div class="input-group">
                            <span class="input-group-text bg-light border-light shadow-sm text-muted ps-3 fw-bold">Rp</span>
                            <input type="number" name="amount" class="form-control border-light shadow-sm bg-white" placeholder="0" required>
                        </div>
                    </div>

                    <div class="mb-4">
                        <label class="form-label small fw-bold text-muted text-uppercase ls-1">Tanggal Bayar</label>
                        <div class="input-group">
                            <span class="input-group-text bg-light border-light shadow-sm text-muted ps-3"><i class="bi bi-calendar-event"></i></span>
                            <input type="date" name="date" class="form-control border-light shadow-sm bg-white" value="{{ date('Y-m-d') }}" required>
                        </div>
                    </div>

                    <div class="mb-4">
                        <label class="form-label small fw-bold text-muted text-uppercase ls-1">Catatan (Opsional)</label>
                        <textarea name="note" class="form-control border-light shadow-sm bg-white" rows="2" placeholder="Detail tambahan..."></textarea>
                    </div>

                    <button type="submit" class="btn btn-danger w-100 rounded-pill py-3 fw-bold shadow-lg hover-top transition-300">
                        <i class="bi bi-save2-fill me-2"></i> SIMPAN DATA
                    </button>
                </form>
            </div>
        </div>
    </div>

    {{-- RIWAYAT PENGELUARAN (KANAN) --}}
    <div class="col-lg-8">
        <div class="card border-0 shadow-soft rounded-4 overflow-hidden h-100">
            <div class="card-header bg-white border-bottom border-light pt-4 px-4 pb-3">
                <h5 class="fw-heading mb-1 d-flex align-items-center gap-2">
                    <i class="bi bi-clock-history text-primary"></i> Riwayat Pengeluaran
                </h5>
                <p class="text-muted small mb-0">Total {{ $expenses->count() }} catatan pengeluaran.</p>
            </div>
            
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="bg-light">
                            <tr>
                                <th class="ps-4 py-3 text-uppercase small fw-bold text-muted border-0 ls-1">Tanggal</th>
                                <th class="py-3 text-uppercase small fw-bold text-muted border-0 ls-1">Keterangan</th>
                                <th class="py-3 text-uppercase small fw-bold text-muted border-0 ls-1 text-end">Nominal</th>
                                <th class="pe-4 py-3 text-uppercase small fw-bold text-muted border-0 ls-1 text-end">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($expenses as $item)
                            <tr class="transition-300 border-bottom border-light-subtle">
                                <td class="ps-4 py-3 text-secondary small">
                                    {{ \Carbon\Carbon::parse($item->date)->format('d M Y') }}
                                </td>
                                <td class="py-3">
                                    <h6 class="fw-bold text-dark mb-0">{{ $item->description }}</h6>
                                    <div class="d-flex align-items-center gap-2 mt-1">
                                        <span class="badge bg-light-primary text-primary border-0 rounded-pill" style="font-size: 0.65rem;">
                                            <i class="bi bi-person-fill"></i> {{ $item->user->name ?? 'Admin' }}
                                        </span>
                                        @if($item->note)
                                            <span class="text-muted small fst-italic" style="font-size: 0.75rem;" title="{{ $item->note }}">
                                                <i class="bi bi-sticky me-1"></i> Ada Catatan
                                            </span>
                                        @endif
                                    </div>
                                </td>
                                <td class="py-3 text-end">
                                    <span class="fw-heading text-danger">Rp {{ number_format($item->amount) }}</span>
                                </td>
                                <td class="pe-4 py-3 text-end">
                                    <form action="{{ route('expenses.destroy', $item->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Yakin hapus data ini?')">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="btn btn-icon btn-light-danger text-danger rounded-circle box-center shadow-sm" style="width: 36px; height: 36px;" title="Hapus">
                                            <i class="bi bi-trash-fill"></i>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="4" class="text-center py-5">
                                    <div class="d-flex flex-column align-items-center justify-content-center p-5">
                                        <div class="bg-light rounded-circle box-center mb-3" style="width: 80px; height: 80px;">
                                            <i class="bi bi-receipt fs-1 text-muted opacity-25"></i>
                                        </div>
                                        <h6 class="text-muted fw-bold">Belum ada data pengeluaran.</h6>
                                    </div>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                        <tfoot class="bg-light-danger bg-opacity-10 border-top border-danger border-opacity-10">
                            <tr>
                                <td colspan="2" class="ps-4 py-3 text-end fw-bold text-uppercase small text-danger">Total Pengeluaran</td>
                                <td class="py-3 text-end fw-heading text-danger fs-5">
                                    Rp {{ number_format($expenses->sum('amount')) }}
                                </td>
                                <td class="pe-4"></td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .hover-top:hover { transform: translateY(-3px); }
    .btn-icon:hover { transform: translateY(-2px); }
    .bg-light-danger { background-color: rgba(239, 68, 68, 0.1) !important; border: 1px solid rgba(239, 68, 68, 0.1); }
</style>
@endsection