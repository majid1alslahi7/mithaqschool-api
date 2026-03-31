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
        Schema::create('monthly_grades', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('student_number');
            $table->foreignId('course_id')->constrained('courses')->onDelete('cascade');
            $table->foreignId('academic_year_id')->constrained('academic_years')->onDelete('cascade');
            $table->foreignId('semester_id')->constrained('semesters')->onDelete('cascade');
            $table->integer('month');
            $table->integer('written_exam');
            $table->integer('homework');
            $table->integer('oral_exam');
            $table->integer('attendance');
            $table->decimal('total_score', 5, 2)->storedAs('written_exam + homework + oral_exam + attendance');
            $table->timestamps();

            $table->foreign('student_number')->references('enrollment_number')->on('students');
        });

        DB::statement('ALTER TABLE monthly_grades ADD CONSTRAINT monthly_grades_month_check CHECK (month >= 1 AND month <= 12)');
        DB::statement('ALTER TABLE monthly_grades ADD CONSTRAINT monthly_grades_written_exam_check CHECK (written_exam >= 0 AND written_exam <= 40)');
        DB::statement('ALTER TABLE monthly_grades ADD CONSTRAINT monthly_grades_homework_check CHECK (homework >= 0 AND homework <= 20)');
        DB::statement('ALTER TABLE monthly_grades ADD CONSTRAINT monthly_grades_oral_exam_check CHECK (oral_exam >= 0 AND oral_exam <= 20)');
        DB::statement('ALTER TABLE monthly_grades ADD CONSTRAINT monthly_grades_attendance_check CHECK (attendance >= 0 AND attendance <= 20)');
        DB::statement('ALTER TABLE monthly_grades ADD CONSTRAINT monthly_grades_total_score_check CHECK (total_score >= 0 AND total_score <= 100)');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('monthly_grades');
    }
};
