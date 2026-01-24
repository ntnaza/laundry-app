@extends('layouts.admin')

@section('title', 'Daftar Paket Laundry')
@section('page-title', 'Manajemen Paket Laundry')

@section('content')
<div class="card border-0 shadow-soft rounded-4 overflow-hidden">
    <div class="card-header bg-white border-bottom border-light pt-4 px-4 pb-3 d-flex justify-content-between align-items-center flex-wrap gap-3">
        <div>
            <h5 class="fw-heading mb-1">Daftar Harga & Layanan</h5>
            <p class="text-muted small mb-0">Atur jenis layanan dan harga per unit.</p>
        </div>
        <a href="{{ route('services.create') }}" class="btn btn-primary rounded-pill px-4 fw-bold d-flex align-items-center gap-2 shadow-sm hover-top transition-300">
            <i class="bi bi-plus-lg"></i> Tambah Paket
        </a>
    </div>

    <div class="card-body p-0">
        @if(session('success'))
            <div class="alert alert-success border-0 shadow-sm rounded-0 mb-0 d-flex align-items-center gap-2">
                <i class="bi bi-check-circle-fill fs-5"></i> {{ session('success') }}
            </div>
        @endif

        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="bg-light">
                    <tr>
                        <th class="ps-4 py-3 text-uppercase small fw-bold text-muted border-0 ls-1">Nama Paket</th>
                        <th class="py-3 text-uppercase small fw-bold text-muted border-0 ls-1">Jenis Layanan</th>
                        <th class="py-3 text-uppercase small fw-bold text-muted border-0 ls-1">Harga Satuan</th>
                        <th class="py-3 text-uppercase small fw-bold text-muted border-0 ls-1">Estimasi</th>
                        <th class="pe-4 py-3 text-uppercase small fw-bold text-muted border-0 ls-1 text-end">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($services as $item)
                    <tr class="border-bottom border-light-subtle transition-300">
                        {{-- Nama Paket --}}
                        <td class="ps-4 py-3">
                            <div class="d-flex align-items-center gap-3">
                                <div class="avatar rounded-circle bg-light-primary text-primary fw-bold d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
                                    <i class="bi bi-basket-fill fs-5"></i>
                                </div>
                                <div>
                                    <h6 class="fw-bold text-dark mb-0">{{ $item->name }}</h6>
                                </div>
                            </div>
                        </td>

                        {{-- Jenis --}}
                        <td class="py-3">
                            @if($item->type == 'kiloan')
                                <span class="badge bg-info-subtle text-info border border-info rounded-pill px-3 py-1">
                                    <i class="bi bi-box-seam me-1"></i> Kiloan
                                </span>
                            @else
                                <span class="badge bg-warning-subtle text-warning border border-warning rounded-pill px-3 py-1">
                                    <i class="bi bi-tag-fill me-1"></i> Satuan
                                </span>
                            @endif
                        </td>

                        {{-- Harga --}}
                        <td class="py-3">
                            <span class="fw-bold text-dark">Rp {{ number_format($item->price) }}</span>
                            <span class="text-muted small"> / {{ $item->unit }}</span>
                        </td>

                        {{-- Estimasi --}}
                        <td class="py-3 text-secondary">
                            <i class="bi bi-clock me-1"></i> {{ $item->estimate_duration }} Jam
                        </td>

                        {{-- Aksi --}}
                        <td class="pe-4 py-3 text-end">
                            <div class="d-flex justify-content-end gap-2">
                                <a href="{{ route('services.edit', $item->id) }}" class="btn btn-icon btn-light-warning text-warning rounded-circle box-center shadow-sm" style="width: 36px; height: 36px;" title="Edit">
                                    <i class="bi bi-pencil-fill"></i>
                                </a>
                                
                                <form action="{{ route('services.destroy', $item->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Yakin ingin menghapus paket ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-icon btn-light-danger text-danger rounded-circle box-center shadow-sm" style="width: 36px; height: 36px;" title="Hapus">
                                        <i class="bi bi-trash-fill"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="text-center py-5">
                            <div class="d-flex flex-column align-items-center justify-content-center">
                                <div class="bg-light rounded-circle box-center mb-3" style="width: 70px; height: 70px;">
                                    <i class="bi bi-basket fs-1 opacity-25 text-muted"></i>
                                </div>
                                <h6 class="text-muted fw-bold">Belum ada paket layanan.</h6>
                                <p class="text-muted small mb-0">Tambahkan paket baru untuk memulai transaksi.</p>
                            </div>
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
    .btn-icon:hover { transform: translateY(-2px); }
    
    .bg-info-subtle { background-color: #ecfeff !important; }
    .bg-warning-subtle { background-color: #fefce8 !important; }
    
    .btn-light-warning { background-color: rgba(255, 193, 7, 0.1); border: 1px solid rgba(255, 193, 7, 0.1); }
    .btn-light-danger { background-color: rgba(220, 53, 69, 0.1); border: 1px solid rgba(220, 53, 69, 0.1); }
</style>
@endsection