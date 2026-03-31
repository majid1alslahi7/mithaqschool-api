<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Models\ClassFee;
use App\Http\Requests\StoreClassFeeRequest;
use App\Http\Requests\UpdateClassFeeRequest;
use App\Http\Resources\ClassFeeResource;
use Illuminate\Http\Request;

class ClassFeeController extends Controller
{
    public function index(Request $request)
    {
        $items = ClassFee::query()
            ->with('feeType')
            ->filter($request->all())
            ->paginate(20);

        return ClassFeeResource::collection($items);
    }

    public function store(StoreClassFeeRequest $request)
    {
        $item = ClassFee::create($request->validated());
        return new ClassFeeResource($item);
    }

    public function show(ClassFee $classFee)
    {
        return new ClassFeeResource($classFee->load('feeType'));
        
    }

    public function update(UpdateClassFeeRequest $request, ClassFee $classFee)
    {
        $classFee->update($request->validated());
        return new ClassFeeResource($classFee);
    }

    public function destroy(ClassFee $classFee)
    {
        $classFee->delete();
        return response()->json(['message' => 'تم الحذف بنجاح.']);
    }
}
