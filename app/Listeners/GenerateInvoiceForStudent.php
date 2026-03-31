<?php

namespace App\Listeners;

use App\Events\StudentRegistered;
use App\Services\InvoiceGeneratorService;

class GenerateInvoiceForStudent
{
    protected $invoiceService;

    public function __construct(InvoiceGeneratorService $invoiceService)
    {
        $this->invoiceService = $invoiceService;
    }

    public function handle(StudentRegistered $event)
    {
        $this->invoiceService->generateForStudent($event->student);
    }
}