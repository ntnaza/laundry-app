@extends('layouts.admin')

@section('title', 'Tambah Paket')
@section('page-title', 'Tambah Paket Baru')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card border-0 shadow-soft rounded-4 overflow-hidden">
            <div class="card-header bg-white border-bottom border-light pt-4 px-4 pb-3">
                <h5 class="fw-heading mb-1">Form Layanan Baru</h5>
                <p class="text-muted small mb-0">Tambahkan jenis layanan laundry baru ke dalam sistem.</p>
            </div>
            
            <div class="card-body p-4">
                <form action="{{ route('services.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    
                    {{-- Nama Paket --}}
                    <div class="mb-4">
                        <label class="form-label small fw-bold text-muted text-uppercase ls-1">Nama Paket</label>
                        <div class="input-group">
                            <span class="input-group-text bg-light border-light shadow-sm text-muted ps-3"><i class="bi bi-tag-fill"></i></span>
                            <input type="text" name="name" class="form-control border-light shadow-sm bg-white" placeholder="Contoh: Cuci Komplit Wangi" required>
                        </div>
                    </div>

                    {{-- Gambar Paket --}}
                    <div class="mb-4">
                        <label class="form-label small fw-bold text-muted text-uppercase ls-1">Gambar Paket (Opsional)</label>
                        <div class="input-group">
                            <span class="input-group-text bg-light border-light shadow-sm text-muted ps-3"><i class="bi bi-image"></i></span>
                            <input type="file" name="image" class="form-control border-light shadow-sm bg-white" accept="image/*">
                        </div>
                        <div class="form-text small text-muted">Format: JPG, PNG, JPEG. Max: 2MB.</div>
                    </div>
                    
                    <div class="row g-4 mb-4">
                        {{-- Jenis --}}
                        <div class="col-md-6">
                            <label class="form-label small fw-bold text-muted text-uppercase ls-1">Jenis Layanan</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light border-light shadow-sm text-muted ps-3"><i class="bi bi-grid-fill"></i></span>
                                <select name="type" class="form-select border-light shadow-sm bg-white" required>
                                    <option value="kiloan">Kiloan (Berat)</option>
                                    <option value="satuan">Satuan (Pcs)</option>
                                </select>
                            </div>
                        </div>
                        
                        {{-- Satuan Unit --}}
                        <div class="col-md-6">
                            <label class="form-label small fw-bold text-muted text-uppercase ls-1">Satuan Unit</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light border-light shadow-sm text-muted ps-3"><i class="bi bi-rulers"></i></span>
                                <input type="text" name="unit" class="form-control border-light shadow-sm bg-white" placeholder="kg / pcs / lembar" required>
                            </div>
                        </div>
                    </div>

                    <div class="row g-4 mb-4">
                        {{-- Harga --}}
                        <div class="col-md-6">
                            <label class="form-label small fw-bold text-muted text-uppercase ls-1">Harga Dasar (Rp)</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light border-light shadow-sm text-muted ps-3 fw-bold">Rp</span>
                                <input type="number" name="price" class="form-control border-light shadow-sm bg-white" placeholder="0" required>
                            </div>
                        </div>
                        
                        {{-- Durasi --}}
                        <div class="col-md-6">
                            <label class="form-label small fw-bold text-muted text-uppercase ls-1">Estimasi Durasi</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light border-light shadow-sm text-muted ps-3"><i class="bi bi-clock-fill"></i></span>
                                <input type="number" name="estimate_duration" class="form-control border-light shadow-sm bg-white" placeholder="24" required>
                                <span class="input-group-text bg-light border-light shadow-sm text-muted pe-3">Jam</span>
                            </div>
                        </div>
                    </div>
                    
                    <div class="d-flex justify-content-end align-items-center gap-3 mt-5 pt-3 border-top border-light-subtle">
                        <a href="{{ route('services.index') }}" class="btn btn-light rounded-pill px-4 fw-bold text-muted">Batal</a>
                        <button type="submit" class="btn btn-primary rounded-pill px-5 fw-bold shadow-lg hover-top transition-300">
                            <i class="bi bi-save2-fill me-2"></i> Simpan Paket
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