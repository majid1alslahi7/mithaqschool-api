<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CheckScheduleOwnership
{
    /**
     * التحقق من أن المستخدم لديه صلاحية الوصول إلى الجدول الدراسي
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        $user = Auth::user();
        $scheduleId = $request->route('schedule') ?? $request->route('schedule_id');
        
        if (!$scheduleId) {
            return $next($request);
        }

        // Super-admin و admin يمكنهم الوصول لأي جدول
        if ($user->hasRole(['super-admin', 'admin'])) {
            return $next($request);
        }

        $schedule = \App\Models\Schedule::find($scheduleId);
        
        if (!$schedule) {
            abort(404, 'الجدول غير موجود');
        }

        // المعلم: يمكنه الوصول إلى جداول المواد التي يدرسها فقط
        if ($user->hasRole('teacher') && $user->teacher) {
            if ($schedule->teacher_id == $user->teacher->id) {
                return $next($request);
            }
            abort(403, 'لا يمكنك الوصول إلى جدول مادة لا تدرسها');
        }

        // الطالب: يمكنه الوصول إلى جدول المواد المسجل فيها فقط
        if ($user->hasRole('student') && $user->student) {
            $isStudentEnrolled = $schedule->course->students()
                ->where('student_id', $user->student->id)
                ->exists();
            
            if ($isStudentEnrolled) {
                return $next($request);
            }
            abort(403, 'لا يمكنك الوصول إلى جدول مادة غير مسجل فيها');
        }

        abort(403, 'غير مصرح لك بالوصول إلى هذا الجدول');
    }
}