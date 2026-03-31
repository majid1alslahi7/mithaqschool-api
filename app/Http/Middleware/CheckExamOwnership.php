<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CheckExamOwnership
{
    /**
     * التحقق من أن المستخدم لديه صلاحية الوصول إلى الامتحان
     * يستخدم في: عرض/تعديل/حذف امتحان
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        $user = Auth::user();
        $examId = $request->route('exam') ?? $request->route('exam_id');
        
        if (!$examId) {
            return $next($request);
        }

        // Super-admin و admin يمكنهم الوصول لأي امتحان
        if ($user->hasRole(['super-admin', 'admin'])) {
            return $next($request);
        }

        $exam = \App\Models\Exam::find($examId);
        
        if (!$exam) {
            abort(404, 'الامتحان غير موجود');
        }

        // المعلم: يمكنه الوصول إلى امتحاناته فقط
        if ($user->hasRole('teacher') && $user->teacher) {
            if ($exam->teacher_id == $user->teacher->id) {
                return $next($request);
            }
            abort(403, 'لا يمكنك الوصول إلى امتحان ليس من إنشائك');
        }

        // الطالب: يمكنه الوصول إلى الامتحان فقط إذا كان مسجلاً في المادة
        if ($user->hasRole('student') && $user->student) {
            $isStudentEnrolled = $exam->course->students()
                ->where('student_id', $user->student->id)
                ->exists();
            
            if ($isStudentEnrolled) {
                return $next($request);
            }
            abort(403, 'لا يمكنك الوصول إلى هذا الامتحان لأنك غير مسجل في المادة');
        }

        abort(403, 'غير مصرح لك بالوصول إلى هذا الامتحان');
    }
}