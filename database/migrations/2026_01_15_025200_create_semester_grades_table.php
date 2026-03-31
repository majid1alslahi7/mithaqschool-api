<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('semester_grades', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('student_number');
            $table->foreign('student_number')->references('enrollment_number')->on('students')->onDelete('cascade');
            $table->foreignId('course_id')->constrained()->onDelete('cascade');
            $table->foreignId('academic_year_id')->constrained('academic_years')->onDelete('cascade');
            $table->foreignId('semester_id')->constrained()->onDelete('cascade');
            $table->integer('semester_work');
            $table->integer('exam_semester');
            $table->decimal('total_score')->storedAs('semester_work + exam_semester');
            $table->timestamps();
        });

        DB::statement('ALTER TABLE semester_grades ADD CONSTRAINT semester_grades_semester_work_check CHECK (semester_work >= 0 AND semester_work <= 20)');
        DB::statement('ALTER TABLE semester_grades ADD CONSTRAINT semester_grades_exam_semester_check CHECK (exam_semester >= 0 AND exam_semester <= 30)');
        DB::statement('ALTER TABLE semester_grades ADD CONSTRAINT semester_grades_total_score_check CHECK (total_score >= 0 AND total_score <= 50)');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('semester_grades');
    }
};
