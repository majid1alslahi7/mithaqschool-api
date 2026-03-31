<?php

namespace App\Policies;

use App\Models\HomeworkSubmission;
use App\Models\User;

class HomeworkSubmissionPolicy
{
    /**
     * عرض قائمة التسليمات
     */
    public function viewAny(User $user)
    {
        return $user->can('view_any_homework_submission');
    }

    /**
     * عرض تسليم محدد
     */
    public function view(User $user, HomeworkSubmission $submission)
    {
        // المدير: يرى كل التسليمات
        if ($user->hasRole(['super-admin', 'admin'])) {
            return true;
        }

        // المعلم: يرى تسليمات واجباته فقط
        if ($user->teacher) {
            return $submission->homework->teacher_id === $user->teacher->id;
        }

        // الطالب: يرى تسليماته فقط
        if ($user->student) {
            return $submission->student_id === $user->student->id;
        }

        // ولي الأمر: يرى تسليمات أبنائه فقط
        if ($user->guardian) {
            $childrenIds = $user->guardian->students->pluck('id')->toArray();
            return in_array($submission->student_id, $childrenIds);
        }

        return false;
    }

    /**
     * إنشاء تسليم جديد (الطالب يسلم الواجب)
     */
    public function create(User $user, Homework $homework = null)
    {
        // الطالب يمكنه إنشاء تسليم للواجب المخصص له
        if (!$homework) {
            return false;
        }
        
        return $user->student && 
               $user->student->id === $homework->student_id && 
               $user->can('submit_homework');
    }

    /**
     * تقييم تسليم (المعلم يصحح)
     */
    public function grade(User $user, HomeworkSubmission $submission)
    {
        // المعلم يمكنه تقييم تسليمات واجباته فقط
        return $user->teacher && 
               $user->teacher->id === $submission->homework->teacher_id && 
               $user->can('grade_homework_submission');
    }

    /**
     * تعديل تسليم (الطالب يعدل تسليمه قبل التصحيح)
     */
    public function update(User $user, HomeworkSubmission $submission)
    {
        // الطالب يمكنه تعديل تسليمه فقط إذا لم يتم تقييمه بعد
        return $user->student && 
               $user->student->id === $submission->student_id && 
               is_null($submission->score) && // لم يتم تقييمه بعد
               $user->can('submit_homework');
    }

    /**
     * حذف تسليم
     */
    public function delete(User $user, HomeworkSubmission $submission)
    {
        // فقط المدير يمكنه الحذف
        return $user->hasRole(['super-admin', 'admin']) && $user->can('delete_homework');
    }
}