<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CheckTeacherOwnership
{
    /**
     * التحقق من أن المستخدم الحالي لديه صلاحية الوصول إلى بيانات المعلم
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        $user = Auth::user();
        $teacherId = $request->route('teacher') ?? $request->route('teacher_id');
        
        if (!$teacherId) {
            return $next($request);
        }

        // Super-admin و admin يمكنهم الوصول لأي معلم
        if ($user->hasRole(['super-admin', 'admin'])) {
            return $next($request);
        }

        // المعلم: يمكنه الوصول إلى بياناته فقط
        if ($user->hasRole('teacher') && $user->teacher) {
            if ($user->teacher->id == $teacherId) {
                return $next($request);
            }
            abort(403, 'لا يمكنك الوصول إلى بيانات معلم آخر');
        }

        abort(403, 'غير مصرح لك بالوصول إلى هذه الصفحة');
    }
}