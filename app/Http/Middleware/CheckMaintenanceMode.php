<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CheckMaintenanceMode
{
    /**
     * التحقق من وضع الصيانة
     * فقط المدير و super-admin يمكنهم الدخول أثناء الصيانة
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        // التحقق من وجود ملف الصيانة أو متغير في قاعدة البيانات
        $isUnderMaintenance = false; // يمكنك جلب هذه القيمة من الإعدادات
        
        if ($isUnderMaintenance) {
            $user = Auth::user();
            
            // إذا لم يكن المستخدم مسجل دخول أو ليس له صلاحية
            if (!$user || !$user->hasRole(['super-admin', 'admin'])) {
                return response()->view('maintenance', [], 503);
            }
        }

        return $next($request);
    }
}