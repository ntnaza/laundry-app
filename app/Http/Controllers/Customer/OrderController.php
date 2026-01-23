<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Transaction; 
use App\Models\Service; // [REVISI: Kita pakai Model Service yang sudah ada]
use App\Models\Customer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB; // <--- INI OBATNYA

class OrderController extends Controller
{
    public function index()
    {
        $myOrders = Transaction::where('customer_id', Auth::id())
                        ->latest()
                        ->get();

        return view('customer.dashboard', compact('myOrders'));
    }

    public function create()
    {
        // [UPDATE]
        // Tarik semua data layanan dari Database (Tabel Services)
        // Data ini dikirim ke View biar nanti bisa dipilih pelanggan
        $services = Service::all(); 
        
        return view('customer.order_create', compact('services'));
    }

    public function store(Request $request)
    {
        // 1. Validasi Input
        $request->validate([
            'phone'          => 'required|numeric', 
            'pickup_address' => 'required|string|max:255',
            'delivery_type'  => 'required|in:pickup,delivery', // Pastikan isinya valid
            'latitude'       => 'nullable', 
            'longitude'      => 'nullable',
            'note'           => 'nullable|string|max:500'
        ]);

        try {
            DB::transaction(function () use ($request) {
                // 2. Simpan/Cek Data Customer
                // Kita cari berdasarkan 'phone' (No HP)
                $customer = Customer::firstOrCreate(
                    ['phone' => $request->phone], 
                    [
                        'name' => Auth::user()->name, // Default pakai nama user login
                        'address' => $request->pickup_address
                    ]
                );
                
                // Update alamat terbaru
                $customer->update([
                    'address' => $request->pickup_address
                ]);

                // 3. Generate Invoice Unik (Cegah Duplikat)
                do {
                    $invoice = 'TRX-' . mt_rand(10000, 99999);
                } while (Transaction::where('invoice_code', $invoice)->exists());

                // 4. Buat Transaksi
                Transaction::create([
                    'invoice_code'   => $invoice,
                    'customer_id'    => $customer->id,
                    'user_id'        => Auth::id(), 
                    'total_price'    => 0, // Menunggu admin
                    'status'         => 'pending',         
                    'payment_status' => 'unpaid',  
                    
                    'pickup_address' => $request->pickup_address,
                    'latitude'       => $request->latitude,
                    'longitude'      => $request->longitude,

                    'delivery_type'   => $request->delivery_type,
                    'delivery_status' => 'pending', 
                    
                    'note' => $request->note 
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

        $transaction = Transaction::where('customer_id', Auth::id())->findOrFail($id);

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
}