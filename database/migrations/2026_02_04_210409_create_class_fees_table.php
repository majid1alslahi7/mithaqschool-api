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
    Schema::create('class_fees', function (Blueprint $table) {
        $table->bigIncrements('id');
        $table->unsignedBigInteger('grade_id');
        $table->foreignId('fee_type_id')->constrained('fee_types');
        $table->decimal('amount', 10, 2);
        $table->string('academic_year');
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('class_fees');
    }
};
