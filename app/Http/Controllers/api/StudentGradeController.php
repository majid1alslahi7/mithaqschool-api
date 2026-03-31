<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Models\StudentGrade;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class StudentGradeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return StudentGrade::with(['student', 'course', 'gradeScale'])->get();
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'student_id' => 'required|exists:students,id',
            'course_id' => 'required|exists:courses,id',
            'score' => 'nullable|numeric|min:0',
            'term' => 'nullable|string|max:20',
            'assessment_type' => 'nullable|string|max:50',
            'max_score' => 'numeric|min:0',
            'grade_id' => 'nullable|exists:grades_scales,id',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

        $studentGrade = StudentGrade::create($request->all());
        return response()->json($studentGrade, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        return StudentGrade::with(['student', 'course', 'gradeScale'])->findOrFail($id);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $studentGrade = StudentGrade::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'student_id' => 'exists:students,id',
            'course_id' => 'exists:courses,id',
            'score' => 'nullable|numeric|min:0',
            'term' => 'nullable|string|max:20',
            'assessment_type' => 'nullable|string|max:50',
            'max_score' => 'numeric|min:0',
            'grade_id' => 'nullable|exists:grades_scales,id',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

        $studentGrade->update($request->all());
        return response()->json($studentGrade, 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        StudentGrade::findOrFail($id)->delete();
        return response()->json(null, 204);
    }
}
