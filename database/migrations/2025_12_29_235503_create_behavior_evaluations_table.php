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
        Schema::create('behavior_evaluations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('student_id')->constrained('students')->cascadeOnDelete();
            $table->foreignId('evaluator_id')->constrained('teachers')->cascadeOnDelete();
            $table->integer('score')->unsigned();
            $table->text('notes')->nullable();
            $table->timestamp('evaluated_at')->useCurrent();
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
        Schema::dropIfExists('behavior_evaluations');
    }
};
