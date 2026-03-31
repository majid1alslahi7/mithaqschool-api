<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreAttendanceRequest;
use App\Http\Requests\UpdateAttendanceRequest;
use App\Http\Resources\AttendanceResource;
use App\Models\Attendance;
use Illuminate\Http\Request;

class AttendanceController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:view_any_attendance')->only('index');
        $this->middleware('permission:view_attendance')->only('show');
        $this->middleware('permission:take_attendance')->only('store');
        $this->middleware('permission:update_attendance')->only('update');
    }

    public function index(Request $request)
    {
        $user = auth()->user();
        $query = Attendance::with(['student', 'course']);

        // تطبيق الفلاتر حسب دور المستخدم
        if ($user->hasRole('teacher') && $user->teacher) {
            // المعلم يرى حضور المواد التي يدرسها فقط
            $query->whereHas('course', function ($q) use ($user) {
                $q->where('teacher_id', $user->teacher->id);
            });
        } elseif ($user->hasRole('student') && $user->student) {
            // الطالب يرى حضوره فقط
            $query->where('student_id', $user->student->id);
        } elseif ($user->hasRole('guardian') && $user->guardian) {
            // ولي الأمر يرى حضور أبنائه فقط
            $childrenIds = $user->guardian->students->pluck('id');
            $query->whereIn('student_id', $childrenIds);
        }

        // Filtering
        if ($request->has('student_id')) {
            $query->where('student_id', $request->student_id);
        }
        if ($request->has('course_id')) {
            $query->where('course_id', $request->course_id);
        }
        if ($request->has('date')) {
            $query->whereDate('date', $request->date);
        }
        if ($request->has('status')) {
            $query->where('status', $request->status);
        }

        // Searching
        if ($request->has('search')) {
            $searchTerm = $request->search;
            $query->whereHas('student', function ($q) use ($searchTerm) {
                $q->where('f_name', 'like', "%{$searchTerm}%")
                  ->orWhere('l_name', 'like', "%{$searchTerm}%")
                  ->orWhere('enrollment_number', 'like', "%{$searchTerm}%");
            })->orWhereHas('course', function ($q) use ($searchTerm) {
                $q->where('name', 'like', "%{$searchTerm}%");
            });
        }

        $attendances = $query->paginate(15);

        return AttendanceResource::collection($attendances);
    }

    public function store(StoreAttendanceRequest $request)
    {
        $this->authorize('take', Attendance::class);
        
        $attendance = Attendance::create($request->validated());
        
        // إرسال إشعار للطالب وولي الأمر
        $this->sendAttendanceNotification($attendance);
        
        return new AttendanceResource($attendance);
    }

    public function show(Attendance $attendance)
    {
        $this->authorize('view', $attendance);
        return new AttendanceResource($attendance->load(['student', 'course']));
    }

    public function update(UpdateAttendanceRequest $request, Attendance $attendance)
    {
        $this->authorize('update', $attendance);
        
        $attendance->update($request->validated());
        return new AttendanceResource($attendance);
    }

    private function sendAttendanceNotification($attendance)
    {
        $student = $attendance->student;
        $statusText = $attendance->status == 'present' ? 'حاضر' : 'غائب';
        
        // إشعار للطالب
        if ($student->user_id) {
            \App\Models\Notification::create([
                'title' => 'تسجيل حضور',
                'message' => "تم تسجيل حضورك لمادة {$attendance->course->name} بتاريخ {$attendance->date} بـحالة: {$statusText}",
                'user_id' => $student->user_id,
            ]);
        }
        
        // إشعار لولي الأمر
        if ($student->guardian && $student->guardian->user_id) {
            \App\Models\Notification::create([
                'title' => 'تسجيل حضور ابنك',
                'message' => "تم تسجيل حضور ابنك {$student->f_name} {$student->l_name} لمادة {$attendance->course->name} بتاريخ {$attendance->date} بـحالة: {$statusText}",
                'user_id' => $student->guardian->user_id,
            ]);
        }
    }
}