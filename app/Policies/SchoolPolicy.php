<?php

namespace App\Policies;

use App\Models\School;
use App\Models\User;

class SchoolPolicy
{
    /**
     * عرض معلومات المدرسة
     */
    public function viewAny(User $user)
    {
        // جميع المستخدمين المسجلين يمكنهم رؤية معلومات المدرسة
        return auth()->check();
    }

    /**
     * عرض معلومات المدرسة
     */
    public function view(User $user, School $school)
    {
        // جميع المستخدمين المسجلين يمكنهم رؤية معلومات المدرسة
        return auth()->check();
    }

    /**
     * تحديث معلومات المدرسة
     */
    public function update(User $user, School $school)
    {
        // فقط المدير يمكنه تحديث معلومات المدرسة
        return $user->hasRole(['super-admin', 'admin']) && 
               $user->can('manage_school_settings');
    }
}