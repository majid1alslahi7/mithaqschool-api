<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Models\Adjustment;
use App\Http\Requests\StoreAdjustmentRequest;
use App\Http\Requests\UpdateAdjustmentRequest;
use App\Http\Resources\AdjustmentResource;
use Illuminate\Http\Request;

class AdjustmentController extends Controller
{
    public function index(Request $request)
    {
        $items = Adjustment::query()
            ->with('invoice')
            ->filter($request->all())
            ->paginate(20);

        return AdjustmentResource::collection($items);
    }

    public function store(StoreAdjustmentRequest $request)
    {
        $item = Adjustment::create($request->validated());
        return new AdjustmentResource($item);
    }

    public function show(Adjustment $adjustment)
    {
        return new AdjustmentResource($adjustment->load('invoice'));
    }

    public function update(UpdateAdjustmentRequest $request, Adjustment $adjustment)
    {
        $adjustment->update($request->validated());
        return new AdjustmentResource($adjustment);
    }

    public function destroy(Adjustment $adjustment)
    {
        $adjustment->delete();
        return response()->json(['message' => 'تم الحذف بنجاح.']);
    }
}
