<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Models\SchoolStage;
use App\Services\SystemNotificationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class SchoolStageController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return SchoolStage::orderBy('order_index')->get();
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:100',
            'description' => 'nullable|string',
            'order_index' => 'integer',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

        $schoolStage = SchoolStage::create($request->all());
        $stageName = $schoolStage->name ?? '';
        $message = $stageName !== '' ? "تمت إضافة مرحلة دراسية جديدة: {$stageName}." : 'تمت إضافة مرحلة دراسية جديدة.';
        app(SystemNotificationService::class)->notifyAllUsers('إضافة مرحلة دراسية', $message);
        return response()->json($schoolStage, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        return SchoolStage::findOrFail($id);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $schoolStage = SchoolStage::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'name' => 'string|max:100',
            'description' => 'nullable|string',
            'order_index' => 'integer',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

        $schoolStage->update($request->all());
        return response()->json($schoolStage, 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        SchoolStage::findOrFail($id)->delete();
        return response()->json(null, 204);
    }
}
