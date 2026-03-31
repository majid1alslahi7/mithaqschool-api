<?php

namespace App\Policies;

use App\Models\Notification;
use App\Models\User;

class NotificationPolicy
{
    /**
     * عرض قائمة الإشعارات
     */
    public function viewAny(User $user)
    {
        return $user->can('view_notification');
    }

    /**
     * عرض إشعار محدد
     */
    public function view(User $user, Notification $notification)
    {
        // المستخدم يمكنه رؤية إشعاراته فقط
        return $notification->user_id === $user->id;
    }

    /**
     * إنشاء إشعار جديد
     */
    public function create(User $user)
    {
        // المدير والمعلم يمكنهم إرسال إشعارات
        return $user->hasRole(['super-admin', 'admin', 'teacher']) && 
               $user->can('send_notification');
    }

    /**
     * تحديث إشعار (تعديل)
     */
    public function update(User $user, Notification $notification)
    {
        // فقط المنشئ يمكنه تعديل الإشعار
        return $notification->user_id === $user->id && 
               $user->can('send_notification');
    }

    /**
     * حذف إشعار
     */
    public function delete(User $user, Notification $notification)
    {
        return $notification->user_id === $user->id && 
               $user->can('send_notification');
    }

    /**
     * تحديث حالة القراءة
     */
    public function markAsRead(User $user, Notification $notification)
    {
        return $notification->user_id === $user->id;
    }
}