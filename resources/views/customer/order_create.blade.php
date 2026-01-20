@extends('layouts.customer')

@section('title', 'Order Baru')

@section('content')

<form action="{{ route('customer.order.store') }}" method="POST">
    @csrf

    <div class="card border-0 shadow-sm mb-3">
        <div class="card-body">
            <h6 class="fw-bold mb-3"><i class="bi bi-person-lines-fill text-primary"></i> Kontak & Lokasi</h6>
            
            <div class="mb-3">
                <label class="form-label text-muted small">Nomor WhatsApp</label>
                <input type="number" name="phone" class="form-control form-control-lg bg-light border-0" placeholder="08xxxx" required>
            </div>

            <div class="mb-3">
                <label class="form-label text-muted small">Alamat Lengkap</label>
                <textarea name="pickup_address" class="form-control bg-light border-0" rows="3" placeholder="Nama jalan, nomor rumah, patokan..." required></textarea>
            </div>
        </div>
    </div>

    <div class="card border-0 shadow-sm mb-3">
        <div class="card-body">
            <h6 class="fw-bold mb-3"><i class="bi bi-truck text-primary"></i> Jenis Layanan</h6>
            
            <div class="btn-group w-100" role="group">
                <input type="radio" class="btn-check" name="delivery_type" id="opt1" value="pickup" checked>
                <label class="btn btn-outline-primary py-3" for="opt1">
                    <i class="bi bi-box-seam d-block fs-4"></i> Jemput Aja
                </label>
            
                <input type="radio" class="btn-check" name="delivery_type" id="opt2" value="both">
                <label class="btn btn-outline-primary py-3" for="opt2">
                    <i class="bi bi-bicycle d-block fs-4"></i> Antar-Jemput
                </label>
            </div>
        </div>
    </div>

    <div class="card border-0 shadow-sm mb-4">
        <div class="card-body">
            <h6 class="fw-bold mb-3"><i class="bi bi-calculator text-primary"></i> Estimasi (Opsional)</h6>
            <div class="input-group">
                <input type="number" name="weight" step="0.1" class="form-control form-control-lg border-0 bg-light" placeholder="0">
                <span class="input-group-text border-0 bg-light">Kg</span>
            </div>
            <small class="text-muted fst-italic mt-2 d-block">*Harga final ditentukan setelah ditimbang Admin.</small>
        </div>
    </div>

    <button type="submit" class="btn btn-primary btn-lg w-100 fw-bold rounded-pill shadow">
        KIRIM PESANAN <i class="bi bi-send-fill ms-2"></i>
    </button>
</form>

@endsection