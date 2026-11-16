<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Promo;
use App\Models\Service;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
    public function create()
    {
        $services = Service::active()->orderBy('name')->get();
        $selectedServiceId = session('selected_service');
        return view('customer.orders.create', compact('services', 'selectedServiceId'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'service_id' => 'required|exists:services,id',
            'quantity' => 'required|numeric|min:0.5|max:100',
            'delivery_method' => 'required|in:antar_sendiri,dijemput',
            'pickup_address' => 'required_if:delivery_method,dijemput|nullable|string',
            'promo_code' => 'nullable|string',
            'notes' => 'nullable|string|max:500',
            'photos' => 'nullable|array|max:5',
            'photos.*' => 'image|mimes:jpg,jpeg,png,webp|max:2048', // upload MULTIPLE
        ]);

        $service = Service::active()->findOrFail($data['service_id']);
        $subtotal = $service->price * $data['quantity'];

        $promo = null;
        $discount = 0;
        if (! empty($data['promo_code'])) {
            $promo = Promo::where('code', $data['promo_code'])->first();
            if (! $promo || ! $promo->isValid()) {
                return back()->withInput()->withErrors(['promo_code' => 'Kode promo tidak valid atau kedaluwarsa.']);
            }
            $discount = $promo->discountFor($subtotal);
        }
        $total = $subtotal - $discount;

        $order = DB::transaction(function () use ($request, $data, $service, $promo, $subtotal, $discount, $total) {
            $order = Order::create([
                'order_number' => 'HD-' . now()->format('ymd') . '-' . str_pad(Order::count() + 1, 4, '0', STR_PAD_LEFT),
                'customer_id' => Auth::id(),
                'promo_id' => $promo?->id,
                'delivery_method' => $data['delivery_method'],
                'pickup_address' => $data['pickup_address'] ?? null,
                'notes' => $data['notes'] ?? null,
                'status' => 'Menunggu Verifikasi',
                'discount' => $discount,
                'total' => $total,
            ]);

            $order->items()->create([
                'service_id' => $service->id,
                'quantity' => $data['quantity'],
                'unit_price' => $service->price,
                'subtotal' => $subtotal,
            ]);

            $order->payment()->create([
                'receipt_number' => 'NT-' . $order->order_number,
                'status' => 'belum_lunas',
                'amount' => $total,
            ]);

            $order->histories()->create(['status' => 'Menunggu Verifikasi']);

            foreach ($request->file('photos', []) as $photo) {
                $order->photos()->create(['path' => $photo->store('orders', 'public')]);
            }
            return $order;
        });

        session()->forget('selected_service');
        return redirect()->route('orders.show', $order)->with('success', 'Pesanan berhasil dibuat.');
    }
}
