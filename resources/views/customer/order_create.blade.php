@extends('layouts.customer')

@section('title', 'Buat Pesanan Baru')

@section('content')

{{-- 1. Panggil Library Leaflet --}}
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>

<style>
    /* --- CSS KHUSUS HALAMAN INI (JURUS ANTI-DENGDEK) --- */

    /* 1. Fix Tombol Kembali (Lingkaran Sempurna) */
    .btn-back-custom {
        width: 45px; 
        height: 45px;
        padding: 0; /* PENTING: Hapus padding bawaan */
        display: flex;
        align-items: center;
        justify-content: center;
        background: white;
        border: 1px solid #f1f5f9;
        border-radius: 50%;
        box-shadow: 0 4px 6px rgba(0,0,0,0.02);
        transition: 0.3s;
        color: var(--dark);
    }
    .btn-back-custom:hover { background: #f8fafc; color: var(--primary); }
    .btn-back-custom i { 
        line-height: 1; 
        display: flex; 
        font-size: 1.2rem; 
    }

    /* 2. Fix Kartu Pilihan (Radio Button) */
    .selector-item { position: relative; height: 100%; }
    .selector-item input[type="radio"] { position: absolute; opacity: 0; cursor: pointer; height: 0; width: 0; }
    
    .selector-card {
        border: 2px solid #f1f5f9;
        border-radius: 20px;
        padding: 25px 15px; /* Padding atas bawah lebih lega */
        text-align: center;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        cursor: pointer;
        background: white;
        height: 100%;
        
        /* FLEXBOX CENTER MUTLAK */
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
    }

    /* State: Checked */
    .selector-item input[type="radio"]:checked + .selector-card {
        border-color: var(--primary);
        background-color: #eff6ff;
        color: var(--primary);
        box-shadow: 0 10px 20px rgba(37, 99, 235, 0.1);
        transform: translateY(-3px);
    }

    /* Fix Ikon di dalam Kartu */
    .selector-card i {
        font-size: 2.5rem;
        margin-bottom: 15px;
        display: flex;      /* Pakai flex biar kotaknya pas */
        line-height: 1;     /* Reset line height */
        align-items: center;
        justify-content: center;
        height: 50px;       /* Tinggi fix buat area ikon */
    }
    
    /* 3. Input Form Style Premium */
    .form-control-soft {
        background-color: #f8fafc;
        border: 1px solid #e2e8f0;
        border-radius: 12px;
        padding: 12px 15px;
        font-size: 0.95rem;
        transition: 0.3s;
    }
    .form-control-soft:focus {
        background-color: #fff;
        border-color: var(--primary);
        box-shadow: 0 0 0 4px rgba(37, 99, 235, 0.1);
    }

    /* 4. Fix Tombol Kirim */
    .btn-submit-custom {
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 10px; /* Jarak antar ikon dan teks */
        height: 55px; /* Tinggi fix sama kayak input */
        padding: 0; /* Reset padding, biar flex yang atur */
    }
    .btn-submit-custom i {
        line-height: 1;
        display: flex;
        margin-top: 2px; /* Visual correction dikit */
    }
</style>

<div class="row justify-content-center">
    <div class="col-md-8 col-lg-6">
        
        {{-- Header --}}
        <div class="d-flex align-items-center gap-3 mb-4 mt-3">
            {{-- TOMBOL KEMBALI FIX --}}
            <a href="{{ route('customer.dashboard') }}" class="btn-back-custom">
                <i class="bi bi-arrow-left"></i>
            </a>
            <div>
                <h4 class="fw-heading mb-0">Buat Pesanan</h4>
                <p class="text-muted small mb-0">Isi detail lokasi penjemputan.</p>
            </div>
        </div>

        @if ($errors->any())
            <div class="alert alert-danger border-0 rounded-4 mb-4 shadow-sm">
                <ul class="mb-0 small">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('customer.order.store') }}" method="POST">
            @csrf

            {{-- 1. BAGIAN PETA --}}
            <div class="card border-0 shadow-sm rounded-4 mb-4 overflow-hidden">
                <div class="card-body p-4">
                    <h6 class="fw-bold mb-3 d-flex align-items-center gap-2">
                        <i class="bi bi-geo-alt-fill text-danger d-flex" style="line-height:1;"></i> Titik Penjemputan
                    </h6>
                    
                    <div id="map" style="height: 250px; border-radius: 16px; z-index: 1;" class="mb-3 border"></div>
                    
                    {{-- Alert Info --}}
                    <div class="alert alert-light-primary border-0 rounded-3 d-flex align-items-center gap-3 py-2 px-3 mb-0">
                        <i class="bi bi-info-circle-fill text-primary d-flex" style="line-height:1;"></i>
                        <small class="text-primary fw-bold">Geser pin merah ke lokasi rumahmu.</small>
                    </div>

                    {{-- Koordinat Hidden --}}
                    <input type="hidden" name="latitude" id="lat">
                    <input type="hidden" name="longitude" id="lng">
                </div>
            </div>

            {{-- 2. DETAIL ALAMAT & KONTAK --}}
            <div class="card border-0 shadow-sm rounded-4 mb-4">
                <div class="card-body p-4">
                    <h6 class="fw-bold mb-3 d-flex align-items-center gap-2">
                        <i class="bi bi-card-text text-primary d-flex" style="line-height:1;"></i> Detail Alamat
                    </h6>

                    <div class="mb-3">
                        <label class="form-label small text-muted fw-bold">ALAMAT LENGKAP / PATOKAN</label>
                        <textarea name="pickup_address" class="form-control form-control-soft" rows="3" placeholder="Contoh: Pagar hitam, sebelah warung madura, blok C no. 5" required></textarea>
                    </div>

                    <div class="mb-0">
                        <label class="form-label small text-muted fw-bold">NOMOR WHATSAPP</label>
                        <div class="input-group">
                            <span class="input-group-text bg-light border-0 fw-bold text-muted rounded-start-3 ps-3">+62</span>
                            <input type="number" name="phone" class="form-control form-control-soft border-start-0 rounded-end-3" placeholder="812xxxx" value="{{ Auth::user()->phone ?? '' }}" required>
                        </div>
                    </div>
                </div>
            </div>
            {{-- 2.5. ITEM LAUNDRY (SHOPPING CART STYLE) --}}
            <div class="card border-0 shadow-sm rounded-4 mb-4">
                <div class="card-body p-4">
                    <h6 class="fw-bold mb-3 d-flex align-items-center gap-2">
                        <i class="bi bi-basket-fill text-success d-flex" style="line-height:1;"></i> Detail Cucian
                    </h6>

                    {{-- Form Tambah Item --}}
                    <div class="bg-light p-3 rounded-3 mb-3 border">
                        <label class="form-label small text-muted fw-bold">TAMBAH LAYANAN</label>
                        <div class="row g-2">
                            <div class="col-12 col-md-7">
                                <select id="serviceSelect" class="form-select form-control-soft">
                                    <option value="" selected disabled>-- Pilih Layanan --</option>
                                    @foreach($services as $service)
                                        <option value="{{ $service->id }}" 
                                                data-name="{{ $service->name }}" 
                                                data-price="{{ $service->price }}" 
                                                data-unit="{{ $service->unit }}"
                                                data-type="{{ $service->type }}">
                                            {{ $service->name }} — Rp {{ number_format($service->price, 0, ',', '.') }} / {{ $service->unit }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-8 col-md-3">
                                <div class="input-group">
                                    <input type="number" id="qtyInput" class="form-control form-control-soft text-center" placeholder="Jml" min="1">
                                    <span class="input-group-text bg-white border-start-0 text-muted small" id="unitLabel">Unit</span>
                                </div>
                            </div>
                            <div class="col-4 col-md-2">
                                <button type="button" class="btn btn-success w-100 fw-bold form-control-soft" onclick="addItem()">
                                    <i class="bi bi-plus-lg"></i>
                                </button>
                            </div>
                        </div>
                        <div class="form-text small text-muted mt-2">
                            <i class="bi bi-info-circle me-1"></i> Untuk Kiloan, masukkan estimasi berat (misal 1 Kg). Nanti akan ditimbang ulang oleh Admin.
                        </div>
                    </div>

                    {{-- Tabel Item Terpilih --}}
                    <div class="table-responsive mb-3 rounded-3 border d-none" id="itemsTableContainer">
                        <table class="table table-borderless table-striped mb-0 small">
                            <thead class="bg-primary text-white">
                                <tr>
                                    <th class="ps-3">Layanan</th>
                                    <th class="text-center">Qty</th>
                                    <th class="text-end pe-3">Subtotal</th>
                                    <th style="width: 10px;"></th>
                                </tr>
                            </thead>
                            <tbody id="itemsList">
                                {{-- Item akan masuk sini via JS --}}
                            </tbody>
                            <tfoot class="border-top">
                                <tr>
                                    <td colspan="2" class="text-end fw-bold pt-3">Estimasi Total:</td>
                                    <td class="text-end fw-bold text-primary pt-3 pe-3 fs-6" id="grandTotalDisplay">Rp 0</td>
                                    <td></td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>

                    {{-- Input Hidden untuk Data Item (Dikirim ke Controller) --}}
                    <div id="hiddenInputsContainer"></div>

                    <div class="mb-3">
                        <label class="form-label small text-muted fw-bold">KODE PROMO (OPSIONAL)</label>
                        <div class="input-group">
                            <span class="input-group-text bg-light border-0 fw-bold text-warning rounded-start-3 ps-3">
                                <i class="bi bi-ticket-perforated-fill"></i>
                            </span>
                            <input type="text" name="promo_code" class="form-control form-control-soft border-start-0 rounded-end-3 text-uppercase fw-bold" placeholder="Punya voucher diskon?">
                        </div>
                    </div>

                    <div class="mb-0">
                        <label class="form-label small text-muted fw-bold">CATATAN KHUSUS</label>
                        <textarea name="note" class="form-control form-control-soft" rows="2" placeholder="Cth: Jangan disetrika, pisahkan baju putih..."></textarea>
                    </div>
                </div>
            </div>
            {{-- 3. JENIS LAYANAN (FIX ALIGNMENT) --}}
            <div class="card border-0 shadow-sm rounded-4 mb-4">
                <div class="card-body p-4">
                    <h6 class="fw-bold mb-3 d-flex align-items-center gap-2">
                        <i class="bi bi-stars text-warning d-flex" style="line-height:1;"></i> Pilih Layanan
                    </h6>
                    
                    <div class="row g-3">
                        {{-- Opsi 1: Jemput Aja --}}
                        <div class="col-6">
                            <div class="selector-item">
                                <input type="radio" name="delivery_type" id="opt1" value="pickup" checked>
                                <label for="opt1" class="selector-card">
                                    {{-- Icon dipaksa center flex --}}
                                    <i class="bi bi-scooter text-muted"></i>
                                    <span class="fw-bold small">Jemput Aja</span>
                                    <span style="font-size: 0.65rem; line-height: 1.2;" class="text-muted mt-2 d-none d-md-block">Kurir jemput, kamu ambil sendiri.</span>
                                </label>
                            </div>
                        </div>
                        
                        {{-- Opsi 2: Antar Jemput --}}
                        <div class="col-6">
                            <div class="selector-item">
                                <input type="radio" name="delivery_type" id="opt2" value="both">
                                <label for="opt2" class="selector-card">
                                    {{-- Icon dipaksa center flex --}}
                                    <i class="bi bi-box-seam-fill text-muted"></i>
                                    <span class="fw-bold small">Antar-Jemput</span>
                                    <span style="font-size: 0.65rem; line-height: 1.2;" class="text-muted mt-2 d-none d-md-block">Terima beres, kurir PP.</span>
                                </label>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- TOMBOL SUBMIT FIX --}}
            <button type="submit" class="btn btn-primary w-100 rounded-pill fw-bold shadow-lg mb-5 hover-scale btn-submit-custom" 
                    style="background: linear-gradient(135deg, var(--primary), var(--primary-dark)); border: none;">
                <i class="bi bi-send-fill fs-5"></i>
                <span>KIRIM PESANAN</span>
            </button>

        </form>
    </div>
</div>

<script>
    // --- 0. LOGIKA KERANJANG ITEM (SHOPPING CART) ---
    let items = [];
    const serviceSelect = document.getElementById('serviceSelect');
    const qtyInput = document.getElementById('qtyInput');
    const unitLabel = document.getElementById('unitLabel');
    const itemsTableContainer = document.getElementById('itemsTableContainer');
    const itemsList = document.getElementById('itemsList');
    const grandTotalDisplay = document.getElementById('grandTotalDisplay');
    const hiddenInputsContainer = document.getElementById('hiddenInputsContainer');

    // Update Label Satuan saat pilih layanan
    serviceSelect.addEventListener('change', function() {
        const option = this.options[this.selectedIndex];
        if (option.dataset.unit) {
            unitLabel.innerText = option.dataset.unit;
        }
    });

    function formatRupiah(num) {
        return 'Rp ' + new Intl.NumberFormat('id-ID').format(num);
    }

    function addItem() {
        const serviceId = serviceSelect.value;
        const option = serviceSelect.options[serviceSelect.selectedIndex];
        const qty = parseFloat(qtyInput.value);

        if (!serviceId) {
            alert('Pilih layanan dulu ya!');
            return;
        }
        if (!qty || qty <= 0) {
            alert('Masukkan jumlah atau estimasi berat!');
            return;
        }

        const name = option.dataset.name;
        const price = parseFloat(option.dataset.price);
        const unit = option.dataset.unit;
        const subtotal = price * qty;

        // Cek apakah item sudah ada, kalau ada update qty
        const existingItem = items.find(i => i.id === serviceId);
        if (existingItem) {
            existingItem.qty += qty;
            existingItem.subtotal = existingItem.qty * price;
        } else {
            items.push({
                id: serviceId,
                name: name,
                price: price,
                qty: qty,
                unit: unit,
                subtotal: subtotal
            });
        }

        renderItems();
        
        // Reset Input
        serviceSelect.value = "";
        qtyInput.value = "";
        unitLabel.innerText = "Unit";
    }

    function removeItem(index) {
        items.splice(index, 1);
        renderItems();
    }

    function renderItems() {
        itemsList.innerHTML = '';
        hiddenInputsContainer.innerHTML = '';
        let grandTotal = 0;

        if (items.length > 0) {
            itemsTableContainer.classList.remove('d-none');
        } else {
            itemsTableContainer.classList.add('d-none');
        }

        items.forEach((item, index) => {
            grandTotal += item.subtotal;

            // 1. Render Baris Tabel
            const row = `
                <tr>
                    <td class="ps-3 align-middle">
                        <div class="fw-bold text-dark">${item.name}</div>
                        <div class="text-muted" style="font-size: 0.7rem;">@ ${formatRupiah(item.price)}</div>
                    </td>
                    <td class="text-center align-middle">${item.qty} ${item.unit}</td>
                    <td class="text-end pe-3 align-middle fw-bold text-dark">${formatRupiah(item.subtotal)}</td>
                    <td class="align-middle text-end pe-2">
                        <button type="button" class="btn btn-link text-danger p-0" onclick="removeItem(${index})">
                            <i class="bi bi-trash"></i>
                        </button>
                    </td>
                </tr>
            `;
            itemsList.insertAdjacentHTML('beforeend', row);

            // 2. Buat Input Hidden (Array) untuk dikirim ke Controller
            // name="items[0][service_id]", name="items[0][qty]"
            hiddenInputsContainer.insertAdjacentHTML('beforeend', `
                <input type="hidden" name="items[${index}][service_id]" value="${item.id}">
                <input type="hidden" name="items[${index}][qty]" value="${item.qty}">
            `);
        });

        grandTotalDisplay.innerText = formatRupiah(grandTotal);
    }


    // --- 1. LOGIKA PETA (Leaflet JS) ---
    // Lokasi Default (Misal: Bandung Kota) - Bisa disesuaikan dengan titik tengah area layanan laundry
    var defaultLat = -6.917464; 
    var defaultLng = 107.619123;

    var map = L.map('map').setView([defaultLat, defaultLng], 15);

    L.tileLayer('https://{s}.basemaps.cartocdn.com/rastertiles/voyager/{z}/{x}/{y}{r}.png', {
        attribution: '&copy; OpenStreetMap contributors',
        maxZoom: 20
    }).addTo(map);

    var redIcon = new L.Icon({
        iconUrl: 'https://raw.githubusercontent.com/pointhi/leaflet-color-markers/master/img/marker-icon-2x-red.png',
        shadowUrl: 'https://cdnjs.cloudflare.com/ajax/libs/leaflet/0.7.7/images/marker-shadow.png',
        iconSize: [25, 41],
        iconAnchor: [12, 41],
        popupAnchor: [1, -34],
        shadowSize: [41, 41]
    });

    var marker = L.marker([defaultLat, defaultLng], {
        draggable: true, 
        icon: redIcon
    }).addTo(map);

    // Fungsi update input hidden
    function updateInput(lat, lng) {
        document.getElementById('lat').value = lat;
        document.getElementById('lng').value = lng;
    }

    // Set nilai awal
    updateInput(defaultLat, defaultLng);

    // Event saat marker digeser
    marker.on('dragend', function (e) {
        var position = marker.getLatLng();
        updateInput(position.lat, position.lng);
    });

    // Event saat peta diklik
    map.on('click', function(e) {
        var lat = e.latlng.lat;
        var lng = e.latlng.lng;
        marker.setLatLng([lat, lng]);
        updateInput(lat, lng);
    });

    // Cek Lokasi User (Geolocation)
    if (navigator.geolocation) {
        navigator.geolocation.getCurrentPosition(function(position) {
            var userLat = position.coords.latitude;
            var userLng = position.coords.longitude;
            map.setView([userLat, userLng], 17);
            marker.setLatLng([userLat, userLng]);
            updateInput(userLat, userLng);
        });
    }

    // --- 2. FITUR KEAMANAN & UX (BARU) ---
    document.addEventListener("DOMContentLoaded", function() {
        
        // A. Auto-Format Nomor HP (Hapus angka 0 di depan)
        const phoneInput = document.querySelector('input[name="phone"]');
        phoneInput.addEventListener('input', function(e) {
            let value = e.target.value;
            // Jika karakter pertama adalah '0', hapus
            if (value.startsWith('0')) {
                e.target.value = value.substring(1);
            }
        });

        // B. Cegah Double Submit
        const form = document.querySelector('form');
        const btnSubmit = document.querySelector('.btn-submit-custom');
        const btnText = btnSubmit.querySelector('span');
        const btnIcon = btnSubmit.querySelector('i');

        form.addEventListener('submit', function() {
            // Matikan tombol
            btnSubmit.disabled = true;
            btnSubmit.style.opacity = '0.7';
            btnSubmit.style.cursor = 'not-allowed';
            
            // Ubah Teks jadi Loading
            btnText.innerText = 'MEMPROSES...';
            
            // Ganti ikon jadi spinner loading
            btnIcon.className = 'spinner-border spinner-border-sm';
        });
    });
</script>

<style>
    .hover-scale:hover { transform: translateY(-3px); box-shadow: 0 10px 25px rgba(37, 99, 235, 0.4); }
</style>

@endsection