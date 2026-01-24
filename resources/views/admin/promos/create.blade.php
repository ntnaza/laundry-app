@extends('layouts.admin')

@section('title', 'Buat Promo')
@section('page-title', 'Buat Kode Promo Baru')

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-8">
        <div class="card border-0 shadow-soft rounded-4 overflow-hidden">
            <div class="card-header bg-white border-bottom border-light pt-4 px-4 pb-3">
                <h5 class="fw-heading mb-1">Form Voucher Diskon</h5>
                <p class="text-muted small mb-0">Atur kode promo dan ketentuannya.</p>
            </div>
            
            <div class="card-body p-4">
                @if ($errors->any())
                    <div class="alert alert-danger border-0 shadow-sm rounded-3 mb-4">
                        <ul class="mb-0 small">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form action="{{ route('promos.store') }}" method="POST">
                    @csrf
                    
                    {{-- KODE PROMO --}}
                    <div class="mb-4">
                        <label class="form-label small fw-bold text-muted text-uppercase ls-1">Kode Promo (Unik)</label>
                        <div class="input-group">
                            <span class="input-group-text bg-light border-light shadow-sm text-muted ps-3"><i class="bi bi-ticket-perforated-fill"></i></span>
                            <input type="text" name="code" class="form-control border-light shadow-sm bg-white font-monospace text-uppercase fw-bold" placeholder="Cth: LEBARAN2026" required>
                        </div>
                        <div class="form-text small">Gunakan huruf kapital dan angka tanpa spasi.</div>
                    </div>

                    <div class="row g-4 mb-4">
                        {{-- TIPE --}}
                        <div class="col-md-6">
                            <label class="form-label small fw-bold text-muted text-uppercase ls-1">Jenis Potongan</label>
                            <select name="type" class="form-select border-light shadow-sm bg-white" id="promoType">
                                <option value="fixed">Nominal Tetap (Rp)</option>
                                <option value="percentage">Persentase (%)</option>
                            </select>
                        </div>
                        
                        {{-- NILAI --}}
                        <div class="col-md-6">
                            <label class="form-label small fw-bold text-muted text-uppercase ls-1">Nilai Potongan</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light border-light shadow-sm text-muted fw-bold" id="valuePrefix">Rp</span>
                                <input type="number" name="value" class="form-control border-light shadow-sm bg-white" placeholder="0" required>
                                <span class="input-group-text bg-light border-light shadow-sm text-muted d-none" id="valueSuffix">%</span>
                            </div>
                        </div>
                    </div>

                    {{-- SYARAT TAMBAHAN --}}
                    <div class="card bg-light border-0 rounded-4 p-3 mb-4">
                        <h6 class="fw-bold text-dark small mb-3">Syarat & Ketentuan (Opsional)</h6>
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label small text-muted">Min. Belanja (Rp)</label>
                                <input type="number" name="min_spend" class="form-control border-0 shadow-sm" placeholder="0">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label small text-muted">Max. Diskon (Rp)</label>
                                <input type="number" name="max_discount" class="form-control border-0 shadow-sm" placeholder="Khusus tipe %" disabled id="maxDiscount">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label small text-muted">Mulai Berlaku</label>
                                <input type="date" name="start_date" class="form-control border-0 shadow-sm">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label small text-muted">Berakhir Pada</label>
                                <input type="date" name="end_date" class="form-control border-0 shadow-sm">
                            </div>
                        </div>
                    </div>

                    <div class="d-flex justify-content-end gap-3 pt-2">
                        <a href="{{ route('promos.index') }}" class="btn btn-light rounded-pill px-4 fw-bold text-muted">Batal</a>
                        <button type="submit" class="btn btn-primary rounded-pill px-5 fw-bold shadow-lg hover-top transition-300">
                            <i class="bi bi-save2-fill me-2"></i> Simpan Promo
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