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

                    {{-- RESEP BAHAN BAKU --}}
                    <div class="card bg-light border-0 rounded-4 p-4 mb-4">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <div>
                                <h6 class="fw-bold text-dark mb-1">Resep Bahan Baku (Opsional)</h6>
                                <p class="text-muted small mb-0">Tentukan bahan yang otomatis berkurang setiap layanan ini diproses.</p>
                            </div>
                            <button type="button" class="btn btn-sm btn-primary rounded-pill px-3 fw-bold shadow-sm" id="addMaterialBtn">
                                <i class="bi bi-plus-lg me-1"></i> Tambah Bahan
                            </button>
                        </div>

                        <div id="materialsContainer">
                            {{-- Row akan ditambahkan via JS --}}
                        </div>
                        
                        <div class="text-center py-3 text-muted small fst-italic d-none" id="emptyRecipeMsg">
                            Belum ada bahan yang ditambahkan.
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

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const container = document.getElementById('materialsContainer');
        const addBtn = document.getElementById('addMaterialBtn');
        let materialIndex = 0;

        // Data Inventaris dari Controller
        const inventories = @json($inventories);

        function addRow() {
            const rowId = `material-row-${materialIndex}`;
            
            let optionsHtml = '<option value="" selected disabled>Pilih Bahan...</option>';
            inventories.forEach(item => {
                optionsHtml += `<option value="${item.id}">${item.name} (Stok: ${item.stock} ${item.unit})</option>`;
            });

            const html = `
                <div class="row g-2 mb-2 align-items-center animate__animated animate__fadeIn" id="${rowId}">
                    <div class="col-6">
                        <select name="materials[${materialIndex}][inventory_id]" class="form-select border-0 shadow-sm" required>
                            ${optionsHtml}
                        </select>
                    </div>
                    <div class="col-4">
                        <div class="input-group">
                            <input type="number" name="materials[${materialIndex}][quantity]" class="form-control border-0 shadow-sm" placeholder="Jml" step="0.01" min="0" required>
                            <span class="input-group-text border-0 bg-white shadow-sm small text-muted">Unit</span>
                        </div>
                    </div>
                    <div class="col-2">
                        <button type="button" class="btn btn-light-danger text-danger rounded-circle shadow-sm box-center" style="width: 38px; height: 38px;" onclick="document.getElementById('${rowId}').remove()">
                            <i class="bi bi-trash"></i>
                        </button>
                    </div>
                </div>
            `;
            
            container.insertAdjacentHTML('beforeend', html);
            materialIndex++;
        }

        addBtn.addEventListener('click', addRow);
        
        // Tambah 1 baris kosong pertama kali (opsional, tapi lebih baik kosong dulu biar bersih)
        // addRow(); 
    });
</script>

<style>
    .hover-top:hover { transform: translateY(-3px); }
</style>
@endsection