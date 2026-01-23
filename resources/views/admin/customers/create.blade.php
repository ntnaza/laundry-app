@extends('layouts.admin')

@section('title', 'Tambah Pelanggan')
@section('page-title', 'Registrasi Pelanggan Baru')

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-8">
        <div class="card border-0 shadow-sm rounded-4">
            
            {{-- HEADER --}}
            <div class="card-header bg-transparent border-0 pt-4 px-4 pb-0 d-flex align-items-center gap-3">
                <a href="{{ route('customers.index') }}" class="btn btn-light rounded-circle shadow-sm box-center" style="width: 40px; height: 40px;">
                    <i class="bi bi-arrow-left"></i>
                </a>
                <div>
                    <h5 class="fw-bold mb-0">Tambah Pelanggan Baru</h5>
                    <p class="text-muted small mb-0">Isi form di bawah untuk mendaftarkan member.</p>
                </div>
            </div>

            <div class="card-body p-4">
                <form action="{{ route('customers.store') }}" method="POST">
                    @csrf

                    {{-- NAMA LENGKAP --}}
                    <div class="mb-4">
                        <label class="form-label fw-bold text-muted small text-uppercase">Nama Lengkap</label>
                        <div class="input-group">
                            <span class="input-group-text bg-light border-end-0 text-primary ps-3 rounded-start-3">
                                <i class="bi bi-person-fill"></i>
                            </span>
                            <input type="text" name="name" class="form-control bg-light border-start-0 py-2 rounded-end-3 text-dark fw-bold" 
                                   placeholder="Contoh: Budi Santoso" required>
                        </div>
                    </div>
                    
                    {{-- NOMOR WHATSAPP --}}
                    <div class="mb-4">
                        <label class="form-label fw-bold text-muted small text-uppercase">Nomor WhatsApp</label>
                        <div class="input-group">
                            <span class="input-group-text bg-light border-end-0 text-success ps-3 rounded-start-3">
                                <i class="bi bi-whatsapp"></i>
                            </span>
                            <input type="number" name="phone" class="form-control bg-light border-start-0 py-2 rounded-end-3 text-dark fw-bold" 
                                   placeholder="Contoh: 62812345678" required>
                        </div>
                        <div class="form-text ms-2">
                            <i class="bi bi-info-circle me-1"></i> Gunakan format 62... (contoh: 62812345678) agar fitur kirim WA berjalan lancar.
                        </div>
                    </div>
                    
                    {{-- ALAMAT DOMISILI --}}
                    <div class="mb-4">
                        <label class="form-label fw-bold text-muted small text-uppercase">Alamat Domisili</label>
                        <div class="input-group">
                            <span class="input-group-text bg-light border-end-0 text-danger ps-3 rounded-start-3">
                                <i class="bi bi-geo-alt-fill"></i>
                            </span>
                            <textarea name="address" class="form-control bg-light border-start-0 py-2 rounded-end-3 text-dark" 
                                      rows="3" placeholder="Alamat lengkap domisili..."></textarea>
                        </div>
                    </div>
                    
                    <hr class="border-light my-4">

                    {{-- TOMBOL AKSI --}}
                    <div class="d-flex justify-content-end gap-2">
                        <a href="{{ route('customers.index') }}" class="btn btn-light rounded-pill px-4 fw-bold">Batal</a>
                        <button type="submit" class="btn btn-primary rounded-pill px-4 fw-bold shadow-sm hover-scale">
                            <i class="bi bi-check-lg me-2"></i> Simpan Data
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<style>
    .hover-scale:hover { transform: translateY(-2px); transition: 0.2s; }
    
    /* Fokus Input jadi lebih soft */
    .form-control:focus {
        background-color: #fff !important;
        border-color: var(--bs-primary);
        box-shadow: none;
    }
    .input-group-text { background-color: #f8f9fa; border-color: #ced4da; }
    
    /* Efek fokus pada ikon input group */
    .input-group:focus-within .input-group-text {
        background-color: #fff;
        border-color: var(--bs-primary);
    }
</style>
@endsection