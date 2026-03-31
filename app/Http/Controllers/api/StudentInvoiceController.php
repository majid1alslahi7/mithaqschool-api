<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Models\StudentInvoice;
use App\Http\Requests\StoreStudentInvoiceRequest;
use App\Http\Requests\UpdateStudentInvoiceRequest;
use App\Http\Resources\StudentInvoiceResource;
use Illuminate\Http\Request;

class StudentInvoiceController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:view_any_invoice')->only('index');
        $this->middleware('permission:view_invoice')->only('show');
        $this->middleware('permission:create_invoice')->only('store');
        $this->middleware('permission:update_invoice')->only('update');
        $this->middleware('permission:delete_invoice')->only('destroy');
    }

    public function index(Request $request)
    {
        $user = auth()->user();
        $query = StudentInvoice::with(['student.grade', 'items', 'payments', 'adjustments']);

        // تطبيق الفلاتر حسب دور المستخدم
        if ($user->hasRole('student') && $user->student) {
            $query->where('student_id', $user->student->id);
        } elseif ($user->hasRole('guardian') && $user->guardian) {
            $childrenIds = $user->guardian->students->pluck('id');
            $query->whereIn('student_id', $childrenIds);
        }

        $query->filter($request->all());

        return StudentInvoiceResource::collection($query->paginate(20));
    }

    public function store(StoreStudentInvoiceRequest $request)
    {
        $this->authorize('create', StudentInvoice::class);
        
        $invoice = StudentInvoice::create($request->validated());
        $invoice->recalculateTotals();
        
        // إشعار للطالب وولي الأمر
        $this->sendInvoiceNotification($invoice);
        
        return new StudentInvoiceResource(
            $invoice->load(['student.grade', 'items', 'payments', 'adjustments'])
        );
    }

    public function show(StudentInvoice $studentInvoice)
    {
        $this->authorize('view', $studentInvoice);
        
        return new StudentInvoiceResource(
            $studentInvoice->load(['student.grade', 'items', 'payments', 'adjustments'])
        );
    }

    public function update(UpdateStudentInvoiceRequest $request, StudentInvoice $studentInvoice)
    {
        $this->authorize('update', $studentInvoice);
        
        $studentInvoice->update($request->validated());
        $studentInvoice->recalculateTotals();
        
        return new StudentInvoiceResource(
            $studentInvoice->load(['student.grade', 'items', 'payments', 'adjustments'])
        );
    }

    public function destroy(StudentInvoice $studentInvoice)
    {
        $this->authorize('delete', $studentInvoice);
        
        $studentInvoice->delete();
        return response()->json(['message' => 'تم الحذف بنجاح.']);
    }

    public function pay(Request $request, StudentInvoice $studentInvoice)
    {
        $this->authorize('pay', $studentInvoice);
        
        $request->validate([
            'amount' => 'required|numeric|min:0.01',
            'payment_method' => 'required|string',
        ]);
        
        $payment = $studentInvoice->payments()->create([
            'student_id' => $studentInvoice->student_id,
            'amount_paid' => $request->amount,
            'payment_method' => $request->payment_method,
            'payment_date' => now(),
        ]);
        
        $studentInvoice->recalculateTotals();
        
        return response()->json([
            'message' => 'تم الدفع بنجاح',
            'payment' => $payment,
            'invoice' => new StudentInvoiceResource($studentInvoice->load(['items', 'payments']))
        ]);
    }

    private function sendInvoiceNotification($invoice)
    {
        $student = $invoice->student;
        
        if ($student->user_id) {
            \App\Models\Notification::create([
                'title' => 'فاتورة جديدة',
                'message' => "تم إنشاء فاتورة جديدة رقم {$invoice->invoice_number} بقيمة {$invoice->total_amount}",
                'user_id' => $student->user_id,
            ]);
        }
        
        if ($student->guardian && $student->guardian->user_id) {
            \App\Models\Notification::create([
                'title' => 'فاتورة جديدة لابنك',
                'message' => "تم إنشاء فاتورة جديدة رقم {$invoice->invoice_number} بقيمة {$invoice->total_amount} للطالب {$student->f_name} {$student->l_name}",
                'user_id' => $student->guardian->user_id,
            ]);
        }
    }
}