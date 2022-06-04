<?php

namespace App\Http\Controllers\Api;

use App\Helpers\ResponseFormatter;
use App\Models\Payment;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\PaymentResource;
use Illuminate\Support\Facades\Storage;
use App\Http\Resources\PaymentCollection;
use App\Http\Requests\PaymentStoreRequest;
use App\Http\Requests\PaymentUpdateRequest;

class PaymentController extends Controller
{
    /**
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $this->authorize('view-any', Payment::class);

        $search = $request->get('search', '');

        $payments = Payment::search($search)
            ->latest()
            ->paginate();

        if ($payments) {
            return ResponseFormatter::success($payments);
        } else {
            return ResponseFormatter::error();
        }
    }

    /**
     * @param \App\Http\Requests\PaymentStoreRequest $request
     * @return \Illuminate\Http\Response
     */
    public function store(PaymentStoreRequest $request)
    {
        try {
            $this->authorize('create', Payment::class);

            $validated = $request->validated();
            if ($request->hasFile('image')) {
                $validated['image'] = $request->file('image')->store('public');
            }

            $payment = Payment::create($validated);
            return ResponseFormatter::success($payment);
        } catch (\Throwable $th) {
            return ResponseFormatter::error($th);
        }
    }

    /**
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\Payment $payment
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, Payment $payment)
    {
        $this->authorize('view', $payment);

        return new PaymentResource($payment);
    }

    /**
     * @param \App\Http\Requests\PaymentUpdateRequest $request
     * @param \App\Models\Payment $payment
     * @return \Illuminate\Http\Response
     */
    public function update(PaymentUpdateRequest $request, Payment $payment)
    {
        try {
            $this->authorize('update', $payment);

            $validated = $request->validated();

            if ($request->hasFile('image')) {
                if ($payment->image) {
                    Storage::delete($payment->image);
                }

                $validated['image'] = $request->file('image')->store('public');
            }

            $payment->update($validated);
            return ResponseFormatter::success($payment);
        } catch (\Throwable $th) {
            return ResponseFormatter::error($th);
        }
    }

    /**
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\Payment $payment
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, Payment $payment)
    {
        try {
            $this->authorize('delete', $payment);

            if ($payment->image) {
                Storage::delete($payment->image);
            }

            $payment->delete();

            return ResponseFormatter::success($payment);
        } catch (\Throwable $th) {
            return ResponseFormatter::error($th);
        }
    }
}
