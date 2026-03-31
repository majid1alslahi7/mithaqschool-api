<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Models\School;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class SchoolController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return School::all();
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:200',
            'code' => 'nullable|string|max:50|unique:schools',
            'type' => 'nullable|string|max:50',
            'principal_name' => 'nullable|string|max:100',
            'phone' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:255',
            'address' => 'nullable|string',
            'city' => 'nullable|string|max:100',
            'region' => 'nullable|string|max:100',
            'logo_url' => 'nullable|string',
            'established_year' => 'nullable|string|max:4',
            'description' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

        $school = School::create($request->all());
        return response()->json($school, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        return School::findOrFail($id);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $school = School::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'name' => 'string|max:200',
            'code' => 'nullable|string|max:50|unique:schools,code,' . $id,
            'type' => 'nullable|string|max:50',
            'principal_name' => 'nullable|string|max:100',
            'phone' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:255',
            'address' => 'nullable|string',
            'city' => 'nullable|string|max:100',
            'region' => 'nullable|string|max:100',
            'logo_url' => 'nullable|string',
            'established_year' => 'nullable|string|max:4',
            'description' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

        $school->update($request->all());
        return response()->json($school, 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        School::findOrFail($id)->delete();
        return response()->json(null, 204);
    }
}
