<?php

namespace App\Policies;

use App\Models\SchoolStage;
use App\Models\User;

class SchoolStagePolicy
{
    /**
     * عرض قائمة المراحل الدراسية
     */
    public function viewAny(User $user)
    {
        return $user->can('view_any_school_stage');
    }

    /**
     * عرض مرحلة دراسية محددة
     */
    public function view(User $user, SchoolStage $schoolStage)
    {
        // المدير: يرى كل المراحل
        if ($user->hasRole(['super-admin', 'admin'])) {
            return true;
        }

        // المعلم: يرى المراحل التي يدرس فيها
        if ($user->teacher && $user->teacher->grade) {
            return $schoolStage->id === $user->teacher->grade->stage_id;
        }

        // الطالب: يرى مرحلته فقط
        if ($user->student && $user->student->grade) {
            return $schoolStage->id === $user->student->grade->stage_id;
        }

        // ولي الأمر: يرى مرحلة أبنائه
        if ($user->guardian) {
            $childrenStageIds = $user->guardian->students
                ->filter(function($student) {
                    return $student->grade;
                })
                ->pluck('grade.stage_id')
                ->unique()
                ->toArray();
            return in_array($schoolStage->id, $childrenStageIds);
        }

        return false;
    }

    /**
     * إنشاء مرحلة دراسية جديدة
     */
    public function create(User $user)
    {
        return $user->hasRole(['super-admin', 'admin']) && $user->can('create_school_stage');
    }

    /**
     * تحديث مرحلة دراسية
     */
    public function update(User $user, SchoolStage $schoolStage)
    {
        return $user->hasRole(['super-admin', 'admin']) && $user->can('update_school_stage');
    }

    /**
     * حذف مرحلة دراسية
     */
    public function delete(User $user, SchoolStage $schoolStage)
    {
        return $user->hasRole(['super-admin', 'admin']) && $user->can('delete_school_stage');
    }
}