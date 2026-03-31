<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreStudentRequest;
use App\Http\Requests\UpdateStudentRequest;
use App\Http\Requests\UpdateStudentAvatarRequest;
use App\Http\Resources\StudentResource;
use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use App\Models\StudentInvoice;
use App\Models\Payment;
use App\Services\SystemNotificationService;

// 🔥 إضافة الحدث
use App\Events\StudentRegistered;

class StudentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $students = Student::with(['user', 'guardian', 'classroom', 'grade'])
            ->applyapiFiltersAndSort($request)
            ->paginate($request->input('per_page', 15));

        return StudentResource::collection($students);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreStudentRequest $request)
    {
        // 1) إنشاء الطالب
        $student = Student::create($request->validated());

        // 2) تحميل العلاقات الأساسية
        $student->load(['user', 'guardian', 'classroom', 'grade']);

        // 3) إطلاق الحدث لإنشاء الفاتورة تلقائيًا
        event(new StudentRegistered($student));

        // 4) إشعار جميع المستخدمين
        $studentName = trim(($student->f_name ?? '') . ' ' . ($student->l_name ?? ''));
        $message = $studentName !== '' ? "تمت إضافة طالب جديد: {$studentName}." : 'تمت إضافة طالب جديد.';
        app(SystemNotificationService::class)->notifyAllUsers('إضافة طالب', $message);

        // 5) إرجاع الطالب
        return new StudentResource($student);
    }

    /**
     * Display the specified resource.
     */
    public function show(Student $student)
    {
        return new StudentResource($student->load(['user', 'guardian', 'classroom', 'grade']));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateStudentRequest $request, Student $student)
    {
        $student->update($request->validated());
        $student->load(['user', 'guardian', 'classroom', 'grade']);
        return new StudentResource($student);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Student $student)
    {
        $student->delete();
        return response()->json(null, Response::HTTP_NO_CONTENT);
    }

    /**
     * Get the avatar for the specified student.
     */
    public function getAvatar(Student $student)
    {
        return response()->json([
            'avatar_url' => $student->avatar_url,
        ]);
    }

    /**
     * Update the avatar for the specified student.
     */
    public function updateAvatar(UpdateStudentAvatarRequest $request, Student $student)
    {
        if ($student->avatar_path) {
            Storage::disk('public')->delete($student->avatar_path);
        }

        $path = $request->file('avatar')->store('avatars', 'public');

        $student->update(['avatar_path' => $path]);

        return new StudentResource($student);
    }

    /**
     * Delete the avatar for the specified student.
     */
    public function deleteAvatar(Student $student)
    {
        if ($student->avatar_path) {
            Storage::disk('public')->delete($student->avatar_path);
        }

        $student->update(['avatar_path' => null]);

        return new StudentResource($student);
    }

    /**
     * Get student statistics.
     */
    public function stats()
    {
        $totalStudents = Student::count();

        $studentsPerGrade = Student::query()
            ->join('grades', 'students.grade_id', '=', 'grades.id')
            ->select('grades.name as grade_name', DB::raw('count(*) as total'))
            ->groupBy('grades.name')
            ->pluck('total', 'grade_name');

        $studentsPerGender = Student::query()
            ->select('gender', DB::raw('count(*) as total'))
            ->groupBy('gender')
            ->pluck('total', 'gender');

        return response()->json([
            'data' => [
                'total_students' => $totalStudents,
                'students_per_grade' => $studentsPerGrade,
                'students_per_gender' => $studentsPerGender,
            ]
        ]);
    }
  public function billingSummary($studentId)
    {
        // 1) جلب كل الفواتير للطالب وحساب الأصل ديناميكيًا لكل فاتورة
        $invoices = StudentInvoice::where('student_id', $studentId)
            ->get()
            ->map(function($invoice) {
                $invoice->original_total = $invoice->total_amount + $invoice->total_discounts - $invoice->total_fines;
                return $invoice;
            });

        // 2) إجمالي أصل الفواتير
        $originalInvoices = $invoices->sum('original_total');

        // 3) إجمالي الفواتير بعد الخصم والغرامة
        $adjustedInvoices = $invoices->sum('total_amount');

        // 4) إجمالي الخصومات
        $totalDiscounts = $invoices->sum('total_discounts');

        // 5) إجمالي الغرامات
        $totalFines = $invoices->sum('total_fines');

        // 6) إجمالي المدفوعات
        $totalPayments = Payment::where('student_id', $studentId)->sum('amount_paid');

        // 7) المتبقي بعد الدفع
        $remaining = $adjustedInvoices - $totalPayments;
        if ($remaining < 0) {
            $remaining = 0;
        }

        // 8) إرجاع JSON جاهز
        return response()->json([
            'student_id'         => $studentId,
            'original_invoices'  => $originalInvoices,   // أصل الفواتير قبل الخصم
            'adjusted_invoices'  => $adjustedInvoices,   // بعد الخصم والغرامة
            'total_payments'     => $totalPayments,
            'total_discounts'    => $totalDiscounts,
            'total_fines'        => $totalFines,
            'remaining'          => $remaining,
            'invoices'           => $invoices,
            'payments'           => Payment::where('student_id', $studentId)->get(),
        ]);
    }
}
