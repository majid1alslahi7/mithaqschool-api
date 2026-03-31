<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreAcademicYearRequest;
use App\Http\Requests\UpdateAcademicYearRequest;
use App\Http\Resources\AcademicYearResource;
use App\Models\AcademicYear;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class AcademicYearController extends Controller
{
    public function __construct()
    {
        // تطبيق الميدلويرات على مستوى الكونترولر
        $this->middleware('permission:view_any_academic_year')->only('index');
        $this->middleware('permission:view_academic_year')->only('show');
        $this->middleware('permission:create_academic_year')->only('store');
        $this->middleware('permission:update_academic_year')->only('update');
        $this->middleware('permission:delete_academic_year')->only('destroy');
    }

    public function index(Request $request)
    {
        $query = AcademicYear::with('semesters');

        if ($request->has('search')) {
            $query->search($request->input('search'));
        }

        // فلترة حسب النشاط
        if ($request->has('is_active')) {
            $query->where('is_active', $request->boolean('is_active'));
        }

        return AcademicYearResource::collection($query->paginate(15));
    }

    public function store(StoreAcademicYearRequest $request)
    {
        // إذا كانت السنة الجديدة نشطة، قم بتعطيل السنوات الأخرى
        if ($request->is_active) {
            AcademicYear::where('is_active', true)->update(['is_active' => false]);
        }
        
        $academicYear = AcademicYear::create($request->validated());
        return new AcademicYearResource($academicYear);
    }

    public function show(AcademicYear $academicYear)
    {
        $this->authorize('view', $academicYear);
        return new AcademicYearResource($academicYear);
    }

    public function update(UpdateAcademicYearRequest $request, AcademicYear $academicYear)
    {
        $this->authorize('update', $academicYear);
        
        // إذا كانت السنة الجديدة نشطة، قم بتعطيل السنوات الأخرى
        if ($request->is_active && !$academicYear->is_active) {
            AcademicYear::where('is_active', true)->update(['is_active' => false]);
        }
        
        $academicYear->update($request->validated());
        return new AcademicYearResource($academicYear);
    }

    public function destroy(AcademicYear $academicYear)
    {
        $this->authorize('delete', $academicYear);
        $academicYear->delete();
        return response()->json(null, Response::HTTP_NO_CONTENT);
    }
}