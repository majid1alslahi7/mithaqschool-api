<?php

namespace App\Observers;

use App\Models\MonthlyGrade;
use App\Models\SemesterGrade;
use App\Models\FinalyGrades;

class GradeObserver
{
    /**
     * بعد إضافة درجة شهرية جديدة
     */
    public function created(MonthlyGrade $monthlyGrade)
    {
        $this->updateSemesterGrade($monthlyGrade);
    }

    /**
     * بعد تحديث درجة شهرية
     */
    public function updated(MonthlyGrade $monthlyGrade)
    {
        $this->updateSemesterGrade($monthlyGrade);
    }

    /**
     * بعد حذف درجة شهرية
     */
    public function deleted(MonthlyGrade $monthlyGrade)
    {
        $this->updateSemesterGrade($monthlyGrade);
    }

    /**
     * تحديث الدرجة الفصلية
     */
    private function updateSemesterGrade(MonthlyGrade $monthlyGrade)
    {
        // جلب جميع الدرجات الشهرية للطالب في نفس المادة والفصل
        $monthlyGrades = MonthlyGrade::where('student_number', $monthlyGrade->student_number)
            ->where('course_id', $monthlyGrade->course_id)
            ->where('semester_id', $monthlyGrade->semester_id)
            ->where('academic_year_id', $monthlyGrade->academic_year_id)
            ->get();

        // حساب مجموع الدرجات الشهرية (total_score محسوب مسبقاً في قاعدة البيانات)
        $totalMonthlyScore = 0;
        $monthsCount = $monthlyGrades->count();

        foreach ($monthlyGrades as $grade) {
            $totalMonthlyScore += $grade->total_score; // total_score من قاعدة البيانات
        }

        // حساب متوسط الدرجة الشهرية
        // القسمة على 4 لأن كل شهر له 4 مواد (تحريري، واجب، شفوي، حضور)
        $averageMonthlyScore = $monthsCount > 0 ? ($totalMonthlyScore / $monthsCount) / 4 : 0;

        // حساب الدرجة الفصلية (40% من متوسط الدرجات الشهرية)
        $semesterWork = $averageMonthlyScore * 0.4;

        // البحث عن الدرجة الفصلية أو إنشاؤها
        $semesterGrade = SemesterGrade::updateOrCreate(
            [
                'student_number' => $monthlyGrade->student_number,
                'course_id' => $monthlyGrade->course_id,
                'semester_id' => $monthlyGrade->semester_id,
                'academic_year_id' => $monthlyGrade->academic_year_id,
            ],
            [
                'semester_work' => $semesterWork,
            ]
        );

        // تحديث الدرجة الفصلية
        $semesterGrade->semester_work = $semesterWork;
        $semesterGrade->save();

        // تحديث الدرجة النهائية
        $this->updateFinalGrade($semesterGrade);
    }

    /**
     * تحديث الدرجة النهائية
     */
    private function updateFinalGrade(SemesterGrade $semesterGrade)
    {
        // جلب جميع الدرجات الفصلية للطالب في نفس المادة
        $semesterGrades = SemesterGrade::where('student_number', $semesterGrade->student_number)
            ->where('course_id', $semesterGrade->course_id)
            ->where('academic_year_id', $semesterGrade->academic_year_id)
            ->get();

        // حساب مجموع الدرجات الفصلية
        $totalSemesterScore = 0;
        $semestersCount = $semesterGrades->count();

        foreach ($semesterGrades as $grade) {
            $totalSemesterScore += ($grade->semester_work ?? 0);
            $totalSemesterScore += ($grade->exam_semester ?? 0);
        }

        // حساب المعدل الفصلي
        $averageSemesterScore = $semestersCount > 0 ? $totalSemesterScore / $semestersCount : 0;

        // الدرجة النهائية = المعدل الفصلي (70%) + امتحان نهائي (30%)
        $finalTestScore = $semesterGrade->exam_semester ?? 0;
        $totalFinalScore = ($averageSemesterScore * 0.7) + ($finalTestScore * 0.3);

        // البحث عن الدرجة النهائية أو إنشاؤها
        $finalGrade = FinalyGrades::updateOrCreate(
            [
                'student_number' => $semesterGrade->student_number,
                'course_id' => $semesterGrade->course_id,
                'academic_year_id' => $semesterGrade->academic_year_id,
            ],
            [
                'total_score' => $totalFinalScore,
            ]
        );

        // تحديث الدرجة النهائية
        $finalGrade->total_score = $totalFinalScore;
        $finalGrade->save();
    }
}
