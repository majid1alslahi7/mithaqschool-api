<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreSemesterGradeRequest;
use App\Http\Requests\UpdateSemesterGradeRequest;
use App\Http\Resources\SemesterGradeResource;
use App\Models\SemesterGrade;
use Illuminate\Http\Request;

class SemesterGradeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $grades = SemesterGrade::with(['student', 'course', 'semester', 'academicYear']);

        // Filter by student
        if ($request->has('student_number')) {
            $grades->where('student_number', $request->student_number);
        }

        // Filter by course
        if ($request->has('course_id')) {
            $grades->where('course_id', $request->course_id);
        }

        // Filter by semester
        if ($request->has('semester_id')) {
            $grades->where('semester_id', $request->semester_id);
        }

        // Filter by academic year
        if ($request->has('academic_year_id')) {
            $grades->where('academic_year_id', $request->academic_year_id);
        }

        // Search functionality
        if ($request->has('search')) {
            $searchTerm = $request->search;
            $grades->whereHas('student', function ($query) use ($searchTerm) {
                $query->where('f_name', 'like', "%{$searchTerm}%")
                      ->orWhere('enrollment_number', 'like', "%{$searchTerm}%");
            })->orWhereHas('course', function ($query) use ($searchTerm) {
                $query->where('name', 'like', "%{$searchTerm}%");
            });
        }

        return SemesterGradeResource::collection($grades->paginate(10));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreSemesterGradeRequest $request)
    {
        $grade = SemesterGrade::create($request->validated());
        return new SemesterGradeResource($grade);
    }

    /**
     * Display the specified resource.
     */
    public function show(SemesterGrade $semesterGrade)
    {
        return new SemesterGradeResource(
            $semesterGrade->load(['student', 'course', 'semester', 'academicYear'])
        );
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateSemesterGradeRequest $request, SemesterGrade $semesterGrade)
    {
        $semesterGrade->update($request->validated());
        return new SemesterGradeResource($semesterGrade);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(SemesterGrade $semesterGrade)
    {
        $semesterGrade->delete();
        return response()->json(null, 204);
    }

    /**
     * Display the semester grades for a specific student and semester.
     */
    public function getByStudentAndSemester($enrollmentNumber, $semesterId)
    {
        $grades = SemesterGrade::with(['student', 'course', 'semester', 'academicYear'])
            ->where('student_number', $enrollmentNumber)
            ->where('semester_id', $semesterId)
            ->get();

        if ($grades->isEmpty()) {
            return response()->json(['message' => 'لا توجد درجات مسجلة لهذا الطالب في هذا الفصل الدراسي.'], 404);
        }

        return SemesterGradeResource::collection($grades);
    }
}
