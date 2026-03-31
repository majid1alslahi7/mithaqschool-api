<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CheckStudentOwnership
{
    /**
     * التحقق من أن المستخدم الحالي لديه صلاحية الوصول إلى بيانات الطالب
     * 
     * يستخدم في: عرض/تعديل بيانات طالب معين
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        $user = Auth::user();
        $studentId = $request->route('student') ?? $request->route('student_id');
        
        // إذا لم يكن هناك student_id في المسار، نمرر الطلب
        if (!$studentId) {
            return $next($request);
        }

        // Super-admin و admin يمكنهم الوصول لأي طالب
        if ($user->hasRole(['super-admin', 'admin'])) {
            return $next($request);
        }

        // المعلم: يمكنه الوصول لطلاب المواد التي يدرسها فقط
        if ($user->hasRole('teacher') && $user->teacher) {
            $teacherCourseIds = $user->teacher->courses->pluck('id');
            $studentExists = \App\Models\Student::where('id', $studentId)
                ->whereHas('courses', function($q) use ($teacherCourseIds) {
                    $q->whereIn('course_id', $teacherCourseIds);
                })
                ->exists();
            
            if ($studentExists) {
                return $next($request);
            }
            
            abort(403, 'غير مصرح لك بالوصول إلى بيانات هذا الطالب');
        }

        // الطالب: يمكنه الوصول إلى بياناته فقط
        if ($user->hasRole('student') && $user->student) {
            if ($user->student->id == $studentId) {
                return $next($request);
            }
            abort(403, 'لا يمكنك الوصول إلى بيانات طالب آخر');
        }

        // ولي الأمر: يمكنه الوصول إلى بيانات أبنائه فقط
        if ($user->hasRole('guardian') && $user->guardian) {
            $childrenIds = $user->guardian->students->pluck('id')->toArray();
            if (in_array($studentId, $childrenIds)) {
                return $next($request);
            }
            abort(403, 'لا يمكنك الوصول إلى بيانات طالب ليس من أبنائك');
        }

        abort(403, 'غير مصرح لك بالوصول إلى هذه الصفحة');
    }
}