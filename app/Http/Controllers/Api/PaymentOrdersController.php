<?php

namespace App\Http\Controllers\Api;

use App\Helpers\ResponseFormatter;
use App\Models\Payment;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\OrderResource;
use App\Http\Resources\OrderCollection;
use App\Models\Order;
use App\Models\Product;

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

    public function getOrder(Request $request)
    {
        // return $code;
        try {
            $data = Order::where('code', $request->code)->first();
            $data['product'] = $data->product;
            $data['user'] = $data->user;
            $data['payment'] = $data->payment;
            return ResponseFormatter::success($data);
        } catch (\Throwable $th) {
            return ResponseFormatter::error();
        }
    }


    /**
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\Payment $payment
     * @return \Illuminate\Http\Response
     */

    public function order(Request $request)
    {
        try {
            $results = [];
            $orders = Order::where('user_id', $request->user_id)->whereNotIn('status', ['Selesai'])->with('user', 'payment', 'product')->latest()->get();
            foreach ($orders as $order) {
                $data = $order;
                foreach ($order['product']->comments as $comment) {
                    $data['comments'] = $comment->user;
                }
                foreach ($order['product']->likes as $like) {
                    $data['likes'] = $like->user;
                }
                array_push($results, $data);
            }
            return ResponseFormatter::success($results);
        } catch (\Throwable $th) {
            return ResponseFormatter::error($th);
        }
    }

    public function payment(Request $request)
    {
        try {
            $results = [];
            $orders = Order::where('id', $request->id)->with('user', 'payment', 'product')->first();
            $data = [
                'date' => date('y-m-d'),
                'status' => 'Menunggu Konfirmasi'
            ];
            if ($request->hasFile('transfer')) {
                $file = $request->file('transfer');
                $fileName = $file->getClientOriginalName();
                $destinationPath = public_path() . '/images';
                $file->move($destinationPath, $fileName);
                $data['transfer'] = $fileName;
            }

            $orders->update($data);

            $data = $orders;
            foreach ($orders['product']->comments as $comment) {
                $data['comments'] = $comment->user;
            }
            foreach ($orders['product']->likes as $like) {
                $data['likes'] = $like->user;
            }
            array_push($results, $data);
            return ResponseFormatter::success($results);
        } catch (\Throwable $th) {
            return ResponseFormatter::error($th);
        }
    }

    public function confirm($id)
    {
        try {
            $results = [];
            $orders = Order::where('id', $id)->with('user', 'payment', 'product')->first();

            $orders->update([
                'status' => 'Pembayaran Dikonfirmasi'
            ]);

            $data = $orders;
            foreach ($orders['product']->comments as $comment) {
                $data['comments'] = $comment->user;
            }
            foreach ($orders['product']->likes as $like) {
                $data['likes'] = $like->user;
            }
            array_push($results, $data);
            return ResponseFormatter::success($results);
        } catch (\Throwable $th) {
            return ResponseFormatter::error($th);
        }
    }

    public function done($id)
    {
        try {
            $results = [];
            $orders = Order::where('id', $id)->with('user', 'payment', 'product')->first();

            $orders->update([
                'status' => 'Selesai'
            ]);

            $data = $orders;
            foreach ($orders['product']->comments as $comment) {
                $data['comments'] = $comment->user;
            }
            foreach ($orders['product']->likes as $like) {
                $data['likes'] = $like->user;
            }
            array_push($results, $data);
            return ResponseFormatter::success($results);
        } catch (\Throwable $th) {
            return ResponseFormatter::error($th);
        }
    }

    public function cancel($id)
    {
        try {
            $orders = Order::where('id', $id)->with('user', 'payment', 'product')->first();
            $orders->delete();
            return ResponseFormatter::success($orders);
        } catch (\Throwable $th) {
            return ResponseFormatter::error($th);
        }
    }

    public function batal($id)
    {
        try {
            $results = [];
            $orders = Order::where('id', $id)->with('user', 'payment', 'product')->first();

            $orders->update([
                'status' => 'Dibatalkan'
            ]);

            $data = $orders;
            foreach ($orders['product']->comments as $comment) {
                $data['comments'] = $comment->user;
            }
            foreach ($orders['product']->likes as $like) {
                $data['likes'] = $like->user;
            }
            array_push($results, $data);
            return ResponseFormatter::success($results);
        } catch (\Throwable $th) {
            return ResponseFormatter::error($th);
        }
    }

    public function orderDone(Request $request)
    {
        try {
            $results = [];
            $orders = Order::where(['user_id' => $request->user_id, 'status' => 'Selesai'])->with('user', 'payment', 'product')->latest()->get();
            foreach ($orders as $order) {
                $data = $order;
                foreach ($order['product']->comments as $comment) {
                    $data['comments'] = $comment->user;
                }
                foreach ($order['product']->likes as $like) {
                    $data['likes'] = $like->user;
                }
                array_push($results, $data);
            }
            return ResponseFormatter::success($results);
        } catch (\Throwable $th) {
            return ResponseFormatter::error($th);
        }
    }

    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'product_id' => ['required', 'exists:products,id'],
                'payment_id' => ['required', 'exists:payments,id'],
                'user_id' => ['required', 'exists:users,id'],
                'quantity' => ['required', 'max:255', 'string'],
                'total' => ['required', 'string'],
                'date' => ['required', 'string'],
                'status' => ['required', 'string'],
            ]);

            $validated['code'] = 'OR' . random_int(10000, 99999);

            $order = Order::create($validated);

            $data = Order::where('id', $order->id)->with('user', 'payment')->first();
            $data['product'] = Product::where('id', $order->product_id)->first();
            foreach ($data['product']->comments as $comment) {
                $data['comments'] = $comment->user;
            }
            foreach ($data['product']->likes as $like) {
                $data['likes'] = $like->user;
                $data['favorite'] = $like->user->id == $request->id ? true : false;
            }


            return ResponseFormatter::success($data);
        } catch (\Throwable $th) {
            return ResponseFormatter::error($th);
        }
    }
}
