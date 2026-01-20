<?php

namespace App\Exports;

use App\Models\Transaction;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class TransactionExport implements FromCollection, WithHeadings, ShouldAutoSize, WithStyles
{
    public function collection()
    {
        // 1. Ambil Semua Data
        $transactions = Transaction::with('customer')->latest()->get();

        // 2. Siapkan Wadah
        $output = collect();

        // 3. Masukkan data per baris
        foreach ($transactions as $trx) {
            $output->push([
                'tanggal'   => $trx->created_at->format('d/m/Y H:i'),
                'invoice'   => $trx->invoice_code,
                'pelanggan' => $trx->customer->name,
                'harga'     => 'Rp ' . number_format($trx->total_price, 0, ',', '.'), 
                'status'    => $this->mapStatus($trx->status),
                'bayar'     => $trx->payment_status == 'paid' ? 'Lunas' : 'Belum Lunas',
            ]);
        }

        // 4. Hitung DUIT & JUMLAH ORANG
        $totalLunas   = $transactions->where('payment_status', 'paid')->sum('total_price');
        $countLunas   = $transactions->where('payment_status', 'paid')->count(); // Hitung Orang Lunas

        $totalPiutang = $transactions->where('payment_status', 'unpaid')->sum('total_price');
        $countPiutang = $transactions->where('payment_status', 'unpaid')->count(); // Hitung Orang Ngutang

        // 5. Tambahkan Baris Jeda
        $output->push(['', '', '', '', '', '']);

        // 6. Baris TOTAL LUNAS
        $output->push([
            '', '',
            'TOTAL PENDAPATAN (LUNAS)', 
            'Rp ' . number_format($totalLunas, 0, ',', '.'),
            $countLunas . ' Transaksi', // Muncul di Kolom E
            ''
        ]);

        // 7. Baris TOTAL PIUTANG
        $output->push([
            '', '',
            'TOTAL BELUM LUNAS (PIUTANG)', 
            'Rp ' . number_format($totalPiutang, 0, ',', '.'),
            $countPiutang . ' Transaksi', // Muncul di Kolom E
            ''
        ]);

        return $output;
    }

    public function headings(): array
    {
        return [
            'Tanggal Transaksi', 'Kode Invoice', 'Nama Pelanggan', 
            'Total Harga', 'Status Laundry', 'Status Pembayaran',
        ];
    }

    public function styles(Worksheet $sheet)
    {
        $lastRow = $sheet->getHighestRow(); // Baris Piutang (Paling Bawah)
        $prevRow = $lastRow - 1;            // Baris Lunas (Atasnya)

        return [
            // 1. Header Tebal
            1 => ['font' => ['bold' => true, 'size' => 12]],
            
            // 2. Baris Lunas (Kuning)
            $prevRow => [
                'font' => ['bold' => true],
                'fill' => ['fillType' => 'solid', 'startColor' => ['argb' => 'FFFFFF00']] 
            ],

            // 3. Baris Piutang (Merah Muda) - Teks Putih
            $lastRow => [
                'font' => ['bold' => true, 'color' => ['argb' => 'FFFFFFFF']], 
                'fill' => ['fillType' => 'solid', 'startColor' => ['argb' => 'FFFF0000']] 
            ],
        ];
    }

    private function mapStatus($status)
    {
        switch ($status) {
            case 'pending': return 'Menunggu';
            case 'process': return 'Sedang Cuci';
            case 'ready':   return 'Siap Ambil';
            case 'done':    return 'Selesai';
            default:        return $status;
        }
    }
}