<?php

namespace App\Http\Controllers\Api;

use App\Helpers\ResponseFormatter;
use App\Models\Order;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\OrderResource;
use App\Http\Resources\OrderCollection;
use App\Http\Requests\OrderStoreRequest;
use App\Http\Requests\OrderUpdateRequest;

class OrderController extends Controller
{
    /**
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        try {
            $search = $request->get('search', '');

            $orders = Order::with(['product', 'user', 'payment'])->search($search)
                ->latest()
                ->paginate();
            return ResponseFormatter::success($orders);
        } catch (\Throwable $th) {
            return ResponseFormatter::error();
        }
    }

    /**
     * @param \App\Http\Requests\OrderStoreRequest $request
     * @return \Illuminate\Http\Response
     */
    public function store(OrderStoreRequest $request)
    {
        $validated = $request->validated();

        $order = Order::create($validated);

        return new OrderResource($order);
    }

    /**
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\Order $order
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, Order $order)
    {
        try {
            $data = $order;
            $data['product'] = $order->product;
            $data['user'] = $order->user;
            $data['payment'] = $order->payment;
            return ResponseFormatter::success($data);
        } catch (\Throwable $th) {
            return ResponseFormatter::error();
        }
    }

    public function getOrder($code)
    {
        try {
            $data = Order::where('code', $code)->first();
            $data['product'] = $data->product;
            $data['user'] = $data->user;
            $data['payment'] = $data->payment;
            return ResponseFormatter::success($data);
        } catch (\Throwable $th) {
            return ResponseFormatter::error();
        }
    }

    /**
     * @param \App\Http\Requests\OrderUpdateRequest $request
     * @param \App\Models\Order $order
     * @return \Illuminate\Http\Response
     */
    public function update(OrderUpdateRequest $request, Order $order)
    {
        $validated = $request->validated();

        $order->update($validated);

        return new OrderResource($order);
    }

    /**
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\Order $order
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, Order $order)
    {
        $order->delete();

        return response()->noContent();
    }
}
