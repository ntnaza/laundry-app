<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Surat Jalan - {{ $transaction->invoice_code }}</title>
    
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700;800&display=swap" rel="stylesheet">
    
    <style>
        /* RESET & BASE */
        * { box-sizing: border-box; -webkit-print-color-adjust: exact !important; print-color-adjust: exact !important; }
        body {
            font-family: 'Inter', sans-serif;
            font-size: 12px;
            color: #000;
            margin: 0;
            padding: 20px;
            background-color: #f4f4f4; /* Background abu di layar biar kertasnya kelihatan */
        }

        /* TOMBOL CETAK */
        .no-print-bar {
            position: fixed;
            top: 0; left: 0; right: 0;
            background: #333;
            padding: 10px;
            text-align: center;
            z-index: 999;
            box-shadow: 0 2px 10px rgba(0,0,0,0.2);
        }
        .btn-print {
            background: #fff; color: #000; border: none; padding: 8px 20px;
            font-weight: bold; border-radius: 4px; cursor: pointer; text-transform: uppercase;
            font-size: 14px;
        }
        .btn-print:hover { background: #eee; }

        /* KERTAS A5 / SETENGAH A4 (Standar Surat Jalan) */
        .page {
            width: 210mm; /* A4 Width */
            min-height: 148mm; /* A5 Height */
            background: #fff;
            margin: 40px auto;
            padding: 20px;
            border: 1px solid #ddd;
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
            position: relative;
        }

        /* HEADER */
        .header { display: flex; justify-content: space-between; align-items: center; border-bottom: 3px solid #000; padding-bottom: 15px; margin-bottom: 20px; }
        .logo { font-size: 24px; font-weight: 800; text-transform: uppercase; letter-spacing: 1px; }
        .store-info { font-size: 10px; margin-top: 5px; color: #444; width: 60%; }
        
        .resi-box { text-align: right; }
        .resi-title { font-size: 10px; text-transform: uppercase; letter-spacing: 2px; }
        .resi-number { font-size: 18px; font-weight: 800; border: 2px solid #000; padding: 5px 10px; display: inline-block; margin-top: 5px; }

        /* CONTENT GRID */
        .grid-info { display: flex; gap: 20px; margin-bottom: 20px; }
        .box-info { flex: 1; border: 1px solid #000; padding: 0; }
        .box-header { background: #eee; padding: 5px 10px; border-bottom: 1px solid #000; font-weight: 700; text-transform: uppercase; font-size: 10px; }
        .box-content { padding: 10px; }

        /* PENERIMA (DIBUAT LEBIH GEDE BIAR KURIR GAMPANG BACA) */
        .receiver-name { font-size: 16px; font-weight: 800; margin-bottom: 5px; display: block; }
        .receiver-address { font-size: 13px; line-height: 1.4; }
        .receiver-phone { margin-top: 5px; font-weight: 600; display: block; }

        /* TABLE */
        table { width: 100%; border-collapse: collapse; margin-bottom: 20px; font-size: 12px; }
        th { background: #000; color: #fff; text-align: left; padding: 8px; text-transform: uppercase; font-size: 10px; }
        td { border-bottom: 1px solid #ccc; padding: 10px 8px; vertical-align: top; }
        .col-qty { text-align: center; width: 80px; font-weight: bold; }

        /* STATUS BADGE */
        .status-badge {
            border: 2px solid #000; padding: 5px 10px; font-weight: bold; text-transform: uppercase; display: inline-block; font-size: 12px;
        }

        /* FOOTER SIGNATURE */
        .footer { display: flex; margin-top: 40px; border-top: 2px dashed #000; padding-top: 20px; }
        .sign-col { flex: 1; text-align: center; }
        .sign-title { font-weight: bold; margin-bottom: 50px; font-size: 11px; text-transform: uppercase; }
        .sign-line { border-bottom: 1px solid #000; width: 80%; margin: 0 auto; }

        /* PRINT MODE */
        @media print {
            body { background: #fff; padding: 0; margin: 0; }
            .no-print-bar { display: none; }
            .page { margin: 0; border: none; box-shadow: none; width: 100%; }
        }
    </style>
</head>
<body>

    <div class="no-print-bar">
        <button class="btn-print" onclick="window.print()">🖨️ Cetak Surat Jalan</button>
    </div>

    <div class="page">
        {{-- HEADER --}}
        <div class="header">
            <div>
                <div class="logo">{{ $setting->shop_name ?? 'LAUNDRY SYSTEM' }}</div>
                <div class="store-info">
                    {{ $setting->address ?? 'Alamat toko belum diatur.' }}<br>
                    Telp/WA: {{ $setting->phone ?? '-' }}
                </div>
            </div>
            <div class="resi-box">
                <div class="resi-title">DELIVERY ORDER / SURAT JALAN</div>
                <div class="resi-number">{{ $transaction->invoice_code }}</div>
                <div style="font-size: 10px; margin-top: 4px;">{{ date('d M Y, H:i') }}</div>
            </div>
        </div>

        {{-- INFO PENGIRIM & PENERIMA --}}
        <div class="grid-info">
            {{-- PENGIRIM --}}
            <div class="box-info">
                <div class="box-header">PENGIRIM (SENDER)</div>
                <div class="box-content">
                    <strong>{{ $setting->shop_name ?? 'Admin Laundry' }}</strong><br>
                    {{ $setting->phone ?? '-' }}<br>
                    <br>
                    <small>Catatan Kurir:</small><br>
                    <div style="border: 1px dashed #ccc; height: 40px;"></div>
                </div>
            </div>

            {{-- PENERIMA --}}
            <div class="box-info" style="flex: 1.5;"> {{-- Kolom Penerima lebih lebar --}}
                <div class="box-header">PENERIMA (RECEIVER)</div>
                <div class="box-content">
                    <span class="receiver-name">{{ $transaction->customer->name }}</span>
                    <span class="receiver-address">
                        {{ $transaction->pickup_address ?? $transaction->customer->address }}
                    </span>
                    <span class="receiver-phone">📞 {{ $transaction->customer->phone }}</span>
                    
                    @if($transaction->latitude)
                        <div style="margin-top: 8px; font-size: 10px; font-style: italic;">
                            📍 Titik koordinat tersedia di aplikasi
                        </div>
                    @endif
                </div>
            </div>
        </div>

        {{-- DAFTAR BARANG --}}
        <table>
            <thead>
                <tr>
                    <th>DESKRIPSI ITEM / LAYANAN</th>
                    <th class="col-qty">QTY/BERAT</th>
                    <th>KETERANGAN</th>
                </tr>
            </thead>
            <tbody>
                @if($transaction->total_price == 0)
                    {{-- KASUS: JEMPUTAN (BELUM DITIMBANG) --}}
                    <tr>
                        <td style="padding: 20px 10px;">
                            <strong style="font-size: 14px;">PICKUP REQUEST (JEMPUT CUCIAN)</strong><br>
                            Mohon jemput cucian kotor di alamat pelanggan.
                        </td>
                        <td class="col-qty">-</td>
                        <td>{{ $transaction->note ?? 'Hati-hati di jalan' }}</td>
                    </tr>
                @else
                    {{-- KASUS: PENGANTARAN (SUDAH SELESAI) --}}
                    @foreach($transaction->details as $detail)
                    <tr>
                        <td>
                            <strong>{{ $detail->service->name }}</strong>
                            <div style="font-size: 10px; color: #666;">Paket Laundry</div>
                        </td>
                        <td class="col-qty">{{ $detail->qty }} {{ $detail->service->unit }}</td>
                        <td>-</td>
                    </tr>
                    @endforeach
                @endif
            </tbody>
        </table>

        {{-- RINCIAN KEUANGAN DETAIL --}}
        <div style="display: flex; justify-content: flex-end; margin-bottom: 20px;">
            <div style="width: 60%; min-width: 300px;">
                <table style="width: 100%; border: none;">
                    {{-- Subtotal --}}
                    <tr>
                        <td style="padding: 2px 0; border: none; text-align: right; color: #666;">Total Harga Laundry:</td>
                        <td style="padding: 2px 0; border: none; text-align: right; width: 120px; font-weight: 600;">Rp {{ number_format($transaction->subtotal) }}</td>
                    </tr>
                    
                    {{-- Ongkir --}}
                    <tr>
                        <td style="padding: 2px 0; border: none; text-align: right; color: #666;">Biaya Antar-Jemput:</td>
                        <td style="padding: 2px 0; border: none; text-align: right; font-weight: 600;">Rp {{ number_format($transaction->delivery_fee) }}</td>
                    </tr>

                    {{-- Diskon --}}
                    @if($transaction->discount_amount > 0)
                    <tr>
                        <td style="padding: 2px 0; border: none; text-align: right; color: #dc3545;">
                            Diskon {{ $transaction->promo ? '('.$transaction->promo->code.')' : '' }}:
                        </td>
                        <td style="padding: 2px 0; border: none; text-align: right; color: #dc3545; font-weight: 600;">- Rp {{ number_format($transaction->discount_amount) }}</td>
                    </tr>
                    @endif

                    {{-- Garis Pemisah --}}
                    <tr><td colspan="2" style="border-bottom: 1px solid #ddd; padding: 5px 0;"></td></tr>

                    {{-- Grand Total (HITUNG ULANG REALTIME BIAR AKURAT) --}}
                    @php
                        $realTotal = ($transaction->subtotal + $transaction->delivery_fee) - $transaction->discount_amount;
                    @endphp
                    <tr>
                        <td style="padding: 8px 0 2px; border: none; text-align: right; font-weight: bold;">TOTAL TAGIHAN:</td>
                        <td style="padding: 8px 0 2px; border: none; text-align: right; font-weight: bold;">Rp {{ number_format($realTotal) }}</td>
                    </tr>

                    {{-- Sudah Dibayar --}}
                    <tr>
                        <td style="padding: 2px 0; border: none; text-align: right; color: #198754;">
                            Sudah Dibayar (Transfer/DP):
                            <div style="font-size: 9px; color: #888;">via {{ $transaction->payment_method == 'online' ? 'Midtrans/QRIS' : 'Manual/Cash' }}</div>
                        </td>
                        <td style="padding: 2px 0; border: none; text-align: right; color: #198754; font-weight: 600;">- Rp {{ number_format($transaction->paid_amount) }}</td>
                    </tr>

                    {{-- Garis Pemisah Tebal --}}
                    <tr><td colspan="2" style="border-bottom: 2px solid #000; padding: 5px 0;"></td></tr>

                    {{-- SISA TAGIHAN (FINAL) --}}
                    @php $sisa = $realTotal - $transaction->paid_amount; @endphp
                    <tr>
                        <td style="padding: 10px 0; border: none; text-align: right; vertical-align: middle;">
                            <span style="font-weight: 800; font-size: 14px; text-transform: uppercase;">SISA YANG HARUS DITAGIH:</span>
                            @if($sisa > 0)
                                <div style="font-size: 10px; color: #dc3545; font-style: italic;">*Wajib ditarik oleh kurir</div>
                            @endif
                        </td>
                        <td style="padding: 10px 0; border: none; text-align: right; font-size: 18px; font-weight: 800; color: #000; vertical-align: middle;">
                            Rp {{ number_format($sisa) }}
                        </td>
                    </tr>
                </table>
            </div>
        </div>

        {{-- STATUS BADGE BESAR --}}
        <div style="margin-bottom: 30px; text-align: right;">
            @if($sisa <= 100)
                <div style="display: inline-block; padding: 10px 20px; background: #d1e7dd; color: #0f5132; border: 2px solid #0f5132; font-weight: 800; font-size: 16px; transform: rotate(-2deg); border-radius: 8px;">
                    LUNAS / SUDAH DIBAYAR
                </div>
            @else
                <div style="display: inline-block; padding: 10px 20px; background: #fff3cd; color: #856404; border: 2px solid #856404; font-weight: 800; font-size: 16px; transform: rotate(-2deg); border-radius: 8px;">
                    COD / BAYAR DITEMPAT
                </div>
            @endif
        </div>

        {{-- TANDA TANGAN --}}
        <div class="footer">
            <div class="sign-col">
                <div class="sign-title">Diserahkan Oleh</div>
                <div class="sign-line"></div>
                <div style="font-size: 10px; margin-top: 5px;">( Admin / Toko )</div>
            </div>
            <div class="sign-col">
                <div class="sign-title">Kurir Pengantar</div>
                <div class="sign-line"></div>
                <div style="font-size: 10px; margin-top: 5px;">( Driver )</div>
            </div>
            <div class="sign-col">
                <div class="sign-title">Diterima Oleh</div>
                <div class="sign-line"></div>
                <div style="font-size: 10px; margin-top: 5px;">( Nama Jelas )</div>
            </div>
        </div>

        <div style="text-align: center; margin-top: 30px; font-size: 10px; color: #888;">
            Dokumen ini dicetak otomatis oleh sistem {{ $setting->shop_name ?? 'LaundrySystem' }} pada {{ date('d/m/Y H:i:s') }}
        </div>
    </div>

</body>
</html>