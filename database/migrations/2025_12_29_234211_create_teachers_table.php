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
        Schema::create('teachers', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('enrollment_number')->unique();
            $table->unsignedBigInteger('user_id')->nullable();
            $table->string('f_name', 200);
            $table->string('l_name', 200);
            $table->enum('gender', ['ذكر', 'أنثى']);
            $table->date('birth_date')->nullable();
            $table->date('hire_date')->nullable();
            $table->text('address')->nullable();
            $table->text('avatar_path')->nullable();
            $table->unsignedBigInteger('grade_id')->nullable();
            $table->unsignedBigInteger('course_id')->nullable();
            $table->unsignedBigInteger('classroom_id')->nullable();
            $table->timestamp('last_modified')->nullable();
            $table->boolean('is_deleted')->default(false);
            $table->boolean('is_synced')->default(false);
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->index('user_id');
            $table->index('classroom_id');
            $table->index('is_deleted');
            $table->index('grade_id');
            $table->index('course_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('teachers');
    }
};
