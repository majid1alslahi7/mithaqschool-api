<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CheckAttendanceOwnership
{
    /**
     * التحقق من أن المستخدم لديه صلاحية الوصول إلى سجل الحضور
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        $user = Auth::user();
        $attendanceId = $request->route('attendance') ?? $request->route('attendance_id');
        
        if (!$attendanceId) {
            return $next($request);
        }

        // Super-admin و admin يمكنهم الوصول لأي سجل حضور
        if ($user->hasRole(['super-admin', 'admin'])) {
            return $next($request);
        }

        $attendance = \App\Models\Attendance::find($attendanceId);
        
        if (!$attendance) {
            abort(404, 'سجل الحضور غير موجود');
        }

        // المعلم: يمكنه الوصول إلى حضور المواد التي يدرسها فقط
        if ($user->hasRole('teacher') && $user->teacher) {
            if ($attendance->course->teacher_id == $user->teacher->id) {
                return $next($request);
            }
            abort(403, 'لا يمكنك الوصول إلى سجل حضور مادة لا تدرسها');
        }

        // الطالب: يمكنه الوصول إلى حضوره فقط
        if ($user->hasRole('student') && $user->student) {
            if ($attendance->student_id == $user->student->id) {
                return $next($request);
            }
            abort(403, 'لا يمكنك الوصول إلى سجل حضور طالب آخر');
        }

        // ولي الأمر: يمكنه الوصول إلى حضور أبنائه فقط
        if ($user->hasRole('guardian') && $user->guardian) {
            $childrenIds = $user->guardian->students->pluck('id')->toArray();
            if (in_array($attendance->student_id, $childrenIds)) {
                return $next($request);
            }
            abort(403, 'لا يمكنك الوصول إلى سجل حضور ليس لأحد أبنائك');
        }

        abort(403, 'غير مصرح لك بالوصول إلى سجل الحضور');
    }
}