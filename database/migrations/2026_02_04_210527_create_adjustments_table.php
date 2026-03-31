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
    Schema::create('adjustments', function (Blueprint $table) {
        $table->bigIncrements('id');
        $table->unsignedBigInteger('student_id');
        $table->foreignId('invoice_id')->nullable()->constrained('student_invoices');
        $table->enum('type', ['discount', 'fine', 'scholarship', 'manual']);
        $table->decimal('amount', 10, 2);
        $table->text('reason')->nullable();
        $table->uuid('created_by')->nullable();
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('adjustments');
    }
};
