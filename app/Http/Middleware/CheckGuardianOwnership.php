<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CheckGuardianOwnership
{
    /**
     * التحقق من أن المستخدم الحالي لديه صلاحية الوصول إلى بيانات ولي الأمر
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        $user = Auth::user();
        $guardianId = $request->route('guardian') ?? $request->route('guardian_id');
        
        if (!$guardianId) {
            return $next($request);
        }

        // Super-admin و admin يمكنهم الوصول لأي ولي أمر
        if ($user->hasRole(['super-admin', 'admin'])) {
            return $next($request);
        }

        // ولي الأمر: يمكنه الوصول إلى بياناته فقط
        if ($user->hasRole('guardian') && $user->guardian) {
            if ($user->guardian->id == $guardianId) {
                return $next($request);
            }
            abort(403, 'لا يمكنك الوصول إلى بيانات ولي أمر آخر');
        }

        abort(403, 'غير مصرح لك بالوصول إلى هذه الصفحة');
    }
}