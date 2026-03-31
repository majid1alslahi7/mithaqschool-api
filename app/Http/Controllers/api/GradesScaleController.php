<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Models\GradesScale;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class GradesScaleController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return GradesScale::all();
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'grade' => 'required|string|max:2',
            'min_score' => 'required|integer|min:0',
            'max_score' => 'required|integer|min:0|gte:min_score',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

        $gradesScale = GradesScale::create($request->all());
        return response()->json($gradesScale, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        return GradesScale::findOrFail($id);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $gradesScale = GradesScale::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'grade' => 'required|string|max:2',
            'min_score' => 'required|integer|min:0',
            'max_score' => 'required|integer|min:0|gte:min_score',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

        $gradesScale->update($request->all());
        return response()->json($gradesScale, 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        GradesScale::findOrFail($id)->delete();
        return response()->json(null, 204);
    }
}
