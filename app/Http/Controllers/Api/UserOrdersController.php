<?php

namespace App\Http\Controllers\Api;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\OrderResource;
use App\Http\Resources\OrderCollection;

class UserOrdersController extends Controller
{
    /**
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\User $user
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request, User $user)
    {
        $this->authorize('view', $user);

        $search = $request->get('search', '');

        $orders = $user
            ->orders()
            ->search($search)
            ->latest()
            ->paginate();

        return new OrderCollection($orders);
    }

    /**
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\User $user
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, User $user)
    {
        $this->authorize('create', Order::class);

        $validated = $request->validate([
            'code' => ['required', 'max:255', 'string'],
            'product_id' => ['required', 'exists:products,id'],
            'payment_id' => ['required', 'exists:payments,id'],
            'quantity' => ['required', 'max:255', 'string'],
            'total' => ['required', 'numeric'],
            'date' => ['required', 'date'],
            'status' => [
                'required',
                'in:menunggu konfirmasi,pembayaran dikonfirmasi,menunggu pembayaran,dibatalkan,selesai',
            ],
        ]);

        $order = $user->orders()->create($validated);

        return new OrderResource($order);
    }
}
