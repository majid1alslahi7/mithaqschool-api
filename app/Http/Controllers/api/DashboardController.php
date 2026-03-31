<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Student;
use App\Models\Teacher;
use App\Models\Guardian;
use App\Models\Backup;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function counts(Request $request)
    {
        try {
            $counts = [
                'users' => User::count(),
                'students' => Student::count(),
                'teachers' => Teacher::count(),
                'guardians' => Guardian::count(),
                'backups' => Backup::count(),
            ];

            return response()->json([
                'success' => true,
                'data' => $counts
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function adminDashboard()
    {
        return response()->json([
            'success' => true,
            'data' => [
                'message' => 'Admin Dashboard'
            ]
        ]);
    }

    public function teacherDashboard()
    {
        return response()->json([
            'success' => true,
            'data' => [
                'message' => 'Teacher Dashboard'
            ]
        ]);
    }

    public function studentDashboard()
    {
        return response()->json([
            'success' => true,
            'data' => [
                'message' => 'Student Dashboard'
            ]
        ]);
    }

    public function parentDashboard()
    {
        return response()->json([
            'success' => true,
            'data' => [
                'message' => 'Parent Dashboard'
            ]
        ]);
    }
}
