<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class SearchController extends Controller
{
    public function __invoke(Request $request)
    {
        $q = $request->input('q');

        if (!$q) {
            return response()->json(['results' => []]);
        }

        $results = [];

        // البحث في الطلاب
        $students = \App\Models\Student::query()->search($q)->limit(10)->get();
        foreach ($students as $student) {
            $results[] = [
                'id' => $student->id,
                'name' => $student->f_name . ' ' . $student->l_name,
                'sub' => "رقم: {$student->enrollment_number}",
                'url' => "/students/{$student->id}",
                'icon' => 'students',
                'type' => 'طالب',
            ];
        }

        // البحث في المعلمين
        $teachers = \App\Models\Teacher::query()->search($q)->limit(10)->get();
        foreach ($teachers as $teacher) {
            $results[] = [
                'id' => $teacher->id,
                'name' => $teacher->f_name . ' ' . $teacher->l_name,
                'sub' => "رقم: {$teacher->enrollment_number}",
                'url' => "/teachers/{$teacher->id}",
                'icon' => 'teachers',
                'type' => 'معلم',
            ];
        }

        // البحث في أولياء الأمور
        $guardians = \App\Models\Guardian::query()->search($q)->limit(10)->get();
        foreach ($guardians as $guardian) {
            $results[] = [
                'id' => $guardian->id,
                'name' => $guardian->f_name . ' ' . $guardian->l_name,
                'sub' => "رقم: {$guardian->enrollment_number}",
                'url' => "/guardians/{$guardian->id}",
                'icon' => 'guardians',
                'type' => 'ولي أمر',
            ];
        }

        return response()->json(['results' => $results]);
    }
}
