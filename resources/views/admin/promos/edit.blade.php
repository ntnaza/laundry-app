@extends('layouts.admin')

@section('title', 'Edit Promo')
@section('page-title', 'Edit Kode Promo')

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-8">
        <div class="card border-0 shadow-soft rounded-4 overflow-hidden">
            <div class="card-header bg-white border-bottom border-light pt-4 px-4 pb-3">
                <h5 class="fw-heading mb-1">Edit Voucher Diskon</h5>
                <p class="text-muted small mb-0">Perbarui ketentuan kode promo.</p>
            </div>
            
            <div class="card-body p-4">
                <form action="{{ route('promos.update', $promo->id) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    
                    {{-- KODE PROMO --}}
                    <div class="mb-4">
                        <label class="form-label small fw-bold text-muted text-uppercase ls-1">Kode Promo</label>
                        <div class="input-group">
                            <span class="input-group-text bg-light border-light shadow-sm text-muted ps-3"><i class="bi bi-ticket-perforated-fill"></i></span>
                            <input type="text" name="code" class="form-control border-light shadow-sm bg-white font-monospace text-uppercase fw-bold" value="{{ $promo->code }}" required>
                        </div>
                    </div>

                    {{-- GAMBAR PROMO --}}
                    <div class="mb-4">
                        <label class="form-label small fw-bold text-muted text-uppercase ls-1">Gambar Banner</label>
                        @if($promo->image)
                            <div class="mb-2">
                                <img src="{{ asset('storage/' . $promo->image) }}" alt="Banner Promo" class="img-fluid rounded shadow-sm" style="max-height: 200px;">
                            </div>
                        @endif
                        <div class="input-group">
                            <span class="input-group-text bg-light border-light shadow-sm text-muted ps-3"><i class="bi bi-image"></i></span>
                            <input type="file" name="image" class="form-control border-light shadow-sm bg-white" accept="image/*">
                        </div>
                        <div class="form-text small">Biarkan kosong jika tidak ingin mengubah gambar.</div>
                    </div>

                    <div class="row g-4 mb-4">
                        {{-- TIPE --}}
                        <div class="col-md-6">
                            <label class="form-label small fw-bold text-muted text-uppercase ls-1">Jenis Potongan</label>
                            <select name="type" class="form-select border-light shadow-sm bg-white" id="promoType">
                                <option value="fixed" {{ $promo->type == 'fixed' ? 'selected' : '' }}>Nominal Tetap (Rp)</option>
                                <option value="percentage" {{ $promo->type == 'percentage' ? 'selected' : '' }}>Persentase (%)</option>
                            </select>
                        </div>
                        
                        {{-- NILAI --}}
                        <div class="col-md-6">
                            <label class="form-label small fw-bold text-muted text-uppercase ls-1">Nilai Potongan</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light border-light shadow-sm text-muted fw-bold {{ $promo->type == 'percentage' ? 'd-none' : '' }}" id="valuePrefix">Rp</span>
                                <input type="number" name="value" class="form-control border-light shadow-sm bg-white" value="{{ $promo->value }}" required>
                                <span class="input-group-text bg-light border-light shadow-sm text-muted {{ $promo->type == 'fixed' ? 'd-none' : '' }}" id="valueSuffix">%</span>
                            </div>
                        </div>
                    </div>

                    {{-- SYARAT TAMBAHAN --}}
                    <div class="card bg-light border-0 rounded-4 p-3 mb-4">
                        <h6 class="fw-bold text-dark small mb-3">Syarat & Ketentuan</h6>
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label small text-muted">Min. Belanja (Rp)</label>
                                <input type="number" name="min_spend" class="form-control border-0 shadow-sm" value="{{ $promo->min_spend }}">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label small text-muted">Max. Diskon (Rp)</label>
                                <input type="number" name="max_discount" class="form-control border-0 shadow-sm" value="{{ $promo->max_discount }}" id="maxDiscount" {{ $promo->type == 'fixed' ? 'disabled' : '' }}>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label small text-muted">Mulai Berlaku</label>
                                <input type="date" name="start_date" class="form-control border-0 shadow-sm" value="{{ $promo->start_date }}">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label small text-muted">Berakhir Pada</label>
                                <input type="date" name="end_date" class="form-control border-0 shadow-sm" value="{{ $promo->end_date }}">
                            </div>
                        </div>
                    </div>

                    <div class="form-check form-switch mb-4">
                        <input class="form-check-input" type="checkbox" id="isActive" name="is_active" {{ $promo->is_active ? 'checked' : '' }}>
                        <label class="form-check-label fw-bold small text-muted" for="isActive">Promo Aktif</label>
                    </div>

                    <div class="d-flex justify-content-end gap-3 pt-2">
                        <a href="{{ route('promos.index') }}" class="btn btn-light rounded-pill px-4 fw-bold text-muted">Batal</a>
                        <button type="submit" class="btn btn-primary rounded-pill px-5 fw-bold shadow-lg hover-top transition-300">
                            <i class="bi bi-save2-fill me-2"></i> Update Promo
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    document.getElementById('promoType').addEventListener('change', function() {
        const type = this.value;
        const prefix = document.getElementById('valuePrefix');
        const suffix = document.getElementById('valueSuffix');
        const maxDisc = document.getElementById('maxDiscount');

        if (type === 'percentage') {
            prefix.classList.add('d-none');
            suffix.classList.remove('d-none');
            maxDisc.removeAttribute('disabled');
        } else {
            prefix.classList.remove('d-none');
            suffix.classList.add('d-none');
            maxDisc.setAttribute('disabled', true);
            maxDisc.value = '';
        }
    });
</script>

<style>
    .hover-top:hover { transform: translateY(-3px); }
</style>
@endsection