<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CheckCourseTeacher
{
    /**
     * التحقق من أن المعلم الحالي هو مدرس المادة المطلوبة
     * 
     * يستخدم في: تعديل امتحانات، إضافة درجات، تعديل واجبات
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        $user = Auth::user();
        
        // Super-admin و admin يمكنهم تجاوز هذا التحقق
        if ($user->hasRole(['super-admin', 'admin'])) {
            return $next($request);
        }

        // إذا لم يكن المستخدم معلمًا
        if (!$user->hasRole('teacher') || !$user->teacher) {
            abort(403, 'هذه الصفحة مخصصة للمعلمين فقط');
        }

        $courseId = $request->route('course') ?? $request->route('course_id');
        
        // إذا لم يكن هناك course_id في المسار، نمرر الطلب
        if (!$courseId) {
            return $next($request);
        }

        // التحقق من أن المعلم يدرس هذه المادة
        $isTeacherOfCourse = \App\Models\Course::where('id', $courseId)
            ->where('teacher_id', $user->teacher->id)
            ->exists();

        if (!$isTeacherOfCourse) {
            abort(403, 'غير مصرح لك بالوصول إلى هذه المادة، لأنك لست المدرس المسؤول عنها');
        }

        return $next($request);
    }
}