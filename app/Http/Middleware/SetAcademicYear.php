<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Models\AcademicYear;

class SetAcademicYear
{
    /**
     * تعيين السنة الدراسية الحالية في session
     * يمكن للمستخدم تغييرها من خلال واجهة الإعدادات
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        // إذا لم تكن هناك سنة دراسية محددة في الجلسة
        if (!session()->has('academic_year_id')) {
            // جلب السنة الدراسية النشطة
            $activeYear = AcademicYear::where('is_active', true)->first();
            
            if ($activeYear) {
                session(['academic_year_id' => $activeYear->id]);
                session(['academic_year_name' => $activeYear->name]);
            }
        }

        // إتاحة السنة الدراسية الحالية لجميع الـ Views
        view()->share('currentAcademicYear', session('academic_year_id'));
        
        return $next($request);
    }
}