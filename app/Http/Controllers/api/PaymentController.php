<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Models\Payment;
use App\Http\Requests\StorePaymentRequest;
use App\Http\Requests\UpdatePaymentRequest;
use App\Http\Resources\PaymentResource;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
    public function index(Request $request)
    {
        $items = Payment::query()
            ->with('invoice')
            ->filter($request->all())
            ->paginate(20);

        return PaymentResource::collection($items);
    }

    public function store(StorePaymentRequest $request)
    {
        $item = Payment::create($request->validated());
        return new PaymentResource($item);
    }

    public function show(Payment $payment)
    {
        return new PaymentResource($payment->load('invoice'));
    }

    public function update(UpdatePaymentRequest $request, Payment $payment)
    {
        $payment->update($request->validated());
        return new PaymentResource($payment);
    }

    public function destroy(Payment $payment)
    {
        $payment->delete();
        return response()->json(['message' => 'تم الحذف بنجاح.']);
    }
}
