@extends('layouts.admin')

@section('title', 'Edit Barang')
@section('page-title', 'Edit Stok Barang')

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-8">
        <div class="card border-0 shadow-soft rounded-4 overflow-hidden">
            <div class="card-header bg-white border-bottom border-light pt-4 px-4 pb-3">
                <h5 class="fw-heading mb-1">Edit Data Barang</h5>
                <p class="text-muted small mb-0">Perbarui informasi stok inventaris.</p>
            </div>
            
            <div class="card-body p-4">
                <form action="{{ route('inventories.update', $inventory->id) }}" method="POST">
                    @csrf
                    @method('PUT')
                    
                    <div class="mb-4">
                        <label class="form-label small fw-bold text-muted text-uppercase ls-1">Nama Barang</label>
                        <div class="input-group">
                            <span class="input-group-text bg-light border-light shadow-sm text-muted ps-3"><i class="bi bi-box-seam-fill"></i></span>
                            <input type="text" name="name" class="form-control border-light shadow-sm bg-white" value="{{ $inventory->name }}" required>
                        </div>
                    </div>

                    <div class="row g-4 mb-4">
                        <div class="col-md-6">
                            <label class="form-label small fw-bold text-muted text-uppercase ls-1">Stok Saat Ini</label>
                            <input type="number" name="stock" class="form-control border-light shadow-sm bg-white fw-bold text-primary" value="{{ $inventory->stock }}" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small fw-bold text-muted text-uppercase ls-1">Satuan</label>
                            <input type="text" name="unit" class="form-control border-light shadow-sm bg-white" value="{{ $inventory->unit }}" required>
                        </div>
                    </div>

                    <div class="row g-4 mb-4">
                        <div class="col-md-6">
                            <label class="form-label small fw-bold text-muted text-uppercase ls-1">Batas Minimum (Alert)</label>
                            <input type="number" name="min_stock" class="form-control border-light shadow-sm bg-white" value="{{ $inventory->min_stock }}" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small fw-bold text-muted text-uppercase ls-1">Harga Beli (Opsional)</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light border-light shadow-sm text-muted fw-bold">Rp</span>
                                <input type="number" name="price" class="form-control border-light shadow-sm bg-white" value="{{ $inventory->price }}">
                            </div>
                        </div>
                    </div>

                    <div class="mb-4">
                        <label class="form-label small fw-bold text-muted text-uppercase ls-1">Catatan</label>
                        <textarea name="note" class="form-control border-light shadow-sm bg-white" rows="2">{{ $inventory->note }}</textarea>
                    </div>

                    <div class="d-flex justify-content-end gap-3 pt-2">
                        <a href="{{ route('inventories.index') }}" class="btn btn-light rounded-pill px-4 fw-bold text-muted">Batal</a>
                        <button type="submit" class="btn btn-primary rounded-pill px-5 fw-bold shadow-lg hover-top transition-300">
                            <i class="bi bi-save2-fill me-2"></i> Simpan Perubahan
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