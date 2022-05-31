<?php

namespace App\Http\Controllers;

use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
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
            ->paginate(5)
            ->withQueryString();

        return view('app.payments.index', compact('payments', 'search'));
    }

    /**
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        $this->authorize('create', Payment::class);

        return view('app.payments.create');
    }

    /**
     * @param \App\Http\Requests\PaymentStoreRequest $request
     * @return \Illuminate\Http\Response
     */
    public function store(PaymentStoreRequest $request)
    {
        $this->authorize('create', Payment::class);

        $validated = $request->validated();
        if ($request->hasFile('image')) {
            $validated['image'] = $request->file('image')->store('public');
        }

        $payment = Payment::create($validated);

        return redirect()
            ->route('payments.edit', $payment)
            ->withSuccess(__('crud.common.created'));
    }

    /**
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\Payment $payment
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, Payment $payment)
    {
        $this->authorize('view', $payment);

        return view('app.payments.show', compact('payment'));
    }

    /**
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\Payment $payment
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request, Payment $payment)
    {
        $this->authorize('update', $payment);

        return view('app.payments.edit', compact('payment'));
    }

    /**
     * @param \App\Http\Requests\PaymentUpdateRequest $request
     * @param \App\Models\Payment $payment
     * @return \Illuminate\Http\Response
     */
    public function update(PaymentUpdateRequest $request, Payment $payment)
    {
        $this->authorize('update', $payment);

        $validated = $request->validated();
        if ($request->hasFile('image')) {
            if ($payment->image) {
                Storage::delete($payment->image);
            }

            $validated['image'] = $request->file('image')->store('public');
        }

        $payment->update($validated);

        return redirect()
            ->route('payments.edit', $payment)
            ->withSuccess(__('crud.common.saved'));
    }

    /**
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\Payment $payment
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, Payment $payment)
    {
        $this->authorize('delete', $payment);

        if ($payment->image) {
            Storage::delete($payment->image);
        }

        $payment->delete();

        return redirect()
            ->route('payments.index')
            ->withSuccess(__('crud.common.removed'));
    }
}
