<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('student_grades', function (Blueprint $table) {
            $table->id();
            $table->foreignId('student_id')->constrained('students')->cascadeOnDelete();
            $table->foreignId('course_id')->constrained('courses')->cascadeOnDelete();
            $table->decimal('score', 5, 2)->nullable();
            $table->string('term', 20)->nullable();
            $table->string('assessment_type', 50)->nullable();
            $table->decimal('max_score', 5, 2)->default(100.00);
            $table->foreignId('grade_id')->nullable()->constrained('grades_scales')->nullOnDelete();
            $table->timestamps();
            $table->boolean('is_deleted')->default(false);
            $table->boolean('is_synced')->default(false);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('student_grades');
    }
};
