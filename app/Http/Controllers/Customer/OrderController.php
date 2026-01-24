<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Transaction; 
use App\Models\Service; // [REVISI: Kita pakai Model Service yang sudah ada]
use App\Models\Customer;
use App\Models\Testimonial;
use App\Models\Promo; // <--- Import Model
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
    public function index()
    {
        $myOrders = Transaction::with(['details.service', 'testimonial'])
                        ->where('user_id', Auth::id())
                        ->latest()
                        ->get();

        return view('customer.dashboard', compact('myOrders'));
    }

    public function create()
    {
        $services = Service::all(); 
        return view('customer.order_create', compact('services'));
    }

    public function store(Request $request)
    {
        // 1. Validasi Input
        $request->validate([
            'phone'          => 'required|numeric', 
            'pickup_address' => 'required|string|max:255',
            'delivery_type'  => 'required|in:pickup,delivery',
            'promo_code'     => 'nullable|string|exists:promos,code', // Cek kode ada di DB
            'latitude'       => 'nullable', 
            'longitude'      => 'nullable',
            'note'           => 'nullable|string|max:500'
        ]);

        try {
            DB::transaction(function () use ($request) {
                // 2. Simpan/Cek Data Customer
                $customer = Customer::firstOrCreate(
                    ['phone' => $request->phone], 
                    [
                        'name' => Auth::user()->name, 
                        'address' => $request->pickup_address
                    ]
                );
                
                $customer->update(['address' => $request->pickup_address]);

                // 3. Cek Promo ID (Diskon dihitung nanti sama admin saat nimbang)
                $promoId = null;
                if ($request->promo_code) {
                    $promo = Promo::where('code', $request->promo_code)->first();
                    if ($promo && $promo->isValid()) {
                        $promoId = $promo->id;
                    }
                }

                // 4. Gabung Note & Preferensi Layanan
                $finalNote = $request->note;
                if ($request->preferred_service) {
                    $finalNote = "Request: " . $request->preferred_service . ". \nCatatan: " . $request->note;
                }

                // 5. Generate Invoice
                do {
                    $invoice = 'TRX-' . mt_rand(10000, 99999);
                } while (Transaction::where('invoice_code', $invoice)->exists());

                // 6. Buat Transaksi
                Transaction::create([
                    'invoice_code'   => $invoice,
                    'customer_id'    => $customer->id,
                    'user_id'        => Auth::id(), 
                    'total_price'    => 0, 
                    'status'         => 'pending',         
                    'payment_status' => 'unpaid',  
                    
                    'pickup_address' => $request->pickup_address,
                    'latitude'       => $request->latitude,
                    'longitude'      => $request->longitude,

                    'delivery_type'   => $request->delivery_type,
                    'delivery_status' => 'pending', 
                    
                    'note'     => $finalNote,
                    'promo_id' => $promoId // Simpan Promo ID
                ]);
            });

            return redirect()->route('customer.dashboard')->with('success', 'Pesanan berhasil dibuat! Kurir akan segera meluncur.');

        } catch (\Exception $e) {
            return back()->with('error', 'Gagal membuat pesanan: ' . $e->getMessage())->withInput();
        }
    }

    public function uploadProof(Request $request, $id)
    {
        $request->validate([
            'payment_proof' => 'required|image|mimes:jpeg,png,jpg|max:2048', 
        ]);

        $transaction = Transaction::where('user_id', Auth::id())->findOrFail($id);

        if ($request->hasFile('payment_proof')) {
            $file = $request->file('payment_proof');
            $filename = time() . '_' . $file->getClientOriginalName();
            $path = $file->storeAs('payment_proofs', $filename, 'public');

            $transaction->update([
                'payment_proof' => $path,
                'payment_status' => 'waiting_confirmation' 
            ]);
        }

        return back()->with('success', 'Bukti transfer diterima! Tunggu admin cek ya.');
    }

    public function storeReview(Request $request, $transactionId)
    {
        $request->validate([
            'rate' => 'required|integer|min:1|max:5',
            'content' => 'required|string|max:500'
        ]);

        // Pastikan transaksi milik user login dan statusnya sudah done
        $transaction = Transaction::where('user_id', Auth::id())
                        ->where('status', 'done')
                        ->findOrFail($transactionId);

        // Cek apakah sudah pernah review
        if (Testimonial::where('transaction_id', $transaction->id)->exists()) {
            return back()->with('error', 'Anda sudah memberikan ulasan untuk pesanan ini.');
        }

        Testimonial::create([
            'user_id' => Auth::id(),
            'transaction_id' => $transaction->id,
            'rate' => $request->rate,
            'content' => $request->content
        ]);

        return back()->with('success', 'Terima kasih atas ulasannya! ⭐');
    }
}