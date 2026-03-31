<?php

return [

    'models' => [
        'permission' => App\Models\Permission::class,
        'role' => App\Models\Role::class,
    ],

    'table_names' => [
        'roles' => 'roles',
        'permissions' => 'permissions',
        'model_has_permissions' => 'model_has_permissions',
        'model_has_roles' => 'model_has_roles',
        'role_has_permissions' => 'role_has_permissions',
    ],

    'column_names' => [
        'role_pivot_key' => null,
        'permission_pivot_key' => null,
        'model_morph_key' => 'model_id',
        'team_foreign_key' => 'team_id',
    ],

    /*
    |--------------------------------------------------------------------------
    | Register Permission Check Method
    |--------------------------------------------------------------------------
    */
    'register_permission_check_method' => true,

    /*
    |--------------------------------------------------------------------------
    | Register Octane Reset Listener
    |--------------------------------------------------------------------------
    */
    'register_octane_reset_listener' => false,

    /*
    |--------------------------------------------------------------------------
    | Events
    |--------------------------------------------------------------------------
    */
    'events_enabled' => true, // تغيير إلى true لتفعيل الأحداث

    /*
    |--------------------------------------------------------------------------
    | Teams Feature
    |--------------------------------------------------------------------------
    */
    'teams' => false,

    /*
    |--------------------------------------------------------------------------
    | Team Resolver
    |--------------------------------------------------------------------------
    */
    'team_resolver' => \Spatie\Permission\DefaultTeamResolver::class,

    /*
    |--------------------------------------------------------------------------
    | Passport Client Credentials Grant
    |--------------------------------------------------------------------------
    */
    'use_passport_client_credentials' => false,

    /*
    |--------------------------------------------------------------------------
    | Display Permission/Role in Exception
    |--------------------------------------------------------------------------
    */
    'display_permission_in_exception' => env('APP_DEBUG', false), // عرض في وضع التطوير فقط
    'display_role_in_exception' => env('APP_DEBUG', false),

    /*
    |--------------------------------------------------------------------------
    | Wildcard Permission
    |--------------------------------------------------------------------------
    */
    'enable_wildcard_permission' => false,

    /*
    |--------------------------------------------------------------------------
    | Cache Settings
    |--------------------------------------------------------------------------
    */
    'cache' => [
        'expiration_time' => \DateInterval::createFromDateString('24 hours'),
        'key' => 'spatie.permission.cache',
        'store' => env('CACHE_STORE', 'default'),
    ],

];