<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CheckGradeOwnership
{
    /**
     * التحقق من أن المستخدم لديه صلاحية الوصول إلى الدرجات
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        $user = Auth::user();
        $gradeId = $request->route('grade') ?? $request->route('grade_id');
        
        if (!$gradeId) {
            return $next($request);
        }

        // Super-admin و admin يمكنهم الوصول لأي درجة
        if ($user->hasRole(['super-admin', 'admin'])) {
            return $next($request);
        }

        // محاولة العثور على الدرجة في أي من الجداول
        $grade = null;
        
        // البحث في MonthlyGrade
        $monthlyGrade = \App\Models\MonthlyGrade::find($gradeId);
        if ($monthlyGrade) {
            $grade = $monthlyGrade;
            $studentNumber = $monthlyGrade->student_number;
            $courseId = $monthlyGrade->course_id;
        }
        
        // البحث في SemesterGrade
        if (!$grade) {
            $semesterGrade = \App\Models\SemesterGrade::find($gradeId);
            if ($semesterGrade) {
                $grade = $semesterGrade;
                $studentNumber = $semesterGrade->student_number;
                $courseId = $semesterGrade->course_id;
            }
        }
        
        // البحث في FinalyGrades
        if (!$grade) {
            $finalGrade = \App\Models\FinalyGrades::find($gradeId);
            if ($finalGrade) {
                $grade = $finalGrade;
                $studentNumber = $finalGrade->student_number;
                $courseId = $finalGrade->course_id;
            }
        }
        
        // البحث في ExamResult
        if (!$grade) {
            $examResult = \App\Models\ExamResult::find($gradeId);
            if ($examResult) {
                $grade = $examResult;
                $studentNumber = $examResult->student->enrollment_number ?? null;
                $courseId = $examResult->exam->course_id ?? null;
            }
        }
        
        if (!$grade) {
            abort(404, 'الدرجة غير موجودة');
        }

        // المعلم: يمكنه الوصول إلى درجات المواد التي يدرسها فقط
        if ($user->hasRole('teacher') && $user->teacher) {
            $course = \App\Models\Course::find($courseId);
            if ($course && $course->teacher_id == $user->teacher->id) {
                return $next($request);
            }
            abort(403, 'لا يمكنك الوصول إلى درجات مادة لا تدرسها');
        }

        // الطالب: يمكنه الوصول إلى درجاته فقط
        if ($user->hasRole('student') && $user->student) {
            if ($user->student->enrollment_number == $studentNumber) {
                return $next($request);
            }
            abort(403, 'لا يمكنك الوصول إلى درجات طالب آخر');
        }

        // ولي الأمر: يمكنه الوصول إلى درجات أبنائه فقط
        if ($user->hasRole('guardian') && $user->guardian) {
            $childrenNumbers = $user->guardian->students->pluck('enrollment_number')->toArray();
            if (in_array($studentNumber, $childrenNumbers)) {
                return $next($request);
            }
            abort(403, 'لا يمكنك الوصول إلى درجات ليس لأحد أبنائك');
        }

        abort(403, 'غير مصرح لك بالوصول إلى هذه الدرجة');
    }
}