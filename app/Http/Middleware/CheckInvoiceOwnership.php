<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CheckInvoiceOwnership
{
    /**
     * التحقق من أن المستخدم لديه صلاحية الوصول إلى الفاتورة
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        $user = Auth::user();
        $invoiceId = $request->route('invoice') ?? $request->route('invoice_id');
        
        if (!$invoiceId) {
            return $next($request);
        }

        // Super-admin و admin يمكنهم الوصول لأي فاتورة
        if ($user->hasRole(['super-admin', 'admin'])) {
            return $next($request);
        }

        $invoice = \App\Models\StudentInvoice::find($invoiceId);
        
        if (!$invoice) {
            abort(404, 'الفاتورة غير موجودة');
        }

        // الطالب: يمكنه الوصول إلى فاتورته فقط
        if ($user->hasRole('student') && $user->student) {
            if ($invoice->student_id == $user->student->id) {
                return $next($request);
            }
            abort(403, 'لا يمكنك الوصول إلى فاتورة طالب آخر');
        }

        // ولي الأمر: يمكنه الوصول إلى فواتير أبنائه فقط
        if ($user->hasRole('guardian') && $user->guardian) {
            $childrenIds = $user->guardian->students->pluck('id')->toArray();
            if (in_array($invoice->student_id, $childrenIds)) {
                return $next($request);
            }
            abort(403, 'لا يمكنك الوصول إلى فاتورة ليس لأحد أبنائك');
        }

        abort(403, 'غير مصرح لك بالوصول إلى هذه الفاتورة');
    }
}