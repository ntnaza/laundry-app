<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Struk #{{ $transaction->invoice_code }}</title>
    <style>
        /* RESET & BASE */
        * { box-sizing: border-box; }
        body {
            font-family: 'Courier New', Courier, monospace; /* Font mesin kasir */
            font-size: 10pt; /* Ukuran pas buat thermal 58mm */
            margin: 0;
            padding: 0;
            width: 58mm; /* Lebar kertas */
            background: #fff;
            color: #000;
        }
        
        .container { padding: 2mm; width: 100%; }
        
        /* UTILITIES */
        .text-center { text-align: center; }
        .text-right { text-align: right; }
        .fw-bold { font-weight: bold; }
        .d-flex { display: flex; justify-content: space-between; }
        
        /* DIVIDER */
        .divider {
            border-top: 1px dashed #000;
            margin: 5px 0;
            width: 100%;
        }
        .divider-bold {
            border-top: 2px solid #000;
            margin: 5px 0;
        }

        /* HEADER */
        .header-title { font-size: 12pt; font-weight: bold; margin-bottom: 2px; }
        .header-info { font-size: 8pt; margin-bottom: 2px; }

        /* ITEM LIST */
        .item-row { margin-bottom: 3px; }
        .item-name { display: block; font-weight: bold; }
        .item-detail { font-size: 9pt; display: flex; justify-content: space-between; }

        /* FOOTER */
        .footer { font-size: 8pt; margin-top: 10px; }

        /* TOMBOL (HANYA DI LAYAR) */
        .no-print {
            position: fixed; top: 0; left: 0; width: 100%;
            background: #333; padding: 10px; text-align: center; z-index: 99;
        }
        .btn-back {
            color: #fff; text-decoration: none; font-family: sans-serif; font-weight: bold; font-size: 14px;
            border: 1px solid #fff; padding: 5px 15px; border-radius: 5px;
        }

        @media print {
            .no-print { display: none; }
            @page { margin: 0; size: auto; }
            body { margin: 0; padding: 0; }
        }
    </style>
</head>
<body onload="window.print()">

    <div class="no-print">
        <a href="{{ route('transactions.show', $transaction->id) }}" class="btn-back">⬅ Kembali ke Detail</a>
    </div>

    <div class="container" style="margin-top: 20px;"> {{-- Margin top buat kompensasi no-print bar di layar --}}
        
        {{-- HEADER TOKO --}}
        <div class="text-center">
            <div class="header-title">{{ strtoupper($setting->shop_name ?? 'LAUNDRY') }}</div>
            <div class="header-info">{{ $setting->address ?? '-' }}</div>
            <div class="header-info">WA: {{ $setting->phone ?? '-' }}</div>
        </div>

        <div class="divider-bold"></div>

        {{-- INFO TRANSAKSI --}}
        <div style="font-size: 9pt;">
            <div class="d-flex">
                <span>No: {{ $transaction->invoice_code }}</span>
                <span>{{ $transaction->created_at->format('d/m H:i') }}</span>
            </div>
            <div style="margin-top: 2px;">
                Plg: <strong>{{ substr($transaction->customer->name, 0, 18) }}</strong>
            </div>
            @if($transaction->customer->phone)
            <div>Tel: {{ $transaction->customer->phone }}</div>
            @endif
        </div>

        <div class="divider"></div>

        {{-- LIST ITEM --}}
        @foreach($transaction->details as $item)
        <div class="item-row">
            <span class="item-name">{{ $item->service->name }}</span>
            <div class="item-detail">
                <span>{{ $item->qty }} {{ $item->service->unit }} x {{ number_format($item->price_per_unit, 0, ',', '.') }}</span>
                <span>{{ number_format($item->subtotal, 0, ',', '.') }}</span>
            </div>
        </div>
        @endforeach

        <div class="divider"></div>

        {{-- TOTAL HARGA --}}
        <div class="d-flex fw-bold" style="font-size: 11pt; margin-bottom: 5px;">
            <span>TOTAL TAGIHAN</span>
            <span>{{ number_format($transaction->total_price, 0, ',', '.') }}</span>
        </div>

        {{-- STATUS PEMBAYARAN --}}
        <div class="text-center" style="margin-top: 5px; border: 2px solid #000; padding: 2px;">
            <strong style="font-size: 12pt;">
                {{ $transaction->payment_status == 'paid' ? 'LUNAS' : 'BELUM LUNAS' }}
            </strong>
        </div>

        <div class="divider"></div>

        {{-- FOOTER --}}
        <div class="text-center footer">
            Terima Kasih!<br>
            Harap simpan struk ini untuk<br>pengambilan cucian.
            <br><br>
            <i>www.laundrykuy.com</i>
        </div>
        
        <br>
        <div class="text-center">.</div> {{-- Dot penutup buat cutter printer --}}
    </div>

</body>
</html>