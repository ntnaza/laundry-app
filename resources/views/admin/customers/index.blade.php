@extends('layouts.admin')

@section('title', 'Data Pelanggan')
@section('page-title', 'Manajemen Pelanggan')

@section('content')
<div class="card border-0 shadow-soft rounded-4 overflow-hidden">
    <div class="card-header bg-white border-bottom border-light pt-4 px-4 pb-3 d-flex justify-content-between align-items-center flex-wrap gap-3">
        <div>
            <h5 class="fw-heading mb-1">List Pelanggan</h5>
            <p class="text-muted small mb-0">Total {{ count($customers) }} pelanggan terdaftar.</p>
        </div>
        <a href="{{ route('customers.create') }}" class="btn btn-primary rounded-pill px-4 fw-bold d-flex align-items-center gap-2 shadow-sm hover-top transition-300">
            <i class="bi bi-person-plus-fill"></i> Pelanggan Baru
        </a>
    </div>
    
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0" id="table1">
                <thead class="bg-light">
                    <tr>
                        <th class="ps-4 py-3 text-uppercase small fw-bold text-muted border-0 ls-1">Nama Pelanggan</th>
                        <th class="py-3 text-uppercase small fw-bold text-muted border-0 ls-1">Kontak</th>
                        <th class="py-3 text-uppercase small fw-bold text-muted border-0 ls-1">Alamat</th>
                        <th class="pe-4 py-3 text-uppercase small fw-bold text-muted border-0 ls-1 text-end">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($customers as $c)
                    <tr class="border-bottom border-light-subtle transition-300">
                        {{-- 1. Nama & Avatar --}}
                        <td class="ps-4 py-3">
                            <div class="d-flex align-items-center gap-3">
                                <div class="avatar rounded-circle bg-light-info text-info fw-bold d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
                                    {{ substr($c->name, 0, 1) }}
                                </div>
                                <div>
                                    <h6 class="mb-0 fw-bold text-dark">{{ $c->name }}</h6>
                                    <small class="text-muted" style="font-size: 0.75rem;">ID: #{{ $c->id }}</small>
                                </div>
                            </div>
                        </td>

                        {{-- 2. Kontak --}}
                        <td class="py-3">
                            <a href="https://wa.me/{{ $c->phone }}" target="_blank" class="text-decoration-none badge bg-success-subtle text-success border border-success rounded-pill px-3 py-1 d-inline-flex align-items-center gap-2 hover-top transition-300">
                                <i class="bi bi-whatsapp"></i> {{ $c->phone }}
                            </a>
                        </td>

                        {{-- 3. Alamat --}}
                        <td class="py-3">
                            <div class="d-flex align-items-start gap-2 text-secondary" style="line-height: 1.4; max-width: 300px;">
                                <i class="bi bi-geo-alt-fill text-danger mt-1 flex-shrink-0" style="font-size: 0.8rem;"></i>
                                <span class="small">{{ Str::limit($c->address, 50, '...') ?: '-' }}</span>
                            </div>
                        </td>

                        {{-- 4. Aksi --}}
                        <td class="pe-4 py-3 text-end">
                            <div class="d-flex justify-content-end gap-2">
                                {{-- Tombol Detail --}}
                                <a href="{{ route('customers.show', $c->id) }}" class="btn btn-icon btn-light-info text-info rounded-circle box-center shadow-sm" style="width: 36px; height: 36px;" title="Riwayat Transaksi">
                                    <i class="bi bi-clock-history"></i>
                                </a>
                                
                                {{-- Tombol Edit --}}
                                <a href="{{ route('customers.edit', $c->id) }}" class="btn btn-icon btn-light-warning text-warning rounded-circle box-center shadow-sm" style="width: 36px; height: 36px;" title="Edit Data">
                                    <i class="bi bi-pencil-fill"></i>
                                </a>
                                
                                {{-- Tombol Hapus --}}
                                <form action="{{ route('customers.destroy', $c->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Yakin ingin menghapus pelanggan ini?')">
                                    @csrf @method('DELETE')
                                    <button class="btn btn-icon btn-light-danger text-danger rounded-circle box-center shadow-sm" style="width: 36px; height: 36px;" title="Hapus">
                                        <i class="bi bi-trash-fill"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="text-center py-5">
                            <div class="d-flex flex-column align-items-center justify-content-center">
                                <div class="bg-light rounded-circle box-center mb-3" style="width: 70px; height: 70px;">
                                    <i class="bi bi-people fs-1 opacity-25 text-muted"></i>
                                </div>
                                <h6 class="text-muted fw-bold">Belum ada data pelanggan.</h6>
                                <p class="text-muted small mb-0">Klik "Pelanggan Baru" untuk menambahkan.</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    
    @if(method_exists($customers, 'links'))
        <div class="card-footer bg-white border-top border-light py-3 px-4">
            {{ $customers->links() }}
        </div>
    @endif
</div>

<style>
    .hover-top:hover { transform: translateY(-3px); }
    .btn-icon:hover { transform: translateY(-2px); }
    .bg-success-subtle { background-color: #f0fdf4 !important; }
    .bg-light-info { background-color: rgba(6, 182, 212, 0.1) !important; }
    .bg-light-warning { background-color: rgba(255, 193, 7, 0.1) !important; border: 1px solid rgba(255, 193, 7, 0.1); }
    .bg-light-danger { background-color: rgba(220, 53, 69, 0.1) !important; border: 1px solid rgba(220, 53, 69, 0.1); }
</style>
@endsection