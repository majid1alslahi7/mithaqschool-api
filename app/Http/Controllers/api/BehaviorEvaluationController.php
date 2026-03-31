<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Models\BehaviorEvaluation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class BehaviorEvaluationController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return BehaviorEvaluation::with(['student', 'teacher'])->get();
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'student_id' => 'required|exists:students,id',
            'teacher_id' => 'required|exists:teachers,id',
            'date' => 'required|date',
            'evaluation' => 'required|string',
            'notes' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

        $behaviorEvaluation = BehaviorEvaluation::create($request->all());
        return response()->json($behaviorEvaluation, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        return BehaviorEvaluation::with(['student', 'teacher'])->findOrFail($id);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $behaviorEvaluation = BehaviorEvaluation::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'student_id' => 'required|exists:students,id',
            'teacher_id' => 'required|exists:teachers,id',
            'date' => 'required|date',
            'evaluation' => 'required|string',
            'notes' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

        $behaviorEvaluation->update($request->all());
        return response()->json($behaviorEvaluation, 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        BehaviorEvaluation::findOrFail($id)->delete();
        return response()->json(null, 204);
    }
}
