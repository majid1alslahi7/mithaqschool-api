<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Models\Classroom;
use App\Services\SystemNotificationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ClassroomController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return Classroom::with(['grade', 'teacher'])->get();
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'grade_id' => 'required|exists:grades,id',
            'teacher_id' => 'nullable|exists:teachers,id',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

        $classroom = Classroom::create($request->all());
        $classroom->load('grade');
        $classroomName = $classroom->name ?? '';
        $gradeName = $classroom->grade?->name;
        $classSuffix = $classroomName !== '' ? " {$classroomName}" : '';
        $gradeSuffix = $gradeName ? " ضمن صف {$gradeName}" : '';
        $message = "تمت إضافة فصل جديد{$classSuffix}{$gradeSuffix}.";
        app(SystemNotificationService::class)->notifyAllUsers('إضافة فصل', $message);
        return response()->json($classroom, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        return Classroom::with(['grade', 'teacher'])->findOrFail($id);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $classroom = Classroom::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'grade_id' => 'required|exists:grades,id',
            'teacher_id' => 'nullable|exists:teachers,id',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

        $classroom->update($request->all());
        return response()->json($classroom, 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        Classroom::findOrFail($id)->delete();
        return response()->json(null, 204);
    }
}
