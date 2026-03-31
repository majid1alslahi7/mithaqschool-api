<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Http\Requests\ReportRequest;
use App\Http\Resources\ReportResource;
use App\Models\Student;
use App\Models\Attendance;
use App\Models\ExamResult;
use App\Models\StudentInvoice;
use App\Models\Teacher;
use Illuminate\Support\Facades\DB;

class ReportController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:view_reports');
        $this->middleware('permission:generate_student_report')->only('students');
        $this->middleware('permission:generate_financial_report')->only('financial');
        $this->middleware('permission:generate_attendance_report')->only('attendance');
        $this->middleware('permission:generate_exam_report')->only('exams');
        $this->middleware('permission:generate_teacher_report')->only('teachers');
        $this->middleware('permission:export_reports')->only('export');
    }

    public function students(ReportRequest $request)
    {
        $query = Student::with(['grade', 'classroom', 'guardian']);
        
        if ($request->filled('grade_id')) {
            $query->where('grade_id', $request->grade_id);
        }
        
        if ($request->filled('classroom_id')) {
            $query->where('classroom_id', $request->classroom_id);
        }
        
        if ($request->filled('gender')) {
            $query->where('gender', $request->gender);
        }
        
        $students = $query->get();
        
        $data = [
            'title' => 'تقرير الطلاب',
            'data' => $students->map(function($student) {
                return [
                    'id' => $student->id,
                    'name' => $student->f_name . ' ' . $student->l_name,
                    'enrollment_number' => $student->enrollment_number,
                    'grade' => $student->grade->name ?? '-',
                    'classroom' => $student->classroom->name ?? '-',
                    'guardian' => $student->guardian ? ($student->guardian->f_name . ' ' . $student->guardian->l_name) : '-',
                    'status' => $student->is_active ? 'نشط' : 'غير نشط',
                ];
            }),
            'summary' => [
                'total' => $students->count(),
                'by_gender' => [
                    'male' => $students->where('gender', 'male')->count(),
                    'female' => $students->where('gender', 'female')->count(),
                ],
                'by_grade' => $students->groupBy('grade.name')->map->count(),
            ]
        ];
        
        return $this->formatResponse($data, $request);
    }

    public function financial(ReportRequest $request)
    {
        $query = StudentInvoice::with(['student', 'payments']);
        
        if ($request->filled('start_date')) {
            $query->where('created_at', '>=', $request->start_date);
        }
        
        if ($request->filled('end_date')) {
            $query->where('created_at', '<=', $request->end_date);
        }
        
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        
        $invoices = $query->get();
        
        $totalAmount = $invoices->sum('total_amount');
        $totalPaid = $invoices->sum(function($invoice) {
            return $invoice->payments->sum('amount_paid');
        });
        
        $data = [
            'title' => 'التقرير المالي',
            'data' => $invoices->map(function($invoice) {
                return [
                    'id' => $invoice->id,
                    'invoice_number' => $invoice->invoice_number,
                    'student' => $invoice->student->f_name . ' ' . $invoice->student->l_name,
                    'amount' => $invoice->total_amount,
                    'paid' => $invoice->payments->sum('amount_paid'),
                    'remaining' => $invoice->remaining_amount,
                    'status' => $invoice->status,
                    'due_date' => $invoice->due_date,
                ];
            }),
            'summary' => [
                'total_invoices' => $invoices->count(),
                'total_amount' => $totalAmount,
                'total_paid' => $totalPaid,
                'total_remaining' => $totalAmount - $totalPaid,
                'collection_rate' => $totalAmount > 0 ? round(($totalPaid / $totalAmount) * 100, 2) : 0,
            ]
        ];
        
        return $this->formatResponse($data, $request);
    }

    public function attendance(ReportRequest $request)
    {
        $query = Attendance::with(['student', 'course']);
        
        if ($request->filled('student_id')) {
            $query->where('student_id', $request->student_id);
        }
        
        if ($request->filled('course_id')) {
            $query->where('course_id', $request->course_id);
        }
        
        if ($request->filled('start_date')) {
            $query->where('date', '>=', $request->start_date);
        }
        
        if ($request->filled('end_date')) {
            $query->where('date', '<=', $request->end_date);
        }
        
        $attendances = $query->get();
        
        $present = $attendances->where('status', 'present')->count();
        $absent = $attendances->where('status', 'absent')->count();
        $late = $attendances->where('status', 'late')->count();
        $excused = $attendances->where('status', 'excused')->count();
        
        $data = [
            'title' => 'تقرير الحضور',
            'data' => $attendances->map(function($attendance) {
                return [
                    'id' => $attendance->id,
                    'student' => $attendance->student->f_name . ' ' . $attendance->student->l_name,
                    'course' => $attendance->course->name,
                    'date' => $attendance->date,
                    'status' => $attendance->status,
                    'notes' => $attendance->notes,
                ];
            }),
            'summary' => [
                'total' => $attendances->count(),
                'present' => $present,
                'absent' => $absent,
                'late' => $late,
                'excused' => $excused,
                'attendance_rate' => $attendances->count() > 0 ? round(($present / $attendances->count()) * 100, 2) : 0,
            ]
        ];
        
        return $this->formatResponse($data, $request);
    }

    public function exams(ReportRequest $request)
    {
        $query = ExamResult::with(['student', 'exam.course']);
        
        if ($request->filled('exam_id')) {
            $query->where('exam_id', $request->exam_id);
        }
        
        if ($request->filled('course_id')) {
            $query->whereHas('exam', function($q) use ($request) {
                $q->where('course_id', $request->course_id);
            });
        }
        
        if ($request->filled('student_id')) {
            $query->where('student_id', $request->student_id);
        }
        
        $results = $query->get();
        
        $passed = $results->filter(function($result) {
            return ($result->score / $result->exam->max_score) >= 0.5;
        })->count();
        
        $average = $results->avg('score');
        $maxScore = $results->max('score');
        $minScore = $results->min('score');
        
        $data = [
            'title' => 'تقرير الامتحانات',
            'data' => $results->map(function($result) {
                return [
                    'id' => $result->id,
                    'student' => $result->student->f_name . ' ' . $result->student->l_name,
                    'exam' => $result->exam->name,
                    'course' => $result->exam->course->name,
                    'score' => $result->score,
                    'max_score' => $result->exam->max_score,
                    'percentage' => round(($result->score / $result->exam->max_score) * 100, 2),
                    'passed' => ($result->score / $result->exam->max_score) >= 0.5,
                ];
            }),
            'summary' => [
                'total' => $results->count(),
                'passed' => $passed,
                'failed' => $results->count() - $passed,
                'pass_rate' => $results->count() > 0 ? round(($passed / $results->count()) * 100, 2) : 0,
                'average_score' => round($average, 2),
                'max_score' => round($maxScore, 2),
                'min_score' => round($minScore, 2),
            ]
        ];
        
        return $this->formatResponse($data, $request);
    }

    public function teachers(ReportRequest $request)
    {
        $query = Teacher::with(['grade', 'courses']);
        
        if ($request->filled('grade_id')) {
            $query->where('grade_id', $request->grade_id);
        }
        
        if ($request->filled('gender')) {
            $query->where('gender', $request->gender);
        }
        
        $teachers = $query->get();
        
        $data = [
            'title' => 'تقرير المعلمين',
            'data' => $teachers->map(function($teacher) {
                return [
                    'id' => $teacher->id,
                    'name' => $teacher->f_name . ' ' . $teacher->l_name,
                    'enrollment_number' => $teacher->enrollment_number,
                    'grade' => $teacher->grade->name ?? '-',
                    'courses_count' => $teacher->courses->count(),
                    'hire_date' => $teacher->hire_date,
                    'status' => $teacher->is_active ? 'نشط' : 'غير نشط',
                ];
            }),
            'summary' => [
                'total' => $teachers->count(),
                'by_gender' => [
                    'male' => $teachers->where('gender', 'male')->count(),
                    'female' => $teachers->where('gender', 'female')->count(),
                ],
                'total_courses' => $teachers->sum(function($t) { return $t->courses->count(); }),
            ]
        ];
        
        return $this->formatResponse($data, $request);
    }

    public function export($type, ReportRequest $request)
    {
        $format = $request->input('format', 'excel');
        
        // تحضير البيانات للتصدير
        $data = $this->getReportData($type, $request);
        
        if ($format === 'pdf') {
            return $this->exportToPdf($data);
        }
        
        return $this->exportToExcel($data);
    }

    private function formatResponse($data, $request)
    {
        $format = $request->input('format', 'json');
        
        if ($format === 'pdf') {
            return $this->exportToPdf($data);
        }
        
        if ($format === 'excel') {
            return $this->exportToExcel($data);
        }
        
        return new ReportResource($data);
    }

    private function exportToPdf($data)
    {
        // استخدام مكتبة PDF لتصدير التقرير
        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('reports.pdf', $data);
        return $pdf->download($data['title'] . '.pdf');
    }

    private function exportToExcel($data)
    {
        // استخدام مكتبة Excel لتصدير التقرير
        $excel = \Maatwebsite\Excel\Facades\Excel::download(
            new \App\Exports\ReportExport($data),
            $data['title'] . '.xlsx'
        );
        return $excel;
    }

    private function getReportData($type, $request)
    {
        switch ($type) {
            case 'students':
                return $this->students($request)->getData(true);
            case 'financial':
                return $this->financial($request)->getData(true);
            case 'attendance':
                return $this->attendance($request)->getData(true);
            case 'exams':
                return $this->exams($request)->getData(true);
            case 'teachers':
                return $this->teachers($request)->getData(true);
            default:
                return [];
        }
    }
}
