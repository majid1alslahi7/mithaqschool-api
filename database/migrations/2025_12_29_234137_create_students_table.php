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
        Schema::create('students', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('enrollment_number')->unique();
            $table->string('f_name', 100);
            $table->string('l_name', 100);
            $table->enum('gender', ['ذكر', 'أنثى']);
            $table->date('birth_date')->nullable();
            $table->text('address')->nullable();
            $table->text('avatar_path')->nullable();
            $table->unsignedBigInteger('parent_id')->nullable();
            $table->unsignedBigInteger('classroom_id')->nullable();
            $table->unsignedBigInteger('grade_id')->nullable();
            $table->string('attendance_status', 20)->nullable();
            $table->boolean('is_deleted')->default(false);
            $table->boolean('is_synced')->default(false);
            $table->boolean('is_active')->default(true);
            $table->timestamp('last_modified')->nullable();
            $table->timestamps();
            $table->unsignedBigInteger('user_id')->unique()->nullable();

            // Indexes
            $table->index('is_active');
            $table->index('is_deleted');
            $table->index('parent_id');
            $table->index('classroom_id');
            $table->index('grade_id');
            $table->index('enrollment_number');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('students');
    }
};
