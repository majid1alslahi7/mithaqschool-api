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
    Schema::create('invoice_items', function (Blueprint $table) {
        $table->bigIncrements('id');
        $table->foreignId('invoice_id')->constrained('student_invoices');
        $table->foreignId('fee_type_id')->constrained('fee_types');
        $table->decimal('amount', 10, 2);
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('invoice_items');
    }
};
