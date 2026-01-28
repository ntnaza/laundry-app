@extends('layouts.customer')

@section('title', 'Pembayaran')

@section('content')
<script src="https://app.sandbox.midtrans.com/snap/snap.js" data-client-key="{{ config('midtrans.client_key') }}"></script>

<div class="row justify-content-center">
    <div class="col-md-5">
        <div class="card border-0 shadow-lg rounded-4 overflow-hidden text-center">
            <div class="card-header bg-primary text-white py-4 border-0">
                <div class="bg-white bg-opacity-20 rounded-circle d-inline-flex p-3 mb-3">
                    <i class="bi bi-shield-lock-fill fs-1"></i>
                </div>
                <h5 class="fw-bold mb-0">Selesaikan Pembayaran</h5>
                <p class="mb-0 opacity-75 small">Lakukan pembayaran untuk memproses pesanan.</p>
            </div>
            <div class="card-body p-4">
                
                <div class="mb-4">
                    <p class="text-muted small fw-bold mb-1">TOTAL TAGIHAN</p>
                    <h2 class="fw-bold text-dark display-6">Rp {{ number_format($transaction->total_price, 0, ',', '.') }}</h2>
                </div>

                <div class="bg-light p-3 rounded-3 mb-4 text-start">
                    <div class="d-flex justify-content-between mb-2">
                        <span class="text-muted small">ID Pesanan</span>
                        <span class="fw-bold small text-dark">{{ $transaction->invoice_code }}</span>
                    </div>
                    <div class="d-flex justify-content-between mb-2">
                        <span class="text-muted small">Layanan Laundry</span>
                        <span class="fw-bold small text-dark">Rp {{ number_format($transaction->subtotal, 0, ',', '.') }}</span>
                    </div>
                    @if($transaction->delivery_fee > 0)
                    <div class="d-flex justify-content-between mb-2">
                        <span class="text-muted small">Ongkos Kirim ({{ $transaction->distance }} KM)</span>
                        <span class="fw-bold small text-dark">Rp {{ number_format($transaction->delivery_fee, 0, ',', '.') }}</span>
                    </div>
                    @endif
                    @if($transaction->discount_amount > 0)
                    <div class="d-flex justify-content-between text-success">
                        <span class="small">Diskon Promo</span>
                        <span class="fw-bold small">-Rp {{ number_format($transaction->discount_amount, 0, ',', '.') }}</span>
                    </div>
                    @endif
                </div>

                <button id="pay-button" class="btn btn-primary w-100 rounded-pill py-3 fw-bold shadow-sm mb-3 hover-scale">
                    BAYAR SEKARANG
                </button>
                
                <a href="{{ route('customer.dashboard') }}" class="btn btn-light w-100 rounded-pill py-3 fw-bold text-muted">
                    Nanti Saja
                </a>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
    var payButton = document.getElementById('pay-button');
    payButton.addEventListener('click', function () {
        window.snap.pay('{{ $transaction->snap_token }}', {
            onSuccess: function (result) {
                window.location.href = "{{ route('customer.dashboard') }}?payment=success";
            },
            onPending: function (result) {
                window.location.href = "{{ route('customer.dashboard') }}?payment=pending";
            },
            onError: function (result) {
                alert("Pembayaran gagal!");
            },
            onClose: function () {
                // Do nothing
            }
        });
    });

    // Auto Trigger Popup (Opsional, biar langsung muncul)
    setTimeout(() => {
        payButton.click();
    }, 1000);
</script>

<style>
    .hover-scale:hover { transform: translateY(-3px); box-shadow: 0 10px 20px rgba(37, 99, 235, 0.3); }
</style>
@endsection