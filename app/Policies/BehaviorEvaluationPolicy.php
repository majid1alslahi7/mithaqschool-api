<?php

namespace App\Policies;

use App\Models\BehaviorEvaluation;
use App\Models\User;

class BehaviorEvaluationPolicy
{
    /**
     * عرض قائمة تقييمات السلوك
     */
    public function viewAny(User $user)
    {
        return $user->can('view_any_behavior_evaluation');
    }

    /**
     * عرض تقييم سلوك محدد
     */
    public function view(User $user, BehaviorEvaluation $evaluation)
    {
        // المدير: يرى كل التقييمات
        if ($user->hasRole(['super-admin', 'admin'])) {
            return true;
        }

        // المعلم: يرى تقييماته فقط
        if ($user->teacher) {
            return $evaluation->evaluator_id === $user->teacher->id;
        }

        // الطالب: يرى تقييمه فقط
        if ($user->student) {
            return $evaluation->student_id === $user->student->id;
        }

        // ولي الأمر: يرى تقييمات أبنائه فقط
        if ($user->guardian) {
            $childrenIds = $user->guardian->students->pluck('id')->toArray();
            return in_array($evaluation->student_id, $childrenIds);
        }

        return false;
    }

    /**
     * إنشاء تقييم سلوك جديد
     */
    public function create(User $user)
    {
        // المعلم يمكنه إنشاء تقييمات سلوك
        return $user->hasRole('teacher') && $user->can('create_behavior_evaluation');
    }

    /**
     * تحديث تقييم سلوك
     */
    public function update(User $user, BehaviorEvaluation $evaluation)
    {
        // المعلم يمكنه تحديث تقييماته فقط
        return $user->teacher && 
               $user->teacher->id === $evaluation->evaluator_id && 
               $user->can('update_behavior_evaluation');
    }

    /**
     * حذف تقييم سلوك
     */
    public function delete(User $user, BehaviorEvaluation $evaluation)
    {
        return $user->hasRole(['super-admin', 'admin']) && $user->can('delete_behavior_evaluation');
    }
}