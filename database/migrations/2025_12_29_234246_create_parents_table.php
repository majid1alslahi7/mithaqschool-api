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
        Schema::create('parents', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('enrollment_number')->unique()->startingValue(20260000);
            $table->string('user_id', 100)->nullable();
            $table->string('f_name', 200);
            $table->string('l_name', 200);
$table->enum('gender', ['ذكر', 'أنثى'
]);
            $table->text('address')->nullable();
            $table->text('avatar_path')->nullable();
            $table->timestamp('last_modified')->nullable();
            $table->boolean('is_deleted')->default(false);
            $table->boolean('is_synced')->default(false);
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->index('user_id');
            $table->index('is_deleted');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('parents');
    }
};
