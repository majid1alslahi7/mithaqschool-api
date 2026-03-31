<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

class Controller extends BaseController
{
    use AuthorizesRequests, ValidatesRequests;

    /**
     * دالة مساعدة للتحقق من صلاحية المستخدم مع رسالة خطأ مخصصة
     */
    protected function checkPermission($Permission, $message = null)
    {
        if (!auth()->user()->can($Permission)) {
            $errorMessage = $message ?? 'ليس لديك الصلاحية اللازمة للوصول إلى هذه الصفحة';
            
            if (request()->expectsJson()) {
                abort(403, $errorMessage);
            }
            
            abort(403, $errorMessage);
        }
        
        return true;
    }

    /**
     * دالة مساعدة للتحقق من دور المستخدم
     */
    protected function checkRole($role, $message = null)
    {
        if (!auth()->user()->hasRole($role)) {
            $errorMessage = $message ?? 'غير مصرح لك بالوصول إلى هذه الصفحة';
            
            if (request()->expectsJson()) {
                abort(403, $errorMessage);
            }
            
            abort(403, $errorMessage);
        }
        
        return true;
    }
}
