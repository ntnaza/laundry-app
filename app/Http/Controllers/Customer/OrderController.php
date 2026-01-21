<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Transaction; // Jangan lupa panggil Model Transaction
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OrderController extends Controller
{
    public function index()
    {
        // Ambil riwayat cucian milik user yang sedang login saja
        $myOrders = Transaction::where('customer_id', Auth::id())
                        ->latest()
                        ->get();

        return view('customer.dashboard', compact('myOrders'));
    }

    public function create()
    {
        return view('customer.order_create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'phone' => 'required',
            'pickup_address' => 'required',
            'delivery_type' => 'required',
            // Pastikan lat long boleh ada (string/numeric)
            'latitude' => 'nullable', 
            'longitude' => 'nullable',
        ]);

        // ... (Kode customer firstOrCreate tetap sama) ...
        $customer = \App\Models\Customer::firstOrCreate(
            ['name' => Auth::user()->name],
            ['phone' => $request->phone, 'address' => $request->pickup_address]
        );

        // ... (Kode hitung harga tetap sama) ...
        $pricePerKg = 7000;
        $estimatedTotal = $request->weight ? $request->weight * $pricePerKg : 0;

        $invoice = 'TRX-' . mt_rand(10000, 99999);

        Transaction::create([
            'invoice_code' => $invoice,
            'customer_id' => $customer->id,
            'user_id' => null,
            'total_price' => $estimatedTotal,
            'status' => 'pending',
            'payment_status' => 'unpaid',
            
            'pickup_address' => $request->pickup_address,
            
            // --- SIMPAN KOORDINAT DISINI ---
            'latitude' => $request->latitude,
            'longitude' => $request->longitude,
            // -------------------------------

            'delivery_type' => $request->delivery_type,
            'delivery_status' => 'pending',
            'note' => $request->note . " (Estimasi Berat: " . ($request->weight ?? 0) . " kg)"
        ]);

        return redirect()->route('customer.dashboard')->with('success', 'Order masuk! Lokasi sudah tercatat.');
    }

    public function uploadProof(Request $request, $id)
    {
        $request->validate([
            'payment_proof' => 'required|image|mimes:jpeg,png,jpg|max:2048', // Max 2MB
        ]);

        $transaction = Transaction::where('customer_id', Auth::id())->findOrFail($id);

        // Upload File ke folder 'public/payment_proofs'
        if ($request->hasFile('payment_proof')) {
            $file = $request->file('payment_proof');
            $filename = time() . '_' . $file->getClientOriginalName();
            $path = $file->storeAs('payment_proofs', $filename, 'public');

            // Simpan path ke database
            $transaction->update([
                'payment_proof' => $path
            ]);
        }

        return back()->with('success', 'Bukti pembayaran berhasil dikirim! Tunggu verifikasi admin.');
    }
}