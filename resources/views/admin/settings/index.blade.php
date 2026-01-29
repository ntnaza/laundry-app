@extends('layouts.admin')

@section('title', 'Pengaturan Toko')
@section('page-title', 'Identitas Laundry')

@section('content')
{{-- Load Leaflet CSS & JS --}}
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin=""/>
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>

<div class="row g-4">
    <div class="col-lg-7">
        <div class="card border-0 shadow-soft rounded-4 overflow-hidden">
            <div class="card-header bg-white border-bottom border-light pt-4 px-4 pb-3">
                <h5 class="fw-heading mb-1">Update Informasi Toko</h5>
                <p class="text-muted small mb-0">Identitas ini akan tampil di seluruh sistem.</p>
            </div>
            <div class="card-body p-4">
                @if(session('success')) 
                    <div class="alert alert-success border-0 shadow-sm rounded-4 mb-4 d-flex align-items-center gap-2">
                        <i class="bi bi-check-circle-fill fs-5"></i> {{ session('success') }}
                    </div> 
                @endif

                <form action="{{ route('settings.update') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    
                    {{-- method form tetap POST sesuai route --}}

                    {{-- Nama Laundry --}}
                    <div class="mb-4">
                        <label class="form-label small fw-bold text-muted text-uppercase ls-1">Nama Laundry</label>
                        <div class="input-group">
                            <span class="input-group-text bg-light border-light shadow-sm text-muted ps-3"><i class="bi bi-shop"></i></span>
                            <input type="text" name="shop_name" class="form-control border-light shadow-sm bg-white" value="{{ $setting->shop_name }}" placeholder="Cth: LaundryKuy Premium" required>
                        </div>
                    </div>

                    {{-- Nomor WhatsApp --}}
                    <div class="mb-4">
                        <label class="form-label small fw-bold text-muted text-uppercase ls-1">WhatsApp Owner</label>
                        <div class="input-group">
                            <span class="input-group-text bg-light border-light shadow-sm text-muted ps-3"><i class="bi bi-whatsapp"></i></span>
                            <input type="number" name="phone" class="form-control border-light shadow-sm bg-white" value="{{ $setting->phone }}" placeholder="628..." required>
                        </div>
                    </div>

                    {{-- Jam Operasional --}}
                    <div class="mb-4">
                        <label class="form-label small fw-bold text-muted text-uppercase ls-1">Jam Operasional</label>
                        <div class="input-group">
                            <span class="input-group-text bg-light border-light shadow-sm text-muted ps-3"><i class="bi bi-clock-history"></i></span>
                            <input type="text" name="operating_hours" class="form-control border-light shadow-sm bg-white" value="{{ $setting->operating_hours }}" placeholder="Cth: 08:00 - 21:00 (Setiap Hari)" required>
                        </div>
                    </div>

                    {{-- Alamat --}}
                    <div class="mb-4">
                        <label class="form-label small fw-bold text-muted text-uppercase ls-1">Alamat Lengkap</label>
                        <div class="input-group">
                            <span class="input-group-text bg-light border-light shadow-sm text-muted ps-3"><i class="bi bi-geo-alt-fill"></i></span>
                            <textarea name="address" class="form-control border-light shadow-sm bg-white" rows="2" placeholder="Alamat lengkap outlet..." required>{{ $setting->address }}</textarea>
                        </div>
                    </div>

                    {{-- SETTING LOKASI TOKO --}}
                    <div class="mb-4">
                        <label class="form-label small fw-bold text-muted text-uppercase ls-1 text-primary">Lokasi Toko & Tarif Delivery</label>
                        <div class="card bg-light border-0 rounded-4 p-3">
                            <div class="row g-3">
                                <div class="col-md-12">
                                    <div id="map" style="height: 300px; border-radius: 12px; z-index: 1;"></div>
                                    <small class="text-muted d-block mt-2"><i class="bi bi-info-circle"></i> Geser pin merah ke lokasi laundry Anda.</small>
                                </div>
                                <div class="col-md-6">
                                    <label class="small text-muted">Latitude</label>
                                    <input type="text" id="lat" name="latitude" class="form-control border-white shadow-sm" value="{{ $setting->latitude }}" readonly>
                                </div>
                                <div class="col-md-6">
                                    <label class="small text-muted">Longitude</label>
                                    <input type="text" id="lng" name="longitude" class="form-control border-white shadow-sm" value="{{ $setting->longitude }}" readonly>
                                </div>
                                <div class="col-md-12">
                                    <label class="small fw-bold text-dark">Tarif Delivery per KM (Rp)</label>
                                    <div class="input-group">
                                        <span class="input-group-text bg-white border-white shadow-sm">Rp</span>
                                        <input type="number" name="delivery_rate_per_km" class="form-control border-white shadow-sm" value="{{ $setting->delivery_rate_per_km ?? 2000 }}" placeholder="2000">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Logo --}}
                    <div class="mb-4">
                        <label class="form-label small fw-bold text-muted text-uppercase ls-1">Logo Toko</label>
                        <div class="d-flex align-items-center gap-3">
                            @if($setting->logo)
                                <img src="{{ asset('storage/'.$setting->logo) }}" width="60" class="rounded-3 shadow-sm border border-white">
                            @endif
                            <input type="file" name="logo" class="form-control border-light bg-white shadow-sm rounded-pill px-4">
                        </div>
                    </div>

                    <div class="pt-3 border-top border-light-subtle">
                        <button type="submit" class="btn btn-primary rounded-pill px-5 py-3 fw-bold shadow-lg hover-top transition-300 w-100">
                            <i class="bi bi-save2-fill me-2"></i> Simpan Pengaturan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    
    <div class="col-lg-5">
        <div class="card border-0 shadow-soft rounded-4 overflow-hidden bg-primary text-white h-100">
            <div class="card-body p-5 position-relative">
                <div class="position-absolute top-0 end-0 p-4 opacity-10">
                    <i class="bi bi-geo-alt-fill" style="font-size: 10rem;"></i>
                </div>
                
                <h4 class="fw-heading text-white mb-3">Sistem Antar Jemput</h4>
                <p class="mb-4 opacity-75">Dengan mengatur lokasi toko, sistem akan otomatis menghitung jarak rumah pelanggan ke laundry Anda.</p>
                
                <div class="bg-white bg-opacity-10 rounded-4 p-4 mb-3">
                    <h6 class="fw-bold text-white mb-2">Rumus Ongkir:</h6>
                    <code class="text-white fs-5">Jarak (KM) x Tarif</code>
                </div>
                <p class="small opacity-75">Pastikan titik lokasi akurat agar perhitungan ongkir tidak merugikan Anda atau pelanggan.</p>
            </div>
        </div>
    </div>
</div>

<script>
    // Inisialisasi Peta
    // Default: Monas (Kalau belum ada setting)
    var curLat = {{ $setting->latitude ?? -6.175392 }};
    var curLng = {{ $setting->longitude ?? 106.827153 }};

    var map = L.map('map').setView([curLat, curLng], 13);

    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '&copy; OpenStreetMap contributors'
    }).addTo(map);

    var marker = L.marker([curLat, curLng], {draggable: true}).addTo(map);

    // Update Input saat marker digeser
    marker.on('dragend', function(e) {
        var position = marker.getLatLng();
        document.getElementById('lat').value = position.lat;
        document.getElementById('lng').value = position.lng;
    });

    // Update Input saat peta diklik
    map.on('click', function(e) {
        marker.setLatLng(e.latlng);
        document.getElementById('lat').value = e.latlng.lat;
        document.getElementById('lng').value = e.latlng.lng;
    });
</script>

<style>
    .hover-top:hover { transform: translateY(-3px); }
</style>
@endsection