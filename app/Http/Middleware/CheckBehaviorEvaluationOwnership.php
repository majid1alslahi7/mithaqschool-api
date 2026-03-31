<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CheckBehaviorEvaluationOwnership
{
    /**
     * التحقق من أن المستخدم لديه صلاحية الوصول إلى تقييم السلوك
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        $user = Auth::user();
        $evaluationId = $request->route('behavior_evaluation') ?? $request->route('evaluation_id');
        
        if (!$evaluationId) {
            return $next($request);
        }

        // Super-admin و admin يمكنهم الوصول لأي تقييم
        if ($user->hasRole(['super-admin', 'admin'])) {
            return $next($request);
        }

        $evaluation = \App\Models\BehaviorEvaluation::find($evaluationId);
        
        if (!$evaluation) {
            abort(404, 'تقييم السلوك غير موجود');
        }

        // المعلم: يمكنه الوصول إلى تقييماته فقط
        if ($user->hasRole('teacher') && $user->teacher) {
            if ($evaluation->evaluator_id == $user->teacher->id) {
                return $next($request);
            }
            abort(403, 'لا يمكنك الوصول إلى تقييم سلوك ليس من إنشائك');
        }

        // الطالب: يمكنه الوصول إلى تقييمه فقط
        if ($user->hasRole('student') && $user->student) {
            if ($evaluation->student_id == $user->student->id) {
                return $next($request);
            }
            abort(403, 'لا يمكنك الوصول إلى تقييم سلوك طالب آخر');
        }

        // ولي الأمر: يمكنه الوصول إلى تقييمات أبنائه فقط
        if ($user->hasRole('guardian') && $user->guardian) {
            $childrenIds = $user->guardian->students->pluck('id')->toArray();
            if (in_array($evaluation->student_id, $childrenIds)) {
                return $next($request);
            }
            abort(403, 'لا يمكنك الوصول إلى تقييم سلوك ليس لأحد أبنائك');
        }

        abort(403, 'غير مصرح لك بالوصول إلى هذا التقييم');
    }
}