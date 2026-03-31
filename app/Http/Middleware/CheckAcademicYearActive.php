<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Models\AcademicYear;

class CheckAcademicYearActive
{
    /**
     * التحقق من وجود سنة دراسية نشطة
     * إذا لم تكن هناك سنة نشطة، يتم التوجيه إلى صفحة الإعدادات للمدير
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        $activeYear = AcademicYear::where('is_active', true)->first();
        
        // إذا لم تكن هناك سنة دراسية نشطة
        if (!$activeYear) {
            $user = auth()->user();
            
            // إذا كان المستخدم مديرًا، يمكنه الذهاب لإعداد السنة الدراسية
            if ($user && $user->hasRole(['super-admin', 'admin'])) {
                return redirect()->route('admin.academic-years.index')
                    ->with('warning', 'الرجاء تحديد سنة دراسية نشطة أولاً');
            }
            
            // للمستخدمين الآخرين، عرض صفحة خطأ
            abort(503, 'النظام في وضع الإعداد، يرجى التواصل مع الإدارة');
        }
        
        return $next($request);
    }
}