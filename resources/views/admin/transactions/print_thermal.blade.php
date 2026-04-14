<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Struk #{{ $transaction->invoice_code }}</title>
    <style>
        @page {
            margin: 0;
            size: 58mm auto;
        }
        
        * { 
            box-sizing: border-box; 
            -webkit-print-color-adjust: exact; 
        }

        body {
            font-family: 'Courier New', Courier, monospace;
            font-size: 9pt;
            margin: 0;
            padding: 0;
            width: 58mm;
            background: #fff;
            color: #000;
            line-height: 1.2;
        }
        
        .container { 
            padding: 2mm; 
            width: 58mm;
        }
        
        /* UTILITIES */
        .text-center { text-align: center; }
        .fw-bold { font-weight: bold; }
        .d-flex { display: flex; justify-content: space-between; }
        
        /* DIVIDER */
        .divider {
            border-top: 1px dashed #000;
            margin: 4px 0;
            width: 100%;
        }
        .divider-double {
            border-top: 1px double #000;
            margin: 4px 0;
            height: 2px;
            border-bottom: 1px double #000;
        }

        /* HEADER */
        .shop-name { 
            font-size: 12pt; 
            font-weight: 900; 
            margin-bottom: 2px; 
        }
        .shop-info { font-size: 7.5pt; margin-bottom: 1px; }

        /* ITEM LIST */
        .item-name { font-weight: bold; display: block; text-transform: uppercase; font-size: 8.5pt; }
        .item-detail { font-size: 8pt; display: flex; justify-content: space-between; margin-bottom: 4px; }

        /* TOTAL SECTION */
        .total-row { display: flex; justify-content: space-between; font-size: 8.5pt; margin-bottom: 2px; }
        .grand-total { 
            display: flex; 
            justify-content: space-between; 
            font-weight: bold; 
            font-size: 11pt; 
            margin-top: 5px;
            padding-top: 5px;
            border-top: 1px solid #000;
        }

        .payment-status {
            margin: 8px 0;
            padding: 4px;
            border: 1px solid #000;
            text-align: center;
            font-weight: bold;
            font-size: 9pt;
        }

        .footer { font-size: 7.5pt; margin-top: 10px; }

        /* Sembunyikan navigasi jika dalam iframe/modal */
        .no-print-nav {
            display: block;
            background: #333;
            padding: 10px;
            text-align: center;
            margin-bottom: 10px;
        }
        
        /* Jika URL punya parameter 'popup', sembunyikan nav bar hitam */
        @media screen {
            body.is-popup .no-print-nav { display: none; }
        }

        @media print {
            .no-print-nav { display: none !important; }
            body { margin: 0; padding: 0; background: #fff; }
            .container { padding: 1mm; width: 58mm; box-shadow: none; border: none; }
        }
    </style>
</head>
<body class="{{ request()->has('popup') ? 'is-popup' : '' }}">

    {{-- Navigasi hitam hanya muncul jika dibuka di tab baru --}}
    @if(!request()->has('popup'))
    <div class="no-print-nav">
        <a href="{{ route('transactions.index') }}" style="color:#fff; text-decoration:none; font-family:sans-serif; font-size:12px; border:1px solid #fff; padding:4px 12px; border-radius:4px;">⬅ Kembali</a>
    </div>
    @endif

    <div class="container">
        
        <div class="text-center">
            <div class="shop-name">{{ strtoupper($setting->shop_name ?? 'LAUNDRY SYSTEM') }}</div>
            <div class="shop-info">{{ $setting->address ?? '-' }}</div>
            <div class="shop-info">Telp/WA: {{ $setting->phone ?? '-' }}</div>
        </div>

        <div class="divider-double"></div>

        <div style="font-size: 8pt;">
            <div class="d-flex">
                <span>Inv: #{{ $transaction->invoice_code }}</span>
                <span>{{ $transaction->created_at->format('d/m/y H:i') }}</span>
            </div>
            <div class="d-flex" style="margin-top: 2px;">
                <span>Kasir: {{ explode(' ', ($transaction->user->name ?? 'System'))[0] }}</span>
                <span>Plg: {{ substr($transaction->customer->name, 0, 12) }}</span>
            </div>
        </div>

        <div class="divider"></div>

        @foreach($transaction->details as $item)
        <div class="item-group">
            <span class="item-name">{{ $item->service->name }}</span>
            <div class="item-detail">
                <span>{{ (float)$item->qty }} {{ $item->service->unit }} x {{ number_format($item->price_per_unit, 0, ',', '.') }}</span>
                <span>{{ number_format($item->subtotal, 0, ',', '.') }}</span>
            </div>
        </div>
        @endforeach

        <div class="divider"></div>

        <div class="total-row">
            <span>Subtotal</span>
            <span>{{ number_format($transaction->subtotal, 0, ',', '.') }}</span>
        </div>
        
        @if($transaction->delivery_fee > 0)
        <div class="total-row">
            <span>Ongkir</span>
            <span>{{ number_format($transaction->delivery_fee, 0, ',', '.') }}</span>
        </div>
        @endif

        @if($transaction->discount_amount > 0)
        <div class="total-row">
            <span>Diskon</span>
            <span>-{{ number_format($transaction->discount_amount, 0, ',', '.') }}</span>
        </div>
        @endif

        <div class="grand-total">
            <span>TOTAL</span>
            <span>{{ number_format($transaction->total_price, 0, ',', '.') }}</span>
        </div>

        <div class="payment-status">
            {{ $transaction->payment_status == 'paid' ? '*** LUNAS ***' : '*** BELUM BAYAR ***' }}
        </div>

        <div class="divider"></div>

        <div class="text-center footer">
            <strong>Terima Kasih</strong><br>
            Simpan struk ini sebagai bukti.<br>
            {{-- <i>Power by {{ $setting->shop_name ?? 'Laundry System' }}</i> --}}
        </div>
        
        {{-- <div style="height: 5mm; text-align: center;">.</div> --}}
    </div>

</body>
</html>
{{-- by {{ $setting->shop_name ?? 'Laundry System' }}</i> --}}
        </div>
        
        {{-- <div style="height: 5mm; text-align: center;">.</div> --}}
    </div>

</body>
</html>
