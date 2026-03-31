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
        Schema::create('finaly_grades', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('student_number');
            $table->foreignId('course_id')->constrained('courses')->onDelete('cascade');
            $table->foreignId('academic_year_id')->constrained('academic_years')->onDelete('cascade');

            $table->integer('first_achievement_score');
            $table->integer('midterm_test');
            $table->integer('second_achievement_score');
            $table->integer('final_test');
            $table->decimal('total_score', 5, 2)->storedAs('first_achievement_score + midterm_test + second_achievement_score + final_test');
            $table->timestamps();

            $table->foreign('student_number')->references('enrollment_number')->on('students');
        });

        DB::statement('ALTER TABLE finaly_grades ADD CONSTRAINT finaly_grades_first_achievement_score_check CHECK (first_achievement_score >= 0 AND first_achievement_score <= 20)');
        DB::statement('ALTER TABLE finaly_grades ADD CONSTRAINT finaly_grades_midterm_test_check CHECK (midterm_test >= 0 AND midterm_test <= 30)');
        DB::statement('ALTER TABLE finaly_grades ADD CONSTRAINT finaly_grades_second_achievement_score_check CHECK (second_achievement_score >= 0 AND second_achievement_score <= 20)');
        DB::statement('ALTER TABLE finaly_grades ADD CONSTRAINT finaly_grades_final_test_check CHECK (final_test >= 0 AND final_test <= 30)');
        DB::statement('ALTER TABLE finaly_grades ADD CONSTRAINT finaly_grades_total_score_check CHECK (total_score >= 0 AND total_score <= 100)');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('finaly_grades');
    }
};
