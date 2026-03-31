<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Models\ExamResult;
use App\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ExamResultController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return ExamResult::with(['student', 'exam'])->get();
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'student_id' => 'required|exists:students,id',
            'exam_id' => 'required|exists:exams,id',
            'score' => 'required|numeric|min:0',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

        $examResult = ExamResult::create($request->all());
        $this->sendExamResultNotifications($examResult);
        return response()->json($examResult, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        return ExamResult::with(['student', 'exam'])->findOrFail($id);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $examResult = ExamResult::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'student_id' => 'required|exists:students,id',
            'exam_id' => 'required|exists:exams,id',
            'score' => 'required|numeric|min:0',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

        $examResult->update($request->all());
        return response()->json($examResult, 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        ExamResult::findOrFail($id)->delete();
        return response()->json(null, 204);
    }

    private function sendExamResultNotifications(ExamResult $examResult): void
    {
        $examResult->load([
            'student.user',
            'student.guardian.user',
            'exam.course',
            'exam.teacher.user',
        ]);

        $student = $examResult->student;
        if (!$student) {
            return;
        }

        $studentName = trim(($student->f_name ?? '') . ' ' . ($student->l_name ?? ''));
        $examName = $examResult->exam?->name ?? 'الاختبار';
        $courseName = $examResult->exam?->course?->name;
        $scoreText = is_null($examResult->score) ? '' : " الدرجة: {$examResult->score}";
        $courseSuffix = $courseName ? " مادة {$courseName}" : '';

        $studentUserId = $student->user_id;
        if ($studentUserId) {
            $message = "تمت إضافة نتيجتك في {$examName}{$courseSuffix}.";
            $message .= $scoreText;
            Notification::create([
                'title' => 'نتيجة امتحان جديدة',
                'message' => $message,
                'user_id' => $studentUserId,
            ]);
        }

        $guardianUserId = $student->guardian?->user_id;
        if ($guardianUserId) {
            $nameSuffix = $studentName ? " للطالب {$studentName}" : '';
            $message = "تمت إضافة نتيجة{$nameSuffix} في {$examName}{$courseSuffix}.";
            $message .= $scoreText;
            Notification::create([
                'title' => 'نتيجة امتحان جديدة',
                'message' => $message,
                'user_id' => $guardianUserId,
            ]);
        }

        $teacherUserId = $examResult->exam?->teacher?->user_id;
        if ($teacherUserId) {
            $nameSuffix = $studentName ? " للطالب {$studentName}" : '';
            $message = "تم تسجيل نتيجة{$nameSuffix} في {$examName}{$courseSuffix}.";
            $message .= $scoreText;
            Notification::create([
                'title' => 'تم رصد نتيجة طالب',
                'message' => $message,
                'user_id' => $teacherUserId,
            ]);
        }
    }
}
