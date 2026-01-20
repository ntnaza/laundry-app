<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Struk - {{ $transaction->invoice_code }}</title>
    <style>
        body {
            font-family: 'Courier New', Courier, monospace;
            font-size: 12px;
            margin: 0;
            padding: 0;
            width: 58mm; /* Ukuran standar kertas thermal */
            color: #000;
        }
        .container {
            padding: 5px;
        }
        .text-center { text-align: center; }
        .text-right { text-align: right; }
        .fw-bold { font-weight: bold; }
        
        .divider {
            border-top: 1px dashed #000;
            margin: 5px 0;
        }
        
        table { width: 100%; border-collapse: collapse; }
        td { vertical-align: top; }
        
        .btn-print {
            display: block;
            width: 100%;
            padding: 10px;
            background: #000;
            color: #fff;
            text-align: center;
            text-decoration: none;
            margin-bottom: 10px;
            font-family: sans-serif;
        }

        /* Sembunyikan tombol print saat dicetak */
        @media print {
            .btn-print { display: none; }
            @page { margin: 0; }
            body { margin: 0; }
        }
    </style>
</head>
<body onload="window.print()">
    <a href="{{ route('transactions.show', $transaction->id) }}" class="btn-print">Kembali</a>

    <div class="container">
        <div class="text-center">
            <div class="fw-bold" style="font-size: 14px;">{{ strtoupper($setting->shop_name ?? 'LAUNDRY') }}</div>
            <div>{{ $setting->address ?? '-' }}</div>
            <div>WA: {{ $setting->phone ?? '-' }}</div>
        </div>

        <div class="divider"></div>

        <div>
            No: {{ $transaction->invoice_code }}<br>
            Tgl: {{ $transaction->created_at->format('d/m/y H:i') }}<br>
            Plg: {{ $transaction->customer->name }}
        </div>

        <div class="divider"></div>

        <table>
            @foreach($transaction->details as $item)
            <tr>
                <td colspan="2">{{ $item->service->name }}</td>
            </tr>
            <tr>
                <td>{{ $item->qty }} x {{ number_format($item->service->price) }}</td>
                <td class="text-right">{{ number_format($item->subtotal) }}</td>
            </tr>
            @endforeach
        </table>

        <div class="divider"></div>

        <table>
            <tr class="fw-bold">
                <td>TOTAL</td>
                <td class="text-right">Rp {{ number_format($transaction->total_price) }}</td>
            </tr>
            <tr>
                <td>Status</td>
                <td class="text-right">{{ $transaction->payment_status == 'paid' ? 'LUNAS' : 'BELUM LUNAS' }}</td>
            </tr>
        </table>

        <div class="divider"></div>

        <div class="text-center" style="margin-top: 10px;">
            Terima Kasih!<br>
            Simpan struk ini untuk<br>pengambilan cucian.
        </div>
        
        <br><br>
        <div class="text-center">.</div>
    </div>
</body>
</html>