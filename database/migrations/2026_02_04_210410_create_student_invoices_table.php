<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
   public function up()
{
    Schema::create('student_invoices', function (Blueprint $table) {
        $table->bigIncrements('id');
        $table->unsignedBigInteger('student_id');
        $table->string('invoice_number')->unique();
        $table->decimal('total_amount', 10, 2);
        $table->date('due_date')->nullable();
        $table->enum('status', ['paid', 'partial', 'unpaid', 'overdue', 'cancelled']);
        $table->timestamps();
    });
}
    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('student_invoices');
    }
};
