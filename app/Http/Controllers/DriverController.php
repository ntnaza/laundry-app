<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DriverController extends Controller
{
    /**
     * Menampilkan daftar tugas aktif (Jemput / Antar)
     */
    public function index()
    {
        $userId = Auth::id();

        // Ambil transaksi yang ditugaskan ke driver ini DAN belum selesai
        $tasks = Transaction::with(['customer', 'user'])
            ->where('courier_id', $userId)
            ->whereIn('delivery_status', ['pending', 'on_the_way']) // Status aktif
            ->orderBy('created_at', 'desc')
            ->get();

        return view('driver.index', compact('tasks'));
    }

    /**
     * Menampilkan riwayat tugas yang sudah selesai
     */
    public function history()
    {
        $userId = Auth::id();

        $tasks = Transaction::with(['customer'])
            ->where('courier_id', $userId)
            ->where('delivery_status', 'delivered') // Status selesai
            ->orderBy('updated_at', 'desc')
            ->paginate(10);

        return view('driver.history', compact('tasks'));
    }

    /**
     * Update status pengiriman (Jalan / Sampai)
     */
    public function updateStatus(Request $request, $id)
    {
        $transaction = Transaction::findOrFail($id);

        // Keamanan: Pastikan yang update adalah driver yang bertugas
        if ($transaction->courier_id != Auth::id()) {
            abort(403, 'Anda tidak memiliki akses ke tugas ini.');
        }

        $request->validate([
            'delivery_status' => 'required|in:pending,on_the_way,delivered',
            'payment_collect' => 'nullable|numeric' // Jika terima uang cash
        ]);

        $data = [
            'delivery_status' => $request->delivery_status
        ];

        // Jika driver menerima uang (COD) saat pengantaran
        if ($request->has('payment_collect') && $request->payment_collect > 0) {
            $data['paid_amount'] = ($transaction->paid_amount ?? 0) + $request->payment_collect;
            
            // Cek apakah lunas?
            if ($data['paid_amount'] >= $transaction->total_price) {
                $data['payment_status'] = 'paid';
            }
        }

        // --- LOGIKA BARU: BEDAKAN JEMPUT (PICKUP) vs ANTAR (DELIVERY) ---
        if ($request->delivery_status == 'delivered') {
            
            // SKENARIO A: FASE PENJEMPUTAN (Barang dari Customer -> Toko)
            // Terjadi jika tipe 'pickup' ATAU tipe 'both' tapi statusnya masih baru (pending)
            if ($transaction->delivery_type == 'pickup' || ($transaction->delivery_type == 'both' && $transaction->status == 'pending')) {
                
                // Status Kurir: Delivered (Sampai di Toko)
                $data['delivery_status'] = 'delivered';
                
                // PENTING: Status Laundry JANGAN diubah jadi 'process' dulu.
                // Biarkan tetap 'pending' agar Admin bisa menimbang & update harga dulu.
                // $data['status'] = 'process'; <--- INI DIHAPUS BIAR GAK MUNCUL TOMBOL BAYAR
                
                // Note: Poin TIDAK diberikan disini.
            }

            // SKENARIO B: FASE PENGANTARAN (Barang dari Toko -> Customer)
            // Terjadi jika tipe 'delivery' ATAU tipe 'both' tapi statusnya sudah siap (ready)
            elseif ($transaction->delivery_type == 'delivery' || ($transaction->delivery_type == 'both' && $transaction->status == 'ready')) {
                
                // Status Kurir: Delivered (Sampai di Tangan Customer)
                $data['delivery_status'] = 'delivered';
                
                // Status Laundry: DONE (Selesai Total)
                $data['status'] = 'done';

                // --- LOGIC POIN REWARD (Hanya saat Antar Selesai) ---
                if ($transaction->getOriginal('status') != 'done') {
                    $pointsEarned = floor($transaction->total_price / 10000); // 1 Poin tiap 10rb
                    
                    if ($pointsEarned > 0) {
                        $transaction->load('customer');
                        if ($transaction->customer) {
                            $transaction->customer->increment('points', $pointsEarned);
                        }
                    }
                }
            }
        }

        $transaction->update($data);

        return back()->with('success', 'Status tugas berhasil diperbarui!');
    }
}