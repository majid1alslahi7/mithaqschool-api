<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\UpdateUserRequest;
use Illuminate\Http\Response;
use App\Http\Resources\UserResource;
use App\Models\User;
use App\Services\SystemNotificationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:view_any_user')->only('index');
        $this->middleware('permission:view_user')->only('show');
        $this->middleware('permission:create_user')->only('store');
        $this->middleware('permission:update_user')->only('update');
        $this->middleware('permission:delete_user')->only('destroy');
    }

    public function index(Request $request)
    {
        $query = User::with(['roles.permissions', 'permissions', 'student', 'teacher', 'guardian']);

        // Search
        if ($request->has('search')) {
            $searchTerm = $request->input('search');
            $query->where(function ($q) use ($searchTerm) {
                $q->where('username', 'like', "%{$searchTerm}%")
                    ->orWhere('email', 'like', "%{$searchTerm}%")
                    ->orWhere('phone', 'like', "%{$searchTerm}%");
            });
        }

        // Filter by role
        if ($request->has('role')) {
            $query->role($request->role);
        }

        // Filter by active status
        if ($request->has('is_active')) {
            $query->where('is_active', $request->boolean('is_active'));
        }

        // Sort
        $sortBy = $request->input('sort_by', 'created_at');
        $sortOrder = $request->input('sort_order', 'desc');
        $query->orderBy($sortBy, $sortOrder);

        // Pagination
        $perPage = $request->input('per_page', 15);
        $users = $query->paginate($perPage);

        // إضافة معلومات الجلسات لكل مستخدم
        $users->getCollection()->transform(function ($user) {
            $user->active_sessions_count = DB::table('sessions')
                ->where('user_id', $user->id)
                ->where('last_activity', '>', now()->subMinutes(30)->timestamp)
                ->count();
            return $user;
        });

        return UserResource::collection($users);
    }

    public function store(StoreUserRequest $request)
    {
        $this->authorize('create', User::class);
        
        $validatedData = $request->validated();

        $password = $validatedData['password'] ?? '12345678';
        $validatedData['password'] = Hash::make($password);

        $roles = $validatedData['roles'] ?? null;
        $permissions = $validatedData['permissions'] ?? null;
        unset($validatedData['roles'], $validatedData['permissions']);

        $user = User::create($validatedData);

        if ($roles) {
            $user->syncRoles($roles);
        }

        if ($permissions) {
            $user->syncPermissions($permissions);
        }

        return new UserResource($user);
    }

    public function show(User $user)
    {
        $this->authorize('view', $user);
        
        // إضافة معلومات الجلسات
        $user->load(['roles.permissions', 'permissions', 'student', 'teacher', 'guardian']);
        $user->active_sessions_count = DB::table('sessions')
            ->where('user_id', $user->id)
            ->where('last_activity', '>', now()->subMinutes(30)->timestamp)
            ->count();
        
        $user->sessions = DB::table('sessions')
            ->where('user_id', $user->id)
            ->orderBy('last_activity', 'desc')
            ->get()
            ->map(function ($session) {
                $session->last_activity_human = date('Y-m-d H:i:s', $session->last_activity);
                $session->is_active = $session->last_activity > now()->subMinutes(30)->timestamp;
                return $session;
            });
        
        return new UserResource($user);
    }

    public function update(UpdateUserRequest $request, User $user)
    {
        $this->authorize('update', $user);
        
        $validatedData = $request->validated();

        if ($request->filled('password')) {
            $validatedData['password'] = Hash::make($validatedData['password']);
        } else {
            unset($validatedData['password']);
        }

        $roles = $validatedData['roles'] ?? null;
        $permissions = $validatedData['permissions'] ?? null;
        unset($validatedData['roles'], $validatedData['permissions']);

        $user->update($validatedData);

        if (!is_null($roles)) {
            $user->syncRoles($roles);
        }

        if (!is_null($permissions)) {
            $user->syncPermissions($permissions);
        }

        return new UserResource($user);
    }

    public function destroy(User $user)
    {
        $this->authorize('delete', $user);
        
        // لا يمكن حذف المستخدم الحالي
        if ($user->id === auth()->id()) {
            return response()->json(['message' => 'لا يمكن حذف المستخدم الحالي'], 400);
        }
        
        $user->delete();
        return response()->noContent();
    }

    public function profile(Request $request)
    {
        $user = $request->user()->load([
            'roles.permissions',
            'permissions',
            'student',
            'guardian',
            'teacher'
        ]);
        
        // إضافة معلومات آخر دخول وآخر خروج
        $lastSession = DB::table('sessions')
            ->where('user_id', $user->id)
            ->orderBy('last_activity', 'desc')
            ->first();
        
        $user->last_login_at = $user->last_login_at;
        $user->last_logout_at = $user->last_logout_at;
        $user->last_activity = $lastSession ? date('Y-m-d H:i:s', $lastSession->last_activity) : null;
        $user->active_sessions_count = DB::table('sessions')
            ->where('user_id', $user->id)
            ->where('last_activity', '>', now()->subMinutes(30)->timestamp)
            ->count();

        return new UserResource($user);
    }

    /**
     * تحديث آخر دخول للمستخدم
     */
    public function updateLastLogin(User $user)
    {
        $user->update(['last_login_at' => now()]);
        return response()->json(['message' => 'تم تحديث آخر دخول']);
    }

    /**
     * تحديث آخر خروج للمستخدم
     */
    public function updateLastLogout(Request $request)
    {
        $request->user()->update(['last_logout_at' => now()]);
        return response()->json(['message' => 'تم تحديث آخر خروج']);
    }
}