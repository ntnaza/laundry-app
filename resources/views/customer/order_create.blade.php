@extends('layouts.customer')

@section('title', 'Buat Pesanan Baru')

@section('content')

{{-- Midtrans Snap JS --}}
<script src="https://app.sandbox.midtrans.com/snap/snap.js" data-client-key="{{ config('midtrans.client_key') }}"></script>

{{-- Leaflet Library --}}
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>

<style>
    :root {
        --primary: #435ebe;
        --primary-dark: #374b9d;
    }

    /* Step Indicator */
    .step-wrapper { display: flex; justify-content: space-between; position: relative; margin-bottom: 2rem; }
    .step-wrapper::before { content: ""; position: absolute; top: 15px; left: 0; width: 100%; height: 2px; background: #e2e8f0; z-index: 0; }
    .step-item { position: relative; z-index: 1; text-align: center; }
    .step-circle { width: 32px; height: 32px; background: white; border: 2px solid #e2e8f0; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-weight: bold; margin: 0 auto 8px; font-size: 0.8rem; transition: 0.3s; }
    .step-item.active .step-circle { background: var(--primary); border-color: var(--primary); color: white; box-shadow: 0 0 0 4px rgba(67, 94, 190, 0.1); }
    .step-text { font-size: 0.7rem; font-weight: 700; color: #94a3b8; text-uppercase; letter-spacing: 0.5px; }
    .step-item.active .step-text { color: var(--primary); }

    .btn-back-custom { width: 42px; height: 42px; display: flex; align-items: center; justify-content: center; background: white; border: 1px solid #f1f5f9; border-radius: 12px; box-shadow: 0 4px 12px rgba(0,0,0,0.03); color: var(--dark); transition: 0.3s; text-decoration: none; }
    .btn-back-custom:hover { background: #f8fafc; color: var(--primary); transform: translateX(-3px); }
    
    /* CSS SELECTOR CARD */
    .selector-item { position: relative; height: 100%; width: 100%; }
    .selector-item input[type="radio"] { position: absolute; opacity: 0; cursor: pointer; height: 0; width: 0; }
    .selector-card { border: 2px solid #f1f5f9; border-radius: 20px; padding: 18px 12px; text-align: center; transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1); cursor: pointer; background: white; height: 100%; width: 100%; display: flex; flex-direction: column; align-items: center; justify-content: center; }
    .selector-item input[type="radio"]:checked + .selector-card { border-color: var(--primary); background-color: rgba(67, 94, 190, 0.03); color: var(--primary); transform: translateY(-3px); box-shadow: 0 10px 20px rgba(67, 94, 190, 0.08); }
    .selector-card i { font-size: 1.8rem; margin-bottom: 8px; line-height: 1; display: inline-flex; transition: 0.3s; }
    .selector-item input[type="radio"]:checked + .selector-card i { transform: scale(1.1); }
    
    .form-control-soft { background-color: #f8fafc; border: 1px solid #e2e8f0; border-radius: 14px; padding: 12px 18px; font-size: 0.9rem; transition: 0.3s; }
    .form-control-soft:focus { background-color: #fff; border-color: var(--primary); box-shadow: 0 0 0 4px rgba(67, 94, 190, 0.08); }
    
    .delivery-info-box { background: #f8fafc; border: 2px dashed #e2e8f0; border-radius: 20px; padding: 20px; margin-top: 15px; }
    
    /* LOGIKA LOCK FORM */
    .locked-section { position: relative; }
    .locked-section .card { filter: grayscale(100%); opacity: 0.6; pointer-events: none; user-select: none; }
    .lock-overlay { position: absolute; top: 0; left: 0; width: 100%; height: 100%; display: flex; flex-direction: column; align-items: center; justify-content: center; z-index: 10; cursor: not-allowed; border-radius: 24px; }
    .lock-badge { background: white; padding: 8px 16px; border-radius: 50px; box-shadow: 0 10px 30px rgba(0,0,0,0.1); font-weight: 800; font-size: 0.75rem; color: #475569; display: flex; align-items: center; gap: 2px; }

    .service-item-row { transition: 0.2s; border-radius: 12px; }
    .service-item-row:hover { background: rgba(0,0,0,0.01); }

    .leaflet-container { font-family: inherit; }
    .btn-gradient-primary { background: linear-gradient(135deg, var(--primary) 0%, var(--primary-dark) 100%); color: white; border: none; transition: 0.3s; }
    .btn-gradient-primary:hover { transform: translateY(-2px); box-shadow: 0 8px 20px rgba(67, 94, 190, 0.3); color: white; }
    .btn-gradient-success { background: linear-gradient(135deg, #10b981 0%, #059669 100%); color: white; border: none; transition: 0.3s; }
    .btn-gradient-success:hover { transform: translateY(-2px); box-shadow: 0 8px 20px rgba(16, 185, 129, 0.3); color: white; }
</style>

{{-- FORM WRAPPER UTAMA (Membungkus Row) --}}
<form action="{{ $pendingTransaction ? route('customer.order.complete', $pendingTransaction->id) : route('customer.order.store') }}" method="POST" id="orderForm">
@csrf
<input type="hidden" id="transactionId" value="{{ $pendingTransaction->id ?? '' }}">

<div class="row g-4">
    {{-- KOLOM KIRI: Peta, Alamat, Opsi (Scrollable) --}}
    <div class="col-lg-8">
        
        <div class="d-flex align-items-center gap-3 mb-4 mt-3">
            <a href="{{ route('customer.dashboard') }}" class="btn-back-custom"><i class="bi bi-arrow-left"></i></a>
            <div>
                <h4 class="fw-heading mb-0">Buat Pesanan</h4>
                <p class="text-muted small mb-0">Lengkapi data untuk mulai mencuci.</p>
            </div>
        </div>

        {{-- Step Progress --}}
        <div class="step-wrapper mb-5">
            <div class="step-item {{ !$pendingTransaction ? 'active' : '' }}" id="step1">
                <div class="step-circle">1</div>
                <div class="step-text">Lokasi</div>
            </div>
            <div class="step-item {{ $pendingTransaction ? 'active' : '' }}" id="step2">
                <div class="step-circle">2</div>
                <div class="step-text">Item</div>
            </div>
            <div class="step-item" id="step3">
                <div class="step-circle">3</div>
                <div class="step-text">Selesai</div>
            </div>
        </div>

        {{-- 1. PETA & LOKASI --}}
        @if(!$pendingTransaction)
        <div class="card border-0 shadow-soft rounded-4 mb-4 overflow-hidden animate__animated animate__fadeInUp">
            <div class="card-body p-4">
                <div class="d-flex align-items-center justify-content-between mb-3">
                    <h6 class="fw-bold mb-0 text-dark"><i class="bi bi-geo-alt-fill text-danger me-2"></i>Titik Penjemputan</h6>
                    <span class="badge bg-light-primary text-primary rounded-pill fw-bold">Wajib</span>
                </div>
                
                <div id="map" style="height: 350px; border-radius: 20px; z-index: 1;" class="mb-3 border border-light"></div>
                
                <input type="hidden" name="latitude" id="lat">
                <input type="hidden" name="longitude" id="lng">
                <input type="hidden" name="distance" id="distanceInput">
                <input type="hidden" name="delivery_fee" id="deliveryFeeInput">

                <div class="delivery-info-box" id="deliveryInfoBox">
                    <div class="row align-items-center">
                        <div class="col-6 border-end">
                            <span class="small text-muted fw-bold d-block">ESTIMASI JARAK</span>
                            <span class="fw-bold text-dark fs-5" id="distanceDisplay">0 KM</span>
                        </div>
                        <div class="col-6 ps-4">
                            <span class="small text-muted fw-bold d-block">ONGKOS KIRIM</span>
                            <span class="fw-heading text-primary fs-4" id="deliveryFeeDisplay">Rp 0</span>
                        </div>
                    </div>
                    
                    <div id="distanceWarning" class="alert alert-danger border-0 rounded-3 py-2 small fw-bold d-none mt-3">
                        <i class="bi bi-exclamation-triangle-fill me-1"></i> Lokasi terlalu jauh (> 10KM).
                    </div>

                    <button type="button" class="btn btn-gradient-primary w-100 rounded-pill py-3 fw-bold shadow-lg mt-3" id="btnPayOngkir">
                        <i class="bi bi-wallet2 me-2"></i> BAYAR ONGKIR SEKARANG
                    </button>
                </div>
            </div>
        </div>
        @endif

        {{-- 2. ALAMAT & WA --}}
        <div class="card border-0 shadow-soft rounded-4 mb-4 animate__animated animate__fadeInUp" id="contactSection">
            <div class="card-body p-4">
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label small text-muted fw-bold ls-1 text-uppercase">Nomor WhatsApp</label>
                        <div class="input-group">
                            <span class="input-group-text bg-light border-0 fw-bold text-muted ps-3 rounded-start-4">+62</span>
                            <input type="number" name="phone" class="form-control form-control-soft border-start-0 rounded-end-4" placeholder="812xxxx" value="{{ $pendingTransaction ? ($pendingTransaction->customer->phone ?? Auth::user()->phone) : (Auth::user()->phone ?? '') }}" required>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label small text-muted fw-bold ls-1 text-uppercase">Metode Pengiriman</label>
                        <div class="d-flex gap-2">
                            <div class="selector-item">
                                <input type="radio" name="delivery_type" id="dt2" value="both" {{ (!$pendingTransaction || $pendingTransaction->delivery_type == 'both') ? 'checked' : '' }}>
                                <label for="dt2" class="selector-card py-2"><i class="bi bi-truck fs-5 mb-1"></i><span class="small fw-bold" style="font-size: 0.7rem;">Delivery</span></label>
                            </div>
                            <div class="selector-item">
                                <input type="radio" name="delivery_type" id="dt1" value="pickup" {{ ($pendingTransaction && $pendingTransaction->delivery_type == 'pickup') ? 'checked' : '' }}>
                                <label for="dt1" class="selector-card py-2"><i class="bi bi-shop fs-5 mb-1"></i><span class="small fw-bold" style="font-size: 0.7rem;">Ambil</span></label>
                            </div>
                        </div>
                    </div>
                    <div class="col-12">
                        <label class="form-label small text-muted fw-bold ls-1 text-uppercase">Alamat Lengkap</label>
                        <textarea name="pickup_address" class="form-control form-control-soft" rows="2" placeholder="Detail lokasi..." required>{{ $pendingTransaction ? $pendingTransaction->pickup_address : '' }}</textarea>
                    </div>
                </div>
            </div>
        </div>

        {{-- 4. OPSI PEMBAYARAN & CATATAN --}}
        {{-- FIX: Locked Section Logic (Only locked if NOT pendingTransaction) --}}
        <div class="{{ $pendingTransaction ? '' : 'locked-section' }}" id="optionSectionWrapper">
            @if(!$pendingTransaction) <div class="lock-overlay"></div> @endif
            <div class="card border-0 shadow-soft rounded-4 mb-4" id="noteSection">
                <div class="card-body p-4">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label small text-muted fw-bold ls-1 text-uppercase">Metode Pembayaran</label>
                            <div class="d-flex gap-2">
                                <div class="selector-item">
                                    <input type="radio" name="payment_method" id="pm1" value="online" checked>
                                    <label for="pm1" class="selector-card py-2"><i class="bi bi-qr-code-scan fs-5 mb-1"></i><span class="small fw-bold" style="font-size: 0.7rem;">QRIS</span></label>
                                </div>
                                <div class="selector-item">
                                    <input type="radio" name="payment_method" id="pm2" value="cash">
                                    <label for="pm2" class="selector-card py-2"><i class="bi bi-cash-stack fs-5 mb-1"></i><span class="small fw-bold" style="font-size: 0.7rem;">Tunai</span></label>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small text-muted fw-bold ls-1 text-uppercase">Kode Promo</label>
                            <input type="text" name="promo_code" class="form-control form-control-soft text-uppercase fw-bold" placeholder="Punya voucher?">
                        </div>
                        <div class="col-12">
                            <label class="form-label small text-muted fw-bold ls-1 text-uppercase">Catatan Tambahan</label>
                            <textarea name="note" class="form-control form-control-soft" rows="2" placeholder="Catatan cuci...">{{ $pendingTransaction->note ?? '' }}</textarea>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>

    {{-- KOLOM KANAN: ITEM & CHECKOUT (Sticky di Desktop) --}}
    <div class="col-lg-4">
        {{-- FIX: Locked Section Logic --}}
        <div class="{{ $pendingTransaction ? '' : 'locked-section' }} position-sticky" style="top: 100px;" id="itemSectionWrapper">
            @if(!$pendingTransaction)
                <div class="lock-overlay" id="lockOverlay1">
                    <div class="lock-badge animate__animated animate__pulse animate__infinite">
                        <i class="bi bi-lock-fill me-1"></i> BAYAR ONGKIR DULU
                    </div>
                </div>
            @endif
            
            <div class="card border-0 shadow-lg rounded-4 overflow-hidden bg-white">
                <div class="card-header bg-white border-0 pt-4 px-4 pb-0">
                    <h6 class="fw-bold mb-0 text-dark"><i class="bi bi-basket2-fill text-primary me-2"></i>Item Laundry</h6>
                </div>
                <div class="card-body p-4">
                    {{-- Form Pilih Item --}}
                    <div class="bg-light p-3 rounded-4 mb-3 border border-light">
                        <div class="row g-2">
                            <div class="col-12">
                                <select id="serviceSelect" class="form-select form-control-soft border-0 shadow-sm py-2">
                                    <option value="" selected disabled>Pilih Layanan</option>
                                    @foreach($services as $service)
                                        <option value="{{ $service->id }}" data-name="{{ $service->name }}" data-price="{{ $service->price }}" data-unit="{{ $service->unit }}">
                                            {{ $service->name }} ({{ number_format($service->price/1000) }}k)
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-8">
                                <input type="number" id="qtyInput" class="form-control form-control-soft border-0 shadow-sm py-2 text-center" placeholder="Jumlah (Kg/Pcs)">
                            </div>
                            <div class="col-4">
                                <button type="button" class="btn btn-primary w-100 h-100 rounded-3 box-center shadow-sm fw-bold" onclick="addItem()">
                                    <i class="bi bi-plus-lg"></i> Tambah
                                </button>
                            </div>
                        </div>
                    </div>

                    {{-- List Item --}}
                    <div class="mb-4" style="max-height: 300px; overflow-y: auto;">
                        <ul class="list-group list-group-flush" id="itemsList">
                            {{-- Loop JS --}}
                            <li class="list-group-item text-center text-muted small py-4 bg-transparent border-0" id="emptyCartMsg">
                                <i class="bi bi-cart-x fs-1 opacity-25 d-block mb-2"></i>
                                Belum ada item dipilih.
                            </li>
                        </ul>
                    </div>
                    <div id="hiddenInputsContainer"></div>
                    
                    <div class="bg-light rounded-4 p-3 border border-light">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <span class="text-muted small fw-bold text-uppercase ls-1">Total Estimasi</span>
                            </div>
                            <span class="fw-heading text-primary fs-4" id="laundryTotalDisplay">Rp 0</span>
                        </div>
                    </div>

                    <button type="submit" class="btn btn-gradient-success w-100 rounded-pill py-3 fw-bold shadow-lg mt-4 {{ $pendingTransaction ? '' : 'd-none' }}" id="btnFinalSubmit">
                        <i class="bi bi-send-check-fill me-2"></i> KIRIM PESANAN
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>
</form> {{-- FORM UTAMA END (Wraps All Rows) --}}

<script>
    const shopLat = {{ $setting->latitude ?? -6.200000 }};
    const shopLng = {{ $setting->longitude ?? 106.816666 }};
    const ratePerKm = {{ $setting->delivery_rate_per_km ?? 2000 }};
    const isResume = {{ $pendingTransaction ? 'true' : 'false' }};

    if (!isResume) {
        var map = L.map('map').setView([shopLat, shopLng], 14);
        L.tileLayer('https://{s}.basemaps.cartocdn.com/rastertiles/voyager/{z}/{x}/{y}{r}.png', { attribution: '&copy; OpenStreetMap', maxZoom: 19 }).addTo(map);
        
        L.marker([shopLat, shopLng], {icon: L.divIcon({ className: 'custom-div-icon', html: "<div style='background-color:#435ebe; width: 14px; height: 14px; border-radius: 50%; border: 3px solid white; box-shadow: 0 0 0 4px rgba(67, 94, 190, 0.2);'></div>", iconSize: [20, 20], iconAnchor: [10, 10] })}).addTo(map);
        
        var userMarker = L.marker([shopLat - 0.005, shopLng - 0.005], {draggable: true, icon: new L.Icon({ iconUrl: 'https://raw.githubusercontent.com/pointhi/leaflet-color-markers/master/img/marker-icon-2x-red.png', shadowUrl: 'https://cdnjs.cloudflare.com/ajax/libs/leaflet/0.7.7/images/marker-shadow.png', iconSize: [25, 41], iconAnchor: [12, 41] })}).addTo(map);
        
        var polyline = L.polyline([], {color: '#435ebe', dashArray: '5, 8', weight: 3}).addTo(map);

        function updateDistance() {
            var userPos = userMarker.getLatLng();
            var R = 6371; var dLat = (userPos.lat - shopLat) * (Math.PI/180); var dLon = (userPos.lng - shopLng) * (Math.PI/180);
            var a = Math.sin(dLat/2) * Math.sin(dLat/2) + Math.cos(shopLat * (Math.PI/180)) * Math.cos(userPos.lat * (Math.PI/180)) * Math.sin(dLon/2) * Math.sin(dLon/2);
            var distanceKm = (R * 2 * Math.atan2(Math.sqrt(a), Math.sqrt(1-a))).toFixed(2);
            
            if (distanceKm > 10) {
                document.getElementById('distanceWarning').classList.remove('d-none');
                document.getElementById('btnPayOngkir').disabled = true;
                document.getElementById('btnPayOngkir').innerText = "JARAK TERLALU JAUH (>10KM)";
                document.getElementById('btnPayOngkir').style.background = "#64748b";
            } else {
                document.getElementById('distanceWarning').classList.add('d-none');
                document.getElementById('btnPayOngkir').disabled = false;
                document.getElementById('btnPayOngkir').innerText = "BAYAR ONGKIR SEKARANG";
                document.getElementById('btnPayOngkir').style.background = "linear-gradient(135deg, var(--primary) 0%, var(--primary-dark) 100%)";
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

    let items = [];
    function addItem() {
        const sel = document.getElementById('serviceSelect'); const qty = parseFloat(document.getElementById('qtyInput').value);
        if(!sel.value || !qty || qty <= 0) return alert("Pilih layanan dan jumlahnya!");
        const opt = sel.options[sel.selectedIndex];
        items.push({ id: sel.value, name: opt.dataset.name, price: parseFloat(opt.dataset.price), qty: qty, subtotal: parseFloat(opt.dataset.price) * qty, unit: opt.dataset.unit });
        renderItems(); sel.value = ""; document.getElementById('qtyInput').value = "";
    }
    function renderItems() {
        const list = document.getElementById('itemsList'); const hidden = document.getElementById('hiddenInputsContainer');
        list.innerHTML = ""; hidden.innerHTML = ""; let total = 0;
        const countEl = document.getElementById('itemCount');
        if (countEl) countEl.innerText = items.length + " Item";
        items.forEach((item, idx) => {
            total += item.subtotal;
            list.innerHTML += `<li class="list-group-item d-flex justify-content-between align-items-center px-0 py-3 bg-transparent service-item-row">
                <div class="d-flex align-items-center gap-3">
                    <div class="bg-light-primary text-primary rounded-circle box-center" style="width: 38px; height: 38px;">
                        <i class="bi bi-check2"></i>
                    </div>
                    <div>
                        <div class="fw-bold text-dark" style="font-size: 0.85rem;">${item.name}</div>
                        <div class="text-muted" style="font-size: 0.75rem;">${item.qty} ${item.unit} x Rp ${new Intl.NumberFormat('id-ID').format(item.price)}</div>
                    </div>
                </div>
                <div class="d-flex align-items-center gap-3">
                    <span class="fw-bold text-dark small">Rp ${new Intl.NumberFormat('id-ID').format(item.subtotal)}</span>
                    <button type="button" class="btn btn-sm btn-light-danger rounded-circle p-1 box-center" onclick="items.splice(${idx},1);renderItems();">
                        <i class="bi bi-x fs-5 text-danger"></i>
                    </button>
                </div>
            </li>`;
            hidden.innerHTML += `<input type="hidden" name="items[${idx}][service_id]" value="${item.id}"><input type="hidden" name="items[${idx}][qty]" value="${item.qty}">`;
        });
        document.getElementById('laundryTotalDisplay').innerText = "Rp " + new Intl.NumberFormat('id-ID').format(total);
    }

    document.addEventListener("DOMContentLoaded", function() {
        const form = document.getElementById('orderForm');
        const btnPay = document.getElementById('btnPayOngkir');
        const btnFinal = document.getElementById('btnFinalSubmit');

        if (btnPay) {
            btnPay.addEventListener('click', async function() {
                if(!document.querySelector('input[name="phone"]').value || !document.querySelector('textarea[name="pickup_address"]').value) return alert('Lengkapi Nomor WhatsApp dan Alamat dulu!');
                
                btnPay.disabled = true;
                btnPay.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span> Memproses Pembayaran...';

                const formData = new FormData(form);
                try {
                    const response = await fetch("{{ route('customer.order.store') }}", { method: 'POST', headers: { 'X-Requested-With': 'XMLHttpRequest', 'X-CSRF-TOKEN': '{{ csrf_token() }}' }, body: formData });
                    const res = await response.json();
                    
                    if (res.status === 'success') {
                        window.snap.pay(res.snap_token, {
                            onSuccess: function() {
                                document.querySelectorAll('.locked-section').forEach(el => el.classList.remove('locked-section'));
                                document.querySelectorAll('.lock-overlay').forEach(el => el.remove());
                                btnPay.closest('.delivery-info-box').style.display = 'none';
                                btnFinal.classList.remove('d-none');
                                document.getElementById('step1').classList.remove('active');
                                document.getElementById('step2').classList.add('active');
                                form.action = "/customer/order/" + res.transaction_id + "/complete";
                                document.getElementById('transactionId').value = res.transaction_id;
                                Toastify({ text: "Ongkir Berhasil Dibayar! Lanjutkan pilih cucian.", duration: 3000, gravity: "top", position: "center", backgroundColor: "#10B981" }).showToast();
                            },
                            onClose: function() { 
                                alert('Pembayaran ongkir dibatalkan. Selesaikan pembayaran untuk melanjutkan.'); 
                                btnPay.disabled = false; 
                                btnPay.innerHTML = '<i class="bi bi-wallet2 me-2"></i> BAYAR ONGKIR SEKARANG'; 
                            }
                        });
                    } else { 
                        alert('Error: ' + res.message); 
                        btnPay.disabled = false;
                        btnPay.innerHTML = '<i class="bi bi-wallet2 me-2"></i> BAYAR ONGKIR SEKARANG'; 
                    }
                } catch (e) { 
                    alert('Gagal menghubungi server.'); 
                    btnPay.disabled = false; 
                }
            });
        }

        btnFinal.addEventListener('click', async function(e) {
            e.preventDefault();
            if(items.length === 0) return alert('Pilih layanan laundry minimal satu!');
            
            btnFinal.disabled = true;
            btnFinal.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span> Mengirim Pesanan...';

            const formData = new FormData(form);
            try {
                const response = await fetch(form.action, { method: 'POST', headers: { 'X-Requested-With': 'XMLHttpRequest', 'X-CSRF-TOKEN': '{{ csrf_token() }}' }, body: formData });
                const res = await response.json();
                if(res.status === 'success') {
                    window.location.href = res.redirect_url; 
                } else {
                    alert('Gagal simpan: ' + (res.message || 'Error'));
                    btnFinal.disabled = false;
                    btnFinal.innerHTML = '<i class="bi bi-send-check-fill me-2"></i> KIRIM PESANAN SEKARANG';
                }
            } catch (e) { 
                alert('Gagal mengirim pesanan.'); 
                btnFinal.disabled = false; 
            }
        });
    });
</script>
@endsection