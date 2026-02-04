@extends(Auth::user()->role == 'admin' || Auth::user()->role == 'owner' || Auth::user()->role == 'staff' ? 'layouts.admin' : (Auth::user()->role == 'driver' ? 'layouts.driver' : 'layouts.customer'))

@section('title', 'Profil Saya')

@section('content')

{{-- Mapbox/Leaflet CSS & JS --}}
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>

<div class="row justify-content-center">
    <div class="col-lg-10">
        
        {{-- Header --}}
        <div class="d-flex align-items-center justify-content-between mb-4">
            <div>
                <h3 class="fw-heading mb-1">Pengaturan Profil</h3>
                <p class="text-muted small mb-0">Kelola informasi akun dan alamat pengiriman Anda.</p>
            </div>
            <a href="{{ Auth::user()->role == 'customer' ? route('customer.dashboard') : (Auth::user()->role == 'driver' ? route('driver.tasks') : route('dashboard')) }}" class="btn btn-white border shadow-sm rounded-pill px-4 fw-bold hover-up text-dark">
                <i class="bi bi-arrow-left me-2"></i> Kembali
            </a>
        </div>

        @if(session('success'))
            <div class="alert alert-success border-0 shadow-sm rounded-4 mb-4 d-flex align-items-center gap-3">
                <div class="d-flex align-items-center justify-content-center bg-success text-white rounded-circle flex-shrink-0" style="width: 38px; height: 38px;">
                    <i class="bi bi-check-lg d-flex align-items-center justify-content-center" style="font-size: 1.3rem;"></i>
                </div>
                <div>
                    <strong class="d-block">Berhasil!</strong> 
                    <span class="small">{{ session('success') }}</span>
                </div>
            </div>
        @endif

        @if($errors->any())
            <div class="alert alert-danger border-0 shadow-sm rounded-4 mb-4 d-flex align-items-start gap-3">
                <div class="d-flex align-items-center justify-content-center bg-danger text-white rounded-circle flex-shrink-0 mt-1" style="width: 38px; height: 38px;">
                    <i class="bi bi-exclamation-triangle-fill d-flex align-items-center justify-content-center" style="font-size: 1rem;"></i>
                </div>
                <div>
                    <strong class="d-block mb-1">Periksa Kembali Inputan Anda:</strong>
                    <ul class="mb-0 small ps-0" style="list-style: none;">
                        @foreach ($errors->all() as $error)
                            <li class="d-flex align-items-start gap-2 mb-1">
                                <i class="bi bi-dot d-flex align-items-center" style="height: 20px;"></i>
                                <span>{{ $error }}</span>
                            </li>
                        @endforeach
                    </ul>
                </div>
            </div>
        @endif

        <form action="{{ Auth::user()->role == 'customer' ? route('customer.profile.update') : (Auth::user()->role == 'driver' ? route('driver.profile.update') : route('profile.update')) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            
            <div class="row g-4 justify-content-center">
                
                {{-- KIRI: FOTO & LOGIN INFO (Selalu Muncul) --}}
                <div class="col-md-5">
                    <div class="card border-0 shadow-sm rounded-4 overflow-hidden h-100">
                        <div class="card-body text-center p-4">
                            
                            {{-- Avatar Upload --}}
                            <div class="position-relative d-inline-block mb-3">
                                <div class="rounded-circle overflow-hidden border border-3 border-white shadow-sm" style="width: 120px; height: 120px;">
                                    @if(Auth::user()->avatar)
                                        <img src="{{ asset('storage/' . Auth::user()->avatar) }}" class="w-100 h-100 object-fit-cover" id="avatarPreview">
                                    @else
                                        <div class="w-100 h-100 bg-light d-flex align-items-center justify-content-center text-primary fw-bold fs-1" id="avatarPlaceholder">
                                            {{ substr(Auth::user()->name, 0, 1) }}
                                        </div>
                                        <img src="" class="w-100 h-100 object-fit-cover d-none" id="avatarPreviewImg">
                                    @endif
                                </div>
                                <label for="avatarInput" class="position-absolute bottom-0 end-0 bg-primary text-white rounded-circle box-center shadow-sm cursor-pointer hover-scale" style="width: 35px; height: 35px;">
                                    <i class="bi bi-camera-fill small"></i>
                                </label>
                                <input type="file" name="avatar" id="avatarInput" class="d-none" accept="image/*" onchange="previewAvatar(this)">
                            </div>

                            <h5 class="fw-bold mb-1">{{ Auth::user()->name }}</h5>
                            <span class="badge bg-primary bg-opacity-10 text-primary rounded-pill px-3">{{ ucfirst(Auth::user()->role) }}</span>

                            <hr class="my-4 border-light">

                            {{-- Akun Login --}}
                            <div class="text-start">
                                <div class="mb-3">
                                    <label class="small fw-bold text-muted mb-1">Nama Lengkap</label>
                                    <input type="text" name="name" class="form-control bg-light border-0" value="{{ Auth::user()->name }}" required>
                                </div>
                                <div class="mb-3">
                                    <label class="small fw-bold text-muted mb-1">Email Login</label>
                                    <input type="email" name="email" class="form-control bg-light border-0" value="{{ Auth::user()->email }}" required>
                                </div>
                                <div class="mb-3">
                                    <label class="small fw-bold text-muted mb-1">Password Baru (Opsional)</label>
                                    <input type="password" name="password" class="form-control bg-light border-0" placeholder="******" autocomplete="new-password">
                                </div>
                                <div class="mb-3">
                                    <label class="small fw-bold text-muted mb-1">Konfirmasi Password</label>
                                    <input type="password" name="password_confirmation" class="form-control bg-light border-0" placeholder="******" autocomplete="new-password">
                                </div>
                                
                                {{-- Tombol Simpan KHUSUS NON-CUSTOMER --}}
                                @if(Auth::user()->role != 'customer')
                                    <button type="submit" class="btn btn-primary w-100 rounded-pill mt-3 fw-bold shadow-sm">
                                        <i class="bi bi-save2-fill me-2"></i> Simpan Profil
                                    </button>
                                @endif
                            </div>

                        </div>
                    </div>
                </div>

                {{-- KANAN: KONTAK & ALAMAT (HANYA CUSTOMER) --}}
                @if(Auth::user()->role == 'customer')
                <div class="col-md-7">
                    <div class="card border-0 shadow-sm rounded-4 overflow-hidden h-100">
                        <div class="card-header bg-white border-bottom border-light py-3 px-4">
                            <h6 class="fw-bold mb-0 text-primary"><i class="bi bi-geo-alt-fill me-2"></i>Alamat & Kontak Pengiriman</h6>
                        </div>
                        <div class="card-body p-4">
                            @php
                                $customer = \App\Models\Customer::where('user_id', Auth::id())->first();
                            @endphp

                            <div class="mb-3">
                                <label class="small fw-bold text-muted mb-1">Nomor WhatsApp (Aktif)</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light border-0 text-muted">+62</span>
                                    <input type="number" name="phone" class="form-control bg-light border-0" value="{{ $customer->phone ?? Auth::user()->phone ?? '' }}" placeholder="8123456789" required>
                                </div>
                                <div class="form-text small">Nomor ini akan digunakan kurir untuk menghubungi Anda.</div>
                            </div>

                            <div class="mb-3">
                                <label class="small fw-bold text-muted mb-1">Alamat Lengkap</label>
                                <textarea name="address" class="form-control bg-light border-0" rows="3" placeholder="Nama Jalan, No. Rumah, RT/RW, Patokan..." required>{{ $customer->address ?? '' }}</textarea>
                            </div>

                            {{-- PETA LOKASI --}}
                            <div class="mb-3">
                                <div class="d-flex justify-content-between align-items-center mb-2">
                                    <label class="small fw-bold text-muted mb-0">Titik Lokasi (Maps)</label>
                                    <span class="badge bg-light text-muted border" id="distanceBadge">Jarak: - KM</span>
                                </div>
                                <div id="map" class="rounded-3 border" style="height: 300px;"></div>
                                <input type="hidden" name="latitude" id="latitude" value="{{ $customer->latitude ?? '-6.200000' }}">
                                <input type="hidden" name="longitude" id="longitude" value="{{ $customer->longitude ?? '106.816666' }}">
                                <div class="d-flex justify-content-between align-items-center mt-2">
                                    <small class="text-muted" id="mapHint"><i class="bi bi-info-circle me-1"></i> Geser pin untuk menyesuaikan lokasi.</small>
                                    <button type="button" class="btn btn-sm btn-outline-primary rounded-pill" onclick="getLocation()">
                                        <i class="bi bi-crosshair me-1"></i> Lokasi Saya
                                    </button>
                                </div>
                            </div>

                        </div>
                        <div class="card-footer bg-white border-top border-light p-4 text-end">
                            <button type="submit" id="btnSubmitProfile" class="btn btn-primary rounded-pill px-5 fw-bold shadow-lg hover-up">
                                <i class="bi bi-save2-fill me-2"></i> Simpan Perubahan
                            </button>
                        </div>
                    </div>
                </div>
                @endif

            </div>
        </form>
    </div>
</div>

<script>
    // 1. Preview Avatar
    function previewAvatar(input) {
        if (input.files && input.files[0]) {
            var reader = new FileReader();
            reader.onload = function(e) {
                document.getElementById('avatarPlaceholder')?.classList.add('d-none');
                var img = document.getElementById('avatarPreview');
                if(!img) {
                    img = document.getElementById('avatarPreviewImg');
                    img.classList.remove('d-none');
                }
                img.src = e.target.result;
            }
            reader.readAsDataURL(input.files[0]);
        }
    }

    // 2. Map Logic & Radius Protection
    @php
        $setting = \App\Models\Setting::first();
        $shopLat = $setting->latitude ?? -6.200000;
        $shopLng = $setting->longitude ?? 106.816666;
    @endphp

    var defaultLat = document.getElementById('latitude').value || {{ $shopLat }};
    var defaultLng = document.getElementById('longitude').value || {{ $shopLng }};
    var shopLat = {{ $shopLat }};
    var shopLng = {{ $shopLng }};
    var maxRange = 10; // KM

    var map = L.map('map').setView([defaultLat, defaultLng], 13);

    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '© OpenStreetMap contributors'
    }).addTo(map);

    // Marker Toko (Fixed)
    var shopIcon = L.divIcon({
        className: 'custom-shop-icon',
        html: '<div style="background-color: #435ebe; color: white; width: 30px; height: 30px; display: flex; align-items: center; justify-content: center; border-radius: 50%; border: 3px solid white; box-shadow: 0 4px 8px rgba(0,0,0,0.2);"><i class="bi bi-shop"></i></div>',
        iconSize: [30, 30],
        iconAnchor: [15, 15]
    });
    L.marker([shopLat, shopLng], {icon: shopIcon}).addTo(map).bindPopup("Lokasi Laundry");

    // Area Coverage Circle
    var coverageCircle = L.circle([shopLat, shopLng], {
        color: '#435ebe',
        fillColor: '#435ebe',
        fillOpacity: 0.1,
        radius: maxRange * 1000 // meter
    }).addTo(map);

    // Marker User (Draggable)
    var userMarker = L.marker([defaultLat, defaultLng], {
        draggable: true
    }).addTo(map);

    function checkDistance(lat, lng) {
        var from = L.latLng(shopLat, shopLng);
        var to = L.latLng(lat, lng);
        var distKm = (from.distanceTo(to) / 1000).toFixed(2);
        
        var badge = document.getElementById('distanceBadge');
        var btn = document.getElementById('btnSubmitProfile');
        var hint = document.getElementById('mapHint');

        badge.innerText = "Jarak: " + distKm + " KM";

        if(distKm > maxRange) {
            // Out of Range
            badge.className = "badge bg-danger text-white border border-danger shadow-sm animate__animated animate__pulse";
            coverageCircle.setStyle({ color: '#ef4444', fillColor: '#ef4444' });
            btn.disabled = true;
            btn.innerHTML = "<i class='bi bi-x-circle me-2'></i> Lokasi Terlalu Jauh";
            btn.classList.add('btn-danger');
            btn.classList.remove('btn-primary');
            hint.innerHTML = "<span class='text-danger fw-bold'><i class='bi bi-exclamation-triangle-fill'></i> Maaf, lokasi ini di luar jangkauan layanan kami (" + maxRange + " KM).</span>";
        } else {
            // In Range
            badge.className = "badge bg-success text-white border border-success shadow-sm";
            coverageCircle.setStyle({ color: '#435ebe', fillColor: '#435ebe' });
            btn.disabled = false;
            btn.innerHTML = "<i class='bi bi-save2-fill me-2'></i> Simpan Perubahan";
            btn.classList.remove('btn-danger');
            btn.classList.add('btn-primary');
            hint.innerHTML = "<i class='bi bi-info-circle me-1'></i> Geser pin untuk menyesuaikan lokasi.";
        }

        document.getElementById('latitude').value = lat;
        document.getElementById('longitude').value = lng;
    }

    // Init Check
    checkDistance(defaultLat, defaultLng);

    // Event Listeners
    userMarker.on('drag', function(e) {
        var pos = userMarker.getLatLng();
        checkDistance(pos.lat, pos.lng);
    });

    userMarker.on('dragend', function(e) {
        var pos = userMarker.getLatLng();
        checkDistance(pos.lat, pos.lng);
        map.panTo(pos);
    });

    map.on('click', function(e) {
        userMarker.setLatLng(e.latlng);
        checkDistance(e.latlng.lat, e.latlng.lng);
    });

    function getLocation() {
        if (navigator.geolocation) {
            navigator.geolocation.getCurrentPosition(function(position) {
                var lat = position.coords.latitude;
                var lng = position.coords.longitude;
                map.flyTo([lat, lng], 15);
                userMarker.setLatLng([lat, lng]);
                checkDistance(lat, lng);
            }, function(error) {
                alert("Gagal mendeteksi lokasi.");
            });
        } else {
            alert("Browser tidak support GPS.");
        }
    }
</script>

<style>
    .hover-scale:hover { transform: scale(1.1); transition: 0.2s; }
    .hover-up:hover { transform: translateY(-3px); transition: 0.3s; }
</style>

@endsection