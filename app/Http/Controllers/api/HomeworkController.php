<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreHomeworkRequest;
use App\Http\Requests\UpdateHomeworkRequest;
use App\Http\Resources\HomeworkResource;
use App\Models\Homework;
use Illuminate\Http\Request;

class HomeworkController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:view_any_homework')->only('index');
        $this->middleware('permission:view_homework')->only('show');
        $this->middleware('permission:create_homework')->only('store');
        $this->middleware('permission:update_homework')->only('update');
        $this->middleware('permission:delete_homework')->only('destroy');
    }

    public function index(Request $request)
    {
        $user = auth()->user();
        $query = Homework::with(['course', 'teacher', 'student']);

        // تطبيق الفلاتر حسب دور المستخدم
        if ($user->hasRole('teacher') && $user->teacher) {
            $query->where('teacher_id', $user->teacher->id);
        } elseif ($user->hasRole('student') && $user->student) {
            $query->where('student_id', $user->student->id);
        } elseif ($user->hasRole('guardian') && $user->guardian) {
            $childrenIds = $user->guardian->students->pluck('id');
            $query->whereIn('student_id', $childrenIds);
        }

        // Filtering
        if ($request->has('course_id')) {
            $query->where('course_id', $request->course_id);
        }
        if ($request->has('teacher_id')) {
            $query->where('teacher_id', $request->teacher_id);
        }
        if ($request->has('due_date')) {
            $query->whereDate('due_date', $request->due_date);
        }

        // Searching
        if ($request->has('search')) {
            $searchTerm = $request->search;
            $query->where('title', 'like', "%{$searchTerm}%")
                  ->orWhere('description', 'like', "%{$searchTerm}%");
        }

        $homeworks = $query->paginate(15);

        return HomeworkResource::collection($homeworks);
    }

    public function store(StoreHomeworkRequest $request)
    {
        $this->authorize('create', Homework::class);
        
        $homework = Homework::create($request->validated());
        
        // إشعار للطالب
        if ($homework->student && $homework->student->user_id) {
            \App\Models\Notification::create([
                'title' => 'واجب منزلي جديد',
                'message' => "تم إضافة واجب جديد: {$homework->title} لمادة {$homework->course->name}، تاريخ التسليم: {$homework->due_date}",
                'user_id' => $homework->student->user_id,
            ]);
        }
        
        return new HomeworkResource($homework->load(['course', 'teacher']));
    }

    public function show(Homework $homework)
    {
        $this->authorize('view', $homework);
        return new HomeworkResource($homework->load(['course', 'teacher', 'student']));
    }

    public function update(UpdateHomeworkRequest $request, Homework $homework)
    {
        $this->authorize('update', $homework);
        
        $homework->update($request->validated());
        return new HomeworkResource($homework->load(['course', 'teacher']));
    }

    public function destroy(Homework $homework)
    {
        $this->authorize('delete', $homework);
        
        $homework->delete();
        return response()->json(null, 204);
    }
}