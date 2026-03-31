<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Http\Requests\StoreCourseRequest;
use App\Http\Requests\UpdateCourseRequest;
use App\Http\Resources\CourseResource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CourseController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $courses = Course::where('is_deleted', false)->paginate(15);
        return CourseResource::collection($courses);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreCourseRequest $request)
    {
        $course = Course::create($request->validated());

        return (new CourseResource($course))
            ->response()
            ->setStatusCode(201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $course = Course::where('is_deleted', false)->find($id);

        if (! $course) {
            return response()->json(['message' => 'المقرر المطلوب غير موجود.'], 404);
        }

        return new CourseResource($course);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateCourseRequest $request, string $id)
    {
        $course = Course::where('is_deleted', false)->find($id);

        if (! $course) {
            return response()->json(['message' => 'المقرر المطلوب غير موجود.'], 404);
        }

        $course->update($request->validated());

        return new CourseResource($course);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $course = Course::where('is_deleted', false)->find($id);

        if (! $course) {
            return response()->json(['message' => 'المقرر المطلوب غير موجود.'], 404);
        }

        // Soft delete
        $course->update(['is_deleted' => true]);

        return response()->json(['message' => 'تم حذف المقرر بنجاح.']);
    }

    /**
     * Search for a course by name.
     */
    public function search(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:100',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $name = $request->input('name');
        $courses = Course::where('name', 'like', "%{$name}%")
            ->where('is_deleted', false)
            ->paginate(15);

        return response()->json($courses);
    }
}
