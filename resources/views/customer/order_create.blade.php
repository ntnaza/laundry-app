@extends('layouts.customer')

@section('title', 'Buat Pesanan Baru')

@section('content')

{{-- Midtrans Snap JS --}}
<script src="https://app.sandbox.midtrans.com/snap/snap.js" data-client-key="{{ config('midtrans.client_key') }}"></script>

{{-- Leaflet Library --}}
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>

<style>
    .btn-back-custom { width: 45px; height: 45px; display: flex; align-items: center; justify-content: center; background: white; border: 1px solid #f1f5f9; border-radius: 50%; box-shadow: 0 4px 6px rgba(0,0,0,0.02); color: var(--dark); transition: 0.3s; text-decoration: none; }
    .btn-back-custom:hover { background: #f8fafc; color: var(--primary); }
    
    /* CSS SELECTOR CARD */
    .selector-item { position: relative; height: 100%; width: 100%; }
    .selector-item input[type="radio"] { position: absolute; opacity: 0; cursor: pointer; height: 0; width: 0; }
    .selector-card { border: 2px solid #f1f5f9; border-radius: 16px; padding: 20px 15px; text-align: center; transition: all 0.3s; cursor: pointer; background: white; height: 100%; width: 100%; display: flex; flex-direction: column; align-items: center; justify-content: center; }
    .selector-item input[type="radio"]:checked + .selector-card { border-color: var(--primary); background-color: #eff6ff; color: var(--primary); transform: translateY(-3px); box-shadow: 0 4px 12px rgba(37, 99, 235, 0.15); }
    .selector-card i { font-size: 2rem; margin-bottom: 12px; line-height: 1; display: inline-flex; }
    
    .form-control-soft { background-color: #f8fafc; border: 1px solid #e2e8f0; border-radius: 12px; padding: 12px 15px; }
    .form-control-soft:focus { background-color: #fff; border-color: var(--primary); }
    
    .delivery-info-box { background: linear-gradient(135deg, #e0f2fe, #f0f9ff); border: 1px dashed #3b82f6; border-radius: 12px; padding: 15px; margin-top: 15px; }
    
    /* LOGIKA LOCK FORM */
    .locked-section { filter: grayscale(100%); opacity: 0.5; pointer-events: none; position: relative; user-select: none; cursor: not-allowed; }
    .locked-section::after { content: "🔒 Bayar Ongkir Untuk Membuka"; position: absolute; top: 0; left: 0; width: 100%; height: 100%; display: flex; align-items: center; justify-content: center; background: rgba(255,255,255,0.2); color: #000; font-weight: bold; z-index: 10; border-radius: 16px; }
</style>

<div class="row justify-content-center">
    <div class="col-md-8 col-lg-6">
        
        <div class="d-flex align-items-center gap-3 mb-4 mt-3">
            <a href="{{ route('customer.dashboard') }}" class="btn-back-custom"><i class="bi bi-arrow-left"></i></a>
            <div>
                <h4 class="fw-heading mb-0">Buat Pesanan</h4>
                @if($pendingTransaction)
                    <p class="text-success small mb-0 fw-bold"><i class="bi bi-check-circle-fill"></i> Ongkir Lunas! Silakan pilih item.</p>
                @else
                    <p class="text-muted small mb-0">Lengkapi data lokasi dan bayar ongkir dulu ya.</p>
                @endif
            </div>
        </div>

        {{-- FORM UTAMA --}}
        {{-- Jika Resume, langsung arahkan ke Complete. Jika Baru, arahkan ke Store --}}
        <form action="{{ $pendingTransaction ? route('customer.order.complete', $pendingTransaction->id) : route('customer.order.store') }}" method="POST" id="orderForm">
            @csrf
            
            {{-- ID Transaksi untuk JS --}}
            <input type="hidden" id="transactionId" value="{{ $pendingTransaction->id ?? '' }}">

            {{-- 1. PETA & LOKASI --}}
            {{-- Jika Resume, sembunyikan Peta agar user tidak bingung/ubah lokasi yang sudah dibayar --}}
            @if(!$pendingTransaction)
            <div class="card border-0 shadow-sm rounded-4 mb-4 overflow-hidden">
                <div class="card-body p-4">
                    <h6 class="fw-bold mb-3"><i class="bi bi-geo-alt-fill text-danger me-2"></i>Titik Penjemputan</h6>
                    <div id="map" style="height: 250px; border-radius: 16px; z-index: 1;" class="mb-3 border"></div>
                    
                    <input type="hidden" name="latitude" id="lat">
                    <input type="hidden" name="longitude" id="lng">
                    <input type="hidden" name="distance" id="distanceInput">
                    <input type="hidden" name="delivery_fee" id="deliveryFeeInput">

                    <div class="delivery-info-box" id="deliveryInfoBox">
                        <div class="d-flex justify-content-between mb-1"><span class="small text-muted">Jarak:</span><span class="fw-bold text-dark" id="distanceDisplay">0 KM</span></div>
                        <div class="d-flex justify-content-between mb-3"><span class="small text-primary fw-bold">Biaya Ongkir:</span><span class="fw-bold text-primary fs-5" id="deliveryFeeDisplay">Rp 0</span></div>
                        
                        <div id="distanceWarning" class="alert alert-danger py-2 small fw-bold d-none">
                            <i class="bi bi-exclamation-circle me-1"></i> Jarak terlalu jauh (Max 10KM).
                        </div>

                        <button type="button" class="btn btn-primary w-100 rounded-pill py-3 fw-bold shadow-sm" id="btnPayOngkir">
                            <i class="bi bi-wallet2 me-2"></i> BAYAR ONGKIR SEKARANG
                        </button>
                    </div>
                </div>
            </div>
            @endif

            {{-- 2. ALAMAT & WA (Selalu Open) --}}
            <div class="card border-0 shadow-sm rounded-4 mb-4" id="contactSection">
                <div class="card-body p-4">
                    <div class="mb-3">
                        <label class="form-label small text-muted fw-bold">NOMOR WHATSAPP</label>
                        <div class="input-group">
                            <span class="input-group-text bg-light border-0 fw-bold text-muted ps-3">+62</span>
                            <input type="number" name="phone" class="form-control form-control-soft border-start-0" placeholder="812xxxx" value="{{ $pendingTransaction ? ($pendingTransaction->customer->phone ?? Auth::user()->phone) : (Auth::user()->phone ?? '') }}" required>
                        </div>
                    </div>
                    <div class="mb-0">
                        <label class="form-label small text-muted fw-bold">ALAMAT LENGKAP</label>
                        {{-- Jika Resume, pakai alamat dari Transaksi --}}
                        <textarea name="pickup_address" class="form-control form-control-soft" rows="2" placeholder="Detail: Pagar hitam, Nomor rumah..." required>{{ $pendingTransaction ? $pendingTransaction->pickup_address : '' }}</textarea>
                    </div>
                </div>
            </div>

            {{-- 3. ITEM LAUNDRY (LOCKED kalau belum bayar) --}}
            <div class="card border-0 shadow-sm rounded-4 mb-4 {{ $pendingTransaction ? '' : 'locked-section' }}" id="itemSection">
                <div class="card-body p-4">
                    <h6 class="fw-bold mb-3"><i class="bi bi-basket-fill text-success me-2"></i>Item Laundry</h6>
                    
                    {{-- Form Pilih Item --}}
                    <div class="bg-light p-3 rounded-3 mb-3 border">
                        <div class="row g-2">
                            <div class="col-7">
                                <select id="serviceSelect" class="form-select form-control-soft form-select-sm">
                                    <option value="" selected disabled>Pilih Layanan</option>
                                    @foreach($services as $service)
                                        <option value="{{ $service->id }}" data-name="{{ $service->name }}" data-price="{{ $service->price }}" data-unit="{{ $service->unit }}">
                                            {{ $service->name }} ({{ number_format($service->price) }})
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-3"><input type="number" id="qtyInput" class="form-control form-control-soft form-control-sm text-center" placeholder="Jml"></div>
                            <div class="col-2"><button type="button" class="btn btn-success btn-sm w-100 h-100" onclick="addItem()"><i class="bi bi-plus-lg"></i></button></div>
                        </div>
                    </div>

                    {{-- List Item --}}
                    <ul class="list-group list-group-flush mb-0" id="itemsList"></ul>
                    <div id="hiddenInputsContainer"></div>
                    
                    <div class="d-flex justify-content-between border-top pt-3 mt-3"><span class="text-muted small">Total Estimasi Laundry</span><span class="fw-bold" id="laundryTotalDisplay">Rp 0</span></div>
                </div>
            </div>

            {{-- 4. OPSI LAYANAN & PEMBAYARAN --}}
            <div class="card border-0 shadow-sm rounded-4 mb-4 {{ $pendingTransaction ? '' : 'locked-section' }}" id="optionSection">
                <div class="card-body p-4">
                    <h6 class="fw-bold mb-3">Pilihan Tambahan</h6>
                    
                    {{-- Delivery Type --}}
                    <div class="row g-3 mb-4">
                        <div class="col-6">
                            <div class="selector-item">
                                <input type="radio" name="delivery_type" id="dt1" value="pickup" {{ ($pendingTransaction && $pendingTransaction->delivery_type == 'pickup') ? 'checked' : '' }}>
                                <label for="dt1" class="selector-card"><i class="bi bi-bicycle"></i><span class="fw-bold small">Ambil Sendiri</span></label>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="selector-item">
                                <input type="radio" name="delivery_type" id="dt2" value="both" {{ (!$pendingTransaction || $pendingTransaction->delivery_type == 'both') ? 'checked' : '' }}>
                                <label for="dt2" class="selector-card"><i class="bi bi-truck"></i><span class="fw-bold small">Antar-Jemput</span></label>
                            </div>
                        </div>
                    </div>

                    {{-- Payment Method (Hanya Visual, karena pembayaran final nanti) --}}
                    <label class="small text-muted fw-bold mb-2">PEMBAYARAN LAUNDRY</label>
                    <div class="row g-3">
                        <div class="col-6">
                            <div class="selector-item">
                                <input type="radio" name="payment_method" id="pm1" value="online" checked>
                                <label for="pm1" class="selector-card"><i class="bi bi-wallet2"></i><span class="fw-bold small">QRIS/Online</span></label>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="selector-item">
                                <input type="radio" name="payment_method" id="pm2" value="cash">
                                <label for="pm2" class="selector-card"><i class="bi bi-cash-stack"></i><span class="fw-bold small">Cash</span></label>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- 5. PROMO & CATATAN --}}
            <div class="card border-0 shadow-sm rounded-4 mb-4 {{ $pendingTransaction ? '' : 'locked-section' }}" id="noteSection">
                <div class="card-body p-4">
                    <div class="mb-3">
                        <label class="form-label small text-muted fw-bold">KODE PROMO</label>
                        <input type="text" name="promo_code" class="form-control form-control-soft text-uppercase" placeholder="Jika ada">
                    </div>
                    <label class="form-label small text-muted fw-bold">CATATAN KHUSUS</label>
                    <textarea name="note" class="form-control form-control-soft" rows="2" placeholder="Contoh: Baju putih dipisah...">{{ $pendingTransaction->note ?? '' }}</textarea>
                </div>
            </div>

            {{-- TOMBOL SUBMIT FINAL --}}
            {{-- Jika Resume, tombol langsung muncul. Jika Baru, tombol hidden dulu. --}}
            <button type="submit" class="btn btn-success w-100 rounded-pill py-3 fw-bold shadow-lg mb-5 {{ $pendingTransaction ? '' : 'd-none' }}" id="btnFinalSubmit">
                <i class="bi bi-send-check-fill me-2"></i> KIRIM PESANAN SEKARANG
            </button>
        </form>
    </div>
</div>

<script>
    // --- DATA DARI BACKEND ---
    const shopLat = {{ $setting->latitude ?? -6.200000 }};
    const shopLng = {{ $setting->longitude ?? 106.816666 }};
    const ratePerKm = {{ $setting->delivery_rate_per_km ?? 2000 }};
    
    // Mode Resume?
    const isResume = {{ $pendingTransaction ? 'true' : 'false' }};

    // --- 1. LOGIKA PETA (Hanya Jika BUKAN Resume) ---
    if (!isResume) {
        var map = L.map('map').setView([shopLat, shopLng], 14);
        L.tileLayer('https://{s}.basemaps.cartocdn.com/rastertiles/voyager/{z}/{x}/{y}{r}.png', { attribution: '&copy; OpenStreetMap', maxZoom: 19 }).addTo(map);
        
        // Toko
        L.marker([shopLat, shopLng], {icon: L.divIcon({ className: 'custom-div-icon', html: "<div style='background-color:#3b82f6; width: 12px; height: 12px; border-radius: 50%; border: 2px solid white; box-shadow: 0 0 0 4px rgba(59, 130, 246, 0.3);'></div>", iconSize: [20, 20], iconAnchor: [10, 10] })}).addTo(map);
        
        // User (Draggable)
        var userMarker = L.marker([shopLat - 0.005, shopLng - 0.005], {draggable: true, icon: new L.Icon({ iconUrl: 'https://raw.githubusercontent.com/pointhi/leaflet-color-markers/master/img/marker-icon-2x-red.png', shadowUrl: 'https://cdnjs.cloudflare.com/ajax/libs/leaflet/0.7.7/images/marker-shadow.png', iconSize: [25, 41], iconAnchor: [12, 41] })}).addTo(map);
        
        var polyline = L.polyline([], {color: '#3b82f6', dashArray: '5, 10'}).addTo(map);

        function updateDistance() {
            var userPos = userMarker.getLatLng();
            var R = 6371; var dLat = (userPos.lat - shopLat) * (Math.PI/180); var dLon = (userPos.lng - shopLng) * (Math.PI/180);
            var a = Math.sin(dLat/2) * Math.sin(dLat/2) + Math.cos(shopLat * (Math.PI/180)) * Math.cos(userPos.lat * (Math.PI/180)) * Math.sin(dLon/2) * Math.sin(dLon/2);
            var distanceKm = (R * 2 * Math.atan2(Math.sqrt(a), Math.sqrt(1-a))).toFixed(2);
            
            // VALIDASI JARAK 10KM
            if (distanceKm > 10) {
                document.getElementById('distanceWarning').classList.remove('d-none');
                document.getElementById('btnPayOngkir').disabled = true;
                document.getElementById('btnPayOngkir').innerText = "LOKASI TERLALU JAUH";
                document.getElementById('btnPayOngkir').classList.add('btn-secondary');
                document.getElementById('btnPayOngkir').classList.remove('btn-primary');
            } else {
                document.getElementById('distanceWarning').classList.add('d-none');
                document.getElementById('btnPayOngkir').disabled = false;
                document.getElementById('btnPayOngkir').innerText = "BAYAR ONGKIR SEKARANG";
                document.getElementById('btnPayOngkir').classList.add('btn-primary');
                document.getElementById('btnPayOngkir').classList.remove('btn-secondary');
            }

            var deliveryFee = Math.ceil(distanceKm * ratePerKm); if(deliveryFee < 5000) deliveryFee = 5000;
            
            document.getElementById('lat').value = userPos.lat; document.getElementById('lng').value = userPos.lng;
            document.getElementById('distanceInput').value = distanceKm; document.getElementById('distanceDisplay').innerText = distanceKm + " KM";
            document.getElementById('deliveryFeeInput').value = deliveryFee; document.getElementById('deliveryFeeDisplay').innerText = "Rp " + new Intl.NumberFormat('id-ID').format(deliveryFee);
            polyline.setLatLngs([[shopLat, shopLng], [userPos.lat, userPos.lng]]);
        }
        userMarker.on('drag', updateDistance); map.on('click', function(e) { userMarker.setLatLng(e.latlng); updateDistance(); });
        updateDistance();
    }

    // --- 2. LOGIKA KERANJANG ---
    let items = [];
    function addItem() {
        const sel = document.getElementById('serviceSelect'); const qty = parseFloat(document.getElementById('qtyInput').value);
        if(!sel.value || !qty || qty <= 0) return alert("Lengkapi data!");
        const opt = sel.options[sel.selectedIndex];
        items.push({ id: sel.value, name: opt.dataset.name, price: parseFloat(opt.dataset.price), qty: qty, subtotal: parseFloat(opt.dataset.price) * qty });
        renderItems(); sel.value = ""; document.getElementById('qtyInput').value = "";
    }
    function renderItems() {
        const list = document.getElementById('itemsList'); const hidden = document.getElementById('hiddenInputsContainer');
        list.innerHTML = ""; hidden.innerHTML = ""; let total = 0;
        items.forEach((item, idx) => {
            total += item.subtotal;
            list.innerHTML += `<li class="list-group-item d-flex justify-content-between align-items-center px-0 bg-transparent"><div><div class="fw-bold small">${item.name}</div><div class="text-muted small">${item.qty} x ${item.price}</div></div><i class="bi bi-trash text-danger" style="cursor:pointer" onclick="items.splice(${idx},1);renderItems();"></i></li>`;
            hidden.innerHTML += `<input type="hidden" name="items[${idx}][service_id]" value="${item.id}"><input type="hidden" name="items[${idx}][qty]" value="${item.qty}">`;
        });
        document.getElementById('laundryTotalDisplay').innerText = "Rp " + new Intl.NumberFormat('id-ID').format(total);
    }

    // --- 3. LOGIKA FORM SUBMIT ---
    document.addEventListener("DOMContentLoaded", function() {
        const form = document.getElementById('orderForm');
        const btnPay = document.getElementById('btnPayOngkir');
        const btnFinal = document.getElementById('btnFinalSubmit');

        // Jika mode baru (ada tombol bayar ongkir)
        if (btnPay) {
            btnPay.addEventListener('click', async function() {
                if(!document.querySelector('input[name="phone"]').value || !document.querySelector('textarea[name="pickup_address"]').value) return alert('Isi WhatsApp dan Alamat dulu!');
                
                btnPay.disabled = true;
                btnPay.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span> Memproses...';

                const formData = new FormData(form);
                try {
                    const response = await fetch("{{ route('customer.order.store') }}", { method: 'POST', headers: { 'X-Requested-With': 'XMLHttpRequest', 'X-CSRF-TOKEN': '{{ csrf_token() }}' }, body: formData });
                    const res = await response.json();
                    
                    if (res.status === 'success') {
                        window.snap.pay(res.snap_token, {
                            onSuccess: function() {
                                // SUKSES BAYAR: Unlock semua section
                                document.querySelectorAll('.locked-section').forEach(el => el.classList.remove('locked-section'));
                                
                                // Hilangkan kotak pembayaran
                                btnPay.closest('.delivery-info-box').style.display = 'none';
                                
                                // Tampilkan tombol final
                                btnFinal.classList.remove('d-none');
                                
                                // Update URL action form ke 'complete'
                                form.action = "/customer/order/" + res.transaction_id + "/complete";
                                document.getElementById('transactionId').value = res.transaction_id;
                                
                                alert("Pembayaran Ongkir Berhasil! Sekarang silakan pilih layanan laundry.");
                            },
                            onClose: function() { 
                                alert('Selesaikan pembayaran dulu!'); 
                                btnPay.disabled = false; 
                                btnPay.innerHTML = '<i class="bi bi-wallet2 me-2"></i> BAYAR ONGKIR SEKARANG'; 
                            }
                        });
                    } else { 
                        alert('Gagal: ' + res.message); 
                        btnPay.disabled = false;
                        btnPay.innerHTML = '<i class="bi bi-wallet2 me-2"></i> BAYAR ONGKIR SEKARANG'; 
                    }
                } catch (e) { 
                    alert('Kesalahan Koneksi / Error: ' + e); 
                    btnPay.disabled = false; 
                    btnPay.innerHTML = '<i class="bi bi-wallet2 me-2"></i> BAYAR ONGKIR SEKARANG';
                }
            });
        }

        // Final Submit (Tahap 2)
        btnFinal.addEventListener('click', async function(e) {
            e.preventDefault();
            if(items.length === 0) return alert('Pilih layanan laundry minimal satu!');
            
            btnFinal.disabled = true;
            btnFinal.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span> Mengirim...';

            const formData = new FormData(form);
            try {
                const response = await fetch(form.action, { 
                    method: 'POST', 
                    headers: { 'X-Requested-With': 'XMLHttpRequest', 'X-CSRF-TOKEN': '{{ csrf_token() }}' }, 
                    body: formData 
                });
                const res = await response.json();
                if(res.status === 'success') {
                    window.location.href = res.redirect_url; 
                } else {
                    alert('Gagal simpan: ' + (res.message || 'Unknown Error'));
                    btnFinal.disabled = false;
                    btnFinal.innerHTML = '<i class="bi bi-send-check-fill me-2"></i> KIRIM PESANAN SEKARANG';
                }
            } catch (e) { 
                alert('Error koneksi.'); 
                btnFinal.disabled = false; 
                btnFinal.innerHTML = '<i class="bi bi-send-check-fill me-2"></i> KIRIM PESANAN SEKARANG';
            }
        });
    });
</script>
@endsection