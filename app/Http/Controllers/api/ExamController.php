<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreExamRequest;
use App\Http\Requests\UpdateExamRequest;
use App\Http\Resources\ExamResource;
use App\Models\Exam;
use Illuminate\Http\Request;

class ExamController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:view_any_exam')->only('index');
        $this->middleware('permission:view_exam')->only('show');
        $this->middleware('permission:create_exam')->only('store');
        $this->middleware('permission:update_exam')->only('update');
        $this->middleware('permission:delete_exam')->only('destroy');
    }

    public function index(Request $request)
    {
        $user = auth()->user();
        $query = Exam::with(['course', 'examType', 'academicYear', 'semester', 'teacher']);

        // تطبيق الفلاتر حسب دور المستخدم
        if ($user->hasRole('teacher') && $user->teacher) {
            $query->where('teacher_id', $user->teacher->id);
        } elseif ($user->hasRole('student') && $user->student) {
            // الطالب يرى فقط الامتحانات الخاصة بالمواد التي يدرسها
            $studentCourseIds = $user->student->courses()->pluck('courses.id');
            $query->whereIn('course_id', $studentCourseIds);
        }

        // Filtering
        if ($request->has('course_id')) {
            $query->where('course_id', $request->course_id);
        }
        if ($request->has('exam_type_id')) {
            $query->where('exam_type_id', $request->exam_type_id);
        }
        if ($request->has('academic_year_id')) {
            $query->where('academic_year_id', $request->academic_year_id);
        }
        if ($request->has('semester_id')) {
            $query->where('semester_id', $request->semester_id);
        }
        if ($request->has('date')) {
            $query->whereDate('date', $request->date);
        }

        // Searching
        if ($request->has('search')) {
            $searchTerm = $request->search;
            $query->where('name', 'like', "%{$searchTerm}%")
                  ->orWhereHas('course', function ($q) use ($searchTerm) {
                      $q->where('name', 'like', "%{$searchTerm}%");
                  });
        }

        $exams = $query->paginate(15);

        return ExamResource::collection($exams);
    }

    public function store(StoreExamRequest $request)
    {
        $this->authorize('create', Exam::class);
        
        $exam = Exam::create($request->validated());
        
        // إشعار للمعلم
        if ($exam->teacher && $exam->teacher->user_id) {
            \App\Models\Notification::create([
                'title' => 'تم إنشاء اختبار',
                'message' => "تم إنشاء اختبار {$exam->name} لمادة {$exam->course->name} بتاريخ {$exam->date}",
                'user_id' => $exam->teacher->user_id,
            ]);
        }
        
        return new ExamResource($exam->load(['course', 'examType', 'academicYear', 'semester', 'teacher']));
    }

    public function show(Exam $exam)
    {
        $this->authorize('view', $exam);
        return new ExamResource($exam->load(['course', 'examType', 'academicYear', 'semester', 'teacher']));
    }

    public function update(UpdateExamRequest $request, Exam $exam)
    {
        $this->authorize('update', $exam);
        
        $exam->update($request->validated());
        return new ExamResource($exam->load(['course', 'examType', 'academicYear', 'semester', 'teacher']));
    }

    public function destroy(Exam $exam)
    {
        $this->authorize('delete', $exam);
        
        $exam->delete();
        return response()->json(null, 204);
    }
}