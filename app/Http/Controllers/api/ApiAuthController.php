<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;

class ApiAuthController extends Controller
{
    public function login(Request $request)
    {
        try {
            Log::info('Login attempt', ['email' => $request->email]);

            $request->validate([
                'email' => 'required|email',
                'password' => 'required',
            ]);

            $user = User::where('email', $request->email)->first();

            if (!$user) {
                return response()->json([
                    'success' => false,
                    'message' => 'البريد الإلكتروني غير مسجل'
                ], 401);
            }

            if (!Hash::check($request->password, $user->password)) {
                return response()->json([
                    'success' => false,
                    'message' => 'كلمة المرور غير صحيحة'
                ], 401);
            }

            // التحقق من أن المستخدم نشط
            if (!$user->is_active) {
                return response()->json([
                    'success' => false,
                    'message' => 'حسابك غير نشط. يرجى التواصل مع الإدارة.'
                ], 401);
            }

            // تحديث آخر دخول
            $user->update(['last_login_at' => now()]);

            $token = $user->createToken('auth_token')->plainTextToken;

            // تحميل العلاقات
            $user->load('roles', 'student', 'teacher', 'guardian');

            return response()->json([
                'success' => true,
                'access_token' => $token,
                'token_type' => 'Bearer',
                'user' => [
                    'id' => $user->id,
                    'username' => $user->username,
                    'email' => $user->email,
                    'phone' => $user->phone,
                    'is_active' => $user->is_active,
                    'last_login_at' => $user->last_login_at,
                    'roles' => $user->getRoleNames(),
                    'roles_with_labels' => $user->roles->map(function($role) {
                        return [
                            'name' => $role->name,
                            'label' => $role->label,
                        ];
                    }),
                    'permissions' => $user->getAllPermissions()->pluck('name'),
                    'permissions_with_labels' => $user->getAllPermissions()->map(function($permission) {
                        return [
                            'name' => $permission->name,
                            'label' => $permission->label,
                        ];
                    }),
                    'type' => $this->getUserType($user),
                ]
            ]);

        } catch (\Exception $e) {
            Log::error('Login error', [
                'message' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine()
            ]);

            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function logout(Request $request)
    {
        try {
            $user = $request->user();
            if ($user) {
                $user->update(['last_logout_at' => now()]);
                $user->currentAccessToken()->delete();
            }
            
            return response()->json([
                'success' => true,
                'message' => 'تم تسجيل الخروج بنجاح'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    private function getUserType($user)
    {
        if ($user->student) return 'student';
        if ($user->teacher) return 'teacher';
        if ($user->guardian) return 'guardian';
        if ($user->hasRole('super-admin')) return 'super-admin';
        if ($user->hasRole('admin')) return 'admin';
        return 'user';
    }
}
