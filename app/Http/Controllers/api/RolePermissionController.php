<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Http\Requests\AssignPermissionToRoleRequest;
use App\Http\Requests\AssignRoleToUserRequest;
use App\Http\Requests\GivePermissionToUserRequest;
use App\Http\Requests\RevokePermissionFromRoleRequest;
use App\Http\Requests\RevokePermissionFromUserRequest;
use App\Http\Requests\RevokeRoleFromUserRequest;
use App\Models\Permission;
use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;

class RolePermissionController extends Controller
{
    public function assignPermissionToRole(AssignPermissionToRoleRequest $request)
    {
        $guard = $this->resolveGuardName($request);
        $role = $this->resolveRole($request, $guard);
        $permission = $this->resolvePermission($request, $guard);

        $role->givePermissionTo($permission);

        $role->load('permissions');

        return response()->json([
            'message' => 'تم إسناد الصلاحية إلى الدور بنجاح.',
            'role' => $this->formatRole($role, true),
        ]);
    }

    public function revokePermissionFromRole(RevokePermissionFromRoleRequest $request)
    {
        $guard = $this->resolveGuardName($request);
        $role = $this->resolveRole($request, $guard);
        $permission = $this->resolvePermission($request, $guard);

        $role->revokePermissionTo($permission);

        $role->load('permissions');

        return response()->json([
            'message' => 'تم سحب الصلاحية من الدور بنجاح.',
            'role' => $this->formatRole($role, true),
        ]);
    }

    public function assignRoleToUser(AssignRoleToUserRequest $request)
    {
        $guard = $this->resolveGuardName($request);
        $users = $this->resolveUsers($request);
        $role = $this->resolveRole($request, $guard);

        foreach ($users as $user) {
            $user->assignRole($role);
        }

        if ($users->count() === 1) {
            $user = $users->first();
            $user->load('roles', 'permissions');
            return response()->json([
                'message' => 'تم إسناد الدور إلى المستخدم بنجاح.',
                'user' => $this->formatUser($user),
            ]);
        }

        return response()->json([
            'message' => 'تم إسناد الدور إلى المستخدمين بنجاح.',
            'user_ids' => $users->pluck('id')->values(),
            'role' => $this->formatRole($role),
        ]);
    }

    public function revokeRoleFromUser(RevokeRoleFromUserRequest $request)
    {
        $guard = $this->resolveGuardName($request);
        $users = $this->resolveUsers($request);
        $role = $this->resolveRole($request, $guard);

        foreach ($users as $user) {
            $user->removeRole($role);
        }

        if ($users->count() === 1) {
            $user = $users->first();
            $user->load('roles', 'permissions');
            return response()->json([
                'message' => 'تم سحب الدور من المستخدم بنجاح.',
                'user' => $this->formatUser($user),
            ]);
        }

        return response()->json([
            'message' => 'تم سحب الدور من المستخدمين بنجاح.',
            'user_ids' => $users->pluck('id')->values(),
            'role' => $this->formatRole($role),
        ]);
    }

    public function givePermissionToUser(GivePermissionToUserRequest $request)
    {
        $guard = $this->resolveGuardName($request);
        $users = $this->resolveUsers($request);
        $permission = $this->resolvePermission($request, $guard);

        foreach ($users as $user) {
            $user->givePermissionTo($permission);
        }

        if ($users->count() === 1) {
            $user = $users->first();
            $user->load('roles', 'permissions');
            return response()->json([
                'message' => 'تم منح الصلاحية للمستخدم بنجاح.',
                'user' => $this->formatUser($user),
            ]);
        }

        return response()->json([
            'message' => 'تم منح الصلاحية للمستخدمين بنجاح.',
            'user_ids' => $users->pluck('id')->values(),
            'permission' => $this->formatPermission($permission),
        ]);
    }

    public function revokePermissionFromUser(RevokePermissionFromUserRequest $request)
    {
        $guard = $this->resolveGuardName($request);
        $users = $this->resolveUsers($request);
        $permission = $this->resolvePermission($request, $guard);

        foreach ($users as $user) {
            $user->revokePermissionTo($permission);
        }

        if ($users->count() === 1) {
            $user = $users->first();
            $user->load('roles', 'permissions');
            return response()->json([
                'message' => 'تم سحب الصلاحية من المستخدم بنجاح.',
                'user' => $this->formatUser($user),
            ]);
        }

        return response()->json([
            'message' => 'تم سحب الصلاحية من المستخدمين بنجاح.',
            'user_ids' => $users->pluck('id')->values(),
            'permission' => $this->formatPermission($permission),
        ]);
    }

    private function resolveGuardName(Request $request): string
    {
        return $request->input('guard_name') ?: config('auth.defaults.guard', 'web');
    }

    private function resolveRole(Request $request, string $guard): Role
    {
        if ($request->filled('role_id')) {
            $role = Role::findOrFail($request->input('role_id'));
            if ($role->guard_name !== $guard) {
                abort(422, 'الحارس المرتبط بالدور لا يطابق قيمة guard_name المرسلة.');
            }
            return $role;
        }

        return Role::where('name', $request->input('role_name'))
            ->where('guard_name', $guard)
            ->firstOrFail();
    }

    private function resolvePermission(Request $request, string $guard): Permission
    {
        if ($request->filled('permission_id')) {
            $permission = Permission::findOrFail($request->input('permission_id'));
            if ($permission->guard_name !== $guard) {
                abort(422, 'الحارس المرتبط بالصلاحية لا يطابق قيمة guard_name المرسلة.');
            }
            return $permission;
        }

        return Permission::where('name', $request->input('permission_name'))
            ->where('guard_name', $guard)
            ->firstOrFail();
    }

    private function resolveUser(Request $request): User
    {
        if ($request->filled('user_id')) {
            return User::findOrFail($request->input('user_id'));
        }
        if ($request->filled('user_email')) {
            return User::where('email', $request->input('user_email'))->firstOrFail();
        }
        if ($request->filled('username')) {
            return User::where('username', $request->input('username'))->firstOrFail();
        }

        return User::where('phone', $request->input('phone'))->firstOrFail();
    }

    private function resolveUsers(Request $request)
    {
        if ($request->filled('user_ids')) {
            $ids = $request->input('user_ids', []);
            return User::whereIn('id', $ids)->get();
        }

        return collect([$this->resolveUser($request)]);
    }

    private function formatRole(Role $role, bool $withPermissions = false): array
    {
        $data = [
            'id' => $role->id,
            'name' => $role->name,
            'label' => $role->label,
            'guard_name' => $role->guard_name,
        ];

        if ($withPermissions) {
            $data['permissions'] = $role->permissions->map(function (Permission $permission) {
                return $this->formatPermission($permission);
            })->values();
        }

        return $data;
    }

    private function formatPermission(Permission $permission): array
    {
        return [
            'id' => $permission->id,
            'name' => $permission->name,
            'label' => $permission->label,
            'guard_name' => $permission->guard_name,
        ];
    }

    private function formatUser(User $user): array
    {
        return [
            'id' => $user->id,
            'username' => $user->username,
            'email' => $user->email,
            'roles' => $user->roles->map(function (Role $role) {
                return $this->formatRole($role);
            })->values(),
            'permissions' => $user->permissions->map(function (Permission $permission) {
                return $this->formatPermission($permission);
            })->values(),
        ];
    }
}
