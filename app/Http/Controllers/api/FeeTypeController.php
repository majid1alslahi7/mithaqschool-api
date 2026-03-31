<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Models\FeeType;
use App\Http\Requests\StoreFeeTypeRequest;
use App\Http\Requests\UpdateFeeTypeRequest;
use App\Http\Resources\FeeTypeResource;
use Illuminate\Http\Request;

class FeeTypeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $items = FeeType::query()
            ->filter($request->all()) // البحث + الفلترة
            ->paginate(20);

        return FeeTypeResource::collection($items);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreFeeTypeRequest $request)
    {
        $item = FeeType::create($request->validated());
        return new FeeTypeResource($item);
    }

    /**
     * Display the specified resource.
     */
    public function show(FeeType $feeType)
    {
        return new FeeTypeResource($feeType);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateFeeTypeRequest $request, FeeType $feeType)
    {
        $feeType->update($request->validated());
        return new FeeTypeResource($feeType);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(FeeType $feeType)
    {
        $feeType->delete();
        return response()->json(['message' => 'تم الحذف بنجاح.']);
    }
}
