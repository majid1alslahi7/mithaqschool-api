<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request; // إضافة: لاستقبال الطلبات والتعامل مع الفلاتر
use App\Http\Requests\StoreFinalyGradeRequest; // إضافة: استخدام Form Request للتحقق من صحة بيانات الإدخال عند الإنشاء
use App\Http\Requests\UpdateFinalyGradeRequest; // إضافة: استخدام Form Request للتحقق من صحة بيانات الإدخال عند التحديث
use App\Http\Resources\FinalyGradeResource; // إضافة: استخدام api Resource لتوحيد شكل البيانات المُرجعة
use App\Models\FinalyGrades; // تصحيح: تم تصحيح اسم الموديل والمسار الخاص به
use App\Models\Student;

class FinalyGradeController extends Controller // تصحيح: تم تعديل اسم الكلاس ليطابق اسم الملف
{
    /**
     * عرض قائمة بالدرجات النهائية مع إمكانية الفلترة.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function index(Request $request)
    {
        // البدء ببناء الاستعلام مع تحميل العلاقات اللازمة
        $query = FinalyGrades::with(['student', 'course', 'academicYear']);

        // قائمة الحقول المتاحة للفلترة
        $filterableFields = [
            'student_number',
            'course_id',
            'academic_year_id',
            'first_achievement_score',
            'midterm_test',
            'second_achievement_score',
            'final_test',
            'total_score',
        ];
        
        // تطبيق الفلاتر بشكل ديناميكي
        foreach ($filterableFields as $field) {
            if ($request->has($field)) {
                $query->where($field, $request->input($field));
            }
        }

        // إضافة خاصية البحث الشامل
        if ($request->has('search')) {
            $searchTerm = $request->input('search');
            $query->where(function ($q) use ($searchTerm) {
                $q->where('student_number', 'like', "%{$searchTerm}%")
                  ->orWhere('first_achievement_score', 'like', "%{$searchTerm}%")
                  ->orWhere('midterm_test', 'like', "%{$searchTerm}%")
                  ->orWhere('second_achievement_score', 'like', "%{$searchTerm}%")
                  ->orWhere('final_test', 'like', "%{$searchTerm}%")
                  ->orWhere('total_score', 'like', "%{$searchTerm}%")
                  // البحث في الجداول المرتبطة
                  ->orWhereHas('student', function ($studentQuery) use ($searchTerm) {
                      // البحث في الاسم الأول والأخير للطالب
                      $studentQuery->where('f_name', 'like', "%{$searchTerm}%")
                                   ->orWhere('l_name', 'like', "%{$searchTerm}%");
                  })
                  ->orWhereHas('course', function ($courseQuery) use ($searchTerm) {
                      // افتراض وجود حقل 'name' في جدول المواد
                      $courseQuery->where('name', 'like', "%{$searchTerm}%");
                  })
                  ->orWhereHas('academicYear', function ($academicYearQuery) use ($searchTerm) {
                      // افتراض وجود حقل 'year' في جدول السنة الدراسية
                      $academicYearQuery->where('name', 'like', "%{$searchTerm}%");
                  });
            });
        }

        // جلب البيانات بعد تطبيق الفلاتر والبحث
        $finalyGrades = $query->get();

        // إرجاع البيانات باستخدام Resource Collection
        return FinalyGradeResource::collection($finalyGrades);
    }

    /**
     * تخزين درجة نهائية جديدة.
     *
     * @param  \App\Http\Requests\StoreFinalyGradeRequest  $request
     * @return \App\Http\Resources\Json\JsonResource
     */
    public function store(StoreFinalyGradeRequest $request)
    {
        // سيتم التحقق من صحة البيانات تلقائياً بواسطة StoreFinalyGradeRequest
        $finalyGrade = FinalyGrades::create($request->validated()); // تعديل: استخدام validated للحصول على البيانات التي تم التحقق منها فقط
        return new FinalyGradeResource($finalyGrade); // تعديل: إرجاع البيانات باستخدام Resource
    }

    /**
     * عرض درجة نهائية محددة.
     *
     * @param  \App\Models\FinalyGrades  $finalygrade
     * @return \App\Http\Resources\Json\JsonResource
     */
    public function show(FinalyGrades $finalygrade) // تعديل: استخدام Route Model Binding لجلب الموديل تلقائياً
    {
        // تحميل العلاقات المطلوبة
        $finalygrade->load(['student', 'course', 'academicYear']);
        return new FinalyGradeResource($finalygrade); // تعديل: إرجاع البيانات باستخدام Resource
    }

    /**
     * تحديث درجة نهائية محددة.
     *
     * @param  \App\Http\Requests\UpdateFinalyGradeRequest  $request
     * @param  \App\Models\FinalyGrades  $finalygrade
     * @return \App\Http\Resources\Json\JsonResource
     */
    public function update(UpdateFinalyGradeRequest $request, FinalyGrades $finalygrade) // تعديل: استخدام Route Model Binding و Form Request
    {
        // سيتم التحقق من صحة البيانات تلقائياً بواسطة UpdateFinalyGradeRequest
        $finalygrade->update($request->validated()); // تعديل: تحديث البيانات التي تم التحقق منها فقط
        return new FinalyGradeResource($finalygrade); // تعديل: إرجاع البيانات المحدثة باستخدام Resource
    }

    /**
     * حذف درجة نهائية محددة.
     *
     * @param  \App\Models\FinalyGrades  $finalygrade
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(FinalyGrades $finalygrade) // تعديل: استخدام Route Model Binding
    {
        $finalygrade->delete(); // تعديل: حذف الموديل مباشرة
        return response()->json(null, 204); // تعديل: إرجاع استجابة فارغة مع كود 204
    }

    /**
     * عرض درجات طالب معين مجمعة حسب العام الدراسي.
     *
     * @param  string $student_number
     * @return \Illuminate\Http\JsonResponse
     */
    public function getStudentGradesByAcademicYear($student_number)
    {
        // التحقق من وجود الطالب
        $student = Student::where('student_number', $student_number)->first();

        if (!$student) {
            return response()->json(['message' => 'الطالب غير موجود.'], 404);
        }

        // جلب درجات الطالب مع تحميل العلاقات
        $grades = FinalyGrades::where('student_number', $student->student_number)
            ->with(['course', 'academicYear'])
            ->get();

        // تجميع الدرجات حسب العام الدراسي
        $groupedGrades = $grades->groupBy(function ($grade) {
            return $grade->academicYear->name ?? 'عام دراسي غير محدد';
        });

        // تنسيق البيانات للإرجاع
        $formattedResponse = $groupedGrades->map(function ($gradesInYear) {
            return FinalyGradeResource::collection($gradesInYear);
        });

        return response()->json($formattedResponse);
    }
}
