<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Models\Grade;
use App\Services\SystemNotificationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class GradeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return Grade::with('schoolStage')->get();
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'school_stage_id' => 'required|exists:school_stages,id',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

        $grade = Grade::create($request->all());
        $gradeName = $grade->name ?? '';
        $message = $gradeName !== '' ? "تمت إضافة صف جديد: {$gradeName}." : 'تمت إضافة صف جديد.';
        app(SystemNotificationService::class)->notifyAllUsers('إضافة صف', $message);
        return response()->json($grade, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        return Grade::with('schoolStage')->findOrFail($id);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $grade = Grade::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'school_stage_id' => 'required|exists:school_stages,id',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

        $grade->update($request->all());
        return response()->json($grade, 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        Grade::findOrFail($id)->delete();
        return response()->json(null, 204);
    }
}
