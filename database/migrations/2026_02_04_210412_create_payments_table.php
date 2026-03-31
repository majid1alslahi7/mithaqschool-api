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
    Schema::create('payments', function (Blueprint $table) {
        $table->bigIncrements('id');
        $table->unsignedBigInteger('student_id');
        $table->foreignId('invoice_id')->constrained('student_invoices');
        $table->decimal('amount_paid', 10, 2);
        $table->string('payment_method')->nullable(); // cash, bank, online
        $table->timestamp('payment_date')->useCurrent();
        $table->string('reference_number')->nullable();
        $table->timestamps();
    });
}
    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};
