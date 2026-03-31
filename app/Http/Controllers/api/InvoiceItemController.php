<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Models\InvoiceItem;
use App\Http\Requests\StoreInvoiceItemRequest;
use App\Http\Requests\UpdateInvoiceItemRequest;
use App\Http\Resources\InvoiceItemResource;
use Illuminate\Http\Request;

class InvoiceItemController extends Controller
{
    public function index(Request $request)
    {
        $items = InvoiceItem::query()
            ->with(['invoice', 'feeType'])
            ->paginate(20);

        return InvoiceItemResource::collection($items);
    }

    public function store(StoreInvoiceItemRequest $request)
    {
        $item = InvoiceItem::create($request->validated());
        return new InvoiceItemResource($item);
    }

    public function show(InvoiceItem $invoiceItem)
    {
        return new InvoiceItemResource($invoiceItem->load(['invoice', 'feeType']));
    }

    public function update(UpdateInvoiceItemRequest $request, InvoiceItem $invoiceItem)
    {
        $invoiceItem->update($request->validated());
        return new InvoiceItemResource($invoiceItem);
    }

    public function destroy(InvoiceItem $invoiceItem)
    {
        $invoiceItem->delete();
        return response()->json(['message' => 'تم الحذف بنجاح.']);
    }
}
