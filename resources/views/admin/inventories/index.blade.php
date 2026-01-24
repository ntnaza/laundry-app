@extends('layouts.admin')

@section('title', 'Stok Inventaris')
@section('page-title', 'Manajemen Stok Bahan')

@section('content')
<div class="card border-0 shadow-soft rounded-4 overflow-hidden">
    <div class="card-header bg-white border-bottom border-light pt-4 px-4 pb-3 d-flex justify-content-between align-items-center flex-wrap gap-3">
        <div>
            <h5 class="fw-heading mb-1">Daftar Barang & Stok</h5>
            <p class="text-muted small mb-0">Pantau ketersediaan deterjen, pewangi, dan bahan lainnya.</p>
        </div>
        <a href="{{ route('inventories.create') }}" class="btn btn-primary rounded-pill px-4 fw-bold d-flex align-items-center gap-2 shadow-sm hover-top transition-300">
            <i class="bi bi-plus-lg"></i> Tambah Barang
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
                        <th class="ps-4 py-3 text-uppercase small fw-bold text-muted border-0 ls-1">Nama Barang</th>
                        <th class="py-3 text-uppercase small fw-bold text-muted border-0 ls-1">Stok Saat Ini</th>
                        <th class="py-3 text-uppercase small fw-bold text-muted border-0 ls-1">Batas Minimum</th>
                        <th class="py-3 text-uppercase small fw-bold text-muted border-0 ls-1">Status</th>
                        <th class="pe-4 py-3 text-uppercase small fw-bold text-muted border-0 ls-1 text-end">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($inventories as $item)
                    <tr class="border-bottom border-light-subtle transition-300">
                        <td class="ps-4 py-3">
                            <h6 class="fw-bold text-dark mb-0">{{ $item->name }}</h6>
                            <small class="text-muted" style="font-size: 0.75rem;">{{ $item->note ?? '-' }}</small>
                        </td>
                        <td class="py-3">
                            <span class="fw-heading fs-5 {{ $item->stock <= $item->min_stock ? 'text-danger' : 'text-dark' }}">
                                {{ $item->stock }}
                            </span>
                            <span class="text-muted small">{{ $item->unit }}</span>
                        </td>
                        <td class="py-3 text-secondary">
                            {{ $item->min_stock }} {{ $item->unit }}
                        </td>
                        <td class="py-3">
                            @if($item->stock <= 0)
                                <span class="badge bg-danger-subtle text-danger border border-danger rounded-pill px-3">Habis!</span>
                            @elseif($item->stock <= $item->min_stock)
                                <span class="badge bg-warning-subtle text-warning border border-warning rounded-pill px-3">Menipis</span>
                            @else
                                <span class="badge bg-success-subtle text-success border border-success rounded-pill px-3">Aman</span>
                            @endif
                        </td>
                        <td class="pe-4 py-3 text-end">
                            <div class="d-flex justify-content-end gap-2">
                                <a href="{{ route('inventories.edit', $item->id) }}" class="btn btn-icon btn-light-warning text-warning rounded-circle box-center shadow-sm" style="width: 36px; height: 36px;">
                                    <i class="bi bi-pencil-fill"></i>
                                </a>
                                
                                <form action="{{ route('inventories.destroy', $item->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Hapus barang ini?')">
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
                            <i class="bi bi-box-seam fs-1 d-block mb-2 opacity-25"></i>
                            Belum ada data inventaris.
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
    .bg-danger-subtle { background-color: #fef2f2 !important; }
    .bg-warning-subtle { background-color: #fefce8 !important; }
    .bg-success-subtle { background-color: #f0fdf4 !important; }
</style>
@endsection