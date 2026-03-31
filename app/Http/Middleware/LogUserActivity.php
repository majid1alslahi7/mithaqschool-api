<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class LogUserActivity
{
    /**
     * تسجيل نشاط المستخدم (آخر نشاط، الصفحات التي زارها، إلخ)
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        $user = Auth::user();
        
        if ($user) {
            // تحديث آخر نشاط للمستخدم
            $user->last_activity_at = now();
            $user->save();
            
            // تسجيل الصفحات التي يزورها (للتتبع - يمكن تعطيله في الإنتاج)
            if (config('app.debug')) {
                Log::info('User Activity', [
                    'user_id' => $user->id,
                    'user_type' => $user->getRoleNames()->first(),
                    'url' => $request->fullUrl(),
                    'method' => $request->method(),
                    'ip' => $request->ip(),
                    'user_agent' => $request->userAgent(),
                ]);
            }
        }
        
        return $next($request);
    }
}