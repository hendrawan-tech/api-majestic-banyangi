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

    public function payment(Request $request)
    {
        $search = $request->get('search', '');

        $payments = Payment::search($search)
            ->latest()
            ->paginate($request->item);

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
            $validated = $request->validated();
            if ($request->hasFile('image')) {
                $file = $request->file('image');
                $fileName = $file->getClientOriginalName();
                $destinationPath = public_path() . '/images';
                $file->move($destinationPath, $fileName);
                $validated['image'] = $file->getClientOriginalName();
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
            $validated = $request->validated();

            if ($request->hasFile('image')) {
                $file = $request->file('image');
                $fileName = $file->getClientOriginalName();
                $destinationPath = public_path() . '/images';
                $file->move($destinationPath, $fileName);
                $validated['image'] = $file->getClientOriginalName();
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
            $payment->delete();

            return ResponseFormatter::success($payment);
        } catch (\Throwable $th) {
            return ResponseFormatter::error($th);
        }
    }
}
