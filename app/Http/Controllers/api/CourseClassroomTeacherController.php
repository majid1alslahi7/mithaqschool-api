<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreCourseClassroomTeacherRequest;
use App\Http\Requests\UpdateCourseClassroomTeacherRequest;
use App\Http\Resources\CourseClassroomTeacherResource;
use App\Models\CourseClassroomTeacher;
use Illuminate\Http\Request;

class CourseClassroomTeacherController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:assign_teacher_to_course')->except(['index', 'show']);
        $this->middleware('permission:view_any_course')->only(['index', 'show']);
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = CourseClassroomTeacher::query()
            ->with(['course', 'classroom', 'teacher.user']);

        // Apply search
        if ($request->has('search')) {
            $query->search($request->input('search'));
        }

        // Apply filters
        if ($request->has('course_id')) {
            $query->where('course_id', $request->course_id);
        }
        if ($request->has('classroom_id')) {
            $query->where('classroom_id', $request->classroom_id);
        }
        if ($request->has('teacher_id')) {
            $query->where('teacher_id', $request->teacher_id);
        }

        $courseClassroomTeachers = $query->paginate($request->input('per_page', 15));

        return CourseClassroomTeacherResource::collection($courseClassroomTeachers);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreCourseClassroomTeacherRequest $request)
    {
        $this->authorize('create', CourseClassroomTeacher::class);
        
        $courseClassroomTeacher = CourseClassroomTeacher::create($request->validated());

        return new CourseClassroomTeacherResource($courseClassroomTeacher->load(['course', 'classroom', 'teacher.user']));
    }

    /**
     * Display the specified resource.
     */
    public function show(CourseClassroomTeacher $courseClassroomTeacher)
    {
        $this->authorize('view', $courseClassroomTeacher);
        
        return new CourseClassroomTeacherResource($courseClassroomTeacher->load(['course', 'classroom', 'teacher.user']));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateCourseClassroomTeacherRequest $request, CourseClassroomTeacher $courseClassroomTeacher)
    {
        $this->authorize('update', $courseClassroomTeacher);
        
        $courseClassroomTeacher->update($request->validated());

        return new CourseClassroomTeacherResource($courseClassroomTeacher->load(['course', 'classroom', 'teacher.user']));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(CourseClassroomTeacher $courseClassroomTeacher)
    {
        $this->authorize('delete', $courseClassroomTeacher);
        
        $courseClassroomTeacher->delete();

        return response()->json(['message' => 'تم الحذف بنجاح.'], 200);
    }

    /**
     * Get assignments by course.
     */
    public function byCourse($courseId)
    {
        $assignments = CourseClassroomTeacher::with(['classroom', 'teacher.user'])
            ->where('course_id', $courseId)
            ->get();
            
        return CourseClassroomTeacherResource::collection($assignments);
    }

    /**
     * Get assignments by classroom.
     */
    public function byClassroom($classroomId)
    {
        $assignments = CourseClassroomTeacher::with(['course', 'teacher.user'])
            ->where('classroom_id', $classroomId)
            ->get();
            
        return CourseClassroomTeacherResource::collection($assignments);
    }

    /**
     * Get assignments by teacher.
     */
    public function byTeacher($teacherId)
    {
        $assignments = CourseClassroomTeacher::with(['course', 'classroom'])
            ->where('teacher_id', $teacherId)
            ->get();
            
        return CourseClassroomTeacherResource::collection($assignments);
    }
}