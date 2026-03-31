<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RedirectBasedOnRole
{
    /**
     * توجيه المستخدم إلى لوحة التحكم المناسبة حسب دوره بعد تسجيل الدخول
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        $user = Auth::user();

        if (!$user) {
            return redirect()->route('login');
        }

        // إذا كان المستخدم يحاول الوصول إلى صفحة dashboard العامة
        if ($request->route()->getName() === 'dashboard' || $request->path() === 'dashboard') {
            
            // توجيه حسب الدور
            if ($user->hasRole('super-admin') || $user->hasRole('admin')) {
                return redirect()->route('admin.dashboard');
            }
            
            if ($user->hasRole('teacher')) {
                return redirect()->route('teacher.dashboard');
            }
            
            if ($user->hasRole('student')) {
                return redirect()->route('student.dashboard');
            }
            
            if ($user->hasRole('guardian')) {
                return redirect()->route('parent.dashboard');
            }
        }

        return $next($request);
    }
}