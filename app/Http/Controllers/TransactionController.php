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
        // Ambil data transaksi terbaru dengan relasi customer & user
        $transactions = Transaction::with(['customer', 'user'])->latest()->get();
        return view('admin.transactions.index', compact('transactions'));
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

                if ($request->promo_code) {
                    $promo = \App\Models\Promo::where('code', $request->promo_code)->first();
                    
                    // Validasi Logis Promo
                    if ($promo && $promo->isValid() && $subtotal >= $promo->min_spend) {
                        $promoId = $promo->id;
                        
                        if ($promo->type == 'percentage') {
                            $discountRaw = $subtotal * ($promo->value / 100);
                            // Cek Max Discount
                            if ($promo->max_discount && $discountRaw > $promo->max_discount) {
                                $discountAmount = $promo->max_discount;
                            } else {
                                $discountAmount = $discountRaw;
                            }
                        } else {
                            $discountAmount = $promo->value;
                        }

                        // Pastikan diskon gak minus
                        if ($discountAmount > $subtotal) $discountAmount = $subtotal;
                    }
                }

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

        // 1. Update Tabel Transaksi
        $transaction->update([
            'status' => $request->status,
            'payment_status' => $request->payment_status
        ]);

        // 2. Catat di Log (Biar ketahuan siapa yang ubah)
        // Pastikan Model TransactionLog sudah dibuat ya (di tahap awal)
        \App\Models\TransactionLog::create([
            'transaction_id' => $transaction->id,
            'status' => $request->status,
            'user_id' => Auth::id() // Siapa yang klik
        ]);

        return redirect()->back()->with('success', 'Status cucian berhasil diperbarui!');
    }

    public function printThermal(Transaction $transaction)
    {
        // Ambil data setting toko buat header struk
        $setting = \App\Models\Setting::first();
        return view('admin.transactions.print_thermal', compact('transaction', 'setting'));
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
            'total_price' => 'required|numeric',
            'weight' => 'nullable|numeric|min:0.1',
            'status' => 'required',
            'delivery_status' => 'required' 
        ]);

        $transaction = Transaction::with('details')->findOrFail($id);

        // Logic Update atau Create Detail (Self-Healing & Auto Calc)
        $finalTotalPrice = $request->total_price; // Default pakai input form

        if ($request->has('weight')) {
            $weight = $request->weight;
            
            if ($transaction->details->isEmpty()) {
                // Buat baru
                $defaultService = \App\Models\Service::first();
                if ($defaultService) {
                    $finalTotalPrice = $weight * $defaultService->price; // HITUNG DI BACKEND
                    
                    $transaction->details()->create([
                        'service_id' => $defaultService->id,
                        'qty' => $weight,
                        'price_per_unit' => $defaultService->price,
                        'subtotal' => $finalTotalPrice
                    ]);
                }
            } else {
                // Update detail
                $detail = $transaction->details->first();
                
                // Pastikan harga paket pakai harga yang tersimpan di detail (history price) atau update ke harga terbaru service?
                // Idealnya pakai harga detail (kontrak awal), tapi kalau mau revisi total, pakai harga detail.
                $pricePerKg = $detail->price_per_unit;
                $finalTotalPrice = $weight * $pricePerKg; // HITUNG DI BACKEND

                $detail->update([
                    'qty' => $weight,
                    'subtotal' => $finalTotalPrice
                ]);
            }
        }

        // Update Transaksi Utama dengan hasil hitungan backend
        $transaction->update([
            'total_price' => $finalTotalPrice,
            'status' => $request->status,
            'delivery_status' => $request->delivery_status,
            'payment_status' => $request->payment_status,
            'user_id' => auth()->id()
        ]);

        return redirect()->route('transactions.index')->with('success', 'Transaksi berhasil diperbarui!');
    }
    // Cetak Surat Jalan (Delivery Slip)
    public function printDelivery($id)
    {
        $transaction = Transaction::with(['customer', 'details'])->findOrFail($id);
        $setting = \App\Models\Setting::first(); // Ambil data toko buat Kop Surat

        return view('admin.transactions.print_delivery', compact('transaction', 'setting'));
    }
}