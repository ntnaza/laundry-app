<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Transaction; 
use App\Models\Service;
use App\Models\Customer;
use App\Models\Testimonial;
use App\Models\Promo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Midtrans\Config;
use Midtrans\Snap;
use Illuminate\Support\Facades\Log;

class OrderController extends Controller
{
    public function index()
    {
        $userId = Auth::id();
        $userPhone = Auth::user()->phone;
        $customerIds = Customer::where('phone', $userPhone)->pluck('id');

        $myOrders = Transaction::with(['details.service', 'testimonial'])
                        ->where(function($query) use ($userId, $customerIds) {
                            $query->where('app_user_id', $userId);
                            if ($customerIds->isNotEmpty()) {
                                $query->orWhereIn('customer_id', $customerIds);
                            }
                        })
                        ->latest()
                        ->get();

        // AMBIL PROMO AKTIF
        $activePromos = Promo::where('start_date', '<=', now())
                            ->where(function($q) {
                                $q->whereNull('end_date')
                                  ->orWhere('end_date', '>=', now());
                            })
                            ->latest()
                            ->limit(3) // Ambil 3 promo terbaru
                            ->get();

        // AMBIL SETTING (Jam Ops, WA, dll)
        $setting = \App\Models\Setting::first();

        return view('customer.dashboard', compact('myOrders', 'activePromos', 'setting'));
    }

    public function create(Request $request)
    {
        $services = Service::all(); 
        $setting = \App\Models\Setting::first(); // Ambil data setting buat koordinat toko
        
        // Mode RESUME: Cek apakah ada ID transaksi yang mau dilanjutin
        $pendingTransaction = null;
        if ($request->has('resume_id')) {
            $pendingTransaction = Transaction::where('app_user_id', Auth::id())
                                    ->where('id', $request->resume_id)
                                    ->where('status', 'pending') // Harus sudah bayar ongkir (pending)
                                    ->first();
        }

        return view('customer.order_create', compact('services', 'setting', 'pendingTransaction'));
    }

    public function store(Request $request)
    {
        // TAHAP 1: BAYAR ONGKIR (LOCK LOCATION)
        $request->validate([
            'phone'          => 'required|numeric', // Tambah validasi HP
            'distance'       => 'required|numeric|min:0.1',
            'delivery_fee'   => 'required|numeric',
            'latitude'       => 'required',
            'longitude'      => 'required'
        ]);

        // VALIDASI JARAK MAKSIMAL (Backend Security)
        if ($request->distance > 10) {
            if ($request->ajax()) {
                return response()->json(['status' => 'error', 'message' => 'Maaf, lokasi Anda terlalu jauh (> 10 KM). Kami belum bisa menjangkau area ini.'], 422);
            }
            return back()->with('error', 'Jarak terlalu jauh!');
        }

        try {
            $transactionId = null;
            $newTransaction = null; 

            DB::transaction(function () use ($request, &$transactionId, &$newTransaction) {
                
                // Cari/Buat Customer berdasarkan HP yang diinput
                $customer = Customer::firstOrCreate(
                    ['phone' => $request->phone], 
                    ['name' => Auth::user()->name, 'address' => $request->pickup_address] // Simpan alamat juga
                );
                
                // Jika customer sudah ada tapi alamat masih '-' atau beda, update alamatnya
                if ($customer->address == '-' || $customer->address != $request->pickup_address) {
                    $customer->update(['address' => $request->pickup_address]);
                }

                $deliveryFee = $request->delivery_fee;
                $invoice = 'TRX-' . mt_rand(10000, 99999);

                // Buat Transaksi DRAFT
                $transaction = Transaction::create([
                    'invoice_code'   => $invoice,
                    'customer_id'    => $customer->id,
                    'app_user_id'    => Auth::id(),
                    'total_price'    => $deliveryFee,
                    'subtotal'       => 0,
                    'delivery_fee'   => $deliveryFee,
                    'distance'       => $request->distance,
                    'status'         => 'draft', 
                    'payment_status' => 'unpaid',  
                    'payment_method' => 'online',
                    'pickup_address' => $request->pickup_address, // SIMPAN ALAMAT ASLI DARI INPUT
                    'latitude'       => $request->latitude,
                    'longitude'      => $request->longitude,
                    'delivery_type'  => 'both',
                    'delivery_status'=> 'pending'
                ]);

                $transactionId = $transaction->id;
                $newTransaction = $transaction; 
            });

            // SETUP MIDTRANS UNTUK DP
            Config::$serverKey = config('midtrans.server_key');
            Config::$isProduction = config('midtrans.is_production');
            Config::$isSanitized = true;
            Config::$is3ds = true;

            $amountToPay = $newTransaction->delivery_fee;
            if ($amountToPay < 100) $amountToPay = 100;

            $midtransOrderId = $newTransaction->invoice_code . '-DP-' . time();
            
            $phone = preg_replace('/[^0-9]/', '', Auth::user()->phone);
            if(empty($phone)) $phone = '08123456789';

            $params = [
                'transaction_details' => [
                    'order_id' => $midtransOrderId,
                    'gross_amount' => (int) $amountToPay,
                ],
                'customer_details' => [
                    'first_name' => substr(Auth::user()->name, 0, 20),
                    'phone' => $phone,
                ],
                'credit_card' => ['secure' => true]
            ];

            $snapToken = Snap::getSnapToken($params);
            $newTransaction->update(['snap_token' => $snapToken]);

            // Return Token ke Frontend
            return response()->json([
                'status' => 'success',
                'snap_token' => $snapToken,
                'transaction_id' => $newTransaction->id
            ]);

        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'message' => $e->getMessage()], 500);
        }
    }

    public function complete(Request $request, $id)
    {
        $request->validate([
            'pickup_address' => 'required|string',
            'payment_method' => 'required|in:online,cash', // Validasi metode bayar
            'items'          => 'required|array|min:1',
            'items.*.service_id' => 'required|exists:services,id',
            'items.*.qty'    => 'required|numeric|min:0.1',
            'promo_code'     => 'nullable|string|exists:promos,code'
        ]);

        try {
            DB::transaction(function () use ($request, $id) {
                $transaction = Transaction::where('app_user_id', Auth::id())->findOrFail($id);

                // Update Alamat Customer
                $transaction->customer->update(['address' => $request->pickup_address]);

                // Hitung Ulang Total
                $subtotal = 0;
                $detailsData = []; 

                foreach ($request->items as $item) {
                    $service = Service::find($item['service_id']);
                    $qty = $item['qty'];
                    $price = $service->price;
                    $itemSubtotal = $price * $qty;
                    $subtotal += $itemSubtotal;
                    
                    // Simpan ke DB
                    $transaction->details()->create([
                        'service_id' => $service->id,
                        'qty' => $qty,
                        'price_per_unit' => $price,
                        'subtotal' => $itemSubtotal
                    ]);
                }

                // Cek Promo
                $discountAmount = 0;
                $promoId = null;
                if ($request->promo_code) {
                    $promo = Promo::where('code', $request->promo_code)->first();
                    if ($promo && $promo->isValid() && $subtotal >= $promo->min_spend) {
                        $promoId = $promo->id;
                        $discountRaw = ($promo->type == 'percentage') ? $subtotal * ($promo->value / 100) : $promo->value;
                        $discountAmount = ($promo->max_discount && $discountRaw > $promo->max_discount) ? $promo->max_discount : $discountRaw;
                        if ($discountAmount > $subtotal) $discountAmount = $subtotal;
                    }
                }

                $grandTotal = ($subtotal + $transaction->delivery_fee) - $discountAmount;

                // LOGIKA RESET STATUS PEMBAYARAN
                // Karena ada tambahan item, tagihan naik. Jadi status harus balik 'unpaid'.
                $paymentStatus = $transaction->payment_status;
                $snapToken = $transaction->snap_token;

                if ($grandTotal > $transaction->paid_amount) {
                    $paymentStatus = 'unpaid';
                    $snapToken = null; // Hapus token lama
                }

                // Update Transaksi Jadi FINAL
                $transaction->update([
                    'subtotal'       => $subtotal,
                    'discount_amount'=> $discountAmount,
                    'total_price'    => $grandTotal,
                    'pickup_address' => $request->pickup_address,
                    'note'           => $request->note,
                    'promo_id'       => $promoId,
                    'payment_status' => $paymentStatus, 
                    'payment_method' => $request->payment_method, // <--- UPDATE METODE BAYAR (CASH/ONLINE)
                    'snap_token'     => $snapToken,
                ]);
            });

            return response()->json([
                'status' => 'success',
                'redirect_url' => route('customer.dashboard')
            ]);

        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'message' => $e->getMessage()], 500);
        }
    }

    public function pay(Request $request, $id)
    {
        $transaction = Transaction::with('customer')->where('app_user_id', Auth::id())->findOrFail($id);

        Config::$serverKey = config('midtrans.server_key');
        Config::$isProduction = config('midtrans.is_production');
        Config::$isSanitized = true;
        Config::$is3ds = true;

        // 1. Tentukan Berapa yang Harus Dibayar
        $amountToPay = 0;
        $paymentTypeSuffix = ''; 

        // LOGIKA BARU:
        // 1. Jika Status DRAFT (Baru Buat) -> Bayar Ongkir (DP)
        if ($transaction->status == 'draft') {
            $amountToPay = $transaction->delivery_fee;
            $paymentTypeSuffix = '-DP'; 
        } 
        // 2. Jika Status SUDAH DIPROSES (Bukan Draft) -> Pelunasan
        else {
            $amountToPay = $transaction->total_price - $transaction->paid_amount;
            $paymentTypeSuffix = '-FINAL';
        }

        // Safety: Minimal bayar 100 perak (Aturan Midtrans)
        if ($amountToPay < 100) {
            // Kalau tagihan 0 atau negatif, anggap lunas (skip midtrans)
            if ($amountToPay <= 0 && $request->ajax()) {
                return response()->json(['error' => 'Tagihan sudah lunas atau 0. Hubungi admin.']);
            }
            $amountToPay = 100;
        }

        // REVISI: GENERATE TOKEN BARU
        // Selalu generate baru untuk pelunasan (-FINAL) untuk menghindari token expired/invalid amount
        if (!$transaction->snap_token || $transaction->status != 'draft') {
            
            $phone = '08123456789'; // Default fallback
            if ($transaction->customer && $transaction->customer->phone) {
                $phone = preg_replace('/[^0-9]/', '', $transaction->customer->phone);
            }
            
            // Order ID Unik: INV-123-FINAL-TIMESTAMP
            $midtransOrderId = $transaction->invoice_code . $paymentTypeSuffix . '-' . time();

            $params = [
                'transaction_details' => [
                    'order_id' => $midtransOrderId,
                    'gross_amount' => (int) $amountToPay,
                ],
                'customer_details' => [
                    'first_name' => substr($transaction->customer->name ?? 'Guest', 0, 20),
                    'phone' => $phone,
                ],
                // Item details opsional, kita skip biar aman dari selisih pembulatan
            ];

            try {
                // LOGGING SEBELUM REQUEST
                Log::info('MIDTRANS REQUEST (PAY):', ['order_id' => $midtransOrderId, 'amount' => $amountToPay]);
                
                $snapToken = Snap::getSnapToken($params);
                $transaction->update(['snap_token' => $snapToken]);
                
            } catch (\Exception $e) {
                // LOGGING ERROR
                Log::error('MIDTRANS ERROR (PAY): ' . $e->getMessage());
                
                if ($request->ajax()) {
                    return response()->json(['error' => $e->getMessage(), 'debug_key_used' => substr(config('midtrans.server_key'), 0, 5) . '...'], 500);
                }
                return back()->with('error', 'Midtrans Error: ' . $e->getMessage());
            }
        }

        // Kalau dipanggil lewat AJAX (tombol bayar dashboard), balikin JSON
        if ($request->ajax()) {
            return response()->json(['snap_token' => $transaction->snap_token]);
        }

        // Kirim variable amountToPay ke view biar view tau ini bayar apa
        return view('customer.payment', compact('transaction', 'amountToPay'));
    }

    public function callback(Request $request)
    {
        // 1. LOGGING DEBUG (Cek apakah Midtrans masuk)
        Log::info('CALLBACK MIDTRANS MASUK!', [
            'all_data' => $request->all(),
            'headers' => $request->headers->all()
        ]);

        $serverKey = config('midtrans.server_key');
        // Validasi Signature Key manual untuk keamanan
        $hashed = hash("sha512", $request->order_id.$request->status_code.$request->gross_amount.$serverKey);

        if ($hashed == $request->signature_key) {
            
            // Format order_id dari kita: "INV-CODE-DP-TIMESTAMP" atau "INV-CODE-FINAL-TIMESTAMP"
            // Kita perlu ambil INV-CODE nya saja.
            // Logika: Ambil semua bagian sebelum suffix (-DP atau -FINAL) dan timestamp
            
            $rawOrderId = $request->order_id;
            // Hapus Timestamp di belakang (Pisahkan by dash terakhir)
            $temp = explode('-', $rawOrderId);
            array_pop($temp); // Buang timestamp
            
            // Cek apakah ada suffix DP atau FINAL
            $lastPart = end($temp);
            if ($lastPart === 'DP' || $lastPart === 'FINAL') {
                array_pop($temp); // Buang suffix
            }
            
            $invoiceCode = implode('-', $temp); // Gabung ulang jadi Invoice Asli
            
            $transaction = Transaction::where('invoice_code', $invoiceCode)->first();
            
            if (!$transaction) {
                Log::error('TRANSAKSI TIDAK DITEMUKAN: ' . $invoiceCode . ' (Raw: ' . $rawOrderId . ')');
                return response()->json(['status' => 'error', 'message' => 'Transaction not found'], 404);
            }

            // A. PEMBAYARAN SUKSES
            if ($request->transaction_status == 'capture' || $request->transaction_status == 'settlement') {
                
                $amountPaid = (float) $request->gross_amount;
                $newPaidAmount = $transaction->paid_amount + $amountPaid;

                // Update Uang Masuk
                $transaction->update([
                    'paid_amount' => $newPaidAmount,
                    'payment_method' => $request->payment_type
                ]);

                // LOGIKA STATUS LUNAS
                // 1. Jika ini pembayaran DP Ongkir (dan status masih DRAFT)
                if ($transaction->status == 'draft' && $newPaidAmount >= $transaction->delivery_fee) {
                    // DRAFT -> PENDING (Resmi Masuk Order)
                    $transaction->update(['status' => 'pending']);
                    Log::info('ORDER OFFICIAL (DRAFT -> PENDING): ' . $invoiceCode);
                }

                // 2. Jika Total Lunas (Semua tagihan terbayar)
                // Pakai toleransi 100 perak (floating point issue)
                if ($newPaidAmount >= ($transaction->total_price - 100)) {
                    $transaction->update(['payment_status' => 'paid']);
                    Log::info('TRANSAKSI LUNAS FULL: ' . $invoiceCode);
                }
            
            // B. PEMBAYARAN KADALUWARSA
            } elseif ($request->transaction_status == 'expire') {
                // Hanya batalkan jika BELUM ada uang masuk sama sekali (DP Gagal)
                if ($transaction->paid_amount == 0) {
                    $transaction->update([
                        'status' => 'canceled', 
                        'payment_status' => 'expired'
                    ]);
                    Log::info('DP EXPIRED (AUTO CANCEL): ' . $invoiceCode);
                }

            // C. PEMBAYARAN DIBATALKAN USER
            } elseif ($request->transaction_status == 'cancel' || $request->transaction_status == 'deny') {
                if ($transaction->paid_amount == 0) {
                    $transaction->update([
                        'status' => 'canceled',
                        'payment_status' => 'failed'
                    ]);
                }
            }

        } else {
            Log::error('SIGNATURE KEY TIDAK COCOK!');
        }
        return response()->json(['status' => 'ok']);
    }

    public function storeReview(Request $request, $transactionId)
    {
        $request->validate([
            'rate' => 'required|integer|min:1|max:5',
            'content' => 'required|string|max:500'
        ]);

        $transaction = Transaction::where('user_id', Auth::id())->where('status', 'done')->findOrFail($transactionId);

        if (Testimonial::where('transaction_id', $transaction->id)->exists()) {
            return back()->with('error', 'Sudah diulas.');
        }

        Testimonial::create([
            'user_id' => Auth::id(),
            'transaction_id' => $transaction->id,
            'rate' => $request->rate,
            'content' => $request->content
        ]);

        return back()->with('success', 'Terima kasih!');
    }

    public function checkStatus(Request $request)
    {
        // Polling untuk update status
        $lastCheck = $request->query('last_check', now());
        
        $updatedOrder = Transaction::where('app_user_id', Auth::id())
                        ->where('updated_at', '>', $lastCheck)
                        ->latest('updated_at')
                        ->first();

        if ($updatedOrder) {
            return response()->json([
                'has_update' => true,
                'status' => $updatedOrder->status,
                'invoice' => $updatedOrder->invoice_code,
                'delivery_status' => $updatedOrder->delivery_status,
                'timestamp' => $updatedOrder->updated_at->toDateTimeString()
            ]);
        }

        return response()->json(['has_update' => false]);
    }
}
