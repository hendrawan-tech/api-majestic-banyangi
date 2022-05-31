<?php

namespace App\Http\Controllers\Api;

use App\Models\Payment;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\OrderResource;
use App\Http\Resources\OrderCollection;

class PaymentOrdersController extends Controller
{
    /**
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\Payment $payment
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request, Payment $payment)
    {
        $this->authorize('view', $payment);

        $search = $request->get('search', '');

        $orders = $payment
            ->orders()
            ->search($search)
            ->latest()
            ->paginate();

        return new OrderCollection($orders);
    }

    /**
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\Payment $payment
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, Payment $payment)
    {
        $this->authorize('create', Order::class);

        $validated = $request->validate([
            'code' => ['required', 'max:255', 'string'],
            'product_id' => ['required', 'exists:products,id'],
            'user_id' => ['required', 'exists:users,id'],
            'quantity' => ['required', 'max:255', 'string'],
            'total' => ['required', 'numeric'],
            'date' => ['required', 'date'],
            'status' => [
                'required',
                'in:menunggu konfirmasi,pembayaran dikonfirmasi,menunggu pembayaran,dibatalkan,selesai',
            ],
        ]);

        $order = $payment->orders()->create($validated);

        return new OrderResource($order);
    }
}
