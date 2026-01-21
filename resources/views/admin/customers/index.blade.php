@extends('layouts.admin')

@section('title', 'Data Pelanggan')
@section('page-title', 'Manajemen Pelanggan')

@section('content')
<div class="card border-0 shadow-sm rounded-4">
    <div class="card-header bg-transparent border-0 pt-4 px-4 pb-3 d-flex justify-content-between align-items-center flex-wrap gap-3">
        <div>
            <h5 class="fw-bold mb-1">List Member & Pelanggan</h5>
            <p class="text-muted small mb-0">Total {{ count($customers) }} pelanggan terdaftar.</p>
        </div>
        <a href="{{ route('customers.create') }}" class="btn btn-primary rounded-pill px-4 fw-bold d-flex align-items-center gap-2 shadow-sm hover-scale">
            <i class="bi bi-person-plus-fill"></i> Pelanggan Baru
        </a>
    </div>
    
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0" id="table1">
                <thead class="bg-light">
                    <tr>
                        <th class="ps-4 py-3 text-uppercase small fw-bold text-muted border-0" style="min-width: 200px;">Nama Pelanggan</th>
                        <th class="py-3 text-uppercase small fw-bold text-muted border-0" style="min-width: 150px;">Kontak</th>
                        <th class="py-3 text-uppercase small fw-bold text-muted border-0" style="min-width: 250px;">Alamat</th>
                        <th class="pe-4 py-3 text-uppercase small fw-bold text-muted border-0 text-center" style="width: 150px;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($customers as $c)
                    <tr class="border-bottom border-light">
                        {{-- 1. Nama (TEKS SAJA, TANPA AVATAR) --}}
                        <td class="ps-4 py-3">
                            <div class="d-flex flex-column justify-content-center">
                                <h6 class="mb-0 fw-bold text-dark">{{ $c->name }}</h6>
                                <small class="text-muted" style="font-size: 0.75rem;">ID Member: #{{ $c->id }}</small>
                            </div>
                        </td>

                        {{-- 2. Kontak --}}
                        <td class="py-3">
                            <a href="https://wa.me/{{ $c->phone }}" target="_blank" class="text-decoration-none badge bg-light-success text-success border border-success rounded-pill px-3 py-2 d-inline-flex align-items-center gap-2 hover-scale">
                                <i class="bi bi-whatsapp"></i> {{ $c->phone }}
                            </a>
                        </td>

                        {{-- 3. Alamat --}}
                        <td class="py-3">
                            <div class="d-flex align-items-start gap-2 text-muted" style="line-height: 1.4;">
                                <i class="bi bi-geo-alt-fill text-danger mt-1 flex-shrink-0" style="font-size: 0.8rem;"></i>
                                <span class="small">{{ Str::limit($c->address, 50, '...') ?: '-' }}</span>
                            </div>
                        </td>

                        {{-- 4. Aksi --}}
                        <td class="pe-4 py-3 text-center">
                            <div class="d-flex justify-content-center gap-2">
                                {{-- Tombol Detail --}}
                                <a href="{{ route('customers.show', $c->id) }}" class="btn btn-icon btn-light-info text-info rounded-circle box-center" style="width: 35px; height: 35px;" title="Riwayat Transaksi">
                                    <i class="bi bi-clock-history"></i>
                                </a>
                                
                                {{-- Tombol Edit --}}
                                <a href="{{ route('customers.edit', $c->id) }}" class="btn btn-icon btn-light-warning text-warning rounded-circle box-center" style="width: 35px; height: 35px;" title="Edit Data">
                                    <i class="bi bi-pencil-fill"></i>
                                </a>
                                
                                {{-- Tombol Hapus --}}
                                <form action="{{ route('customers.destroy', $c->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Yakin ingin menghapus pelanggan ini?')">
                                    @csrf @method('DELETE')
                                    <button class="btn btn-icon btn-light-danger text-danger rounded-circle box-center" style="width: 35px; height: 35px;" title="Hapus">
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
                                <div class="bg-light rounded-circle box-center mb-3" style="width: 60px; height: 60px;">
                                    <i class="bi bi-people fs-2 opacity-25 text-muted"></i>
                                </div>
                                <p class="text-muted small fw-bold mb-0">Belum ada data pelanggan.</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    
    @if(method_exists($customers, 'links'))
        <div class="card-footer bg-white border-0 py-3 px-4">
            {{ $customers->links() }}
        </div>
    @endif
</div>

<style>
    /* Styling */
    .table thead th { font-family: 'Plus Jakarta Sans', sans-serif; letter-spacing: 0.5px; vertical-align: middle; }
    
    /* Tombol Aksi */
    .btn-icon { 
        transition: all 0.2s; 
        border: 1px solid transparent; 
        display: flex; 
        align-items: center; 
        justify-content: center;
        padding: 0;
    }
    .btn-icon:hover { transform: translateY(-2px); border-color: currentColor; }
    .btn-icon i { line-height: 1; display: flex; } 
    
    /* Warna Custom */
    .btn-light-info { background-color: rgba(51, 154, 240, 0.1); border: 1px solid rgba(51, 154, 240, 0.1); }
    .btn-light-warning { background-color: rgba(255, 193, 7, 0.1); border: 1px solid rgba(255, 193, 7, 0.1); }
    .btn-light-danger { background-color: rgba(220, 53, 69, 0.1); border: 1px solid rgba(220, 53, 69, 0.1); }
    .btn-light-success { background-color: rgba(25, 135, 84, 0.1); border: 1px solid rgba(25, 135, 84, 0.1); }

    .hover-scale:hover { transform: translateY(-2px); }
</style>
@endsection