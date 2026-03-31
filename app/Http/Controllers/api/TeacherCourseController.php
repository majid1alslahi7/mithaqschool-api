<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Models\TeacherCourse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class TeacherCourseController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return TeacherCourse::with(['teacher', 'course'])->get();
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'teacher_id' => 'required|exists:teachers,id',
            'course_id' => 'required|exists:courses,id',
            'academic_year' => 'nullable|string|max:20',
            'semester' => 'nullable|string|max:20',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

        $teacherCourse = TeacherCourse::create($request->all());
        return response()->json($teacherCourse, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        return TeacherCourse::with(['teacher', 'course'])->findOrFail($id);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $teacherCourse = TeacherCourse::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'teacher_id' => 'exists:teachers,id',
            'course_id' => 'exists:courses,id',
            'academic_year' => 'nullable|string|max:20',
            'semester' => 'nullable|string|max:20',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

        $teacherCourse->update($request->all());
        return response()->json($teacherCourse, 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        TeacherCourse::findOrFail($id)->delete();
        return response()->json(null, 204);
    }
}
