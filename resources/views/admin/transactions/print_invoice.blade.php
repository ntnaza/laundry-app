<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Invoice #{{ $transaction->invoice_code }}</title>
    <style>
        body { font-family: 'Helvetica', 'Arial', sans-serif; color: #333; line-height: 1.6; margin: 0; padding: 20px; font-size: 14px; }
        .invoice-box { max-width: 800px; margin: auto; padding: 30px; border: 1px solid #eee; box-shadow: 0 0 10px rgba(0, 0, 0, 0.15); }
        .header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 40px; border-bottom: 2px solid #eee; padding-bottom: 20px; }
        .company-info h1 { margin: 0; color: #2563EB; font-size: 24px; text-transform: uppercase; }
        .invoice-title { text-align: right; }
        .invoice-title h2 { margin: 0; color: #555; font-size: 28px; text-transform: uppercase; letter-spacing: 2px; }
        .invoice-details { margin-top: 5px; color: #777; }
        
        .client-info { display: flex; justify-content: space-between; margin-bottom: 40px; }
        .info-block h3 { margin: 0 0 10px; font-size: 16px; color: #2563EB; text-transform: uppercase; border-bottom: 1px solid #eee; padding-bottom: 5px; display: inline-block; }
        
        table { width: 100%; border-collapse: collapse; margin-bottom: 30px; }
        th { background: #f8f9fa; color: #333; font-weight: bold; text-align: left; padding: 12px; text-transform: uppercase; font-size: 12px; border-bottom: 2px solid #ddd; }
        td { padding: 12px; border-bottom: 1px solid #eee; }
        .text-right { text-align: right; }
        .text-center { text-align: center; }
        
        .totals { width: 40%; margin-left: auto; }
        .totals-row { display: flex; justify-content: space-between; padding: 8px 0; border-bottom: 1px solid #eee; }
        .totals-row.grand-total { border-top: 2px solid #333; border-bottom: none; font-weight: bold; font-size: 18px; color: #2563EB; margin-top: 10px; padding-top: 15px; }
        
        .footer { margin-top: 50px; text-align: center; color: #999; font-size: 12px; border-top: 1px solid #eee; padding-top: 20px; }
        .status-stamp {
            display: inline-block; padding: 5px 15px; border-radius: 4px; font-weight: bold; text-transform: uppercase; letter-spacing: 1px;
            border: 2px solid; margin-top: 10px;
        }
        .paid { color: #198754; border-color: #198754; }
        .unpaid { color: #dc3545; border-color: #dc3545; }

        @media print {
            .invoice-box { border: none; box-shadow: none; padding: 0; }
            .no-print { display: none; }
        }
    </style>
</head>
<body onload="window.print()">

    <div class="invoice-box">
        <div class="header">
            <div class="company-info">
                <h1>{{ $setting->shop_name ?? 'LAUNDRY KUY' }}</h1>
                <div>{{ $setting->address ?? 'Alamat Toko Belum Diatur' }}</div>
                <div>WA: {{ $setting->phone ?? '-' }}</div>
            </div>
            <div class="invoice-title">
                <h2>INVOICE</h2>
                <div class="invoice-details">No: <strong>{{ $transaction->invoice_code }}</strong></div>
                <div class="invoice-details">Tgl: {{ $transaction->created_at->format('d M Y') }}</div>
            </div>
        </div>

        <div class="client-info">
            <div class="info-block">
                <h3>Tagihan Kepada</h3>
                <div><strong>{{ $transaction->customer->name }}</strong></div>
                <div>{{ $transaction->customer->phone }}</div>
                <div style="max-width: 300px;">{{ $transaction->customer->address ?? '-' }}</div>
            </div>
            <div class="info-block text-right">
                <h3>Status Pembayaran</h3>
                @if($transaction->payment_status == 'paid')
                    <div class="status-stamp paid">LUNAS</div>
                    <div style="margin-top: 5px; color: #777;">Metode: {{ $transaction->payment_method ?? 'Cash' }}</div>
                @else
                    <div class="status-stamp unpaid">BELUM LUNAS</div>
                @endif
            </div>
        </div>

        <table>
            <thead>
                <tr>
                    <th>Layanan</th>
                    <th class="text-center">Qty</th>
                    <th class="text-right">Harga Satuan</th>
                    <th class="text-right">Total</th>
                </tr>
            </thead>
            <tbody>
                @foreach($transaction->details as $item)
                <tr>
                    <td>
                        <strong>{{ $item->service->name }}</strong><br>
                        <small style="color: #777;">{{ $item->service->type == 'kiloan' ? 'Kiloan' : 'Satuan' }}</small>
                    </td>
                    <td class="text-center">{{ $item->qty }} {{ $item->service->unit }}</td>
                    <td class="text-right">Rp {{ number_format($item->price_per_unit) }}</td>
                    <td class="text-right">Rp {{ number_format($item->subtotal) }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>

        <div class="totals">
            <div class="totals-row">
                <span>Subtotal</span>
                <span>Rp {{ number_format($transaction->subtotal) }}</span>
            </div>
            @if($transaction->discount_amount > 0)
            <div class="totals-row" style="color: #dc3545;">
                <span>Diskon ({{ $transaction->promo->code ?? '' }})</span>
                <span>- Rp {{ number_format($transaction->discount_amount) }}</span>
            </div>
            @endif
            <div class="totals-row grand-total">
                <span>Total Tagihan</span>
                <span>Rp {{ number_format($transaction->total_price) }}</span>
            </div>
        </div>

        <div class="footer">
            <p>Terima kasih atas kepercayaan Anda menggunakan layanan kami.</p>
            <p><i>Invoice ini sah dan diproses secara otomatis oleh komputer.</i></p>
        </div>
    </div>

</body>
</html>
