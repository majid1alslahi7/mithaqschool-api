<?php

namespace App\Console\Commands;

use App\Models\MonthlyGrade;
use App\Models\SemesterGrade;
use App\Models\FinalyGrades;
use Illuminate\Console\Command;

class RecalculateGrades extends Command
{
    protected $signature = 'grades:recalculate';
    protected $description = 'إعادة حساب جميع الدرجات الفصلية والنهائية';

    public function handle()
    {
        $this->info('بدء إعادة حساب الدرجات...');

        // جلب جميع الطلاب الذين لديهم درجات
        $students = MonthlyGrade::distinct('student_number')->pluck('student_number');
        
        if ($students->isEmpty()) {
            $this->warn('لا توجد درجات شهرية لإعادة حسابها');
            return 0;
        }

        $bar = $this->output->createProgressBar($students->count());
        $bar->start();

        foreach ($students as $studentNumber) {
            // جلب جميع المواد للطالب
            $courses = MonthlyGrade::where('student_number', $studentNumber)
                ->distinct('course_id')
                ->pluck('course_id');
            
            foreach ($courses as $courseId) {
                // جلب جميع الفصول للطالب في هذه المادة
                $semesters = MonthlyGrade::where('student_number', $studentNumber)
                    ->where('course_id', $courseId)
                    ->distinct('semester_id')
                    ->pluck('semester_id');
                
                foreach ($semesters as $semesterId) {
                    $yearId = MonthlyGrade::where('student_number', $studentNumber)
                        ->where('course_id', $courseId)
                        ->where('semester_id', $semesterId)
                        ->value('academic_year_id');
                    
                    // حساب الدرجة الفصلية
                    $monthlyGrades = MonthlyGrade::where('student_number', $studentNumber)
                        ->where('course_id', $courseId)
                        ->where('semester_id', $semesterId)
                        ->where('academic_year_id', $yearId)
                        ->get();
                    
                    $totalMonthlyScore = 0;
                    foreach ($monthlyGrades as $grade) {
                        $totalMonthlyScore += $grade->total_score;
                    }
                    
                    $monthsCount = $monthlyGrades->count();
                    $averageMonthlyScore = $monthsCount > 0 ? ($totalMonthlyScore / $monthsCount) / 4 : 0;
                    $semesterWork = round($averageMonthlyScore * 0.4, 2);
                    
                    // تحديث أو إنشاء الدرجة الفصلية
                    $semesterGrade = SemesterGrade::updateOrCreate(
                        [
                            'student_number' => $studentNumber,
                            'course_id' => $courseId,
                            'semester_id' => $semesterId,
                            'academic_year_id' => $yearId,
                        ],
                        [
                            'semester_work' => $semesterWork,
                            'exam_semester' => 0,
                        ]
                    );
                    
                    $semesterGrade->semester_work = $semesterWork;
                    $semesterGrade->save();
                }
                
                // حساب الدرجة النهائية
                $semesterGrades = SemesterGrade::where('student_number', $studentNumber)
                    ->where('course_id', $courseId)
                    ->where('academic_year_id', $yearId)
                    ->get();
                
                $totalSemesterScore = 0;
                foreach ($semesterGrades as $sg) {
                    $totalSemesterScore += ($sg->semester_work ?? 0);
                }
                
                $semestersCount = $semesterGrades->count();
                $averageSemesterScore = $semestersCount > 0 ? $totalSemesterScore / $semestersCount : 0;
                $totalFinalScore = round($averageSemesterScore * 0.7, 2);
                
                $finalGrade = FinalyGrades::updateOrCreate(
                    [
                        'student_number' => $studentNumber,
                        'course_id' => $courseId,
                        'academic_year_id' => $yearId,
                    ],
                    [
                        'total_score' => $totalFinalScore,
                    ]
                );
                
                $finalGrade->total_score = $totalFinalScore;
                $finalGrade->save();
            }
            
            $bar->advance();
        }
        
        $bar->finish();
        $this->newLine();
        $this->info('✅ تم إعادة حساب جميع الدرجات بنجاح');
        
        return 0;
    }
}
