<?php

namespace App\Policies;

use App\Models\Session;
use App\Models\User;

class SessionPolicy
{
    /**
     * عرض قائمة الجلسات
     */
    public function viewAny(User $user)
    {
        // فقط المدير يمكنه رؤية الجلسات النشطة
        return $user->hasRole(['super-admin', 'admin']);
    }

    /**
     * عرض جلسة محددة
     */
    public function view(User $user, Session $session)
    {
        // المستخدم يمكنه رؤية جلساته فقط
        if ($user->id === $session->user_id) {
            return true;
        }

        // المدير يمكنه رؤية كل الجلسات
        return $user->hasRole(['super-admin', 'admin']);
    }

    /**
     * إنهاء جلسة (تسجيل خروج)
     */
    public function terminate(User $user, Session $session)
    {
        // المستخدم يمكنه إنهاء جلساته فقط
        if ($user->id === $session->user_id) {
            return true;
        }

        // المدير يمكنه إنهاء أي جلسة
        return $user->hasRole(['super-admin', 'admin']);
    }

    /**
     * إنهاء جميع الجلسات
     */
    public function terminateAll(User $user)
    {
        // فقط المدير يمكنه إنهاء جميع الجلسات
        return $user->hasRole(['super-admin', 'admin']);
    }
}