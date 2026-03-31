<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreTeacherRequest;
use App\Http\Requests\UpdateTeacherRequest;
use App\Http\Requests\UpdateTeacherAvatarRequest;
use App\Http\Resources\TeacherResource;
use App\Models\Teacher;
use App\Services\SystemNotificationService;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class TeacherController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $teachers = Teacher::with(['user', 'grade', 'course', 'classroom'])
            ->applyapiFiltersAndSort($request)
            ->paginate($request->input('per_page', 15));

        return TeacherResource::collection($teachers);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreTeacherRequest $request)
    {
        $teacher = Teacher::create($request->validated());
        $teacher->load(['user', 'grade', 'course', 'classroom']);
        $teacherName = trim(($teacher->f_name ?? '') . ' ' . ($teacher->l_name ?? ''));
        $message = $teacherName !== '' ? "تمت إضافة معلم جديد: {$teacherName}." : 'تمت إضافة معلم جديد.';
        app(SystemNotificationService::class)->notifyAllUsers('إضافة معلم', $message);
        return new TeacherResource($teacher);
    }

    /**
     * Display the specified resource.
     */
    public function show(Teacher $teacher)
    {
        return new TeacherResource($teacher->load(['user', 'grade', 'course', 'classroom']));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateTeacherRequest $request, Teacher $teacher)
    {
        $teacher->update($request->validated());
        $teacher->load(['user', 'grade', 'course', 'classroom']);
        return new TeacherResource($teacher);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Teacher $teacher)
    {
        $teacher->delete();
        return response()->json(null, Response::HTTP_NO_CONTENT);
    }

    /**
     * Get the avatar for the specified teacher.
     *
     * @param Teacher $teacher
     * @return \Illuminate\Http\JsonResponse
     */
    public function getAvatar(Teacher $teacher)
    {
        return response()->json([
            'avatar_url' => $teacher->avatar_url,
        ]);
    }

    /**
     * Update the avatar for the specified teacher.
     *
     * @param UpdateTeacherAvatarRequest $request
     * @param Teacher $teacher
     * @return TeacherResource
     */
    public function updateAvatar(UpdateTeacherAvatarRequest $request, Teacher $teacher)
    {
        // Delete the old avatar if it exists
        if ($teacher->avatar_path) {
            Storage::disk('public')->delete($teacher->avatar_path);
        }

        // Upload the new avatar
        $path = $request->file('avatar')->store('avatars', 'public');

        // Update the teacher's avatar_path
        $teacher->update(['avatar_path' => $path]);

        return new TeacherResource($teacher);
    }

    /**
     * Delete the avatar for the specified teacher.
     *
     * @param Teacher $teacher
     * @return TeacherResource
     */
    public function deleteAvatar(Teacher $teacher)
    {
        // Delete the old avatar if it exists
        if ($teacher->avatar_path) {
            Storage::disk('public')->delete($teacher->avatar_path);
        }

        // Update the teacher's avatar_path to null
        $teacher->update(['avatar_path' => null]);

        return new TeacherResource($teacher);
    }

    /**
     * Get teacher statistics.
     */
    public function stats()
    {
        $totalTeachers = Teacher::count();

        $teachersPerGrade = Teacher::query()
            ->join('grades', 'teachers.grade_id', '=', 'grades.id')
            ->select('grades.name as grade_name', DB::raw('count(*) as total'))
            ->groupBy('grades.name')
            ->pluck('total', 'grade_name');

        $teachersPerGender = Teacher::query()
            ->select('gender', DB::raw('count(*) as total'))
            ->groupBy('gender')
            ->pluck('total', 'gender');

        return response()->json([
            'data' => [
                'total_teachers' => $totalTeachers,
                'teachers_per_grade' => $teachersPerGrade,
                'teachers_per_gender' => $teachersPerGender,
            ]
        ]);
    }
}
