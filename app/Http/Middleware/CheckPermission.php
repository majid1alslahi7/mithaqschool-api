<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CheckPermission
{
    /**
     * التحقق من وجود صلاحية معينة مع إمكانية تخصيص رسالة الخطأ
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string  $permission
     * @param  string|null  $message
     * @return mixed
     */
    public function handle(Request $request, Closure $next, $Permission, $message = null)
    {
        $user = Auth::user();
        
        if (!$user) {
            return redirect()->route('login');
        }
        
        if (!$user->can($permission)) {
            $errorMessage = $message ?? 'ليس لديك الصلاحية اللازمة للوصول إلى هذه الصفحة';
            
            if ($request->expectsJson()) {
                return response()->json([
                    'message' => $errorMessage,
                    'status' => 'error'
                ], 403);
            }
            
            abort(403, $errorMessage);
        }
        
        return $next($request);
    }
}