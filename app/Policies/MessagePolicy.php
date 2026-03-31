<?php

namespace App\Policies;

use App\Models\Message;
use App\Models\User;

class MessagePolicy
{
    /**
     * عرض قائمة الرسائل
     */
    public function viewAny(User $user)
    {
        return $user->can('view_message');
    }

    /**
     * عرض رسالة محددة
     */
    public function view(User $user, Message $message)
    {
        // المستخدم يمكنه رؤية الرسائل التي أرسلها أو استلمها فقط
        return $message->sender_id === $user->id || $message->receiver_id === $user->id;
    }

    /**
     * إنشاء رسالة جديدة
     */
    public function create(User $user)
    {
        return $user->can('send_message');
    }

    /**
     * تحديث رسالة (تعديل)
     */
    public function update(User $user, Message $message)
    {
        // فقط المرسل يمكنه تعديل الرسالة قبل قراءتها
        return $message->sender_id === $user->id && 
               is_null($message->read_at) && 
               $user->can('send_message');
    }

    /**
     * حذف رسالة
     */
    public function delete(User $user, Message $message)
    {
        // المرسل أو المستقبل يمكنه حذف الرسالة
        return ($message->sender_id === $user->id || $message->receiver_id === $user->id) &&
               $user->can('send_message');
    }

    /**
     * قراءة رسالة (تحديث حالة القراءة)
     */
    public function read(User $user, Message $message)
    {
        // فقط المستقبل يمكنه تحديث حالة القراءة
        return $message->receiver_id === $user->id;
    }
}