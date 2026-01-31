<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use App\Models\TransactionDetail;
use App\Models\Customer;
use App\Models\Service;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth; // Jangan lupa import Auth

class TransactionController extends Controller
{
    // Tampilkan Riwayat Transaksi
    public function index()
    {
        // Ambil data transaksi terbaru (KECUALI DRAFT)
        $transactions = Transaction::with(['customer', 'user', 'courier']) // Tambah relation courier
                        ->where('status', '!=', 'draft') 
                        ->latest()
                        ->get();
        
        // Ambil list driver aktif
        $drivers = \App\Models\User::where('role', 'driver')->get();

        return view('admin.transactions.index', compact('transactions', 'drivers'));
    }

    // Form Transaksi Baru
    public function create()
    {
        $customers = Customer::all(); // Buat dropdown pelanggan
        $services = Service::all();   // Buat dropdown paket
        
        // Generate No Invoice Otomatis (INV-TAHUNBULANTANGGAL-JAMMENIT)
        $invoice_code = 'INV-' . date('Ymd-Hi'); 

        return view('admin.transactions.create', compact('customers', 'services', 'invoice_code'));
    }

    // PROSES SIMPAN (The Magic Happens Here)
    public function store(Request $request)
    {
        // 1. Validasi input
        $request->validate([
            'customer_id' => 'required|exists:customers,id',
            'service_id'  => 'required|array',
            'qty'         => 'required|array',
            'qty.*'       => 'required|numeric|min:1', // Pastikan qty angka & minimal 1
            'promo_code'  => 'nullable|string|exists:promos,code' // Validasi awal kode ada di db
        ]);

        try {
            DB::transaction(function () use ($request) {
                
                // 2. Hitung Subtotal Dulu
                $subtotal = 0;
                $items = []; // Simpan data item sementara

                foreach ($request->service_id as $key => $service_id) {
                    $service = Service::findOrFail($service_id);
                    $qty = $request->qty[$key];
                    $itemSubtotal = $service->price * $qty;
                    
                    $subtotal += $itemSubtotal;
                    $items[] = [
                        'service_id' => $service_id,
                        'qty' => $qty,
                        'price' => $service->price,
                        'subtotal' => $itemSubtotal
                    ];
                }

                // 3. Cek Promo & Hitung Diskon
                $discountAmount = 0;
                $promoId = null;
                $pointsRedeemed = 0;
                $pointDiscount = 0;

                // A. Hitung Promo Dulu
                if ($request->promo_code) {
                    $promo = \App\Models\Promo::where('code', $request->promo_code)->first();
                    
                    if ($promo && $promo->isValid() && $subtotal >= $promo->min_spend) {
                        $promoId = $promo->id;
                        
                        if ($promo->type == 'percentage') {
                            $discountRaw = $subtotal * ($promo->value / 100);
                            if ($promo->max_discount && $discountRaw > $promo->max_discount) {
                                $discountAmount = $promo->max_discount;
                            } else {
                                $discountAmount = $discountRaw;
                            }
                        } else {
                            $discountAmount = $promo->value;
                        }
                    }
                }

                // B. Hitung Tukar Poin (Redeem)
                if ($request->has('use_points') && $request->use_points == '1') {
                    $customer = Customer::findOrFail($request->customer_id);
                    $currentPoints = $customer->points;
                    
                    if ($currentPoints > 0) {
                        // Rate: 1 Poin = Rp 50
                        $maxPointValue = $currentPoints * 50;
                        
                        // Sisa tagihan setelah kena promo
                        $remainingBill = $subtotal - $discountAmount;
                        if ($remainingBill < 0) $remainingBill = 0;

                        // Tentukan berapa rupiah yang dipotong poin
                        if ($maxPointValue >= $remainingBill) {
                            $pointDiscount = $remainingBill; // Bayar Full pakai Poin
                            $pointsRedeemed = ceil($remainingBill / 50); // Poin yang ditarik secukupnya aja
                        } else {
                            $pointDiscount = $maxPointValue; // Habisin semua poin
                            $pointsRedeemed = $currentPoints;
                        }

                        // Tambahkan ke total diskon
                        $discountAmount += $pointDiscount;

                        // Kurangi Poin Pelanggan
                        $customer->decrement('points', $pointsRedeemed);
                        
                        // Tambah catatan auto
                        $request->merge(['note' => $request->note . " [Tukar $pointsRedeemed Poin: -Rp $pointDiscount]"]);
                    }
                }

                // Pastikan diskon gak minus atau melebihi subtotal (Double Check)
                if ($discountAmount > $subtotal) $discountAmount = $subtotal;

                $grandTotal = $subtotal - $discountAmount;

                // 4. Generate Invoice
                $invoice_code = 'INV-' . date('Ymd-His'); 

                // 5. Simpan Kepala Transaksi
                $transaction = Transaction::create([
                    'invoice_code'   => $invoice_code,
                    'customer_id'    => $request->customer_id,
                    'user_id'        => Auth::id(),
                    'subtotal'       => $subtotal,        // <--- Data Baru
                    'discount_amount'=> $discountAmount,  // <--- Data Baru
                    'promo_id'       => $promoId,         // <--- Data Baru
                    'total_price'    => $grandTotal,      // Harga Akhir
                    'status'         => 'pending',
                    'payment_status' => 'unpaid',
                    'delivery_type'  => $request->delivery_type,
                    'delivery_status'=> ($request->delivery_type == 'none') ? 'none' : 'pending',
                    'note'           => $request->note
                ]);

                // 6. Simpan Detail Item
                foreach ($items as $item) {
                    TransactionDetail::create([
                        'transaction_id' => $transaction->id,
                        'service_id'     => $item['service_id'],
                        'qty'            => $item['qty'],
                        'price_per_unit' => $item['price'],
                        'subtotal'       => $item['subtotal']
                    ]);
                }
                
                // Opsional: Catat Log
                \App\Models\TransactionLog::create([
                    'transaction_id' => $transaction->id,
                    'status' => 'pending',
                    'user_id' => Auth::id()
                ]);
            });

            return redirect()->route('transactions.index')->with('success', 'Transaksi berhasil dibuat!');

        } catch (\Exception $e) {
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage())->withInput();
        }
    }


    public function show(Transaction $transaction)
    {
        // Kita load detail item, servicenya, dan log riwayat biar muncul semua
        $transaction->load(['details.service', 'customer', 'logs.user']);
        
        return view('admin.transactions.show', compact('transaction'));
    }

    public function updateStatus(Request $request, Transaction $transaction)
    {
        $request->validate([
            'status' => 'required',
            'payment_status' => 'required'
        ]);

        // Cek status lama sebelum update
        $oldStatus = $transaction->status;
        $newStatus = $request->status;

        // 1. Update Tabel Transaksi
        $transaction->update([
            'status' => $newStatus,
            'payment_status' => $request->payment_status
        ]);

        // --- A. LOGIC PENGURANGAN STOK (INVENTORY DEDUCTION) ---
        // Trigger: Status berubah jadi 'process' (Mulai Cuci)
        if ($newStatus == 'process' && $oldStatus != 'process') {
            foreach ($transaction->details as $detail) {
                // Ambil resep bahan baku dari layanan ini
                foreach ($detail->service->materials as $material) {
                    $qtyNeeded = $material->pivot->quantity * $detail->qty;
                    
                    // Kurangi Stok (Cek dulu cukup gak, kalau minus biarin minus atau error?)
                    // Kita biarkan minus sebagai indikator 'Hutang Stok' biar proses gak macet
                    $material->decrement('stock', $qtyNeeded);
                }
            }
        }

        // --- B. LOGIC POIN REWARD ---
        // Jika status berubah jadi 'done' DAN sebelumnya bukan 'done', maka kasih poin.
        if ($newStatus == 'done' && $oldStatus != 'done') {
            // Hitung poin: Tiap kelipatan 10.000 dapat 1 poin
            $pointsEarned = floor($transaction->total_price / 10000);
            
            if ($pointsEarned > 0) {
                // Tambah poin ke customer
                $transaction->load('customer');
                if ($transaction->customer) {
                    $transaction->customer->increment('points', $pointsEarned);
                }
                
                // Notifikasi Poin
                session()->flash('success_point', "Status Selesai! Pelanggan dapat +$pointsEarned Poin ✨");
            }
        }
        
        // KOREKSI POIN (Rollback)
        if ($oldStatus == 'done' && $newStatus != 'done') {
            $pointsToRevoke = floor($transaction->total_price / 10000);
            $transaction->load('customer');
            if ($transaction->customer) {
                if ($transaction->customer->points >= $pointsToRevoke) {
                    $transaction->customer->decrement('points', $pointsToRevoke);
                } else {
                    $transaction->customer->update(['points' => 0]);
                }
            }
        }

        // 2. Catat di Log
        \App\Models\TransactionLog::create([
            'transaction_id' => $transaction->id,
            'status' => $request->status,
            'user_id' => Auth::id()
        ]);

        return redirect()->back()->with('success', 'Status cucian diperbarui!');
    }

    public function printThermal(Transaction $transaction)
    {
        // Ambil data setting toko buat header struk
        $setting = \App\Models\Setting::first();
        return view('admin.transactions.print_thermal', compact('transaction', 'setting'));
    }

    // Cetak Invoice A4 Resmi
    public function printInvoice(Transaction $transaction)
    {
        $setting = \App\Models\Setting::first();
        // Load relasi biar datanya lengkap di view
        $transaction->load(['details.service', 'customer', 'promo']);
        
        return view('admin.transactions.print_invoice', compact('transaction', 'setting'));
    }

    public function edit($id)
    {
        // Ambil data transaksi dengan detail service
        $transaction = Transaction::with(['details.service', 'customer'])->findOrFail($id);
        
        // Tampilkan halaman form edit (Timbang & Harga)
        return view('admin.transactions.edit', compact('transaction'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'status' => 'required',
            'delivery_status' => 'nullable', // Bisa null kalau delivery_type = none
            'payment_status' => 'required',
            'qty' => 'nullable|array', // Input qty (bisa berat atau jumlah pcs)
        ]);

        $transaction = Transaction::with(['details.service', 'promo'])->findOrFail($id);
        
        // 1. HITUNG ULANG TOTAL (Berdasarkan Input Admin)
        // Kita tidak menimpa service_id, hanya mengupdate Qty/Berat dari item yang sudah ada.
        
        $newSubtotal = 0;

        // Loop setiap detail transaksi yang ada
        foreach ($transaction->details as $detail) {
            // Ambil input qty baru dari form (name="qty[detail_id]")
            // Jika tidak ada input (misal admin gak ubah), pakai qty lama
            $newQty = $request->input('qty.' . $detail->id, $detail->qty);
            
            // Validasi: Qty minimal 0.1 (buat berat) atau 1 (buat satuan)
            if ($newQty <= 0) $newQty = $detail->qty;

            // Hitung Subtotal Item
            $itemSubtotal = $newQty * $detail->price_per_unit;
            
            // Update Data Detail di Database
            $detail->update([
                'qty' => $newQty,
                'subtotal' => $itemSubtotal
            ]);

            $newSubtotal += $itemSubtotal;
        }

        // 2. HITUNG ULANG DISKON (Jika ada promo)
        $discountAmount = 0;
        if ($transaction->promo_id && $transaction->promo) {
            $promo = $transaction->promo;
            
            // Cek lagi syarat min spend
            if ($newSubtotal >= $promo->min_spend) {
                if ($promo->type == 'percentage') {
                    $discountRaw = $newSubtotal * ($promo->value / 100);
                    $discountAmount = ($promo->max_discount && $discountRaw > $promo->max_discount) 
                                      ? $promo->max_discount 
                                      : $discountRaw;
                } else {
                    $discountAmount = $promo->value;
                }
                // Diskon gak boleh lebih gede dari subtotal
                if ($discountAmount > $newSubtotal) $discountAmount = $newSubtotal;
            } else {
                // Kalau setelah edit ternyata totalnya jadi di bawah min_spend, diskon hangus
                $discountAmount = 0;
            }
        }

        // PERBAIKAN: Jangan masukkan Delivery Fee ke Total Price (Sesuai request user)
        // Ongkir dianggap terpisah atau sudah beres.
        $finalTotalPrice = $newSubtotal - $discountAmount;

        // --- LOGIKA STATUS PENGIRIMAN OTOMATIS (AUTO-PILOT) ---
        $deliveryStatus = $request->delivery_status ?? $transaction->delivery_status;
        $finalStatus = $request->status; // Tampung status pilihan admin
        $oldStatus = $transaction->getOriginal('status'); // Ambil status lama
        
        // 1. Jika BERUBAH jadi 'process' -> Pickup Selesai
        if ($request->status == 'process' && $oldStatus != 'process') {
            $deliveryStatus = 'delivered'; 
        }
        // 2. Jika BERUBAH jadi 'ready' (Siap Antar) -> Reset Delivery jadi Pending (Cari Kurir Antar)
        elseif ($request->status == 'ready' && $oldStatus != 'ready' && $transaction->delivery_type != 'pickup') {
            $deliveryStatus = 'pending';
        }
        // 3. Jika 'done' (Selesai) -> Berarti Pengantaran Selesai
        elseif ($request->status == 'done') {
            $deliveryStatus = 'delivered';
        }

        // --- LOGIKA TERBALIK: KURIR SAMPAI -> OTOMATIS SELESAI ---
        // Jika kurir diset 'Sampai Tujuan' saat fase pengantaran (Ready), otomatis 'Done'
        if ($deliveryStatus == 'delivered' && $finalStatus == 'ready') {
            $finalStatus = 'done';
        }

        // --- AUTO INVENTORY DEDUCTION (Saat Edit) ---
        // Cek jika status berubah jadi 'process'
        if ($finalStatus == 'process' && $oldStatus != 'process') {
            foreach ($transaction->details as $detail) {
                foreach ($detail->service->materials as $material) {
                    $qtyNeeded = $material->pivot->quantity * $detail->qty;
                    $material->decrement('stock', $qtyNeeded);
                }
            }
        }

        // 3. UPDATE TRANSAKSI UTAMA
        $transaction->update([
            'subtotal' => $newSubtotal, 
            'discount_amount' => $discountAmount, 
            'total_price' => $finalTotalPrice,
            'status' => $finalStatus, // Pakai variable finalStatus
            'delivery_status' => $deliveryStatus, 
            'payment_status' => $request->payment_status,
            'user_id' => auth()->id() 
        ]);

        // LOGIC POIN REWARD (Copy dari updateStatus biar konsisten)
        // Jika status berubah jadi 'done', kasih poin.
        if ($request->status == 'done' && $transaction->getOriginal('status') != 'done') {
            $pointsEarned = floor($finalTotalPrice / 10000);
            if ($pointsEarned > 0) {
                $transaction->load('customer'); // Load relasi
                if ($transaction->customer) {
                    $transaction->customer->increment('points', $pointsEarned);
                }
            }
        }
        // KOREKSI POIN (Rollback - Jika batal selesai)
        elseif ($transaction->getOriginal('status') == 'done' && $request->status != 'done') {
            $pointsToRevoke = floor($transaction->total_price / 10000);
            $transaction->load('customer');
            if ($transaction->customer) {
                 if ($transaction->customer->points >= $pointsToRevoke) {
                    $transaction->customer->decrement('points', $pointsToRevoke);
                } else {
                    $transaction->customer->update(['points' => 0]);
                }
            }
        }

        return redirect()->route('transactions.index')->with('success', 'Transaksi berhasil diperbarui!');
    }
    // Cetak Surat Jalan (Delivery Slip)
    public function printDelivery($id)
    {
        $transaction = Transaction::with(['customer', 'details'])->findOrFail($id);
        $setting = \App\Models\Setting::first(); // Ambil data toko buat Kop Surat

        return view('admin.transactions.print_delivery', compact('transaction', 'setting'));
    }

    // Assign Kurir (Penugasan Driver)
    public function assignCourier(Request $request, Transaction $transaction)
    {
        $request->validate([
            'courier_id' => 'required|exists:users,id'
        ]);

        $transaction->update([
            'courier_id' => $request->courier_id
        ]);

        return back()->with('success', 'Kurir berhasil ditugaskan! Pesanan akan muncul di aplikasi Driver.');
    }
}