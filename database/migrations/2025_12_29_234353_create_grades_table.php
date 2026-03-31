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
        Schema::create('grades', function (Blueprint $table) {
            $table->id();
            $table->string('name', 100);
            $table->text('description')->nullable();
            $table->foreignId('stage_id')->nullable()->constrained('school_stages')->onDelete('set null');
            $table->timestamp('last_modified')->nullable();
            $table->boolean('is_deleted')->default(false);
            $table->boolean('is_synced')->default(false);
            $table->timestamps();

            $table->index('stage_id');
            $table->index('is_deleted');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('grades');
    }
};
