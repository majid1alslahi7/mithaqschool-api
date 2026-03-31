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
        Schema::create('homework_submissions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('student_id')->constrained('students')->cascadeOnDelete();
            $table->foreignId('homework_id')->constrained('homeworks')->cascadeOnDelete();
            $table->timestamp('submission_date')->nullable();
            $table->decimal('score', 5, 2)->nullable();
            $table->string('grade', 10)->nullable();
            $table->text('file_url')->nullable();
            $table->text('feedback')->nullable();
            $table->string('status', 20)->default('submitted');
            $table->timestamp('submitted_at')->useCurrent();
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
        Schema::dropIfExists('homework_submissions');
    }
};
