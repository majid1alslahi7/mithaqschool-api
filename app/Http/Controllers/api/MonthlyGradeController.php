<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreMonthlyGradeRequest;
use App\Http\Requests\UpdateMonthlyGradeRequest;
use App\Http\Resources\MonthlyGradeResource;
use App\Models\MonthlyGrade;
use Illuminate\Http\Request;

class MonthlyGradeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $grades = MonthlyGrade::with(['student', 'course', 'semester', 'academicYear']);

        // Filter by student
        if ($request->has('student_number')) {
            $grades->where('student_number', $request->student_number);
        }

        // Filter by course
        if ($request->has('course_id')) {
            $grades->where('course_id', $request->course_id);
        }

        // Filter by month
        if ($request->has('month')) {
            $grades->where('month', $request->month);
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


        return MonthlyGradeResource::collection($grades->paginate(10));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreMonthlyGradeRequest $request)
    {
        $grade = MonthlyGrade::create($request->validated());
        return new MonthlyGradeResource($grade);
    }

    /**
     * Display the specified resource.
     */
    public function show(MonthlyGrade $monthlyGrade)
    {
        return new MonthlyGradeResource($monthlyGrade->load(['student', 'course', 'semester', 'academicYear']));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateMonthlyGradeRequest $request, MonthlyGrade $monthlyGrade)
    {
        $monthlyGrade->update($request->validated());
        return new MonthlyGradeResource($monthlyGrade);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(MonthlyGrade $monthlyGrade)
    {
        $monthlyGrade->delete();
        return response()->json(null, 204);
    }

    /**
     * Display the monthly grades for a specific student and month.
     */
    public function getByStudentAndMonth($enrollmentNumber, $month)
    {
        $grades = MonthlyGrade::with(['student', 'course', 'semester', 'academicYear'])
            ->where('student_number', $enrollmentNumber)
            ->where('month', $month)
            ->get();

        if ($grades->isEmpty()) {
            return response()->json(['message' => 'لا توجد درجات مسجلة لهذا الطالب في هذا الشهر.'], 404);
        }

        return MonthlyGradeResource::collection($grades);
    }
}
