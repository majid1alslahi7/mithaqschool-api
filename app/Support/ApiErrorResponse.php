<?php

namespace App\Support;

use Illuminate\Database\QueryException;
use Illuminate\Http\JsonResponse;

class ApiErrorResponse
{
    public static function make(string $message, int $status, array $errors = [], array $extra = []): JsonResponse
    {
        $payload = array_merge([
            'success' => false,
            'status' => $status,
            'message' => $message,
        ], $extra);

        if ($errors !== []) {
            $payload['errors'] = $errors;
        }

        return response()->json($payload, $status);
    }

    public static function firstValidationMessage(array $errors, string $fallback = 'تعذر التحقق من البيانات المرسلة.'): string
    {
        foreach ($errors as $messages) {
            if (is_array($messages) && isset($messages[0]) && is_string($messages[0])) {
                return $messages[0];
            }
        }

        return $fallback;
    }

    public static function messageForStatus(int $status, ?string $message = null): string
    {
        $normalized = trim((string) $message);

        if ($normalized !== '' && ! in_array($normalized, [
            'Unauthorized',
            'Forbidden',
            'Not Found',
            'Method Not Allowed',
            'Too Many Requests',
            'Server Error',
            'Unauthenticated.',
        ], true)) {
            return $normalized;
        }

        return match ($status) {
            400 => 'تعذر تنفيذ الطلب. يرجى مراجعة البيانات المرسلة.',
            401 => 'يجب تسجيل الدخول أولاً للوصول إلى هذا المورد.',
            403 => 'ليس لديك صلاحية لتنفيذ هذا الإجراء.',
            404 => 'المورد المطلوب غير موجود.',
            405 => 'طريقة الطلب غير مدعومة لهذا المسار.',
            409 => 'تعذر تنفيذ العملية بسبب تعارض في البيانات.',
            422 => 'تعذر تنفيذ الطلب بسبب خطأ في البيانات المرسلة.',
            429 => 'تم إرسال عدد كبير من الطلبات. يرجى المحاولة بعد قليل.',
            default => 'حدث خطأ غير متوقع في الخادم. يرجى المحاولة لاحقاً.',
        };
    }

    public static function modelNotFoundMessage(?string $modelClass): string
    {
        $label = self::modelLabel($modelClass);

        return $label === 'السجل'
            ? 'السجل المطلوب غير موجود.'
            : "{$label} المطلوب غير موجود.";
    }

    public static function queryExceptionStatus(QueryException $exception): int
    {
        return ($exception->errorInfo[0] ?? null) === '23000' ? 409 : 500;
    }

    public static function queryExceptionMessage(QueryException $exception): string
    {
        $sqlState = $exception->errorInfo[0] ?? null;
        $driverCode = $exception->errorInfo[1] ?? null;

        if ($sqlState === '23000') {
            if (in_array($driverCode, [1451, 1452], true)) {
                return 'تعذر تنفيذ العملية لارتباط هذا السجل ببيانات أخرى.';
            }

            return 'تعذر تنفيذ العملية بسبب تعارض في البيانات أو تكرار قيمة فريدة.';
        }

        return 'حدث خطأ في قاعدة البيانات أثناء تنفيذ الطلب.';
    }

    private static function modelLabel(?string $modelClass): string
    {
        return match (class_basename((string) $modelClass)) {
            'AcademicYear' => 'العام الدراسي',
            'Adjustment' => 'التسوية',
            'Attendance' => 'سجل الحضور',
            'BehaviorEvaluation' => 'تقييم السلوك',
            'ClassFee' => 'رسوم الصف',
            'Classroom' => 'الفصل',
            'Course' => 'المقرر',
            'CourseClassroomTeacher' => 'ربط المقرر والفصل والمعلم',
            'Exam' => 'الاختبار',
            'ExamResult' => 'نتيجة الاختبار',
            'ExamType' => 'نوع الاختبار',
            'FeeType' => 'نوع الرسوم',
            'FinalyGrades', 'FinalyGrade' => 'الدرجة النهائية',
            'Grade' => 'الصف',
            'GradesScale' => 'سلم الدرجات',
            'Guardian' => 'ولي الأمر',
            'Homework' => 'الواجب',
            'HomeworkSubmission' => 'تسليم الواجب',
            'InvoiceItem' => 'بند الفاتورة',
            'Message' => 'الرسالة',
            'MonthlyGrade' => 'الدرجة الشهرية',
            'Notification' => 'الإشعار',
            'Payment' => 'الدفعة',
            'Permission' => 'الصلاحية',
            'Role' => 'الدور',
            'Schedule' => 'الجدول',
            'School' => 'المدرسة',
            'SchoolStage' => 'المرحلة الدراسية',
            'Semester' => 'الفصل الدراسي',
            'SemesterGrade' => 'درجة الفصل الدراسي',
            'Session' => 'الجلسة',
            'Student' => 'الطالب',
            'StudentGrade' => 'درجة الطالب',
            'StudentInvoice' => 'فاتورة الطالب',
            'Teacher' => 'المعلم',
            'TeacherCourse' => 'ربط المعلم بالمقرر',
            'User' => 'المستخدم',
            default => 'السجل',
        };
    }
}
