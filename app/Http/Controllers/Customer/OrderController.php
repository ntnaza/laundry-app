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

        return view('customer.dashboard', compact('myOrders'));
    }

    public function create()
    {
        $services = Service::all(); 
        return view('customer.order_create', compact('services'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'phone'          => 'required|numeric', 
            'pickup_address' => 'required|string|max:255',
            'delivery_type'  => 'required|in:pickup,delivery,both,none',
            'promo_code'     => 'nullable|string|exists:promos,code',
            'items'          => 'required|array|min:1',
            'items.*.service_id' => 'required|exists:services,id',
            'items.*.qty'    => 'required|numeric|min:0.1'
        ]);

        try {
            DB::transaction(function () use ($request) {
                $customer = Customer::firstOrCreate(
                    ['phone' => $request->phone], 
                    [
                        'name' => Auth::user()->name, 
                        'address' => $request->pickup_address
                    ]
                );
                
                $customer->update(['address' => $request->pickup_address]);

                $subtotal = 0;
                $detailsData = []; 

                foreach ($request->items as $item) {
                    $service = Service::find($item['service_id']);
                    $qty = $item['qty'];
                    $price = $service->price;
                    $itemSubtotal = $price * $qty;
                    $subtotal += $itemSubtotal;
                    $detailsData[] = [
                        'service_id' => $service->id,
                        'qty' => $qty,
                        'price_per_unit' => $price,
                        'subtotal' => $itemSubtotal
                    ];
                }

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
                $grandTotal = $subtotal - $discountAmount;

                do {
                    $invoice = 'TRX-' . mt_rand(10000, 99999);
                } while (Transaction::where('invoice_code', $invoice)->exists());

                $transaction = Transaction::create([
                    'invoice_code'   => $invoice,
                    'customer_id'    => $customer->id,
                    'app_user_id'    => Auth::id(),
                    'total_price'    => $grandTotal,
                    'subtotal'       => $subtotal,
                    'discount_amount'=> $discountAmount,
                    'status'         => 'pending',         
                    'payment_status' => 'unpaid',  
                    'pickup_address' => $request->pickup_address,
                    'delivery_type'   => $request->delivery_type,
                    'delivery_status' => 'pending', 
                    'note'     => $request->note,
                    'promo_id' => $promoId
                ]);

                foreach ($detailsData as $detail) {
                    $transaction->details()->create($detail);
                }
            });

            return redirect()->route('customer.dashboard')->with('success', 'Pesanan berhasil dibuat!');

        } catch (\Exception $e) {
            return back()->with('error', 'Gagal: ' . $e->getMessage())->withInput();
        }
    }

    public function pay($id)
    {
        $transaction = Transaction::where('app_user_id', Auth::id())->findOrFail($id);

        Config::$serverKey = config('midtrans.server_key');
        Config::$isProduction = config('midtrans.is_production');
        Config::$isSanitized = true;
        Config::$is3ds = true;
        
        $amount = (int) $transaction->total_price;
        if ($amount < 100) $amount = 100;
        
        $phone = preg_replace('/[^0-9]/', '', $transaction->customer->phone);
        if (empty($phone)) $phone = '08123456789';

        $params = [
            'transaction_details' => [
                'order_id' => $transaction->invoice_code . '-' . time(),
                'gross_amount' => $amount,
            ],
            'customer_details' => [
                'first_name' => substr($transaction->customer->name, 0, 20),
                'phone' => $phone,
            ],
        ];

        try {
            $snapToken = Snap::getSnapToken($params);
            $transaction->update(['snap_token' => $snapToken]);
            return response()->json(['snap_token' => $snapToken]);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage(), 'payload_sent' => $params], 500);
        }
    }

    public function callback(Request $request)
    {
        $serverKey = config('midtrans.server_key');
        $hashed = hash("sha512", $request->order_id.$request->status_code.$request->gross_amount.$serverKey);

        if ($hashed == $request->signature_key) {
            if ($request->transaction_status == 'capture' || $request->transaction_status == 'settlement') {
                $orderIdParts = explode('-', $request->order_id);
                $invoiceCode = $orderIdParts[0] . '-' . $orderIdParts[1]; 
                
                $transaction = Transaction::where('invoice_code', $invoiceCode)->first();
                if ($transaction) {
                    $transaction->update([
                        'payment_status' => 'paid',
                        'payment_method' => $request->payment_type
                    ]);
                }
            }
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
}
