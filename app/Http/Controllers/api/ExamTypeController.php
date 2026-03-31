<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Models\ExamType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ExamTypeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return ExamType::all();
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255|unique:exam_types',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

        $examType = ExamType::create($request->all());
        return response()->json($examType, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        return ExamType::findOrFail($id);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $examType = ExamType::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255|unique:exam_types,name,' . $examType->id,
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

        $examType->update($request->all());
        return response()->json($examType, 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        ExamType::findOrFail($id)->delete();
        return response()->json(null, 204);
    }
}
