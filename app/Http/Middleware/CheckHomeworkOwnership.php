<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CheckHomeworkOwnership
{
    /**
     * التحقق من أن المستخدم لديه صلاحية الوصول إلى الواجب المنزلي
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        $user = Auth::user();
        $homeworkId = $request->route('homework') ?? $request->route('homework_id');
        
        if (!$homeworkId) {
            return $next($request);
        }

        // Super-admin و admin يمكنهم الوصول لأي واجب
        if ($user->hasRole(['super-admin', 'admin'])) {
            return $next($request);
        }

        $homework = \App\Models\Homework::find($homeworkId);
        
        if (!$homework) {
            abort(404, 'الواجب المنزلي غير موجود');
        }

        // المعلم: يمكنه الوصول إلى واجباته فقط
        if ($user->hasRole('teacher') && $user->teacher) {
            if ($homework->teacher_id == $user->teacher->id) {
                return $next($request);
            }
            abort(403, 'لا يمكنك الوصول إلى واجب ليس من إنشائك');
        }

        // الطالب: يمكنه الوصول إلى واجباته فقط
        if ($user->hasRole('student') && $user->student) {
            if ($homework->student_id == $user->student->id) {
                return $next($request);
            }
            abort(403, 'لا يمكنك الوصول إلى واجب ليس مخصصاً لك');
        }

        // ولي الأمر: يمكنه الوصول إلى واجبات أبنائه فقط
        if ($user->hasRole('guardian') && $user->guardian) {
            $childrenIds = $user->guardian->students->pluck('id')->toArray();
            if (in_array($homework->student_id, $childrenIds)) {
                return $next($request);
            }
            abort(403, 'لا يمكنك الوصول إلى واجب ليس لأحد أبنائك');
        }

        abort(403, 'غير مصرح لك بالوصول إلى هذا الواجب');
    }
}