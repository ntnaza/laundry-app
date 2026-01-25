@extends('layouts.admin')

@section('title', 'Edit Paket')
@section('page-title', 'Edit Paket Laundry')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card border-0 shadow-soft rounded-4 overflow-hidden">
            <div class="card-header bg-white border-bottom border-light pt-4 px-4 pb-3">
                <h5 class="fw-heading mb-1">Form Edit Layanan</h5>
                <p class="text-muted small mb-0">Perbarui informasi paket laundry.</p>
            </div>

            <div class="card-body p-4">
                <form action="{{ route('services.update', $service->id) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT') 

                    {{-- Nama Paket --}}
                    <div class="mb-4">
                        <label class="form-label small fw-bold text-muted text-uppercase ls-1">Nama Paket</label>
                        <div class="input-group">
                            <span class="input-group-text bg-light border-light shadow-sm text-muted ps-3"><i class="bi bi-tag-fill"></i></span>
                            <input type="text" name="name" class="form-control border-light shadow-sm bg-white" value="{{ $service->name }}" required>
                        </div>
                    </div>

                    {{-- Gambar Paket --}}
                    <div class="mb-4">
                        <label class="form-label small fw-bold text-muted text-uppercase ls-1">Gambar Paket</label>
                        @if($service->image)
                            <div class="mb-2">
                                <img src="{{ asset('storage/' . $service->image) }}" alt="Current Image" class="img-thumbnail rounded" style="max-height: 150px;">
                            </div>
                        @endif
                        <div class="input-group">
                            <span class="input-group-text bg-light border-light shadow-sm text-muted ps-3"><i class="bi bi-image"></i></span>
                            <input type="file" name="image" class="form-control border-light shadow-sm bg-white" accept="image/*">
                        </div>
                        <div class="form-text small text-muted">Biarkan kosong jika tidak ingin mengubah gambar.</div>
                    </div>
                    
                    <div class="row g-4 mb-4">
                        {{-- Jenis --}}
                        <div class="col-md-6">
                            <label class="form-label small fw-bold text-muted text-uppercase ls-1">Jenis Layanan</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light border-light shadow-sm text-muted ps-3"><i class="bi bi-grid-fill"></i></span>
                                <select name="type" class="form-select border-light shadow-sm bg-white" required>
                                    <option value="kiloan" {{ $service->type == 'kiloan' ? 'selected' : '' }}>Kiloan (Berat)</option>
                                    <option value="satuan" {{ $service->type == 'satuan' ? 'selected' : '' }}>Satuan (Pcs)</option>
                                </select>
                            </div>
                        </div>
                        
                        {{-- Satuan Unit --}}
                        <div class="col-md-6">
                            <label class="form-label small fw-bold text-muted text-uppercase ls-1">Satuan Unit</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light border-light shadow-sm text-muted ps-3"><i class="bi bi-rulers"></i></span>
                                <input type="text" name="unit" class="form-control border-light shadow-sm bg-white" value="{{ $service->unit }}" required>
                            </div>
                        </div>
                    </div>

                    <div class="row g-4 mb-4">
                        {{-- Harga --}}
                        <div class="col-md-6">
                            <label class="form-label small fw-bold text-muted text-uppercase ls-1">Harga Dasar (Rp)</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light border-light shadow-sm text-muted ps-3 fw-bold">Rp</span>
                                <input type="number" name="price" class="form-control border-light shadow-sm bg-white" value="{{ $service->price }}" required>
                            </div>
                        </div>
                        
                        {{-- Durasi --}}
                        <div class="col-md-6">
                            <label class="form-label small fw-bold text-muted text-uppercase ls-1">Estimasi Durasi</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light border-light shadow-sm text-muted ps-3"><i class="bi bi-clock-fill"></i></span>
                                <input type="number" name="estimate_duration" class="form-control border-light shadow-sm bg-white" value="{{ $service->estimate_duration }}" required>
                                <span class="input-group-text bg-light border-light shadow-sm text-muted pe-3">Jam</span>
                            </div>
                        </div>
                    </div>
                    
                    <div class="d-flex justify-content-end align-items-center gap-3 mt-5 pt-3 border-top border-light-subtle">
                        <a href="{{ route('services.index') }}" class="btn btn-light rounded-pill px-4 fw-bold text-muted">Batal</a>
                        <button type="submit" class="btn btn-primary rounded-pill px-5 fw-bold shadow-lg hover-top transition-300">
                            <i class="bi bi-save2-fill me-2"></i> Update Perubahan
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