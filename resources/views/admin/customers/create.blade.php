@extends('layouts.admin')

@section('title', 'Tambah Pelanggan')
@section('page-title', 'Registrasi Pelanggan Baru')

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-8">
        <div class="card border-0 shadow-soft rounded-4 overflow-hidden">
            
            {{-- HEADER --}}
            <div class="card-header bg-white border-bottom border-light pt-4 px-4 pb-3 d-flex align-items-center gap-3">
                <a href="{{ route('customers.index') }}" class="btn btn-light rounded-circle shadow-sm box-center transition-300" style="width: 42px; height: 42px;">
                    <i class="bi bi-arrow-left"></i>
                </a>
                <div>
                    <h5 class="fw-heading mb-1">Tambah Pelanggan Baru</h5>
                    <p class="text-muted small mb-0">Isi form di bawah untuk mendaftarkan member.</p>
                </div>
            </div>

            <div class="card-body p-4">
                <form action="{{ route('customers.store') }}" method="POST">
                    @csrf

                    {{-- NAMA LENGKAP --}}
                    <div class="mb-4">
                        <label class="form-label small fw-bold text-muted text-uppercase ls-1">Nama Lengkap</label>
                        <div class="input-group">
                            <span class="input-group-text bg-light border-light shadow-sm text-muted ps-3"><i class="bi bi-person-fill"></i></span>
                            <input type="text" name="name" class="form-control border-light shadow-sm bg-white" 
                                   placeholder="Contoh: Budi Santoso" required>
                        </div>
                    </div>
                    
                    {{-- NOMOR WHATSAPP --}}
                    <div class="mb-4">
                        <label class="form-label small fw-bold text-muted text-uppercase ls-1">Nomor WhatsApp</label>
                        <div class="input-group">
                            <span class="input-group-text bg-light border-light shadow-sm text-muted ps-3"><i class="bi bi-whatsapp"></i></span>
                            <input type="number" name="phone" class="form-control border-light shadow-sm bg-white" 
                                   placeholder="Contoh: 62812345678" required>
                        </div>
                        <div class="form-text small text-muted fst-italic">
                            <i class="bi bi-info-circle me-1"></i> Gunakan format 62... (contoh: 62812345678) agar fitur kirim WA berjalan lancar.
                        </div>
                    </div>
                    
                    {{-- ALAMAT DOMISILI --}}
                    <div class="mb-4">
                        <label class="form-label small fw-bold text-muted text-uppercase ls-1">Alamat Domisili</label>
                        <div class="input-group">
                            <span class="input-group-text bg-light border-light shadow-sm text-muted ps-3"><i class="bi bi-geo-alt-fill"></i></span>
                            <textarea name="address" class="form-control border-light shadow-sm bg-white" 
                                      rows="3" placeholder="Alamat lengkap domisili..."></textarea>
                        </div>
                    </div>
                    
                    <div class="d-flex justify-content-end align-items-center gap-3 mt-5 pt-3 border-top border-light-subtle">
                        <a href="{{ route('customers.index') }}" class="btn btn-light rounded-pill px-4 fw-bold text-muted">Batal</a>
                        <button type="submit" class="btn btn-primary rounded-pill px-5 fw-bold shadow-lg hover-top transition-300">
                            <i class="bi bi-save2-fill me-2"></i> Simpan Data
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<style>
    .hover-top:hover { transform: translateY(-3px); }
</style>
@endsection