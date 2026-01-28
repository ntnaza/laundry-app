@extends('layouts.admin')

@section('title', 'Proses Pesanan')
@section('page-title', 'Order #' . $transaction->invoice_code)

@section('content')
<form action="{{ route('transactions.update', $transaction->id) }}" method="POST">
    @csrf
    @method('PUT')

    <div class="row g-4">
        {{-- SECTION UTAMA (KIRI) --}}
        <div class="col-xl-8 col-lg-7">
            
            {{-- 1. RINCIAN ITEM & VERIFIKASI (DYNAMIC) --}}
            <div class="card border-0 shadow-soft rounded-4 mb-4 overflow-hidden">
                <div class="card-header bg-white border-bottom border-light p-4">
                    <div class="d-flex align-items-center gap-3">
                        <div class="bg-primary text-white rounded-circle box-center flex-shrink-0" style="width: 40px; height: 40px;">
                            <i class="bi bi-list-check fs-5"></i>
                        </div>
                        <div>
                            <h6 class="fw-heading text-dark mb-0">Rincian & Verifikasi Item</h6>
                            <p class="text-muted small mb-0">Sesuaikan berat/jumlah riil jika berbeda.</p>
                        </div>
                    </div>
                </div>

                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="bg-light">
                                <tr>
                                    <th class="ps-4 py-3 text-uppercase small text-muted">Layanan</th>
                                    <th class="py-3 text-uppercase small text-muted" style="width: 150px;">Qty / Berat</th>
                                    <th class="pe-4 py-3 text-end text-uppercase small text-muted">Subtotal</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($transaction->details as $item)
                                <tr>
                                    <td class="ps-4 py-3">
                                        <div class="fw-bold text-dark">{{ $item->service->name }}</div>
                                        <div class="small text-muted">
                                            @if($item->service->type == 'kiloan')
                                                <span class="badge bg-info-subtle text-info border border-info rounded-pill px-2">Kiloan</span>
                                            @else
                                                <span class="badge bg-warning-subtle text-warning border border-warning rounded-pill px-2">Satuan</span>
                                            @endif
                                            <span class="ms-1">@ Rp {{ number_format($item->price_per_unit) }}</span>
                                        </div>
                                    </td>
                                    <td class="py-3">
                                        <div class="input-group input-group-sm">
                                            {{-- LOGIKA INPUT: 
                                                - Jika Kiloan: Step 0.1 (bisa koma), Min 0.1, Value tampil apa adanya (float)
                                                - Jika Satuan: Step 1 (bulat), Min 1, Value dipaksa jadi Integer (int) biar gak dianggap desimal 
                                            --}}
                                            @php $isKiloan = $item->service->type == 'kiloan'; @endphp
                                            
                                            <input type="number" 
                                                   step="{{ $isKiloan ? '0.01' : '1' }}" 
                                                   min="{{ $isKiloan ? '0.1' : '1' }}"
                                                   name="qty[{{ $item->id }}]" 
                                                   class="form-control fw-bold text-center qty-input"
                                                   data-price="{{ $item->price_per_unit }}"
                                                   value="{{ $isKiloan ? $item->qty : (int)$item->qty }}">
                                            
                                            <span class="input-group-text bg-light text-muted">{{ $item->service->unit }}</span>
                                        </div>
                                    </td>
                                    <td class="pe-4 py-3 text-end fw-bold text-dark item-subtotal">
                                        Rp {{ number_format($item->subtotal) }}
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                            <tfoot class="border-top">
                                {{-- Subtotal --}}
                                <tr>
                                    <td colspan="2" class="text-end py-2 text-muted small text-uppercase fw-bold">Subtotal</td>
                                    <td class="pe-4 py-2 text-end fw-bold" id="displaySubtotal">Rp {{ number_format($transaction->subtotal) }}</td>
                                </tr>
                                {{-- Diskon --}}
                                @if($transaction->promo)
                                <tr class="text-danger">
                                    <td colspan="2" class="text-end py-2 small text-uppercase fw-bold">
                                        Diskon ({{ $transaction->promo->code }}) 
                                        {{-- Tampilkan Detail Diskon --}}
                                        <span class="badge bg-danger-subtle text-danger ms-1 border border-danger">
                                            @if($transaction->promo->type == 'percentage')
                                                {{ $transaction->promo->value }}%
                                            @else
                                                Potongan Tetap
                                            @endif
                                        </span>

                                        <input type="hidden" id="promoType" value="{{ $transaction->promo->type }}">
                                        <input type="hidden" id="promoValue" value="{{ $transaction->promo->value }}">
                                        <input type="hidden" id="promoMax" value="{{ $transaction->promo->max_discount }}">
                                        <input type="hidden" id="promoMinSpend" value="{{ $transaction->promo->min_spend }}">
                                    </td>
                                    <td class="pe-4 py-2 text-end fw-bold" id="displayDiscount">- Rp {{ number_format($transaction->discount_amount) }}</td>
                                </tr>
                                @endif
                                {{-- Grand Total --}}
                                <tr class="bg-light-primary bg-opacity-10">
                                    <td colspan="2" class="text-end py-3 text-primary text-uppercase fw-bold">Total Tagihan</td>
                                    <td class="pe-4 py-3 text-end fw-heading fs-5 text-primary" id="displayGrandTotal">Rp {{ number_format($transaction->total_price) }}</td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>

            {{-- 2. STATUS WORKFLOW --}}
            <div class="card border-0 shadow-soft rounded-4 mb-4">
                <div class="card-body p-4">
                    <h6 class="fw-bold text-dark mb-3 small text-uppercase ls-1">Status Pengerjaan</h6>
                    <div class="row g-2">
                        @foreach([
                            'pending' => ['icon' => 'bi-clock', 'label' => 'Pending', 'color' => 'secondary'],
                            'process' => ['icon' => 'bi-clipboard-check', 'label' => 'Diterima', 'color' => 'primary'],
                            'washing' => ['icon' => 'bi-water', 'label' => 'Mencuci', 'color' => 'info'],
                            'ironing' => ['icon' => 'bi-tropical-storm', 'label' => 'Setrika', 'color' => 'warning'],
                            'ready'   => ['icon' => 'bi-bag-check', 'label' => 'Siap', 'color' => 'success'],
                            'done'    => ['icon' => 'bi-check-all', 'label' => 'Selesai', 'color' => 'dark']
                        ] as $key => $meta)
                        <div class="col-4 col-md-4 mb-2">
                            <input type="radio" class="btn-check status-radio" name="status" id="status_{{ $key }}" value="{{ $key }}" {{ $transaction->status == $key ? 'checked' : '' }}>
                            <label class="btn btn-outline-light border border-light-subtle text-dark w-100 p-2 rounded-4 d-flex flex-column align-items-center justify-content-center gap-1 h-100 transition-300" for="status_{{ $key }}">
                                <i class="bi {{ $meta['icon'] }} fs-5 {{ $transaction->status == $key ? 'text-white' : 'text-muted' }} icon-transition"></i>
                                <span class="small fw-bold" style="font-size: 0.7rem;">{{ $meta['label'] }}</span>
                            </label>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>

            {{-- 3. STATUS LOGISTIK (DINAMIS SESUAI FASE) --}}
            @if($transaction->delivery_type != 'none')
            <div class="card border-0 shadow-soft rounded-4">
                <div class="card-body p-4">
                    {{-- Tentukan Judul & Label berdasarkan Fase --}}
                    @php
                        $isPickupPhase = in_array($transaction->status, ['pending', 'process', 'washing', 'ironing']);
                        $phaseTitle = $isPickupPhase ? 'Logistik Penjemputan (Pickup)' : 'Logistik Pengantaran (Delivery)';
                        
                        $labels = [
                            'pending' => $isPickupPhase ? 'Cari Kurir Jemput' : 'Cari Kurir Antar',
                            'on_the_way' => $isPickupPhase ? 'Kurir OTW Jemput' : 'Kurir OTW Antar',
                            'delivered' => $isPickupPhase ? 'Sampai di Toko' : 'Sampai Tujuan'
                        ];
                    @endphp

                    <h6 class="fw-bold text-dark mb-3 small text-uppercase ls-1">
                        <i class="bi bi-truck me-2"></i> {{ $phaseTitle }}
                    </h6>
                    
                    <div class="row g-2">
                        @foreach([
                            'pending'     => ['icon' => 'bi-search'],
                            'on_the_way'  => ['icon' => 'bi-scooter'],
                            'delivered'   => ['icon' => 'bi-geo-alt-fill']
                        ] as $key => $meta)
                        <div class="col-4">
                            <input type="radio" class="btn-check delivery-radio" name="delivery_status" id="del_{{ $key }}" value="{{ $key }}" {{ $transaction->delivery_status == $key ? 'checked' : '' }}>
                            <label class="btn btn-outline-light border border-light-subtle text-dark w-100 p-2 rounded-3 d-flex flex-column align-items-center justify-content-center gap-1 transition-300" for="del_{{ $key }}">
                                <i class="bi {{ $meta['icon'] }} fs-6 text-muted"></i>
                                <span class="small fw-bold" style="font-size: 0.7rem;">{{ $labels[$key] }}</span>
                            </label>
                        </div>
                        @endforeach
                    </div>
                    
                    {{-- Info Tambahan --}}
                    <div class="mt-3 small text-muted bg-light p-2 rounded border border-light-subtle">
                        <i class="bi bi-info-circle me-1"></i>
                        @if($isPickupPhase)
                            Setelah barang sampai toko, ubah status pengerjaan jadi <strong>"Diterima"</strong>.
                        @else
                            Setelah barang sampai tujuan, ubah status pengerjaan jadi <strong>"Selesai"</strong>.
                        @endif
                    </div>
                </div>
            </div>
            @endif

        </div>

        {{-- SECTION SIDEBAR (KANAN) --}}
        <div class="col-xl-4 col-lg-5">
            
            {{-- TOMBOL AKSI & STATUS BAYAR --}}
            <div class="card border-0 shadow-soft rounded-4 mb-4">
                <div class="card-body p-4">
                    <h6 class="fw-bold text-dark mb-3 small text-uppercase ls-1">Pembayaran</h6>
                    
                    {{-- TOGGLE PEMBAYARAN --}}
                    <div class="bg-light rounded-pill p-1 d-flex position-relative user-select-none border border-light mb-4" style="height: 50px;">
                        <input type="radio" class="btn-check" name="payment_status" id="unpaid" value="unpaid" {{ $transaction->payment_status == 'unpaid' ? 'checked' : '' }}>
                        <label class="btn btn-sm btn-transparent w-50 rounded-pill fw-bold text-muted d-flex align-items-center justify-content-center z-1 transition-300" for="unpaid">
                            Belum Lunas
                        </label>

                        <input type="radio" class="btn-check" name="payment_status" id="paid" value="paid" {{ $transaction->payment_status == 'paid' ? 'checked' : '' }}>
                        <label class="btn btn-sm btn-transparent w-50 rounded-pill fw-bold text-muted d-flex align-items-center justify-content-center z-1 transition-300" for="paid">
                            Lunas
                        </label>
                        
                        <div class="slider-bg bg-white shadow-sm rounded-pill position-absolute top-0 bottom-0 m-1" style="width: calc(50% - 8px); transition: 0.3s cubic-bezier(0.4, 0, 0.2, 1);"></div>
                    </div>

                    <div class="d-grid gap-2">
                        <button type="submit" class="btn btn-primary rounded-pill fw-bold shadow-lg py-3 hover-top transition-300">
                            <i class="bi bi-save2-fill me-2"></i> Update Transaksi
                        </button>
                        <a href="{{ route('transactions.index') }}" class="btn btn-light rounded-pill fw-bold text-muted py-2">
                            Batal
                        </a>
                    </div>
                </div>
            </div>

            {{-- INFO CUSTOMER --}}
            <div class="card border-0 shadow-soft rounded-4 mb-4">
                <div class="card-body p-4">
                    <h6 class="fw-bold text-dark mb-3 small text-uppercase ls-1">Info Pelanggan</h6>
                    <div class="d-flex align-items-center gap-3 mb-3">
                        <div class="avatar rounded-circle bg-light-primary text-primary d-flex align-items-center justify-content-center flex-shrink-0 fw-bold" style="width: 48px; height: 48px; font-size: 1.2rem;">
                            {{ substr($transaction->customer->name, 0, 1) }}
                        </div>
                        <div class="overflow-hidden">
                            <h6 class="fw-bold text-dark mb-0 text-truncate">{{ $transaction->customer->name }}</h6>
                            <small class="text-muted d-block text-truncate">{{ $transaction->customer->phone ?? '-' }}</small>
                        </div>
                    </div>
                    
                    <div class="d-flex gap-2 align-items-start small text-secondary bg-light p-3 rounded-3">
                        <i class="bi bi-geo-alt-fill text-danger mt-1"></i>
                        <span class="lh-sm">{{ $transaction->pickup_address ?? 'Datang ke Outlet' }}</span>
                    </div>
                </div>
            </div>

            {{-- NOTE & BUKTI --}}
            @if($transaction->note)
            <div class="card border-0 shadow-soft rounded-4 mb-4 bg-light-warning">
                <div class="card-body p-3">
                    <small class="fw-bold text-warning-emphasis d-flex align-items-center gap-2 mb-1">
                        <i class="bi bi-sticky-fill"></i> Catatan
                    </small>
                    <p class="mb-0 text-dark opacity-75 small fst-italic">"{{ $transaction->note }}"</p>
                </div>
            </div>
            @endif

            @if($transaction->payment_proof)
            <div class="card border-0 shadow-soft rounded-4 overflow-hidden">
                <div class="card-header bg-white border-0 py-3">
                    <h6 class="fw-bold mb-0 small text-uppercase ls-1">Bukti Pembayaran</h6>
                </div>
                <div class="position-relative bg-light text-center p-2">
                    <img src="{{ asset('storage/' . $transaction->payment_proof) }}" 
                         class="img-fluid rounded-3 shadow-sm" 
                         style="max-height: 200px; cursor: zoom-in;"
                         onclick="window.open(this.src)"
                         alt="Bukti">
                </div>
            </div>
            @endif

        </div>
    </div>
</form>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const qtyInputs = document.querySelectorAll('.qty-input');
        const displaySubtotal = document.getElementById('displaySubtotal');
        const displayDiscount = document.getElementById('displayDiscount');
        const displayGrandTotal = document.getElementById('displayGrandTotal');
        const realTotalPrice = document.getElementById('realTotalPrice'); // Input Hidden buat ke Server

        // Ambil Data Promo (Jika Ada)
        const promoType = document.getElementById('promoType') ? document.getElementById('promoType').value : null;
        const promoValue = document.getElementById('promoValue') ? parseFloat(document.getElementById('promoValue').value) : 0;
        const promoMax = document.getElementById('promoMax') ? parseFloat(document.getElementById('promoMax').value) : 0;
        const promoMinSpend = document.getElementById('promoMinSpend') ? parseFloat(document.getElementById('promoMinSpend').value) : 0;

        function formatRupiah(number) {
            return 'Rp ' + new Intl.NumberFormat('id-ID').format(number);
        }

        function calculate() {
            let subtotal = 0;

            // 1. Hitung Subtotal dari semua item
            qtyInputs.forEach(input => {
                let qty = parseFloat(input.value);
                let price = parseFloat(input.getAttribute('data-price'));
                
                if (isNaN(qty) || qty < 0) qty = 0;

                // Update tampilan subtotal per baris
                const rowTotal = qty * price;
                input.closest('tr').querySelector('.item-subtotal').innerText = formatRupiah(rowTotal);

                subtotal += rowTotal;
            });

            // 2. Hitung Diskon
            let discount = 0;
            if (promoType && subtotal >= promoMinSpend) {
                if (promoType === 'percentage') {
                    let rawDisc = subtotal * (promoValue / 100);
                    if (promoMax > 0 && rawDisc > promoMax) {
                        discount = promoMax;
                    } else {
                        discount = rawDisc;
                    }
                } else {
                    discount = promoValue;
                }
                
                if (discount > subtotal) discount = subtotal;
            }

            // 3. Grand Total
            const grandTotal = subtotal - discount;

            // Update UI
            displaySubtotal.innerText = formatRupiah(subtotal);
            if (displayDiscount) displayDiscount.innerText = '- ' + formatRupiah(discount);
            displayGrandTotal.innerText = formatRupiah(grandTotal);

            // PENTING: Update nilai input hidden agar terkirim ke server
            if (realTotalPrice) realTotalPrice.value = grandTotal;
        }

        // Pasang Event Listener ke setiap input
        qtyInputs.forEach(input => {
            input.addEventListener('input', calculate);
        });

        // Jalankan perhitungan saat halaman pertama kali dimuat (Supaya angka langsung muncul)
        calculate();
    });
</script>

<style>
    /* UTILS */
    .hover-top:hover { transform: translateY(-3px); }
    
    /* REMOVE INPUT NUMBER SPINNER */
    input[type=number]::-webkit-inner-spin-button, 
    input[type=number]::-webkit-outer-spin-button { 
        -webkit-appearance: none; margin: 0; 
    }

    /* PAYMENT TOGGLE */
    #paid:checked ~ .slider-bg { transform: translateX(100%); }
    #paid:checked ~ label[for="paid"] { color: var(--bs-success) !important; }
    #unpaid:checked ~ label[for="unpaid"] { color: var(--bs-danger) !important; }
    
    /* STATUS CARDS */
    .status-radio:checked + label {
        border-color: var(--bs-primary) !important;
        background-color: var(--bs-primary) !important;
        color: white !important;
        box-shadow: 0 10px 20px rgba(37, 99, 235, 0.2);
    }
    .status-radio:checked + label .text-muted { color: white !important; }
    
    /* DELIVERY CARDS */
    .delivery-radio:checked + label {
        border-color: var(--bs-dark) !important;
        background-color: var(--bs-dark) !important;
        color: white !important;
    }
    .delivery-radio:checked + label i { color: white !important; }

</style>
@endsection